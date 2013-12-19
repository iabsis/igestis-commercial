<?php


/**
 * CommercialInvoice
 *
 * @Table(name="COMMERCIAL_INVOICE")
 * @Entity(repositoryClass="CommercialInvoiceRepository")
 * @HasLifecycleCallbacks
 */
class CommercialInvoice
{
    /**
     * @var string $invoiceNumber
     *
     * @Column(name="invoice_number", type="string", length=12)
     */
    private $invoiceNumber;


    /**
     * @var date $invoicesDate
     *
     * @Column(name="invoices_date", type="date")
     */
    private $invoicesDate;

    /**
     * @var date $paymentDateLimit
     *
     * @Column(name="payment_date_limit", type="date")
     */
    private $paymentDateLimit;

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
     * @var boolean $exported
     *
     * @Column(name="exported", type="boolean")
     */
    private $exported;

    /**
     * @var string $invoicesType
     *
     * @Column(name="invoices_type", type="string")
     */
    private $invoicesType;

    /**
     * @var decimal $invoiceTotalDf
     *
     * @Column(name="invoice_total_DF", type="decimal")
     */
    private $invoiceTotalDf;

    /**
     * @var decimal $invoiceTotalTi
     *
     * @Column(name="invoice_total_TI", type="decimal")
     */
    private $invoiceTotalTi;

    /**
     * @var boolean $paid
     *
     * @Column(name="paid", type="boolean")
     */
    private $paid;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialEstimate
     *
     * @ManyToOne(targetEntity="CommercialEstimate")
     * @JoinColumns({
     *   @JoinColumn(name="estimate_id", referencedColumnName="id")
     * })
     */
    private $estimate;

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
     * @var CommercialSoldType
     *
     * @ManyToOne(targetEntity="CommercialSoldType")
     * @JoinColumns({
     *   @JoinColumn(name="payment_mode", referencedColumnName="code")
     * })
     */
    private $paymentMode;
    
    /**
     * @OneToMany(targetEntity="CommercialInvoiceArticle", mappedBy="invoice", cascade={"all"})
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
     * @var \Doctrine\Common\Collections\ArrayCollection List  of the associated rows
     * @OneToMany(targetEntity="CommercialBankAssocOperations", mappedBy="invoice", cascade={"all"}, orphanRemoval=true)
     */
    private $bankAssocs;

    const TYPE_INVOICE = "invoices";
    const TYPE_CREDIT = "credits";
    
    /**
     * 
     * @param \CommercialCommercialDocument $commercialDocument
     */
    public function __construct(\CommercialCommercialDocument $commercialDocument) {
        $this->articles = new Doctrine\Common\Collections\ArrayCollection();
        $this->bankAssocs = new Doctrine\Common\Collections\ArrayCollection();
        
        $this->paid = false;
        $this->exported = false;
        $this->invoicesType = self::TYPE_INVOICE;
        
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
            $newArticle = new CommercialInvoiceArticle;
            $newArticle->setAccountingLabel($currentArticle->getSellingAccount()->getLabel())
                       ->setAccountingNumber($currentArticle->getSellingAccount()->getAccountNumber())
                       ->setAmountTax($currentArticle->getAmountTax())
                       ->setArticleOrder($currentArticle->getArticleOrder())
                       ->setComment($currentArticle->getComment())
                       ->setInvoice($this)
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
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     * @return CommercialInvoice
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    /**
     * Get invoiceNumber
     *
     * @return string 
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }


    /**
     * Set invoicesDate
     *
     * @param date $invoicesDate
     * @return CommercialInvoice
     */
    public function setInvoicesDate($invoicesDate)
    {
        $this->invoicesDate = $invoicesDate;
        return $this;
    }

    /**
     * Get invoicesDate
     *
     * @return date 
     */
    public function getInvoicesDate()
    {
        return $this->invoicesDate;
    }

    /**
     * Set paymentDateLimit
     *
     * @param date $paymentDateLimit
     * @return CommercialInvoice
     */
    public function setPaymentDateLimit($paymentDateLimit)
    {
        $this->paymentDateLimit = $paymentDateLimit;
        return $this;
    }

    /**
     * Get paymentDateLimit
     *
     * @return date 
     */
    public function getPaymentDateLimit()
    {
        return $this->paymentDateLimit;
    }

    /**
     * Set taxNumCustomer
     *
     * @param string $taxNumCustomer
     * @return CommercialInvoice
     */
    public function setTaxNumCustomer($taxNumCustomer)
    {
        $this->taxNumCustomer = $taxNumCustomer;
        return $this;
    }

    /**
     * Get taxNumCustomer
     *
     * @return string 
     */
    public function getTaxNumCustomer()
    {
        return $this->taxNumCustomer;
    }

    /**
     * Set pathPdfFile
     *
     * @param string $pathPdfFile
     * @return CommercialInvoice
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
     * Set exported
     *
     * @param boolean $exported
     * @return CommercialInvoice
     */
    public function setExported($exported)
    {
        $this->exported = $exported;
        return $this;
    }

    /**
     * Get exported
     *
     * @return boolean 
     */
    public function getExported()
    {
        return $this->exported;
    }

    /**
     * Set invoicesType
     *
     * @param string $invoicesType
     * @return CommercialInvoice
     */
    public function setInvoicesType($invoicesType)
    {
        $this->invoicesType = $invoicesType;
        return $this;
    }

    /**
     * Get invoicesType
     *
     * @return string 
     */
    public function getInvoicesType()
    {
        return $this->invoicesType;
    }

    /**
     * Set invoiceTotalDf
     *
     * @param decimal $invoiceTotalDf
     * @return CommercialInvoice
     */
    public function setInvoiceTotalDf($invoiceTotalDf)
    {
        $invoiceTotalDf = (float)  str_replace(",", ".", $invoiceTotalDf);
        $this->invoiceTotalDf = $invoiceTotalDf;
        return $this;
    }

    /**
     * Get invoiceTotalDf
     *
     * @return decimal 
     */
    public function getInvoiceTotalDf()
    {
        return $this->invoiceTotalDf;
    }

    /**
     * Set invoiceTotalTi
     *
     * @param decimal $invoiceTotalTi
     * @return CommercialInvoice
     */
    public function setInvoiceTotalTi($invoiceTotalTi)
    {
        $invoiceTotalTi = (float)  str_replace(",", ".", $invoiceTotalTi);
        $this->invoiceTotalTi = $invoiceTotalTi;
        return $this;
    }

    /**
     * Get invoiceTotalTi
     *
     * @return decimal 
     */
    public function getInvoiceTotalTi()
    {
        return $this->invoiceTotalTi;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     * @return CommercialInvoice
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean 
     */
    public function getPaid()
    {
        return $this->paid;
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
     * Set estimate
     *
     * @param CommercialEstimate $estimate
     * @return CommercialInvoice
     */
    public function setEstimate(\CommercialEstimate $estimate = null)
    {
        $this->estimate = $estimate;
        return $this;
    }

    /**
     * Get estimate
     *
     * @return CommercialEstimate 
     */
    public function getEstimate()
    {
        return $this->estimate;
    }

    /**
     * Set project
     *
     * @param \CommercialProject $project
     * @return CommercialInvoice
     */
    public function setProject(\CommercialProject $project = null)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Get project
     *
     * @return \CommercialProject 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set paymentMode
     *
     * @param CommercialSoldType $paymentMode
     * @return CommercialInvoice
     */
    public function setPaymentMode(\CommercialSoldType $paymentMode = null)
    {
        $this->paymentMode = $paymentMode;
        return $this;
    }

    /**
     * Get paymentMode
     *
     * @return CommercialSoldType 
     */
    public function getPaymentMode()
    {
        return $this->paymentMode;
    }
    
    /**
     * Add a new article to the list
     *
     * @param \CommercialInvoiceArticle $article
     * @return self
     */
    public function addArticle(\CommercialInvoiceArticle $article)
    {        
        $this->articles[] = $article;
        $article->setInvoice($this);
        return $this;
    }

    
    /**
     * Return the list of associated articles
     * @return array(\CommercialEstimateArticle) List of articles
     */
    public function getArticles() {
        return $this->articles;
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
     * @PrePersist
     * @PreUpdate
     */
    public function PrePersist() {
        if(count($this->bankAssocs) && !$this->paid) {
            throw new \Exception(Igestis\I18n\Translate::_("This document is associated to one or more bank operation. You cannot change the payment status"));
        } 
        
        
        $this->invoiceTotalDf = 0;
        $this->invoiceTotalTi = 0;
        foreach ($this->articles as $article) {
            $this->invoiceTotalDf += $article->getTotSellPriceArticleDf();
            $this->invoiceTotalTi += $article->getTotSellPriceArticleTi();
        }
    }    
    
    public function export() {
        
        $companyVatAccounting = \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::getVatAccountig();
        $this->setExported(true);
        $string = "";
        foreach($this->articles as $article) {
            
            $string = \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::exportLineFormatter(
                $this->id,
                \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::TYP_SELLING, 
                $this->getCommercialDocument()->getCustomerUser()->getAccountCode(), 
                $article->getTaxAccountingNumber($companyVatAccounting->getSellingVatAccount()),
                $article->getAccountingNumber(),
                $this->invoiceNumber, 
                $this->invoicesDate,
                $article->getTotSellPriceArticleDf(), 
                $article->getTotSellPriceArticleTi(), 
                $article->getAmountTax(),
                $this->getCommercialDocument()->getCustomerUser()->getUserLabel()
            );
        }
        
        return $string;
    }
    
    
}

class CommercialInvoiceRepository extends Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("i")
               ->from("CommercialInvoice", "i")
               ->leftJoin("i.commercialDocument", "cd")
               ->where("cd.company = :company")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult(); 
    }
    
    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null) {
        $result = parent::find($id, $lockMode, $lockVersion);
        if(!$result || $result->getCommercialDocument()->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return null;
        return $result;
    }
    
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        $criteria['company'] = \IgestisSecurity::init()->user->getCompany();
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
    
    public function findAssociableToOperation(\CommercialBankOperation $operation) {
        $invoicesId = array();
        foreach($operation->getAssocs() as $assoc) {
            if($assoc->getInvoice()) $invoicesId[] = $assoc->getInvoice ();
        }
        
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("i")
               ->from("CommercialInvoice", "i")
               ->leftJoin("i.commercialDocument", "cd")
               ->where("(cd.company = :company and i.invoiceTotalTi = :totalTi and i.paid=0)")      
               ->setParameter("company", $userCompany)
               ->setParameter("totalTi", abs($operation->getOperationAmount()));
            
            if(count($invoicesId)) {
                $qb->orWhere('i.id in (:invoicesId)')
                   ->setParameter("invoicesId", $invoicesId);
            }
            
            if($operation->getOperationAmount() < 0) {
                $qb->andWhere("i.invoicesType = 'credits'");
            }
            else {
                $qb->andWhere("i.invoicesType = 'invoices'");
            }
            
        }
        catch (\Exception $e) {
            throw $e;
        }
        //Igestis\Utils\Dump::show($qb->getQuery()->getSQL()); exit;
        return $qb->getQuery()->getResult();  
    }

}