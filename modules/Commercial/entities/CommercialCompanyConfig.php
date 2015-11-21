<?php

/**
 * CommercialCompanyConfig
 *
 * @Table(name="COMMERCIAL_COMPANY_CONFIG")
 * @Entity(repositoryClass="CommercialCompanyConfigRepository")
 * @HasLifecycleCallbacks
 */
class CommercialCompanyConfig
{
    /**
     * @var integer $estimateExpirationDays
     *
     * @Column(name="estimate_expiration_days", type="integer")
     */
    private $estimateExpirationDays;

    /**
     * @var integer $invoicePaymentLimit
     *
     * @Column(name="invoice_payment_limit", type="integer")
     */
    private $invoicePaymentLimit;
    
    /**
     * @var string $mailInvoice
     *
     * @Column(name="mail_invoice", type="string")
     */
    private $mailInvoice;

    /**
     * @var string $mailEstimate
     *
     * @Column(name="mail_estimate", type="string")
     */
    private $mailEstimate;
    
    /**
     * @var string $exportFormat
     *
     * @Column(name="export_format", type="string")
     */
    private $exportFormat;
    
    /**
     * @var string $exportBuyingTi
     *
     * @Column(name="export_buying_TI", type="string")
     */
    private $exportBuyingTi;
    
    /**
     * @var string $exportBuyingTaxes
     *
     * @Column(name="export_buying_taxes", type="string")
     */
    private $exportBuyingTaxes;
    
    /**
     * @var string $exportBuyingDf
     *
     * @Column(name="export_buying_DF", type="string")
     */
    private $exportBuyingDf;
    
    /**
     * @var string $exportSellingDf
     *
     * @Column(name="export_selling_DF", type="string")
     */
    private $exportSellingDf;
    
    /**
     * @var string $exportSellingTi
     *
     * @Column(name="export_selling_TI", type="string")
     */
    private $exportSellingTi;
    
    /**
     * @var string $exportSellingTaxes
     *
     * @Column(name="export_selling_taxes", type="string")
     */
    private $exportSellingTaxes;
    
    /**
     * @var string $exportHeader
     *
     * @Column(name="export_header", type="string")
     */
    private $exportHeader;
    
    /**
     * @var string $imprint
     *
     * @Column(name="imprint", type="string")
     */
    private $imprint;

    /**
     * project_show_documents  
     * @var string $project_show_documents  
     * @Column(name="project_show_documents", type="boolean")
     */
    private $projectShowDocuments;

    /**
     * project_show_buying_invoices    
     * @var string $project_show_buying_invoices    
     * @Column(name="project_show_buying_invoices", type="boolean")
     */
    private $projectShowBuyingInvoices;

    /**
     * project_show_sales_documents    
     * @var string $project_show_sales_documents    
     * @Column(name="project_show_sales_documents", type="boolean")
     */
    private $projectShowSalesDocuments;

    /**
     * project_show_interventions
     * @var string $project_show_interventions
     * @Column(name="project_show_interventions", type="boolean")
     */
    private $projectShowInterventions;

    /**
     * @var string $terms
     *
     * @Column(name="terms", type="string")
     */
    private $terms;
    
    /**
     * @var float $termsFontSize
     *
     * @Column(name="terms_font_size", type="decimal")
     */
    private $termsFontSize;
    
 

    /**
     * @var integer $companyId
     *
     * @Column(name="company_id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $companyId;

    /**
     * @var CoreCompanies
     *
     * @ManyToOne(targetEntity="CoreCompanies")
     * @JoinColumns({
     *   @JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    public function __construct() {
        $this->taxRate = 0;
        $this->estimateExpirationDays = 7;
        $this->invoicePaymentLimit = 30;

        $this->projectShowDocuments = false;
        $this->projectShowInterventions = false;
        $this->projectShowBuyingInvoices = false;
        $this->projectShowSalesDocuments = false;
        
        $app = Application::getInstance();
        
        $this->mailEstimate = $app->render("Commercial/mails/estimate.twig", array(), true);
        $this->mailInvoice = $app->render("Commercial/mails/invoice.twig", array(), true);
    }
    
    /**
     * 
     * @return type
     */
    public function getMailInvoice() {
        return $this->mailInvoice;
    }

    /**
     * 
     * @param type $mailInvoice
     * @return \CommercialCompanyConfig
     */
    public function setMailInvoice($mailInvoice) {
        $this->mailInvoice = $mailInvoice;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getMailEstimate() {
        return $this->mailEstimate;
    }

    /**
     * 
     * @param type $mailEstimate
     * @return \CommercialCompanyConfig
     */
    public function setMailEstimate($mailEstimate) {
        $this->mailEstimate = $mailEstimate;
        return $this;
    }

    

    /**
     * Set estimateExpirationDays
     *
     * @param integer $estimateExpirationDays
     * @return CommercialCompanyConfig
     */
    public function setEstimateExpirationDays($estimateExpirationDays)
    {
        $this->estimateExpirationDays = $estimateExpirationDays;
        return $this;
    }

    /**
     * Get estimateExpirationDays
     *
     * @return integer 
     */
    public function getEstimateExpirationDays()
    {
        return $this->estimateExpirationDays;
    }

    /**
     * Set invoicePaymentLimit
     *
     * @param integer $invoicePaymentLimit
     * @return CommercialCompanyConfig
     */
    public function setInvoicePaymentLimit($invoicePaymentLimit)
    {
        $this->invoicePaymentLimit = $invoicePaymentLimit;
        return $this;
    }

    /**
     * Get invoicePaymentLimit
     *
     * @return integer 
     */
    public function getInvoicePaymentLimit()
    {
        return $this->invoicePaymentLimit;
    }

    /**
     * Get companyId
     *
     * @return integer 
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set company
     *
     * @param CoreCompanies $company
     * @return CommercialCompanyConfig
     */
    public function setCompany(\CoreCompanies $company = null)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Get company
     *
     * @return CoreCompanies 
     */
    public function getCompany()
    {
        return $this->company;
    }
    
    /**
     * 
     * @return string
     */
    public function getExportHeader($transformConstants = false) {  
  
        
        $format = $this->exportHeader;
        
        if($transformConstants) {               
            $format = preg_replace("/STR_PAD_LEFT/", STR_PAD_LEFT, $format);
            $format = preg_replace("/STR_PAD_RIGHT/", STR_PAD_RIGHT, $format);
            $format = preg_replace("/STR_PAD_BOTH/", STR_PAD_BOTH, $format);
        }
        
        return $format;
    }

    /**
     * 
     * @param string $exportHeader
     * @return \CommercialCompanyConfig
     */
    public function setExportHeader($exportHeader) {
        
        $this->exportHeader = $exportHeader;
        return $this;
    }

        
    /**
     * 
     * @return string
     */
    public function getExportFormat($transformConstants = false) {
        
        $format = $this->exportFormat;
        
        if($transformConstants) {
            $format = preg_replace("/STR_PAD_LEFT/", STR_PAD_LEFT, $format);
            $format = preg_replace("/STR_PAD_RIGHT/", STR_PAD_RIGHT, $format);
            $format = preg_replace("/STR_PAD_BOTH/", STR_PAD_BOTH, $format);
        }
        
        return $format;
    }
    
    private function transformation($format) {
        $format = preg_replace("/STR_PAD_LEFT/", STR_PAD_LEFT, $format);
        $format = preg_replace("/STR_PAD_RIGHT/", STR_PAD_RIGHT, $format);
        $format = preg_replace("/STR_PAD_BOTH/", STR_PAD_BOTH, $format);
        
        return $format;
    }

    /**
     * 
     * @param type $exportFormat
     * @return \CommercialCompanyConfig
     */
    public function setExportFormat($exportFormat) {
        $this->exportFormat = $exportFormat;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getExportBuyingTi($transformConstants = false) {
        return ($transformConstants ? $this->transformation($this->exportBuyingTi) : $this->exportBuyingTi);
    }

    /**
     * 
     * @return string
     */
    public function getExportBuyingTaxes($transformConstants = false) {
        return ($transformConstants ? $this->transformation($this->exportBuyingTaxes) : $this->exportBuyingTaxes);
    }

    /**
     * 
     * @return string
     */
    public function getExportBuyingDf($transformConstants = false) {
        return ($transformConstants ? $this->transformation($this->exportBuyingDf) : $this->exportBuyingDf);
    }

    /**
     * 
     * @return string
     */
    public function getExportSellingDf($transformConstants = false) {
        return ($transformConstants ? $this->transformation($this->exportSellingDf) : $this->exportSellingDf);
    }

    /**
     * 
     * @return string
     */
    public function getExportSellingTi($transformConstants = false) {
        return ($transformConstants ? $this->transformation($this->exportSellingTi) : $this->exportSellingTi);
    }

    /**
     * 
     * @return string
     */
    public function getExportSellingTaxes($transformConstants = false) {
        return ($transformConstants ? $this->transformation($this->exportSellingTaxes) : $this->exportSellingTaxes);
    }

    /**
     * 
     * @param string $exportBuyingTi
     * @return \CommercialCompanyConfig
     */
    public function setExportBuyingTi($exportBuyingTi) {
        $this->exportBuyingTi = $exportBuyingTi;
        return $this;
    }

    /**
     * 
     * @param string $exportBuyingTaxes
     * @return \CommercialCompanyConfig
     */
    public function setExportBuyingTaxes($exportBuyingTaxes) {
        $this->exportBuyingTaxes = $exportBuyingTaxes;
        return $this;
    }

    /**
     * 
     * @param string $exportBuyingDf
     * @return \CommercialCompanyConfig
     */
    public function setExportBuyingDf($exportBuyingDf) {
        $this->exportBuyingDf = $exportBuyingDf;
        return $this;
    }

    /**
     * 
     * @param string $exportSellingDf
     * @return \CommercialCompanyConfig
     */
    public function setExportSellingDf($exportSellingDf) {
        $this->exportSellingDf = $exportSellingDf;
        return $this;
    }

    /**
     * 
     * @param string $exportSellingTi
     * @return \CommercialCompanyConfig
     */
    public function setExportSellingTi($exportSellingTi) {
        $this->exportSellingTi = $exportSellingTi;
        return $this;
    }

    /**
     * 
     * @param string $exportSellingTaxes
     * @return \CommercialCompanyConfig
     */
    public function setExportSellingTaxes($exportSellingTaxes) {
        $this->exportSellingTaxes = $exportSellingTaxes;
        return $this;
    }

    /**
     * Return the legal mentions
     * @return type
     */
    public function getImprint() {
        return $this->imprint;
    }

    /**
     * Return the selling generation condition
     * @return type
     */
    public function getTerms() {
        return $this->terms;
    }

    /**
     * 
     * @param string $imprint Legal mentions
     * @return \CommercialCompanyConfig
     */
    public function setImprint($imprint) {
        $this->imprint = $imprint;
        return $this;
    }

    /**
     * 
     * @param string $terms selling generation condition
     * @return \CommercialCompanyConfig
     */
    public function setTerms($terms) {
        $this->terms = $terms;
        return $this;
    }

    /**
     * 
     * @return float $termsFontSize Font size for the terms (in px)
     */
    public function getTermsFontSize() {
        return $this->termsFontSize ? $this->termsFontSize : 8.0 ;
    }


    /**
     * 
     * @param float $termsFontSize Font size (in px) for the terms
     * @return \CommercialCompanyConfig
     */
    public function setTermsFontSize($termsFontSize) {
        $this->termsFontSize = $termsFontSize;
        return $this;
    }

    /**
     * Gets the project_show_documents.
     *
     * @return string $project_show_documents
     */
    public function getProjectShowDocuments()
    {
        return $this->projectShowDocuments;
    }

    /**
     * Sets the project_show_documents.
     *
     * @param string $project_show_documents $projectShowDocuments the project show documents
     *
     * @return self
     */
    public function setProjectShowDocuments($projectShowDocuments)
    {
        $this->projectShowDocuments = $projectShowDocuments;

        return $this;
    }

    /**
     * Gets the project_show_interventions.
     *
     * @return string $project_show_interventions
     */
    public function getProjectShowInterventions()
    {
        return $this->projectShowInterventions;
    }

    /**
     * Sets the project_show_interventions.
     *
     * @param string $project_show_interventions $projectShowInterventions the project show interventions
     *
     * @return self
     */
    public function setProjectShowInterventions($projectShowInterventions)
    {
        $this->projectShowInterventions = $projectShowInterventions;

        return $this;
    }

    /**
     * Gets the project_show_buying_invoices.
     *
     * @return string $project_show_buying_invoices
     */
    public function getProjectShowBuyingInvoices()
    {
        return $this->projectShowBuyingInvoices;
    }

    /**
     * Sets the project_show_buying_invoices.
     *
     * @param string $project_show_buying_invoices $projectShowBuyingInvoices the project show buying invoices
     *
     * @return self
     */
    public function setProjectShowBuyingInvoices($projectShowBuyingInvoices)
    {
        $this->projectShowBuyingInvoices = $projectShowBuyingInvoices;

        return $this;
    }

    /**
     * Gets the project_show_sales_documents.
     *
     * @return string $project_show_sales_documents
     */
    public function getProjectShowSalesDocuments()
    {
        return $this->projectShowSalesDocuments;
    }

    /**
     * Sets the project_show_sales_documents.
     *
     * @param string $project_show_sales_documents $projectShowSalesDocuments the project show sales documents
     *
     * @return self
     */
    public function setProjectShowSalesDocuments($projectShowSalesDocuments)
    {
        $this->projectShowSalesDocuments = $projectShowSalesDocuments;

        return $this;
    }

        
    /**
     * @PrePersist
     * @PreUpdate
     */
    public function prePersist() {
        if($this->company == null) {
            $this->company = \IgestisSecurity::init()->user->getCompany();
        }
        
        $this->exportFormat = null;
    }


}

// ---------------------------------------------------------------------

class CommercialCompanyConfigRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("cc")
               ->from("CommercialCompanyConfig", "cc")
               ->where("cc.company = :company")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();        
        
    }
    
    public function find($id, $lockMode = null, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        if(!$result || $result->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return new CommercialCompanyConfig;
        return $result;
    }
    
    /**
     * 
     * @return \CommercialCompanyConfig
     * @throws Exception
     */
    public function getCompanyConfig() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("cc")
               ->from("CommercialCompanyConfig", "cc")
               ->where("cc.company = :company")
               ->setParameter("company", $userCompany)
               ->setMaxResults(1);
            
            $result = $qb->getQuery()->getOneOrNullResult();
            if(!$result) {
                $result = new CommercialCompanyConfig();
                $result->setCompany($userCompany);
            }
            
            return $result;
        }
        catch (\Exception $e) {
            throw $e;
        }
          
        
    }


    
}