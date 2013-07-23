<?php

use Doctrine\ORM\Mapping\PreRemove;
use Doctrine\ORM\Mapping\PostLoad;
/**
 * CommercialBankOperation
 *
 * @Table(name="COMMERCIAL_BANK_OPERATION")
 * @Entity(repositoryClass="CommercialBankOperationRepository")
 * @HasLifecycleCallbacks
 */
class CommercialBankOperation
{
    const OPERATION_TYPE_DEBIT = "debit";
    const OPERATION_TYPE_CREDIT = "credit";
    
    /**
     * @var boolean $notAssign
     *
     * @Column(name="not_assign", type="boolean")
     */
    private $notAssign;
    
    
    /**
     * @var string $label
     *
     * @Column(name="label", type="string", length=120)
     */
    private $label;

    /**
     * @var datetime $importDate
     *
     * @Column(name="import_date", type="datetime")
     */
    private $importDate;

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
     * 
     * @var decimal $initialOperationAmount
     */
    private $initialOperationAmount;

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
     * @ManyToOne(targetEntity="CommercialBankAccount", cascade={"persist"})
     * @JoinColumns({
     *   @JoinColumn(name="account_id", referencedColumnName="id")
     * })
     */
    private $account;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection List  of the associated rows
     * @OneToMany(targetEntity="CommercialBankAssocOperations", mappedBy="operation", cascade={"all"}, orphanRemoval=true)
     */
    private $assocs;
    
    public function __construct() {
        $this->assocs  =  new Doctrine\Common\Collections\ArrayCollection();
        $this->notAssign = false;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getNotAssign() {
        return $this->notAssign;
    }

    /**
     * 
     * @param boolean $notAssign
     * @return \CommercialBankOperation
     */
    public function setNotAssign($notAssign) {
        $this->notAssign = (bool)$notAssign;
        return $this;
    }

        
    /**
     * list of the associations
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAssocs() {
        return $this->assocs;
    }
    
    /**
     * Add a new amount association
     * @param \CommercialBankAssocOperations $assoc
     * @return \CommercialBankOperation
     */
    public function addAssoc(\CommercialBankAssocOperations $assoc) {
        $assoc->setOperation($this);
        $this->assocs->add($assoc);
        return $this;
    }
    
    public function emptyAssocs() {
        foreach ($this->assocs as $assoc) {
            $assoc->setOperation(null);
        }
        $this->assocs->clear();
    }

        

    /**
     * Set label
     *
     * @param string $label
     * @return CommercialBankOperation
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
     * Set importDate
     *
     * @param datetime $importDate
     * @return CommercialBankOperation
     */
    public function setImportDate($importDate)
    {
        $this->importDate = $importDate;
        return $this;
    }

    /**
     * Get importDate
     *
     * @return datetime 
     */
    public function getImportDate()
    {
        return $this->importDate;
    }

    /**
     * Set operationDate
     *
     * @param date $operationDate
     * @return CommercialBankOperation
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
     * @return CommercialBankOperation
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
     * Set operationRef
     *
     * @param string $operationRef
     * @return CommercialBankOperation
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
     * @return CommercialBankOperation
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
     * Set account
     *
     * @param CommercialBankAccount $account
     * @return CommercialBankOperation
     */
    public function setAccount(\CommercialBankAccount $account = null)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Get account
     *
     * @return CommercialBankAccount 
     */
    public function getAccount()
    {
        return $this->account;
    }
    
    /**
     * @PreRemove
     */
    public function PreRemove() {
        $this->account->updateAccountBalance(-1 * $this->operationAmount);
    }
    
    /**
     * @PostLoad
     */
    public function PostLoad() {
        $this->initialOperationAmount = $this->operationAmount;
    }
    
    /**
     * @PreUpdate
     * @PrePersist
     */
    public function PreUpdate() {
        if($this->operationAmount < 0) {
            $this->operationType = self::OPERATION_TYPE_DEBIT;
        }
        else {
            $this->operationType = self::OPERATION_TYPE_CREDIT;
        }
        
        if($this->importDate == null) $this->importDate = new \DateTime; 
        $this->account->updateAccountBalance($this->operationAmount - $this->initialOperationAmount);
    }
}


// ---------------------------------------------------------------------

class CommercialBankOperationRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("o")
            ->from("CommercialBankOperation", "o")
            ->leftJoin("o.account", "a")
            ->where("a.company = :company")
            ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }

        return $qb->getQuery()->getResult();
    }
    
    public function findFromSearchForm(\CommercialBankAccount $account, Igestis\Modules\Commercial\Forms\bankOperationsSearchForm  $searchForm) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("o", "a", "assocs")
               ->from("CommercialBankOperation", "o")
               ->leftJoin("o.account", "a")
               ->leftJoin("o.assocs", "assocs")
               ->where("a.company = :company")
               ->andWhere("o.account = :account")
               ->setParameter("account", $account)
               ->setParameter("company", $userCompany);

            
            switch($searchForm->getAssigned()) {
                case Igestis\Modules\Commercial\Forms\bankOperationsSearchForm::ASSIGNED_NO :
                    $qb->andWhere("assocs.id is null")->andWhere("o.notAssign=0");
                    break;
                case Igestis\Modules\Commercial\Forms\bankOperationsSearchForm::ASSIGNED_YES :
                    $qb->andWhere("(assocs.id is not null or o.notAssign=1)");
                    break;
            }
        }
        catch (\Exception $e) {
            throw $e;
        }

        return $qb->getQuery()->getResult();
    }

    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        if(!$result || $result->getAccount()->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return null;
        return $result;
    }
    
    /**
     * Return the next non assigned operation for the current account
     * @param \CommercialBankAccount $account
     * @return \CommercialBankOperation
     * @throws Exception
     */
    public function findNextNonAssignedOperation(\CommercialBankAccount $account) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("o")
               ->from("CommercialBankOperation", "o")
               ->leftJoin("o.account", "a")
               ->leftJoin("o.assocs", "ao")
               ->where("a.company = :company")
               ->andWhere("a.id = :accountId")
               ->groupBy('o.id')
               ->having('COUNT(ao.id) = 0')
               ->orderBy('o.operationDate')
               ->setParameter("company", $userCompany)
               ->setParameter("accountId", $account->getId())
               ->setMaxResults(1);
        }
        catch (\Exception $e) {
            throw $e;
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

}