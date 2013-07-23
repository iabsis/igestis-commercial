<?php



/**
 * CommercialBankTmpOperation
 *
 * @Table(name="COMMERCIAL_BANK_TMP_OPERATION")
 * @Entity
 */
class CommercialBankTmpOperation
{
    /**
     * @var string $label
     *
     * @Column(name="label", type="string", length=120)
     */
    private $label;

    /**
     * @var date $operationDate
     *
     * @Column(name="operation_date", type="date")
     */
    private $operationDate;

    /**
     * @var string $operationType
     *
     * @Column(name="operation_type", type="string")
     */
    private $operationType;

    /**
     * @var string $tokenring
     *
     * @Column(name="tokenring", type="string", length=20)
     */
    private $tokenring;

    /**
     * @var datetime $importTimestamp
     *
     * @Column(name="import_timestamp", type="datetime")
     */
    private $importTimestamp;

    /**
     * @var string $operationRef
     *
     * @Column(name="operation_ref", type="string", length=20)
     */
    private $operationRef;

    /**
     * @var decimal $operationAmount
     *
     * @Column(name="operation_amount", type="decimal")
     */
    private $operationAmount;
    
    /**
     * @var boolean $alreadyExists Does the operation already exist ?
     *
     * @Column(name="already_exists", type="boolean")
     */
    private $alreadyExists;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialBankTmpAccount
     *
     * @ManyToOne(targetEntity="CommercialBankTmpAccount")
     * @JoinColumns({
     *   @JoinColumn(name="tmp_account_id", referencedColumnName="id")
     * })
     */
    private $tmpAccount;



    /**
     * Set label
     *
     * @param string $label
     * @return CommercialBankTmpOperation
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
     * Set operationDate
     *
     * @param date $operationDate
     * @return CommercialBankTmpOperation
     */
    public function setOperationDate($operationDate)
    {
        $this->operationDate = $operationDate;
        return $this;
    }

    /**
     * Get operationDate
     *
     * @return date 
     */
    public function getOperationDate()
    {
        return $this->operationDate;
    }

    /**
     * Set operationType
     *
     * @param string $operationType
     * @return CommercialBankTmpOperation
     */
    public function setOperationType($operationType)
    {
        $this->operationType = $operationType;
        return $this;
    }

    /**
     * Get operationType
     *
     * @return string 
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * Set tokenring
     *
     * @param string $tokenring
     * @return CommercialBankTmpOperation
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
     * Set operationRef
     *
     * @param string $operationRef
     * @return CommercialBankTmpOperation
     */
    public function setOperationRef($operationRef)
    {
        $this->operationRef = $operationRef;
        return $this;
    }

    /**
     * Get operationRef
     *
     * @return string 
     */
    public function getOperationRef()
    {
        return $this->operationRef;
    }

    /**
     * Set operationAmount
     *
     * @param decimal $operationAmount
     * @return CommercialBankTmpOperation
     */
    public function setOperationAmount($operationAmount)
    {
        $this->operationAmount = $operationAmount;
        return $this;
    }

    /**
     * Get operationAmount
     *
     * @return decimal 
     */
    public function getOperationAmount()
    {
        return $this->operationAmount;
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
     * Set tmpAccount
     *
     * @param CommercialBankTmpAccount $tmpAccount
     * @return CommercialBankTmpOperation
     */
    public function setTmpAccount(\CommercialBankTmpAccount $tmpAccount = null)
    {
        $this->tmpAccount = $tmpAccount;
        $this->tokenring = $tmpAccount->getTokenring();
        $this->setImportTimestamp($tmpAccount->getImportTimestamp());
        return $this;
    }

    /**
     * Get tmpAccount
     *
     * @return CommercialBankTmpAccount 
     */
    public function getTmpAccount()
    {
        return $this->tmpAccount;
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
     * 
     * @return boolean True if operation already exist, false else
     */
    public function doesAlreadyExist() {
        return $this->alreadyExists;
    }
    
    /**
     * 
     * @param boolean $exist
     * @return CommercialBankTmpOperation
     */
    public function setAlreadyExists(bool $exist) {
        $this->alreadyExists = $exist;
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
     * Create a new bank operation from the current temporary operation
     * @return CommercialBankOperation
     */
    public function createNewOperation() {
        $operation = new CommercialBankOperation();
        $operation->setAccount($this->tmpAccount->getLinkedBankAccount())
                  ->setImportDate(new \DateTime)
                  ->setLabel($this->label)
                  ->setOperationAmount($this->operationAmount)
                  ->setOperationDate($this->operationDate)
                  ->setOperationRef($this->operationRef)
                  ->setOperationType($this->operationType);
        return $operation;
    }

}