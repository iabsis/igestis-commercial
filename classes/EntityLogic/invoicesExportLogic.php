<?php

namespace Igestis\Modules\Commercial\EntityLogic;

/**
 * This class generate the export file for the passed invoices
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class invoicesExportLogic {
    const TYPE_BUYING = "buying";
    const TYPE_SELLING = "selling";
    
    /**
     *
     * @var array List of invoices to export
     */
    private $invoicesList;
    /**
     * @var \Doctrine\ORM\EntityManager Helper for the entitymanager to access to the doctrine entities
     */
    private static $entityManager;
    
    /**
     *
     * @var \Application
     */
    private static $context;
    
    /**
     *
     * @var \CommercialVatAccounting 
     */
    private static $vatAccounting;
    
    /**
     *
     * @var \CommercialCompanyConfig 
     */
    private static $companyConfig;

    /**
     * @param \Application
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct($context, $entityManager) {
        
        self::$context = $context;
        self::$entityManager = $entityManager;
        
        self::$companyConfig = self::$entityManager->getRepository("CommercialCompanyConfig")->getCompanyConfig(); 
    }
    /**
     * 
     * @param \CommercialInvoice|\CommercialProviderInvoice $invoice
     * @throws Exception
     */
    public function addInvoice($invoice) {
        if(!($invoice instanceof \CommercialInvoice) && !($invoice instanceof \CommercialProviderInvoice)) {
            throw new \Exception(\Igestis\I18n\Translate::_("The invoice is not a known invoice format"));
        }
        $this->invoicesList[] = $invoice;
    }
    
    public function generateExportFile() {
        $fileContent = "";
        $header = "";
        
        if(self::$companyConfig->getExportHeader(true)) {
            $header = self::$context->renderFromString(self::$companyConfig->getExportHeader(true)) . "\n";
        }
        
        foreach ($this->invoicesList as $invoice) {
            $exported = $invoice->getExported();
            $fileContent .= $invoice->export();
            if(!$exported) {
                self::$entityManager->persist($invoice);  
            }
        }
        
        self::$entityManager->flush();  
        return $header . $fileContent;
    }
    
    /**
     * 
     * @param string "sell" or "b
     * @param type $userAccount
     * @param type $factureNumber
     * @param type $amountDf
     * @param type $amountTi
     * @param type $taxes
     */
    public static function exportLineFormatter($invoiceId, $type, $userAccount, $taxAccout, $articleAccount, $invoiceNumber, $invoiceDate, $amountDf, $amountTi, $taxes, $customerLabel) {
        $amount = 0;
        
        if($amountDf) {
            $amount = $amountDf;
        }
        if($amountTi) {
            $amount = $amountTi;
        }
        if($taxes) {
            $amount = $taxes;
        }
        
        if(!$amount) {
            return "";
        }
        
        $template = "";
        
        switch ($type) {
            case self::TYPE_BUYING :
                if($amountDf != 0) {
                    $template = self::$companyConfig->getExportBuyingDf();
                }
                if($amountTi != 0) {
                    $template = self::$companyConfig->getExportBuyingTi();
                }
                if($taxes != 0) {
                    $template = self::$companyConfig->getExportBuyingTaxes();
                }
                break;
            case self::TYPE_SELLING :
                if($amountDf != 0) {
                    $template = self::$companyConfig->getExportSellingDf();
                }
                if($amountTi != 0) {
                    $template = self::$companyConfig->getExportSellingTi();
                }
                if($taxes != 0) {
                    $template = self::$companyConfig->getExportSellingTaxes();
                }
                break;
        }
        if(!$template) {
            return "";
        }
        
        return self::$context->renderFromString($template, array(
            "type" => $type,
            "userAccount" => $userAccount,
            "taxAccout" => $taxAccout,
            "articleAccount" => $articleAccount,
            "invoiceNumber" => $invoiceNumber,
            "invoiceDate" => $invoiceDate,
            "amount" => $amount,
            "incoiceId" => $invoiceId,
            "userLabel" => $customerLabel
        )) . "\n";
    }
    
    /**
     * 
     * @return \CommercialVatAccounting
     */
    public static function getVatAccountig() {
        if(self::$vatAccounting) {
            return self::$vatAccounting;
        }
        
        self::$vatAccounting = self::$entityManager->getRepository("CommercialVatAccounting")->getCompanyConfig();        
        return self::$vatAccounting;
    }
}
