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
     * @var string $exportHeader
     *
     * @Column(name="export_header", type="string")
     */
    private $exportHeader;
    

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
        if(!$this->exportHeader) {
            $format = "NUMJL;NUMCP;DTOPE;NPIE;LIBEC;MTDEB;MTVCRE";
            $this->exportHeader = $format;
        }
        
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
        if(!$this->exportFormat) {
            $format.= '{# Taxes line #}' . "\n";
            $format.= '{{ type == \'selling\' ? \'VE\' : \'HA\' }};';
            $format.= '{{ taxAccout }};';
            $format.= '{{ invoiceDate|date(\'d/m/Y\') }};';
            $format.= '{{ type == \'selling\' ? \'Selling\' : \'Buying\' }} invoice \'{{ invoiceNumber }}\';';
            $format.= '{{ type == \'selling\' ? \'0.00\' : taxes|number_format() }};';
            $format.= '{{ type == \'buying\' ? \'0.00\' : taxes|number_format() }};' . "\n";

            $format.= '{# Duty free line #}' . "\n";
            $format.= '{{ type == \'selling\' ? \'VE\' : \'HA\' }};';
            $format.= '{{ userAccount }};';
            $format.= '{{ invoiceDate|date(\'d/m/Y\') }};';
            $format.= '{{ type == \'selling\' ? \'Selling\' : \'Buying\' }} invoice \'{{ invoiceNumber }}\';';
            $format.= '{{ type == \'selling\' ? \'0.00\' : amountDf|number_format() }};';
            $format.= '{{ type == \'buying\' ? \'0.00\' : amountDf|number_format() }};' . "\n";

            $format.= '{# Tax included line #}' . "\n";
            $format.= '{{ type == \'selling\' ? \'VE\' : \'HA\' }};';
            $format.= '{{ articleAccount }};';
            $format.= '{{ invoiceDate|date(\'d/m/Y\') }};';
            $format.= '{{ type == \'selling\' ? \'Selling\' : \'Buying\' }} invoice \'{{ invoiceNumber }}\';';
            $format.= '{{ type == \'selling\' ? amountTi|number_format() : \'0.00\' }};';
            $format.= '{{ type == \'buying\' ? amountTi|number_format() : \'0.00\' }};';     
            
            $this->exportFormat = $format;
        }
        
        $format = $this->exportFormat;
        
        if($transformConstants) {
            $format = preg_replace("/STR_PAD_LEFT/", STR_PAD_LEFT, $format);
            $format = preg_replace("/STR_PAD_RIGHT/", STR_PAD_RIGHT, $format);
            $format = preg_replace("/STR_PAD_BOTH/", STR_PAD_BOTH, $format);
        }
        
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
     * @PrePersist
     * @PreUpdate
     */
    public function prePersist() {
        if($this->company == null) {
            $this->company = \IgestisSecurity::init()->user->getCompany();
        }
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
    
    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null) {
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