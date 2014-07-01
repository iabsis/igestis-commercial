<?php


/**
 * CommercialEstimate
 *
 * @Table(name="COMMERCIAL_ESTIMATE")
 * @Entity(repositoryClass="CommercialEstimateRepository")
 */
class CommercialEstimate
{
    /**
     * @var string $estimationNumber
     *
     * @Column(name="estimation_number", type="string", length=10)
     */
    private $estimationNumber;

    /**
     * @var date $dateEstimate
     *
     * @Column(name="date_estimate", type="date")
     */
    private $dateEstimate;


    /**
     * @var date $validUntil
     *
     * @Column(name="valid_until", type="date")
     */
    private $validUntil;

    /**
     * @var string $pathPdfFile
     *
     * @Column(name="path_pdf_file", type="string", length=256)
     */
    private $pathPdfFile;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialCommercialDocument
     *
     * @ManyToOne(targetEntity="CommercialCommercialDocument")
     * @JoinColumns({
     *   @JoinColumn(name="commercial_document_id", referencedColumnName="id")
     * })
     */
    private $commercialDocument;
    
    /**
     * @OneToMany(targetEntity="CommercialEstimateArticle", mappedBy="estimate", cascade={"all"})
     * @OrderBy({"articleOrder" = "ASC"})
     */
    private $articles;
    
    public function __construct(\CommercialCommercialDocument $commercialDocument = null) {
        $this->articles = new Doctrine\Common\Collections\ArrayCollection();
        
        if($commercialDocument) {
            // Import all commercial document into the current new estimate
            $this->commercialDocument = $commercialDocument;
            
            foreach($this->commercialDocument->getArticles() as $currentArticle) {
                $newArticle =  new CommercialEstimateArticle;
                $newArticle->setSellingAccount($currentArticle->getSellingAccount())
                           ->setAmountTax($currentArticle->getAmountTax())
                           ->setArticleOrder($currentArticle->getArticleOrder())
                           ->setComment($currentArticle->getComment())
                           ->setEstimate($this)
                           ->setIsGroup($currentArticle->getIsGroup())
                           ->setItemLabel($currentArticle->getItemLabel())
                           ->setItemRef($currentArticle->getItemRef())
                           ->setParent($currentArticle->getParent())
                           ->setPurchasingDfUnitPrice($currentArticle->getPurchasingDfUnitPrice())
                           ->setQuantityArticle($currentArticle->getQuantityArticle())
                           ->setSellingDfUnitPrice($currentArticle->getSellingDfUnitPrice())
                           ->setTaxRate($currentArticle->getTaxRate())
                           ->setTotSellPriceArticleDf($currentArticle->getTotSellPriceArticleDf())
                           ->setTotSellPriceArticleTi($currentArticle->getTotSellPriceArticleTi());
                $this->addArticle($newArticle);
            }
            
        }
    }


    /**
     * Set estimationNumber
     *
     * @param string $estimationNumber
     * @return CommercialEstimate
     */
    public function setEstimationNumber($estimationNumber)
    {
        $this->estimationNumber = $estimationNumber;
        return $this;
    }

    /**
     * Get estimationNumber
     *
     * @return string 
     */
    public function getEstimationNumber()
    {
        return $this->estimationNumber;
    }

    /**
     * Set dateEstimate
     *
     * @param date $dateEstimate
     * @return CommercialEstimate
     */
    public function setDateEstimate($dateEstimate)
    {
        $this->dateEstimate = $dateEstimate;
        return $this;
    }

    /**
     * Get dateEstimate
     *
     * @return date 
     */
    public function getDateEstimate()
    {
        return $this->dateEstimate;
    }

    /**
     * Set validUntil
     *
     * @param date $validUntil
     * @return CommercialEstimate
     */
    public function setValidUntil($validUntil)
    {
        $this->validUntil = $validUntil;
        return $this;
    }

    /**
     * Get validUntil
     *
     * @return date 
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * Set pathPdfFile
     *
     * @param string $pathPdfFile
     * @return CommercialEstimate
     */
    public function setPathPdfFile($pathPdfFile)
    {
        $this->pathPdfFile = $pathPdfFile;
        return $this;
    }

    /**
     * Get pathPdfFile
     *
     * @return string 
     */
    public function getPathPdfFile()
    {
        return $this->pathPdfFile;
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
     * Set commercialDocument
     *
     * @param CommercialCommercialDocument $commercialDocument
     * @return CommercialEstimate
     */
    public function setCommercialDocument(\CommercialCommercialDocument $commercialDocument = null)
    {
        $this->commercialDocument = $commercialDocument;
        return $this;
    }

    /**
     * Get commercialDocument
     *
     * @return CommercialCommercialDocument 
     */
    public function getCommercialDocument()
    {
        return $this->commercialDocument;
    }
    
    /**
     * Add a new article to the list
     *
     * @param \CommercialEstimateArticle $article
     * @return self
     */
    public function addArticle(\CommercialEstimateArticle $article)
    {        
        $this->articles[] = $article;
        $article->setEstimate($this);
        return $this;
    }

    
    /**
     * Return the list of associated articles
     * @return array(\CommercialEstimateArticle) List of articles
     */
    public function getArticles() {
        return $this->articles;
    }
}

// -----------------------------------------------------------------------

class CommercialEstimateRepository extends \Doctrine\ORM\EntityRepository {

    function findLastEstimateForDocument(\CommercialCommercialDocument $document) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("e")
               ->from("CommercialEstimate", "e")
               ->leftJoin("e.commercialDocument", "d")
               ->where("d.company = :company")
               ->andWhere("d = :document")
               ->setParameter("document", $document)
               ->setParameter("company", $userCompany)
               ->orderBy("e.id", "desc")
               ->setMaxResults(1);
            return $qb->getQuery()->getOneOrNullResult(); 

        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return null;
        
    }

}