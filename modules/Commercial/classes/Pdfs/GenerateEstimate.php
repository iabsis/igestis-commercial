<?php

namespace Igestis\Modules\Commercial\Pdfs;

use \Igestis\Modules\Commercial\ConfigModuleVars;

/**
 * This class allow to generate 
 *
 * @author Gilles Hemmerlé
 */
class GenerateEstimate extends GenerateCommercialDocument {
    
    private $document;
    
    /**
     *
     * @var \CommercialEstimate 
     */
    private $commercialEstimate;
    
    /**
     *
     * @var \DateTime $estimateDate
     */
    private $estimateDate;
    
    /**
     *
     * @var \DateTime $validUntil
     */
    private $validUntil;
    
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
    public function getEstimateDate() {
        return $this->estimateDate;
    }

    /**
     * 
     * @param \DateTime $estimateDate
     * @return \Igestis\Modules\Commercial\Pdfs\GenerateEstimate
     */
    public function setEstimateDate(\DateTime $estimateDate) {
        $this->estimateDate = $estimateDate;
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
     * @return \Igestis\Modules\Commercial\Pdfs\GenerateEstimate
     */
    public function setValidUntil(\DateTime $validUntil) {
        $this->validUntil = $validUntil;
        return $this;
    }

    
    /**
     * 
     * @return \Igestis\Modules\Commercial\Pdfs\GenerateEstimate
     */
    public function generate() {
        
        $autoIncrement = $this->entityManager->getRepository("CommercialAutoIncrement")->find($this->document->getCompany()->getId());
        if($autoIncrement == null) $autoIncrement = new \CommercialAutoIncrement($this->document->getCompany());
        
        // Create the estimate object
        $this->commercialEstimate = new \CommercialEstimate($this->document);
        
        $this->commercialEstimate->setDateEstimate($this->estimateDate)
                                 ->setValidUntil($this->validUntil)
                                 ->setEstimationNumber($autoIncrement->getNextEstimateId())
                                 ->setPathPdfFile('');
        
        if($this->saveMode) {
            $this->entityManager->persist($autoIncrement);
            $this->entityManager->persist($this->commercialEstimate);
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
        $this->tcpdfObject->SetAutoPageBreak(TRUE, 70);
        
        $this->addReplacements(
            array(
                "commercialEstimate" => $this->commercialEstimate,
                "numPage" => $this->tcpdfObject->getAliasNumPage(),
                "nbPages" => $this->tcpdfObject->getAliasNbPages(),
                "companyConfig" => $this->entityManager->getRepository('CommercialCompanyConfig')->find($this->commercialEstimate->getCommercialDocument()->getCompany()->getId())
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
            if(!is_dir(ConfigModuleVars::quotationsFolder())) {
                if(!mkdir(ConfigModuleVars::quotationsFolder())) {
                    throw new \Exception(sprintf(\Igestis\I18n\Translate::_("Unable to create the estimate folder '%s'"), ConfigModuleVars::quotationsFolder()));
                }
            }
            
            // Create the quotation folder for the needed company if not exist
            $companyQuotationFolder = ConfigModuleVars::quotationsFolder() . "/" . $this->document->getCompany()->getId();
            if(!is_dir($companyQuotationFolder)) {
                if(!mkdir($companyQuotationFolder)) {
                    throw new \Exception(sprintf(\Igestis\I18n\Translate::_("Unable to create the estimate folder '%s'"), $companyQuotationFolder));
                }
            }
            
            $filename = $companyQuotationFolder . "/" . $this->commercialEstimate->getEstimationNumber() . ".pdf";
            //$mode = "FD";
            
            $fileInfos = pathinfo($filename);  
            $this->commercialEstimate->setPathPdfFile($fileInfos['basename']);
            $this->entityManager->persist($this->commercialEstimate);
            $this->entityManager->flush();
        }
        
        parent::show($filename, $mode);
    }
}
