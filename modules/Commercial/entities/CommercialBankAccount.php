<?php





use Doctrine\DBAL\Types\FloatType;
/**
 * CommercialBankAccount
 *
 * @Table(name="COMMERCIAL_BANK_ACCOUNT")
 * @Entity(repositoryClass="CommercialBankAccountRepository")
 * @HasLifecycleCallbacks
 */
class CommercialBankAccount
{
    /**
     * @var string $accountName
     *
     * @Column(name="account_name", type="string", length=120)
     */
    private $accountName;

    /**
     * @var string $accountRef
     *
     * @Column(name="account_ref", type="string", length=80)
     */
    private $accountRef;

    /**
     * @var decimal $accountBalance
     *
     * @Column(name="account_balance", type="decimal")
     */
    private $accountBalance;

    /**
     * @var string $bankName
     *
     * @Column(name="bank_name", type="string", length=120)
     */
    private $bankName;

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
     *   @JoinColumn(name="CORE_COMPANIES_id", referencedColumnName="id")
     * })
     */
    private $company;


    /**
     * Set accountName
     *
     * @param string $accountName
     * @return CommercialBankAccount
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;
        return $this;
    }

    /**
     * Get accountName
     *
     * @return string 
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Set accountRef
     *
     * @param string $accountRef
     * @return CommercialBankAccount
     */
    public function setAccountRef($accountRef)
    {
        $this->accountRef = $accountRef;
        return $this;
    }

    /**
     * Get accountRef
     *
     * @return string 
     */
    public function getAccountRef()
    {
        return $this->accountRef;
    }

    /**
     * Set accountBalance
     *
     * @param decimal $accountBalance
     * @return CommercialBankAccount
     */
    public function setAccountBalance($accountBalance)
    {
        $accountBalance = (float)  str_replace(",", ".", $accountBalance);
        $this->accountBalance = $accountBalance;
        return $this;
    }

    /**
     * Get accountBalance
     *
     * @return decimal 
     */
    public function getAccountBalance()
    {
        return $this->accountBalance;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     * @return CommercialBankAccount
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * Get bankName
     *
     * @return string 
     */
    public function getBankName()
    {
        return $this->bankName;
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
     * Set coreCompanies
     *
     * @param CoreCompanies $coreCompanies
     * @return CommercialBankAccount
     */
    public function setCompany(\CoreCompanies $coreCompanies = null)
    {
        $this->company = $coreCompanies;
        return $this;
    }
    
    /**
     * Add the passed values to the account balance
     * @param float $amount
     */
    public function updateAccountBalance($amount) {
        $amount = (float)  str_replace(",", ".", $amount);
        $this->accountBalance += $amount;
    }

    /**
     * Get coreCompanies
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
        // Setting company
        if($this->company == null) {
            $this->company = \IgestisSecurity::init()->user->getCompany();
        }
    }
}


// ---------------------------------------------------------------------

class CommercialBankAccountRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("a")
               ->from("CommercialBankAccount", "a")
               ->where("a.company = :company")
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