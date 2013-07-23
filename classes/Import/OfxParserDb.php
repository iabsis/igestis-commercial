<?php

namespace Igestis\Modules\Commercial\Import;

use Igestis\Utils\Dump;
/**
 * Description of OfxParserDb
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class OfxParserDb {
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;
    
    private $token;
    
    /**
     * List of linked bank accounts
     * @var \CommercialBankTmpAccount[]
     */
    private $tmpAccountsList;
    
    /**
     *
     * @var \Application 
     */
    private $context;
    
    /**
     * Initialize object environment and remove import token already setin the session
     */
    public function __construct() {
        $this->_em  = \Application::getEntityMaanger();
        $this->context = \Application::getInstance();
        unset($_SESSION['commercialTmpImportToken']);
    }

    /**
     * Get an unique token for the import identification and set in on the commercialTmpImportToken session variable
     * @return string Generated token
     */
    public function getUniqueToken() {
        if(!$this->token) {
            $this->token = uniqid();
            $_SESSION['commercialTmpImportToken'] = $this->token;
        }
        return $this->token;
    }
    
    /**
     * Begin database transaction to avoid partially import in case of any error during process
     */
    public function beginTransaction() {
        $this->_em->getConnection()->beginTransaction();   
    }
    
    /**
     * Persist data in the database
     */
    public function flush() {
        foreach ($this->tmpAccountsList as $account) {
            $this->_em->persist($account);
        }
        $this->_em->flush();
        $_SESSION['commercialTmpImportToken'];
    }
    
    /**
     * Terminate database transaction
     */
    public function endTransaction() {
        $this->_em->getConnection()->commit();
    }
    
    /**
     * Remove all imports older than 1 day
     * @todo Complete this method
     */
    public function removeOldImports() {
        
    }
    
    /**
     * Get all already existing bank account from database
     * @return \CommercialBankAccount[]|null List of existing account 
     */
    public function getAccountsList() {
        return $this->_em->getRepository("CommercialBankAccount")->findAll();
    }
    
    /**
     * Return the list of operation reference already existing for the requested account
     * @param \CommercialBankAccount $account
     * @return array
     */
    public function getOperationsList(\CommercialBankAccount $account) {
        if($account == null) return array();
        $operationsList = $this->_em->getRepository("CommercialBankOperation")->findBy(array("account" => $account));
        $returnedList = array();
        foreach ($operationsList as $operation) {
            $returnedList[$operation->getOperationRef()] = true;
        }
        
        return $returnedList;
    }
    
    
    /**
     * 
     * @param type $linkedAccount
     * @param type $accountRef
     * @param type $accountBalance
     * @return \CommercialBankTmpAccount
     */
    public function createTmpAccount($linkedAccount, $accountRef , $accountBalance) {
        $tmpBankAccount = new \CommercialBankTmpAccount;
        $tmpBankAccount->setAccountBalance($accountBalance)
                       ->setAccountRef($accountRef)
                       ->setAccountName($accountRef)
                       ->setLinkedBankAccount($linkedAccount == null ? null : $this->_em->find("CommercialBankAccount", $linkedAccount))
                       ->setTokenring($this->token)
                       ->setImporterContact($this->context->security->contact);

        $this->tmpAccountsList[] = $tmpBankAccount;
        return  $tmpBankAccount;
        
    }
    
    /**
     * 
     * @param \CommercialBankTmpAccount $currentTmpAccount
     * @param string $operationLabel
     * @param string $operationRef
     * @param float $operationAmount
     * @param \DateTime $operationDate
     * @param string $operationType
     */
    public function createTmpOperation($alreadyExists, $currentTmpAccount, $operationLabel, $operationRef, $operationAmount, $operationDate, $operationType) {
        $operation = new \CommercialBankTmpOperation;
        $operation->setLabel($operationLabel)
                  ->setOperationRef($operationRef)
                  ->setOperationAmount($operationAmount)
                  ->setOperationDate($operationDate)
                  ->setOperationType($operationType)
                  ->setAlreadyExists($alreadyExists);
        $currentTmpAccount->addOperation($operation);
    }
}

?>
