<?php

namespace Igestis\Modules\Commercial\Import;

use Igestis\Utils\Dump;
/**
 * Description of OfXParser
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class OfXParser {

    private $importFileTarget;
    private $dbParser;
    private $token;
    private $existingAccounts;
    private $existingOperations;

    /**
     * 
     * @param string $importFileTarget
     * @param \Igestis\Modules\Commercial\Import\OfxParserDb $parser
     * @throws \Exception If file does not exist
     * @throws \Exception If file is not readable
     * @throws \Exception If the file is not ofx format
     */
    public function __construct($importFileTarget, OfxParserDb $dbManagement) {
        $this->importFileTarget = $importFileTarget;
        if (!is_file($this->importFileTarget))
            throw new \Exception("This file does not exist");
        if (!is_readable($this->importFileTarget))
            throw new \Exception("This file is not readable");
        if (!$this->isValidOfx())
            throw new \Exception("This file seems not to be a valid ofx file");

        $this->dbParser = $dbManagement;
        $this->createAccountsList();
    }
    
    /**
     * Check if the given file has the ofx format 
     * @return bool True if given file is an ofx format, false else
     */
    private function isValidOfx() {
        $f = fopen($this->importFileTarget, "r");
        while ($line = fgets($f)) {
            if (preg_match("/OFXHEADER.*/", $line)) {
                fclose($f);
                return true;
            }
        }
        fclose($f);
        return false;
    }
    
    /**
     * Get the existing account list
     */
    private function createAccountsList() {
        $accounts = $this->dbParser->getAccountsList();
        foreach ($accounts as $currentAccount) {
            $this->existingAccounts[$currentAccount->getAccountRef()] = $currentAccount;
            $this->existingOperations[$currentAccount->getAccountRef()] = $this->dbParser->getOperationsList($currentAccount);
        } 
    }
    
    /**
     * Convert date from YYYYMMDDto DateTime object
     * @param string $string Date format YYYYMMDD
     * @return \DateTime
     */
    private function formatDate($string) {
        return \DateTime::createFromFormat("Ymd", $string);
    }
    
    /**
     * Parse an ofx line to get the pear key/value
     * @param string $string Ofx row
     * @return array("key","value") 
     */
    private function getAssoc($string) {
        $string = trim($string);
        $matches = null;
        
        preg_match("/^\<(.*)>(.*)/", $string, $matches);

        if(isset($matches[1]) && isset($matches[2])) {
            return array("key" => $matches[1], "value" => $matches[2]);
        }
        return null;
    }

    /**
     * Start the ofx parsing and import all data in the tmp table
     */
    public function parse() {
        $this->token = $this->dbParser->getUniqueToken();
        $this->dbParser->beginTransaction();
        
        $fileId = fopen($this->importFileTarget, "r");
        
        $accountRef = null;
        $lastLine = "";
        $accountBalance = 0;
        $operationDate = $operationLabel = $operationAmount = $operationType = null;
        $currentTmpAccount = 0;
        
        
        while($line = fgets($fileId)) {
            // Si on détecte les infos du RIB, alors on crée l'id

            $assoc = $this->getAssoc($line);
            if($assoc['key'] == "BANKID") $accountRef = $assoc['value'];
            if($assoc['key'] == "BRANCHID") $accountRef .= "-" . $assoc['value'];
            if($assoc['key'] == "ACCTID") $accountRef .= "-" . $assoc['value'];
            if($assoc['key'] == "BALAMT" && $lastLine == "<LEDGERBAL>") $accountBalance = $assoc['value'];

            if(trim($line) == "</BANKACCTFROM>") {
                // On ajoute le compte à la base de données
                $currentTmpAccount = $this->dbParser->createTmpAccount($this->existingAccounts[$accountRef], $accountRef, $accountBalance);
            }

            if(trim($line) == "</LEDGERBAL>") {
                // On ajoute le compte à la base de données
                $currentTmpAccount->setAccountBalance($accountBalance);
            }

            // Ajout des opérations à la liste des opérations
            if($assoc['key'] == "DTPOSTED") $operationDate = $this->formatDate($assoc['value']);
            if($assoc['key'] == "TRNAMT") $operationAmount = $assoc['value'];
            if($assoc['key'] == "NAME") $operationLabel = $assoc['value'];
            if($assoc['key'] == "FITID") $operationRef = $assoc['value'];
            if(trim($line) == "</STMTTRN>") {
                
                if((float)$operationAmount > 0) $operationType = "credit";
                else $operationType = "debit";                
                
                $this->dbParser->createTmpOperation(!empty($this->existingOperations[$accountRef][$operationRef]), $currentTmpAccount, $operationLabel, $operationRef, $operationAmount, $operationDate, $operationType);                
            }

            $lastLine = trim($line);

        }
        fclose($fileId);             
        
        $this->dbParser->removeOldImports();
        $this->dbParser->flush();
        $this->dbParser->endTransaction();
    }

}
