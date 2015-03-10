<?php

namespace Igestis\Modules\Commercial\Pdfs;

use \Igestis\Modules\Commercial\ConfigModuleVars;

/**
 * This class allow to generate 
 *
 * @author Gilles Hemmerlé
 */
class GenerateInvoice extends GenerateCommercialDocument {
    
    private $document;
    
    /**
     *
     * @var \CommercialInvoice 
     */
    private $commercialInvoice;
    
    /**
     *
     * @var \DateTime $invoiceDate
     */
    private $invoiceDate;
    
    /**
     *
     * @var \DateTime $validUntil
     */
    private $validUntil;
    
    /**
     *
     * @var \CommercialSoldType commercialSoldType
     */
    private $commercialSoldType;
    
    /**
     * Invoice type
     * @var string 
     */
    private $invoiceType;
    
    /**
     * 
     * @param \EntityManager $entityManager
     * @param \CommercialCommercialDocument $document
     * @param Igestis\Modules\Keytours\HtmlRendererInterface $htmlRenderer
     * @param string $htmlRendererFile
     */
    public function __construct(\EntityManager $entityManager, \CommercialCommercialDocument $document = null, $htmlRenderer = null, $htmlRendererFile = null) {
        parent::__construct($entityManager, $document, $htmlRenderer, $htmlRendererFile);
        $this->document = $document;
        $this->saveMode(true);
    }    
    
    /**
     * 
     * @return \DateTime
     */
    public function getInvoiceDate() {
        return $this->invoiceDate;
    }

    /**
     * 
     * @param \DateTime $invoiceDate
     * @return \Igestis\Modules\Commercial\Pdfs\GenerateEstimate
     */
    public function setInvoiceDate(\DateTime $invoiceDate) {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getInvoiceType() {
        return $this->invoiceType;
    }

    /**
     * 
     * @param string $invoiceType
     * @return \Igestis\Modules\Commercial\Pdfs\GenerateInvoice
     */
    public function setInvoiceType($invoiceType) {
        $this->invoiceType = $invoiceType;
        return $this;
    }

        
    /**
     * 
     * @return \CommercialSoldType
     */
    public function getCommercialSoldType() {
        return $this->commercialSoldType;
    }

    /**
     * 
     * @param \CommercialSoldType $commercialSoldType
     * @return \Igestis\Modules\Commercial\Pdfs\GenerateInvoice
     */
    public function setCommercialSoldType(\CommercialSoldType $commercialSoldType) {
        $this->commercialSoldType = $commercialSoldType;
        return $this;
    }

    
    /**
     * 
     * @return \DateTime
     */
    public function getValidUntil() {
        return $this->validUntil;
    }

    /**
     * 
     * @param \DateTime $validUntil
     * @return \Igestis\Modules\Commercial\Pdfs\GenerateInvoice
     */
    public function setValidUntil(\DateTime $validUntil) {
        $this->validUntil = $validUntil;
        return $this;
    }

    
    /**
     * 
     * @return \Igestis\Modules\Commercial\Pdfs\GenerateInvoice
     */
    public function generate() {
        if(count($this->document->getInvoices())) {
            $this->commercialInvoice = $this->document->getInvoices()->get(0); 
            foreach ($this->commercialInvoice->getArticles() as $currentArticle) {
                $this->entityManager->remove($currentArticle);
                $this->entityManager->flush();
            }
            
            $this->commercialInvoice->removeArticles();
            
            if($this->saveMode) {
                $this->entityManager->persist($this->commercialInvoice);
                $this->entityManager->flush();
            }
            
            $this->commercialInvoice->updateFromCommercialDocument($this->document);
            
            
        }
        else {
            $autoIncrement = $this->entityManager->getRepository("CommercialAutoIncrement")->find($this->document->getCompany()->getId());
            if($autoIncrement == null) $autoIncrement = new \CommercialAutoIncrement($this->document->getCompany());

            // Create the estimate object
            $this->commercialInvoice = new \CommercialInvoice($this->document);
            $this->commercialInvoice->setInvoiceNumber($autoIncrement->getNextInvoiceId())
                                    ->setInvoicesDate($this->invoiceDate ? $this->invoiceDate : new \DateTime);
        }
        
        
        $estimate = $this->entityManager->getRepository("CommercialEstimate")->findLastEstimateForDocument($this->document);
        
        $this->commercialInvoice->setPaymentDateLimit($this->validUntil)                                
                                ->setEstimate($estimate)
                                ->setPaymentMode($this->commercialSoldType)
                                ->setPathPdfFile('')
                                ->setInvoicesType($this->invoiceType);
        
        if($this->saveMode) {
            if($autoIncrement) $this->entityManager->persist($autoIncrement);
            $this->entityManager->persist($this->commercialInvoice);
            $this->entityManager->flush();
        }
        
         // Generate pdf
        $this->tcpdfObject = new TcpdfWithHeaderAndFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // Set default font
        $this->tcpdfObject->SetFont('helvetica', '', 9);
        //$this->tcpdfObject->setPrintHeader(false);
        //$this->tcpdfObject->setPrintFooter(false);
        
        // set margins
        $this->tcpdfObject->SetMargins(PDF_MARGIN_LEFT, 80, PDF_MARGIN_RIGHT);
        $this->tcpdfObject->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->tcpdfObject->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $this->tcpdfObject->SetAutoPageBreak(TRUE, 40);
        
        $this->addReplacements(
            array(
                'commercialInvoice' => $this->commercialInvoice,
                "numPage" => $this->tcpdfObject->getAliasNumPage(),
                "nbPages" => $this->tcpdfObject->getAliasNbPages(),
                "companyConfig" => $this->entityManager->getRepository('CommercialCompanyConfig')->find($this->commercialInvoice->getCommercialDocument()->getCompany()->getId())
            )
        );
        //Define and run Header and Footer settings.
        $this->tcpdfObject->setHeaderContent($this->generateHeader());
        $this->tcpdfObject->setFooterContent($this->generateFooter());
        
        $this->tcpdfObject->AddPage();
        $this->tcpdfObject->writeHTML($this->generateHtml());
        
        $terms = $this->generateTerms();
        if($terms) {
            $this->tcpdfObject->SetPrintHeader(false);
            $this->tcpdfObject->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $this->tcpdfObject->AddPage();
            
            $this->tcpdfObject->SetPrintFooter(false);
            $this->tcpdfObject->writeHTML($terms);
        }
        
        return $this;
    }    
    
    /**
     * Filename and mode only used if savedMode  is disabled
     * @param type $filename
     * @param type $mode
     */
    public function show($filename = 'fichier.pdf', $mode = 'D') {
        if($this->saveMode) {
            // Create quotation root folder if not exist
            if(!is_dir(ConfigModuleVars::invoicesFolder())) {
                if(!mkdir(ConfigModuleVars::invoicesFolder())) {
                    throw new \Exception(sprintf(\Igestis\I18n\Translate::_("Unable to create the estimate folder '%s'"), ConfigModuleVars::invoicesFolder()));
                }
            }
            
            // Create the quotation folder for the needed company if not exist
            $companyQuotationFolder = ConfigModuleVars::invoicesFolder() . "/" . $this->document->getCompany()->getId();
            if(!is_dir($companyQuotationFolder)) {
                if(!mkdir($companyQuotationFolder)) {
                    throw new \Exception(sprintf(\Igestis\I18n\Translate::_("Unable to create the estimate folder '%s'"), $companyQuotationFolder));
                }
            }
            
            $filename = $companyQuotationFolder . "/" . $this->commercialInvoice->getInvoiceNumber() . ".pdf";
            //$mode = "FD";
            
            $fileInfos = pathinfo($filename);  
            $this->commercialInvoice->setPathPdfFile($fileInfos['basename']);
            $this->entityManager->persist($this->commercialInvoice);
            $this->entityManager->flush();
        }
        
        parent::show($filename, $mode);
    }
}
