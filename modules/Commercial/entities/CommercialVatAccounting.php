<?php





/**
 * CommercialVatAccounting
 *
 * @Table(name="COMMERCIAL_VAT_ACCOUNTING")
 * @Entity(repositoryClass="CommercialVatAccountingRepository")
 * @HasLifecycleCallbacks
 */
class CommercialVatAccounting
{
    /**
     * @var string $sellingVatAccount
     *
     * @Column(name="selling_vat_account", type="string")
     */
    private $sellingVatAccount;

    /**
     * @var string $buyingVatAccount
     *
     * @Column(name="buying_vat_account", type="string")
     */
    private $buyingVatAccount;    
    

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
        $this->sellingVatAccount = "4457";
        $this->buyingVatAccount = "4456";
        
    }
    
    /**
     * 
     * @return float
     */
    public function getSellingVatAccount() {
        return $this->sellingVatAccount;
    }

    /**
     * 
     * @param float $sellingVatAccount
     * @return \CommercialVatAccounting
     */
    public function setSellingVatAccount($sellingVatAccount) {
        $this->sellingVatAccount = $sellingVatAccount;
        return $this;
    }

    /**
     * 
     * @return float
     */
    public function getBuyingVatAccount() {
        return $this->buyingVatAccount;
    }

    /**
     * 
     * @param float $buyingVatAccount
     * @return \CommercialVatAccounting
     */
    public function setBuyingVatAccount($buyingVatAccount) {
        $this->buyingVatAccount = $buyingVatAccount;
        return $this;
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

class CommercialVatAccountingRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("va")
               ->from("CommercialVatAccounting", "va")
               ->where("va.company = :company")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();        
        
    }
    
    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        if(!$result || $result->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return new \CommercialVatAccounting;
        return $result;
    }
    
    /**
     * 
     * @return \CommercialVatAccounting
     * @throws Exception
     */
    public function getCompanyConfig() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("va")
               ->from("CommercialVatAccounting", "va")
               ->where("va.company = :company")
               ->setParameter("company", $userCompany)
               ->setMaxResults(1);
            
            $result = $qb->getQuery()->getOneOrNullResult();
            if(!$result) $result = new \CommercialVatAccounting;
            
            return $result;
        }
        catch (\Exception $e) {
            throw $e;
        }
          
        
    }

}