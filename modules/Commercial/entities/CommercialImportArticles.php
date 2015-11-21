<?php


/**
 * CommercialArticle
 *
 * @Table(name="COMMERCIAL_IMPORT_ARTICLES")
 * @Entity(repositoryClass="CommercialImportArticlesRepository")
 * @HasLifecycleCallbacks
 */
class CommercialImportArticles
{
    /**
     * @var string $importLabel
     *
     * @Column(name="import_label", type="string", length=120)
     */
    private $importLabel; 

    /**
     * @var \Datetime $importDate
     *
     * @Column(name="import_date", type="datetime")
     */
    private $importDate; 

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \CoreCompanies
     *
     * @ManyToOne(targetEntity="CoreCompanies")
     * @JoinColumns({
     *   @JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var \CoreContacts
     *
     * @ManyToOne(targetEntity="CoreContacts")
     * @JoinColumns({
     *   @JoinColumn(name="importer_contact_id", referencedColumnName="id")
     * })
     */
    private $importerContact;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="CommercialArticle", mappedBy="import_id", cascade={"persist"})
     * 
     */
    private $articles;

    public function __construct(\CoreCompanies $company, $importLabel)
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->importDate = new \Datetime;
        $this->importLabel = $importLabel;
        $this->company = $company;
    }

    /**
     * Gets the value of importLabel.
     *
     * @return string $importLabel
     */
    public function getImportLabel()
    {
        return $this->importLabel;
    }

    /**
     * Sets the value of importLabel.
     *
     * @param string $importLabel $importLabel the import label
     *
     * @return self
     */
    public function setImportLabel($importLabel)
    {
        $this->importLabel = $importLabel;

        return $this;
    }

    /**
     * Gets the value of importDate.
     *
     * @return \Datetime $importDate
     */
    public function getImportDate()
    {
        return $this->importDate;
    }

    /**
     * Sets the value of importDate.
     *
     * @param \Datetime $importDate $importDate the import date
     *
     * @return self
     */
    public function setImportDate($importDate)
    {
        $this->importDate = $importDate;

        return $this;
    }

    /**
     * Gets the value of id.
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param integer $id $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the }).
     *
     * @return CoreCompanies
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the }).
     *
     * @param CoreCompanies $company the company
     *
     * @return self
     */
    public function setCompany(\CoreCompanies $company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Gets the }).
     *
     * @return importerContact
     */
    public function getImporterContact()
    {
        return $this->importerContact;
    }

    /**
     * Sets the }).
     *
     * @param importerContact $importerContact the importer contact
     *
     * @return self
     */
    public function setImporterContact(\CoreContacts $importerContact)
    {
        $this->importerContact = $importerContact;

        return $this;
    }

    /**
     * Gets the value of articles.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Sets the value of articles.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $articles the articles
     *
     * @return self
     */
    public function setArticles(\Doctrine\Common\Collections\ArrayCollection $articles)
    {
        $this->articles = $articles;

        return $this;
    }

    public function addArticles(CommercialArticle $article)
    {
        $this->articles->add($article);
        $article->setCompany($this->company);
        $article->setImport($this);
    }

    
}


// ---------------------------------------------------------------------

class CommercialImportArticlesRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("a")
               ->from("CommercialArticle", "a")
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