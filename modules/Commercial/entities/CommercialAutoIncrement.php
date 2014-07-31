<?php





/**
 * CommercialAutoIncrement
 *
 * @Table(name="COMMERCIAL_AUTO_INCREMENT")
 * @Entity
 */
class CommercialAutoIncrement
{
    /**
     * @var integer $nextInvoiceId
     *
     * @Column(name="next_invoice_id", type="integer")
     */
    private $nextInvoiceId;
    
    /**
     * @var integer $nextInvoiceId
     *
     * @Column(name="next_estimate_id", type="integer")
     */
    private $nextEstimateId;

    /**
     * @var integer $companyId
     *
     * @Column(name="company_id", type="integer")
     * @Id
     * @GeneratedValue
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
    
    /**
     * 
     * @param \CoreCompanies $company
     */
    public function __construct(\CoreCompanies $company) {
        $this->nextEstimateId = 1;
        $this->nextInvoiceId = 1;
        
        $this->company = $company;
        $this->companyId = $company->getId();
    }


    /**
     * Get nextInvoiceId
     *
     * @return integer 
     */
    public function getNextInvoiceId()
    {
        $return = $this->nextInvoiceId;
        while(strlen($return) < 7) $return = "0" . $return;
        
        $this->nextInvoiceId++;
        
        return "INV" . $return;
    }
    
    /**
     * Get nextInvoiceId
     *
     * @return integer 
     */
    public function getNextEstimateId()
    {
        $return = $this->nextEstimateId;
        while(strlen($return) < 7) $return = "0" . $return;
        
        $this->nextEstimateId++;
        return "EST" . $return;
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
     * 
     * @param type $companyId
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
    }

    
    /**
     * Set company
     *
     * @param CoreCompanies $company
     * @return CommercialAutoIncrement
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
}