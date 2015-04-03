<?php


use Igestis\Modules\Commercial\Common\StringManipulation;


/**
 * CommercialProject
 *
 * @Table(name="COMMERCIAL_PROJECT")
 * @Entity(repositoryClass="CommercialProjectRepository")
 * @HasLifecycleCallbacks
 */
class CommercialProject
{
    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=120)
     */
    private $name;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text")
     */
    private $description;
    
    /**
     *
     * @var boolean 
     * @Column(name="closed", type="boolean")
     */
    private $closed;

    /**
     * @var decimal $purchasingPriceDf
     *
     * @Column(name="initial_time_sold", type="decimal")
     */
    private $initialTimeSold;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CoreContacts
     *
     * @ManyToOne(targetEntity="CoreContacts")
     * @JoinColumns({
     *   @JoinColumn(name="creator_contact_id", referencedColumnName="id")
     * })
     */
    private $creatorContact;
    
    /**
     * @var CoreUsers
     *
     * @ManyToOne(targetEntity="CoreUsers")
     * @JoinColumns({
     *   @JoinColumn(name="customer_user_id", referencedColumnName="id")
     * })
     */
    private $customerUser;

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
     * @var \Doctrine\Common\Collections\ArrayCollection List  of the associated rows
     * @OneToMany(targetEntity="CommercialCommercialDocument", mappedBy="project")
     */
    private $commercialDocuments;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection List  of the associated rows
     * @OneToMany(targetEntity="CommercialSupportIntervention", mappedBy="project")
     */
    private $supportInterventions;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection List  of the associated rows
     * @OneToMany(targetEntity="CommercialProviderInvoice", mappedBy="project")
     */
    private $providerInvoices;
    

    public function __construct() {
        $this->commercialDocuments = new \Doctrine\Common\Collections\ArrayCollection  ;
        $this->supportInterventions = new \Doctrine\Common\Collections\ArrayCollection  ;
        $this->providerInvoices = new \Doctrine\Common\Collections\ArrayCollection  ;
        
        $this->initialTimeSold = 0;
        $this->closed = false;
    }
    
    /**
     * 
     * @param \CommercialCommercialDocument $document
     */
    public function addCommercialDocument(\CommercialCommercialDocument $document) {
        $this->commercialDocuments->add($document);
    }
    
    /**
     * 
     * @param \CommercialProviderInvoice $invoice
     */
    public function addProviderInvoice(\CommercialProviderInvoice $invoice) {
        $this->providerInvoices->add($invoice);
    }
    
    /**
     * 
     * @param \CommercialSupportIntervention $intervention
     */
    public function addIntervention(\CommercialSupportIntervention $intervention) {
        $this->supportInterventions->add($intervention);
    }
    
    /**
     * 
     * @param \CommercialCommercialDocument $document
     */
    public function removeCommercialDocument(\CommercialCommercialDocument $document) {
        $this->commercialDocuments->removeElement($document);
    }
    
    /**
     * 
     * @param \CommercialProviderInvoice $invoice
     */
    public function removeProviderInvoice(\CommercialProviderInvoice $invoice) {
        $this->providerInvoices->removeElement($invoice);
    }
    
    /**
     * 
     * @param \CommercialSupportIntervention $intervention
     */
    public function removeIntervention(\CommercialSupportIntervention $intervention) {
        $this->supportInterventions->removeElement($intervention);
    }
    
    /**
     * 
     * @return boolean
     */
    public function getClosed() {
        return $this->closed;
    }

    /**
     * 
     * @param boolean $closed
     * @return \CommercialProject
     */
    public function setClosed($closed) {
        $this->closed = $closed;
        return $this;
    }

        

    /**
     * Set name
     *
     * @param string $name
     * @return CommercialProject
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return CommercialProject
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set creatorContact
     *
     * @param CoreContacts $creatorContact
     * @return CommercialProject
     */
    public function setCreatorContact(\CoreContacts $creatorContact = null)
    {
        $this->creatorContact = $creatorContact;
        return $this;
    }

    /**
     * Get creatorContact
     *
     * @return CoreContacts 
     */
    public function getCreatorContact()
    {
        return $this->creatorContact;
    }
    
    /**
     * Set customer
     *
     * @param CoreUsers $creatorContact
     * @return CommercialProject
     */
    public function setCustomerUser(\CoreUsers $customerUser = null)
    {
        $this->customerUser = $customerUser;
        return $this;
    }

    /**
     * Get customer
     *
     * @return CoreUsers
     */
    public function getCustomerUser()
    {
        return $this->customerUser;
    }

    /**
     * Set company
     *
     * @param CoreCompanies $company
     * @return CommercialProject
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
     * Gets the value of initialTimeSold.
     *
     * @return decimal $initialTimeSold
     */
    public function getInitialTimeSold()
    {
        return $this->initialTimeSold;
    }

    /**
     * Gets the value of initialTimeSold.
     *
     * @return decimal $initialTimeSold
     */
    public function getInitialTimeSoldFormatted()
    {
        return StringManipulation::convertDecimalToTimeFormat($this->initialTimeSold);
    }

    /**
     * Sets the value of initialTimeSold.
     *
     * @param decimal $initialTimeSold the initial time sold
     *
     * @return self
     */
    public function setInitialTimeSold($initialTimeSold)
    {
        if (preg_match("#^(|[0-9\-]{1,3}\:[0-9]{1,2})$#", $initialTimeSold)) {
            $aTime = explode(":", $initialTimeSold);
            $initialTimeSold = (int)$aTime[0] * 60;
            if ((int)$aTime[0] < 0) {
                $initialTimeSold -= (int)$aTime[1];
            } else {
                $initialTimeSold += (int)$aTime[1];
            }
        } else {
            $initialTimeSold = (int)$initialTimeSold;
        }
        
        $this->initialTimeSold = $initialTimeSold;

        return $this;
    }
    
        
    /**
     * @PrePersist
     * @PreUpdate
     */
    public function prePersist()
    {
        // Setting company
        if ($this->company == null) {
            $this->company = \IgestisSecurity::init()->user->getCompany();
        }
        
        if (!($this->creatorContact instanceof \CoreContacts)) {
            $this->creatorContact = \IgestisSecurity::init()->contact;
        }
    }
    
    public function getTotCommercialDoc()
    {
        $tot = 0;
        foreach ($this->commercialDocuments as $document) {
            
            $tot += $document->getAmountTi();
        }
        return $tot;
    }
    
    public function getTotSupportIntervention()
    {
        $totTime = 0;
        foreach ($this->supportInterventions as $intervention) {
            $totTime += $intervention->getIntegerPeriodTime();
        }
        return \Igestis\Modules\Commercial\Common\StringManipulation::convertDecimalToTimeFormat($totTime);
    }
    
    public function getTotBuyingInvoices()
    {
        $tot = 0;
        foreach ($this->providerInvoices as $invoice) {
            $tot += $invoice->getAmountTi();
        }
        return $tot;
    }

    public function getTotCreditTime()
    {
        $tot = 0;
        foreach ($this->commercialDocuments as $document) {
            $creditTime = $document->getCreditTime();
            if (!$creditTime) {
                continue;
            }
            $tot += $creditTime->getCreditMinutes();
        }
        return CommercialTimeCredit::getTimeString($tot);
    }

    public function remainingCreditTime()
    {

        $totBuyingInvoices = \Igestis\Modules\Commercial\Common\StringManipulation::convertTimeToDecimalFormat($this->getTotSupportIntervention());
        $totCreditTime = \Igestis\Modules\Commercial\Common\StringManipulation::convertTimeToDecimalFormat($this->getTotCreditTime());

        $tot = $totCreditTime - $totBuyingInvoices + $this->initialTimeSold;
        return \Igestis\Modules\Commercial\Common\StringManipulation::convertDecimalToTimeFormat($tot);
    }
}



// ---------------------------------------------------------------------

class CommercialProjectRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAll()
    {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("p")
               ->from("CommercialProject", "p")
               ->where("p.company = :company")
               ->setParameter("company", $userCompany);
        } catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null)
    {
        $result = parent::find($id, $lockMode, $lockVersion);
        if (!$result || $result->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) {
            return null;
        }
        return $result;
    }
    
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria['company'] = \IgestisSecurity::init()->user->getCompany();
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
    
    public function findFromSearchForm(Igestis\Modules\Commercial\Forms\projectSearchForm $searchForm)
    {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("p")
               ->from("CommercialProject", "p")
               ->where("p.company = :company")
               ->setParameter("company", $userCompany);

            if ($searchForm->getCustomerUser()) {
                $qb->andWhere("p.customerUser = :customerUserId")->setParameter('customerUserId', $searchForm->getCustomerUser());
            }
            
            switch($searchForm->getStatus()) {
                case Igestis\Modules\Commercial\Forms\projectSearchForm::STATUS_CLOSED:
                    $qb->andWhere("p.closed=1");
                    break;
                case Igestis\Modules\Commercial\Forms\projectSearchForm::STATUS_OPENED:
                    $qb->andWhere("p.closed=0");
                    break;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();
    }


    public function findByCustomer(\CoreUsers $customerUser)
    {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("p")
               ->from("CommercialProject", "p")
               ->where("p.customerUser = :customerUser")
               ->setParameter("customerUser", $customerUser);
        } catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();
    }
}
