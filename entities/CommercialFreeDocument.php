<?php


/**
 * CommercialFreeDocument
 *
 * @Table(name="COMMERCIAL_FREE_DOCUMENT")
 * @Entity(repositoryClass="CommercialFreeDocumentRepository")
 * @HasLifecycleCallbacks
 */
class CommercialFreeDocument
{
    /**
     * @var string $title
     *
     * @Column(name="title", type="string", length=45)
     */
    private $title;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var string $title
     *
     * @Column(name="filename", type="string", length=30)
     */
    private $filename;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialProject
     *
     * @ManyToOne(targetEntity="CommercialProject")
     * @JoinColumns({
     *   @JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;
    
    /**
     * 
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * 
     * @param string $filename
     * @return \CommercialFreeDocument
     */
    public function setFilename($filename) {
        $this->filename = $filename;
        return $this;
    }

    

    /**
     * Set title
     *
     * @param string $title
     * @return CommercialFreeDocument
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
     * @return CommercialFreeDocument
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
     * Set project
     *
     * @param CommercialProject $project
     * @return CommercialFreeDocument
     */
    public function setProject(\CommercialProject $project = null)
    {
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
     * @PrePersist
     * @PreUpdate
     */
    public function PrePersist() {
        if(!trim($this->title)) $this->title = $this->filename;
    }
}

class CommercialFreeDocumentRepository extends \Doctrine\ORM\EntityRepository {

    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        
        if(!$result || $result->getProject()->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return null;
        return $result;
    }

}
