<?php


/**
 * CommercialPurchaseOrder
 *
 * @Table(name="COMMERCIAL_PURCHASE_ORDER")
 * @Entity(repositoryClass="CommercialPurchaseOrderRepository")
 * @HasLifecycleCallbacks
 */
class CommercialPurchaseOrder
{
    /**
     * @var string $poNumber
     *
     * @Column(name="po_number", type="string", length=12)
     */
    private $poNumber;

    /**
     * @var date $documentDate
     *
     * @Column(name="document_date", type="date")
     */
    private $documentDate;


    /**
     * @var string $taxNumCustomer
     *
     * @Column(name="tax_num_customer", type="string", length=20)
     */
    private $taxNumCustomer;

    /**
     * @var string $pathPdfFile
     *
     * @Column(name="path_pdf_file", type="string", length=256)
     */
    private $pathPdfFile;


    /**
     * @var decimal $documentTotalDf
     *
     * @Column(name="document_total_DF", type="decimal")
     */
    private $documentTotalDf;

    /**
     * @var decimal $documentTotalTi
     *
     * @Column(name="document_total_TI", type="decimal")
     */
    private $documentTotalTi;

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
     * @ManyToOne(targetEntity="CommercialProject")
     * @JoinColumns({
     *   @JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    
    /**
     * @OneToMany(targetEntity="CommercialPurchaseOrderArticle", mappedBy="PurchaseOrder", cascade={"all"})
     * @OrderBy({"articleOrder" = "ASC"})
     */
    private $articles;
    
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
     * 
     * @param \CommercialCommercialDocument $commercialDocument
     */
    public function __construct(\CommercialCommercialDocument $commercialDocument) {
        $this->articles = new Doctrine\Common\Collections\ArrayCollection();
        $this->documentDate = new \DateTime();
        
        if($commercialDocument) {
            // Import all commercial document into the current new estimate
            $this->updateFromCommercialDocument($commercialDocument);
        }
    }
    
    /**
     * 
     * @param CommercialCommercialDocument $commercialDocument
     */
    public function updateFromCommercialDocument(CommercialCommercialDocument $commercialDocument) {
        $this->articles = new Doctrine\Common\Collections\ArrayCollection();
        $this->commercialDocument = $commercialDocument;
        
        foreach($this->commercialDocument->getArticles() as $currentArticle) {
            $newArticle = new CommercialPurchaseOrderArticle;
            $newArticle->setAccountingLabel($currentArticle->getSellingAccount()->getLabel())
                       ->setAccountingNumber($currentArticle->getSellingAccount()->getAccountNumber())
                       ->setAmountTax($currentArticle->getAmountTax())
                       ->setArticleOrder($currentArticle->getArticleOrder())
                       ->setComment($currentArticle->getComment())
                       ->setPurchaseOrder($this)
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

            $this->setTaxNumCustomer($this->commercialDocument->getCustomerUser()->getTvaNumber());
        }
    }


    /**
     * Gets the value of poNumber.
     *
     * @return string $poNumber
     */
    public function getPoNumber()
    {
        return $this->poNumber;
    }

    /**
     * Sets the value of poNumber.
     *
     * @param string $poNumber $poNumber the delivery form number
     *
     * @return self
     */
    public function setPoNumber($poNumber)
    {
        $this->poNumber = $poNumber;

        return $this;
    }

    /**
     * Gets the value of documentDate.
     *
     * @return date $documentDate
     */
    public function getDocumentDate()
    {
        return $this->documentDate;
    }

    /**
     * Sets the value of documentDate.
     *
     * @param date $documentDate $documentDate the document date
     *
     * @return self
     */
    public function setDocumentDate($documentDate)
    {
        $this->documentDate = $documentDate;

        return $this;
    }

    /**
     * Gets the value of taxNumCustomer.
     *
     * @return string $taxNumCustomer
     */
    public function getTaxNumCustomer()
    {
        return $this->taxNumCustomer;
    }

    /**
     * Sets the value of taxNumCustomer.
     *
     * @param string $taxNumCustomer $taxNumCustomer the tax num customer
     *
     * @return self
     */
    public function setTaxNumCustomer($taxNumCustomer)
    {
        $this->taxNumCustomer = $taxNumCustomer;

        return $this;
    }

    /**
     * Gets the value of pathPdfFile.
     *
     * @return string $pathPdfFile
     */
    public function getPathPdfFile()
    {
        return $this->pathPdfFile;
    }

    /**
     * Sets the value of pathPdfFile.
     *
     * @param string $pathPdfFile $pathPdfFile the path pdf file
     *
     * @return self
     */
    public function setPathPdfFile($pathPdfFile)
    {
        $this->pathPdfFile = $pathPdfFile;

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
     * @return CommercialCommercialDocument
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Sets the }).
     *
     * @param CommercialCommercialDocument $project the project
     *
     * @return self
     */
    public function setProject(CommercialCommercialDocument $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Sets the value of articles.
     *
     * @param mixed $articles the articles
     *
     * @return self
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;

        return $this;
    }

    /**
     * Gets the }).
     *
     * @return CommercialCommercialDocument
     */
    public function getCommercialDocument()
    {
        return $this->commercialDocument;
    }

    /**
     * Sets the }).
     *
     * @param CommercialCommercialDocument $commercialDocument the commercial document
     *
     * @return self
     */
    public function setCommercialDocument(CommercialCommercialDocument $commercialDocument)
    {
        $this->commercialDocument = $commercialDocument;

        return $this;
    }


    /**
     * Set documentTotalDf
     *
     * @param decimal $documentTotalDf
     * @return Commercialdocument
     */
    public function setDocumentTotalDf($documentTotalDf)
    {
        $documentTotalDf = (float)  str_replace(",", ".", $documentTotalDf);
        $this->documentTotalDf = $documentTotalDf;
        return $this;
    }

    /**
     * Get documentTotalDf
     *
     * @return decimal 
     */
    public function getDocumentTotalDf()
    {
        return $this->documentTotalDf;
    }

    /**
     * Set documentTotalTi
     *
     * @param decimal $documentTotalTi
     * @return Commercialdocument
     */
    public function setDocumentTotalTi($documentTotalTi)
    {
        $documentTotalTi = (float)  str_replace(",", ".", $documentTotalTi);
        $this->documentTotalTi = $documentTotalTi;
        return $this;
    }

    /**
     * Get documentTotalTi
     *
     * @return decimal 
     */
    public function getDocumentTotalTi()
    {
        return $this->documentTotalTi;
    }

    /**
     * Add a new article to the list
     *
     * @param \CommercialPurchaseOrderArticle $article
     * @return self
     */
    public function addArticle(\CommercialPurchaseOrderArticle $article)
    {        
        $this->articles[] = $article;
        $article->setPurchaseOrder($this);
        return $this;
    }

    
    /**
     * Return the list of associated articles
     * @return array(\CommercialEstimateArticle) List of articles
     */
    public function getArticles() {
        return $this->articles;
    }
    
    public function removeArticles() {
        $this->articles->clear();
    }
    
    /**
     * @PrePersist
     * @PreUpdate
     */
    public function PrePersist() {
        $this->documentTotalDf = 0;
        $this->documentTotalTi = 0;
        foreach ($this->articles as $article) {
            $this->documentTotalDf += $article->getTotSellPriceArticleDf();
            $this->documentTotalTi += $article->getTotSellPriceArticleTi();
        }
    }    
}

class CommercialPurchaseOrderRepository extends Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("df")
               ->from("CommercialPurchaseOrder", "df")
               ->leftJoin("df.commercialDocument", "cd")
               ->where("cd.company = :company")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult(); 
    }
    
    public function find($id, $lockMode = null, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        if(!$result || $result->getCommercialDocument()->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return null;
        return $result;
    }
    
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        $criteria['company'] = \IgestisSecurity::init()->user->getCompany();
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

}