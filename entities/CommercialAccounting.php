<?php





/**
 * CommercialAccounting
 *
 * @Table(name="COMMERCIAL_ACCOUNTING")
 * @Entity
 */
class CommercialAccounting
{
    /**
     * @var string $label
     *
     * @Column(name="label", type="string", length=20)
     */
    private $label;

    /**
     * @var string $accountNo
     *
     * @Column(name="account_no", type="string", length=12)
     */
    private $accountNo;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * Set label
     *
     * @param string $label
     * @return CommercialAccounting
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set accountNo
     *
     * @param string $accountNo
     * @return CommercialAccounting
     */
    public function setAccountNo($accountNo)
    {
        $this->accountNo = $accountNo;
        return $this;
    }

    /**
     * Get accountNo
     *
     * @return string 
     */
    public function getAccountNo()
    {
        return $this->accountNo;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set company
     *
     * @param CoreCompanies $company
     * @return CommercialAccounting
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