<?php


/**
 * CommercialArticle
 *
 * @Table(name="COMMERCIAL_ARTICLE")
 * @Entity(repositoryClass="CommercialArticleRepository")
 * @HasLifecycleCallbacks
 */
class CommercialArticle
{
    /**
     * @var string $companyRef
     *
     * @Column(name="company_ref", type="string", length=45)
     */
    private $companyRef; 

    /**
     * @var string $manufacturerRef
     *
     * @Column(name="manufacturer_ref", type="string", length=45)
     */
    private $manufacturerRef;

    /**
     * @var string $designation
     *
     * @Column(name="designation", type="string", length=120)
     */
    private $designation;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text")
     */
    private $description;

    /**
     * @var decimal $purchasingPriceDf
     *
     * @Column(name="purchasing_price_df", type="decimal")
     */
    private $purchasingPriceDf;

    /**
     * @var decimal $sellingPriceDf
     *
     * @Column(name="selling_price_df", type="decimal")
     */
    private $sellingPriceDf;

    /**
     * @var boolean $isGroup
     *
     * @Column(name="is_group", type="boolean")
     */
    private $isGroup;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialSellingAccount
     *
     * @ManyToOne(targetEntity="CommercialSellingAccount")
     * @JoinColumns({
     *   @JoinColumn(name="selling_account_id", referencedColumnName="id")
     * })
     */
    private $sellingAccount;

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
     * @var taxeRate
     *
     * @ManyToOne(targetEntity="CommercialTaxeRate")
     * @JoinColumns({
     *   @JoinColumn(name="taxe_rate_id", referencedColumnName="id")
     * })
     */
    private $taxeRate;

    /**
     * @var CommercialArticle
     *
     * @ManyToOne(targetEntity="CommercialArticle")
     * @JoinColumns({
     *   @JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToMany(targetEntity="CommercialArticleCategory", inversedBy="article", cascade={"persist"})
     * @JoinTable(name="COMMERCIAL_ASSOC_ARTICLES_CATEGORIES",
     *   joinColumns={
     *     @JoinColumn(name="article_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @JoinColumn(name="category_label", referencedColumnName="label")
     *   }
     * )
     */
    private $categoryLabel;

    /**
     * @var CommercialImportArticles
     *
     * @ManyToOne(targetEntity="CommercialImportArticles")
     * @JoinColumns({
     *   @JoinColumn(name="import_id", referencedColumnName="id")
     * })
     */
    private $import;

    public function __construct()
    {
        $this->categoryLabel = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set companyRef
     *
     * @param string $companyRef
     * @return CommercialArticle
     */
    public function setCompanyRef($companyRef)
    {
        $this->companyRef = $companyRef;
        return $this;
    }

    /**
     * Get companyRef
     *
     * @return string 
     */
    public function getCompanyRef()
    {
        return $this->companyRef;
    }

    /**
     * Set manufacturerRef
     *
     * @param string $manufacturerRef
     * @return CommercialArticle
     */
    public function setManufacturerRef($manufacturerRef)
    {
        $this->manufacturerRef = $manufacturerRef;
        return $this;
    }

    /**
     * Get manufacturerRef
     *
     * @return string 
     */
    public function getManufacturerRef()
    {
        return $this->manufacturerRef;
    }

    /**
     * Set designation
     *
     * @param string $designation
     * @return CommercialArticle
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;
        return $this;
    }

    /**
     * Get designation
     *
     * @return string 
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return CommercialArticle
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
     * Set purchasingPriceDf
     *
     * @param decimal $purchasingPriceDf
     * @return CommercialArticle
     */
    public function setPurchasingPriceDf($purchasingPriceDf)
    {
        $purchasingPriceDf = (float)  str_replace(",", ".", $purchasingPriceDf);
        $this->purchasingPriceDf = $purchasingPriceDf;
        return $this;
    }

    /**
     * Get purchasingPriceDf
     *
     * @return decimal 
     */
    public function getPurchasingPriceDf()
    {
        return $this->purchasingPriceDf;
    }

    /**
     * Set sellingPriceDf
     *
     * @param decimal $sellingPriceDf
     * @return CommercialArticle
     */
    public function setSellingPriceDf($sellingPriceDf)
    {
        $sellingPriceDf = (float)  str_replace(",", ".", $sellingPriceDf);
        $this->sellingPriceDf = $sellingPriceDf;
        return $this;
    }

    /**
     * Get sellingPriceDf
     *
     * @return decimal 
     */
    public function getSellingPriceDf()
    {
        return $this->sellingPriceDf;
    }

    /**
     * Set taxRate
     *
     * @param \CommercialTaxeRate $taxRate
     * @return CommercialArticle
     */
    public function setTaxRate(\CommercialTaxeRate $taxRate)
    {
        $this->taxeRate = $taxRate;
        return $this;
    }

    /**
     * Get taxRate
     *
     * @return \CommercialTaxeRate 
     */
    public function getTaxRate()
    {
        return $this->taxeRate;
    }

    /**
     * Set isGroup
     *
     * @param boolean $isGroup
     * @return CommercialArticle
     */
    public function setIsGroup($isGroup)
    {
        $this->isGroup = $isGroup;
        return $this;
    }

    /**
     * Get isGroup
     *
     * @return boolean 
     */
    public function getIsGroup()
    {
        return $this->isGroup;
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
     * Set sellingAccount
     *
     * @param CommercialSellingAccount $sellingAccount
     * @return CommercialArticle
     */
    public function setSellingAccount(\CommercialSellingAccount $sellingAccount = null)
    {
        $this->sellingAccount = $sellingAccount;
        return $this;
    }

    /**
     * Get sellingAccount
     *
     * @return CommercialSellingAccount 
     */
    public function getSellingAccount()
    {
        return $this->sellingAccount;
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
     * Set parent
     *
     * @param CommercialArticle $parent
     * @return CommercialArticle
     */
    public function setParent(\CommercialArticle $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return CommercialArticle 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add categoryLabel
     *
     * @param CommercialArticleCategory $categoryLabel
     * @return CommercialArticle
     */
    public function addCommercialArticleCategory(\CommercialArticleCategory $categoryLabel)
    {
        if($this->categoryLabel->contains($categoryLabel)) return $this;
        $this->categoryLabel[] = $categoryLabel;
        $categoryLabel->addCommercialArticle($this);
        return $this;
    }

    /**
     * Get categoryLabel
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategoryLabel()
    {
        return $this->categoryLabel;
    }
    
    public function ereaseCategories() {
        $this->categoryLabel->clear();
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
    
    /**
     * @PostPersist
     * @PostUpdate
     */
    public function postPersist() {
        // Delete unused categories
        $entityManager = \Application::getEntityMaanger();
        $unusedCategories = $entityManager->getRepository("CommercialArticleCategory")->getUnused($entityManager);
        \Igestis\Modules\Commercial\EntityLogic\CommercialArticleLogic::remove($entityManager, $unusedCategories);
        $entityManager->flush();
    }
}


// ---------------------------------------------------------------------

class CommercialArticleRepository extends \Doctrine\ORM\EntityRepository {

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
    
    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        if(!$result || $result->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return null;
        return $result;
    }

    public function articlesDatatableResults(\IgestisFormRequest $request, $fromSellingInvoice = false)
    {
        $userCompany = \IgestisSecurity::init()->user->getCompany();
        $qb = $this->_em->createQueryBuilder();
        $qb->select("COUNT(a)")
           ->from("CommercialArticle", "a")
           ->where("a.company = :company")
           ->setParameter("company", $userCompany);


        if ($request->getGet("sSearch")) {
            $qb
                ->andWhere("(a.designation like :query or a.description like :query or a.companyRef like :query or a.manufacturerRef like :query)")
                ->setParameter("query", "%".$request->getGet("sSearch")."%");
        }

        $nbResults = $qb->getQuery()->getSingleScalarResult();
        $qb->select("a");

        $output = new \Igestis\DataTables\AjaxDatatableOutput($request, $qb, $nbResults);
        $fieldsList = new \Igestis\DataTables\AjaxDatatableSorting();

        if ($fromSellingInvoice) {
            $fieldsList->addField("a.id");
            $fieldsList->addField("a.companyRef");
            $fieldsList->addField("a.designation");
            $fieldsList->addField("a.sellingPriceDf");

            $output->sortingManager($fieldsList);
            $results = $qb->getQuery()->getResult();
            foreach ($results as $currentResult) {
                $output->addRow(array(
                    $currentResult->getId(),
                    $currentResult->getCompanyRef(),
                    $currentResult->getDesignation(),
                    $currentResult->getSellingPriceDf(),
                ));
            }
        } else {
            $fieldsList->addField("a.companyRef");
            $fieldsList->addField("a.designation");
            $fieldsList->addField("a.purchasingPriceDf");
            $fieldsList->addField("a.sellingPriceDf");
            $fieldsList->addField("a.id");

            $output->sortingManager($fieldsList);
            $results = $qb->getQuery()->getResult();
            foreach ($results as $currentResult) {
                $output->addRow(array(
                    $currentResult->getCompanyRef(),
                    $currentResult->getDesignation(),
                    $currentResult->getPurchasingPriceDf(),
                    $currentResult->getSellingPriceDf(),
                    $currentResult->getId(),
                ));
            }
        }
        
        
        
        return $output->output();
    }

}