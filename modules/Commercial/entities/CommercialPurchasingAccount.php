<?php





/**
 * CommercialPurchasingAccount
 *
 * @Table(name="COMMERCIAL_PURCHASING_ACCOUNT")
 * @Entity(repositoryClass="CommercialPurchasingAccountRepository")
 * @HasLifecycleCallbacks
 */
class CommercialPurchasingAccount
{
    /**
     * @var string $label
     *
     * @Column(name="label", type="string", length=45)
     */
    private $label;

    /**
     * @var string $accountNumber
     *
     * @Column(name="account_number", type="string", length=15)
     */
    private $accountNumber;

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
     * @return CommercialPurchasingAccount
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
     * Set accountNumber
     *
     * @param string $accountNumber
     * @return CommercialPurchasingAccount
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string 
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
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
     * @return CommercialPurchasingAccount
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

class CommercialPurchasingAccountRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("pa")
               ->from("CommercialPurchasingAccount", "pa")
               ->where("pa.company = :company")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();
        
        
    }
    
    public function find($id, $lockMode = null, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        if(!$result || $result->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return null;
        return $result;
    }

}