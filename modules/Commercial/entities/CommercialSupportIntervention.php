<?php

use Igestis\Modules\Commercial\Common;

/**
 * CommercialSupportIntervention
 *
 * @Table(name="COMMERCIAL_SUPPORT_INTERVENTION")
 * @Entity(repositoryClass="CommercialSupportInterventionRepository")
 * @HasLifecycleCallbacks
 */
class CommercialSupportIntervention
{
    /**
     * @var datetime $date
     *
     * @Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var datetime $end
     *
     * @Column(name="end", type="datetime")
     */
    private $end;

    /**
     * @var integer $pause
     *
     * @Column(name="pause", type="integer")
     */
    private $pause;

    /**
     * @var string $title
     *
     * @Column(name="title", type="string", length=120)
     */
    private $title;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer $period
     *
     * @Column(name="period", type="integer")
     */
    private $period;

    /**
     * @var string $fileName
     *
     * @Column(name="file_name", type="string", length=50)
     */
    private $fileName;

    /**
     * @var string $type
     *
     * @Column(name="type", type="string", length=45)
     */
    private $type;

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
     * @var CoreContacts
     *
     * @ManyToOne(targetEntity="CoreContacts")
     * @JoinColumns({
     *   @JoinColumn(name="reported_by_contact_id", referencedColumnName="id")
     * })
     */
    private $workerContact;

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
     * @var CommercialProject
     *
     * @ManyToOne(targetEntity="CommercialProject")
     * @JoinColumns({
     *   @JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;
    
    public function __construct()
    {
        $this->workerContact =  \IgestisSecurity::init()->contact;
    }

    /**
     * Return a copy of the original intervention
     * @return self copy of the object
     */
    public function duplicate($date = null)
    {
        $newIntervention = clone($this);
        $newIntervention->id = null;

        $newIntervention->workerContact = \IgestisSecurity::init()->contact;

        if ($date) {
            $newIntervention->date = $date->setTime(
                $newIntervention->date->format("H"),
                $newIntervention->date->format("i"),
                $newIntervention->date->format("s")
            );
            $newIntervention->end->setDate($date->format("Y"), $date->format("m"), $date->format("d"));

            if ($newIntervention->end < $newIntervention->date) {
                $newIntervention->end->add(new DateInterval('P1D'));
            }
        }

        return $newIntervention;
    }



    /**
     * Set date
     *
     * @param datetime $date
     * @return CommercialSupportIntervention
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set end
     *
     * @param datetime $end
     * @return CommercialSupportIntervention
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * Get end
     *
     * @return datetime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set pause
     *
     * @param integer|string $pause
     * @return CommercialSupportIntervention
     */
    public function setPause($pause)
    {
        if(preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $pause)) {
            $aTime = explode(":", $pause);
            $pause = (int)$aTime[0] * 60 + (int)$aTime[1];
        }
        else $pause = (int)$pause;
        
        $this->pause = $pause;
        return $this;
    }
   

    /**
     * Get pause
     *
     * @return integer 
     */
    public function getPause()
    {
        return $this->pause;
    }
    
    /**
     * Return the pause duration in string format
     * @return string H:i
     */
    public function getPauseTime()
    {
        return Common\StringManipulation::convertDecimalToTimeFormat($this->pause);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return CommercialSupportIntervention
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return CommercialSupportIntervention
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
     * Set period
     *
     * @param integer $period
     * @return CommercialSupportIntervention
     */
    public function setPeriod($period)
    {
        if(preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $period)) {
            $aTime = explode(":", $period);
            $period = (int)$aTime[0] * 60 + (int)$aTime[1];
        }
        else $period = (int)$period;
        
        $this->period = $period;
        return $this;
    }

    /**
     * Get period
     *
     * @return integer 
     */
    public function getPeriod()
    {
        return $this->period;
    }
    
    /**
     * Return the duration in string format
     * @return string H:i
     */
    public function getPeriodTime()
    {
       return Common\StringManipulation::convertDecimalToTimeFormat($this->period);
    }
    
    /**
     * Return the duration in string format
     * @return string H:i
     */
    public function getIntegerPeriodTime()
    {
       return $this->period;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return CommercialSupportIntervention
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return CommercialSupportIntervention
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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
     * Set reportedByContact
     *
     * @param CoreContacts $reportedByContact
     * @return CommercialSupportIntervention
     */
    public function setWorkerContact(\CoreContacts $reportedByContact = null)
    {
        $this->workerContact = $reportedByContact;
        return $this;
    }

    /**
     * Get reportedByContact
     *
     * @return CoreContacts 
     */
    public function getWorkerContact()
    {
        return $this->workerContact;
    }

    /**
     * Set customerUser
     *
     * @param CoreUsers $customerUser
     * @return CommercialSupportIntervention
     */
    public function setCustomerUser(\CoreUsers $customerUser = null)
    {
        $this->customerUser = $customerUser;
        return $this;
    }

    /**
     * Get customerUser
     *
     * @return CoreUsers 
     */
    public function getCustomerUser()
    {
        return $this->customerUser;
    }

    /**
     * Set project
     *
     * @param CommercialProject $project
     * @return CommercialSupportIntervention
     */
    public function setProject(\CommercialProject $project = null)
    {
        if($project != null) $project->addIntervention($this);
        else {
            if($this->project) {
                $this->project->removeIntervention($this);
            }
        }
        
        $this->project = $project;
        return $this;
    }

    /**
     * Get project
     *
     * @return CommercialProject 
     */
    public function getProject()
    {
        return $this->project;
    }
    
    /**
     * Set company
     *
     * @param CoreCompanies $company
     * @return CommercialArticle
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
        // Setting company
        if($this->company == null) {
            $this->company = \IgestisSecurity::init()->user->getCompany();
        }
        
        if(!$this->workerContact){
            $this->workerContact =  \IgestisSecurity::init()->contact;
        }
        
        if($this->getProject()) {
            $this->customerUser = $this->getProject()->getCustomerUser();
        }
    }
}

// ---------------------------------------------------------------------

class CommercialSupportInterventionRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("i")
               ->from("CommercialSupportIntervention", "i")
               ->where("i.company = :company")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();        
        
    }
    
    public function findAllBySearchForm(Igestis\Modules\Commercial\Forms\InterventionsSearchForm $searchForm, $returnOnlyTotalTime=false) {
        
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("i")
               ->from("CommercialSupportIntervention", "i")
               ->where("i.company = :company")
               ->setParameter("company", $userCompany);
            
            if($searchForm->getCustomerUser()) {
                $qb->andWhere("i.customerUser = :customerUser")->setParameter("customerUser", $searchForm->getCustomerUser());
            }
            if($searchForm->getEmployeeContact()) {
                $qb->andWhere("i.workerContact = :employeeContact")->setParameter("employeeContact", $searchForm->getEmployeeContact());
            }
            if($searchForm->getType()) {
                $qb->andWhere("i.type = :type")->setParameter("type", $searchForm->getType());
            }
            if($searchForm->getFrom()) {
                $qb->andWhere("i.date >= :dateFrom")->setParameter("dateFrom", $searchForm->getFrom()->format("Y-m-d"));
            }
            if($searchForm->getTo()) {
                $to = clone $searchForm->getTo();
                $to->add(new DateInterval("P1D"));
                $qb->andWhere("i.date < :dateTo")->setParameter("dateTo", $to->format("Y-m-d"));
            }
            if($returnOnlyTotalTime) {
                $qb->select("sum(i.period) as totalPeriod");
                $result = $qb->getQuery()->getResult();
                if(!$result || count($result) != 1) {
                    return '00:00';
                }
                else {
                    return Common\StringManipulation::convertDecimalToTimeFormat($result[0]['totalPeriod']);
                }                
            }
            else {
                return $qb->getQuery()->getResult(); 
            }
        }
        catch (\Exception $e) {
            throw $e;
        }
          
    }
    
    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        if(!$result || $result->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return null;
        return $result;
    }
    
    public function findAllTypes($simpleArray = false) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("i.type")
               ->from("CommercialSupportIntervention", "i")
               ->where("i.company = :company")
               ->andWhere("TRIM(i.type) != '' and i.type is not null")
               ->groupBy("i.type")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        $result = $qb->getQuery()->getArrayResult();
        if($simpleArray) {            
            $return = array();
            foreach ($result as $type) $return[] = $type['type'];

            return $return; 
        }
        else return $result;
    }
    
    /**
     * 
     * @param \CoreUsers $customerUser
     * @return type
     * @throws Exception
     */
    public function getUnlinkedToProjectForCustomer($customerUser) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("i")
               ->from("CommercialSupportIntervention", "i")
               ->where("i.company = :company")
               ->andWhere("i.project is null")
               ->andWhere("i.customerUser = :customerUser")
               ->setParameter("company", $userCompany)
               ->setParameter("customerUser", $customerUser);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();   
    }

}