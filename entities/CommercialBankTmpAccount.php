<?php

/**
 * CommercialBankTmpAccount
 *
 * @Table(name="COMMERCIAL_BANK_TMP_ACCOUNT")
 * @Entity(repositoryClass="CommercialBankAccountTmpAccountRepository")
 * @HasLifecycleCallbacks
 */
class CommercialBankTmpAccount
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
     * @var datetime $importTimestamp
     *
     * @Column(name="import_timestamp", type="datetime")
     */
    private $importTimestamp;

    /**
     * @var string $tokenring
     *
     * @Column(name="tokenring", type="string", length=20)
     */
    private $tokenring;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialBankAccount
     *
     * @ManyToOne(targetEntity="CommercialBankAccount")
     * @JoinColumns({
     *   @JoinColumn(name="linked_bank_account_id", referencedColumnName="id")
     * })
     */
    private $linkedBankAccount;
    
    /**
     * @var CoreContacts
     *
     * @ManyToOne(targetEntity="CoreContacts")
     * @JoinColumns({
     *   @JoinColumn(name="importer_contact_id", referencedColumnName="id")
     * })
     */
    private $importerContact;
    
    
    /**
     * @var Doctrine\Common\Collections\ArrayCollection()
     * 
     * @OneToMany(targetEntity="CommercialBankTmpOperation", mappedBy="tmpAccount", cascade={"all"})
     */
    private $operations;


    public function __construct() {
        $this->setImportTimestamp(time());
        $this->operations = new Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Set accountName
     *
     * @param string $accountName
     * @return CommercialBankTmpAccount
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
     * @return CommercialBankTmpAccount
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
     * @return CommercialBankTmpAccount
     */
    public function setAccountBalance($accountBalance)
    {
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
     * @return CommercialBankTmpAccount
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
     * Set importTimestamp
     *
     * @param int|\DateTime $importTimestamp timestamp got with time() function
     * @return CommercialBankTmpAccount
     */
    public function setImportTimestamp($importTimestamp)
    {
    	if($importTimestamp instanceof \DateTime) {
    		$this->importTimestamp = $importTimestamp;
    	}
    	else {
    		$this->importTimestamp = DateTime::createFromFormat('U', $importTimestamp);
    	}
        
        return $this;
    }

    /**
     * Get importTimestamp
     *
     * @return datetime 
     */
    public function getImportTimestamp()
    {
        return $this->importTimestamp;
    }

    /**
     * Set tokenring
     *
     * @param string $tokenring
     * @return CommercialBankTmpAccount
     */
    public function setTokenring($tokenring)
    {
        $this->tokenring = $tokenring;
        return $this;
    }

    /**
     * Get tokenring
     *
     * @return string 
     */
    public function getTokenring()
    {
        return $this->tokenring;
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
     * Set linkedBankAccount
     *
     * @param CommercialBankAccount $linkedBankAccount
     * @return CommercialBankTmpAccount
     */
    public function setLinkedBankAccount(\CommercialBankAccount $linkedBankAccount = null)
    {
        $this->linkedBankAccount = $linkedBankAccount;
        return $this;
    }

    /**
     * Get linkedBankAccount
     *
     * @return CommercialBankAccount 
     */
    public function getLinkedBankAccount()
    {
        return $this->linkedBankAccount;
    }
    
    /**
     * Set importerContact
     *
     * @param CoreContacts $importerContact
     * @return CommercialBankTmpOperation
     */
    public function setImporterContact(\CoreContacts $importerContact = null)
    {
        $this->importerContact = $importerContact;
        return $this;
    }

    /**
     * Get importerContact
     *
     * @return CoreContacts 
     */
    public function getImporterContact()
    {
        return $this->importerContact;
    }
    
    /**
     * Add an operation to the operations list
     * @param CommercialBankTmpOperation $operation
     * @return \CommercialBankTmpAccount
     */
    public function addOperation(CommercialBankTmpOperation $operation) {
        $this->operations->add($operation);
        $operation->setTmpAccount($this);
        return $this;
    }
    
    public function getOperations() {
        return $this->operations;
    }
    
    /**
     * Reset all linked operations
     */
    public function resetOperations(){
        foreach ($this->operations as $operation) {
            $operation->setTmpAccount(null);
        }
        $this->operations->clear();
    }    
    
    /**
     * Create a new account from the temporary one
     * @return CommercialBankAccount
     */
    public function createNewAccount() {
        $account = new CommercialBankAccount();
        $account->setAccountBalance($this->accountBalance)
                ->setAccountName($this->accountName)
                ->setAccountRef($this->accountRef)
                ->setBankName($this->bankName);
        
        $this->linkedBankAccount = $account;
        return $account;
    }

}

// ---------------------------------------------------------------------

class CommercialBankAccountTmpAccountRepository extends \Doctrine\ORM\EntityRepository {
    
    public function findCurrentUserLastImport() {
        try {
            if(empty($_SESSION['commercialTmpImportToken'])) return null;
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("a")
               ->from("CommercialBankTmpAccount", "a")
               ->leftJoin("a.operations", "o")
               ->andWhere("a.tokenring = :token")
               ->setParameter("token", $_SESSION['commercialTmpImportToken']);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();
    }

}