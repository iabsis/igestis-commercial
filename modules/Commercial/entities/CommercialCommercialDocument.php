<?php

/**
 * CommercialCommercialDocument
 *
 * @Table(name="COMMERCIAL_COMMERCIAL_DOCUMENT")
 * @Entity(repositoryClass="CommercialCommercialDocumentRepository")
 * @HasLifecycleCallbacks
 */
class CommercialCommercialDocument
{
    /**
     * @var string $customerName
     *
     * @Column(name="customer_name", type="string", length=150)
     */
    private $customerName;

    /**
     * @var string $address1
     *
     * @Column(name="address1", type="string", length=100)
     */
    private $address1;

    /**
     * @var string $address2
     *
     * @Column(name="address2", type="string", length=100)
     */
    private $address2;

    /**
     * @var string $postalCode
     *
     * @Column(name="postal_code", type="string", length=10)
     */
    private $postalCode;

    /**
     * @var string $city
     *
     * @Column(name="city", type="string", length=60)
     */
    private $city;

    /**
     * @var string $description
     *
     * @Column(name="description", type="string", length=150)
     */
    private $description;

    /**
     * @var text $freeComment
     *
     * @Column(name="free_comment", type="text")
     */
    private $freeComment;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CoreCountries
     *
     * @ManyToOne(targetEntity="CoreCountries")
     * @JoinColumns({
     *   @JoinColumn(name="country_code", referencedColumnName="code")
     * })
     */
    private $countryCode;

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
     * @OneToMany(targetEntity="CommercialCommercialDocumentArticle", mappedBy="document", cascade={"all"})
     * @OrderBy({"articleOrder" = "ASC"})
     */
    private $articles;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @OneToMany(targetEntity="CommercialInvoice", mappedBy="commercialDocument", cascade={"all"})
     * @OrderBy({"id" = "ASC"})
     */
    private $invoices;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @OneToMany(targetEntity="CommercialEstimate", mappedBy="commercialDocument", cascade={"all"})
     * @OrderBy({"id" = "ASC"})
     */
    private $estimates;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @OneToMany(targetEntity="CommercialDeliveryForm", mappedBy="commercialDocument", cascade={"all"})
     * @OrderBy({"id" = "ASC"})
     */
    private $deliveryForms;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @OneToMany(targetEntity="CommercialPurchaseOrder", mappedBy="commercialDocument", cascade={"all"})
     * @OrderBy({"id" = "ASC"})
     */
    private $purchaseOrders;


    /**
     * @var CommercialTimeCredit
     * @OneToOne(targetEntity="CommercialTimeCredit", mappedBy="commercialDocument")
     */
    private $creditTime;
    
    /**
     *
     * @var \DateTime
     * @Column(name="creation_date", type="datetime")
     * 
     */
    private $creationDate;
    
    
    const STATUS_DRAFT = 1;
    const STATUS_ESTIMATE = 2;
    const STATUS_INVOICE = 4;
    const STATUS_CREDIT = 8; 
    
    /**
     * 
     * @param \CommercialCommercialDocument $cloneFrom
     */
    public function __construct(\CommercialCommercialDocument $cloneFrom=null) {
        $this->articles = new Doctrine\Common\Collections\ArrayCollection();
        $this->invoices = new Doctrine\Common\Collections\ArrayCollection();
        $this->estimates = new Doctrine\Common\Collections\ArrayCollection();
        $this->deliveryForms = new Doctrine\Common\Collections\ArrayCollection();
        $this->purchaseOrders = new Doctrine\Common\Collections\ArrayCollection();

        $this->creationDate = new \DateTime();
        
        if($cloneFrom) {
            foreach($cloneFrom->getArticles() as $article) {
                $newArticle = clone $article;
                $this->addArticle($newArticle);
            }
        }
    }

    /**
     * Return the credit time
     * @return CommercialTimeCredit credit time
     */
    public function getCreditTime() {
        if ($this->creditTime) {
            return $this->creditTime;
        } else {
            return new CommercialTimeCredit($this);
        }
        
    }

    /**
     * Set the credit time
     * @param CommercialTimeCredit $time Credit time
     * @return self
     */
    public function setCreditTime(CommercialTimeCredit $time) {
        $this->creditTime = $time;
        return $this;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getInvoices() {
        return $this->invoices;
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getEstimates() {
        return $this->estimates;
    }
    
    
    /**
     * 
     * @return \DateTime
     */
    public function getCreationDate() {
        return $this->creationDate;
    }
    
    /**
     * 
     * @return int ID of the document type
     */
    public function getStatus() {
        if(count($this->invoices)) {
            if($this->invoices->get(0)->getInvoicesType() == \CommercialInvoice::TYPE_CREDIT) return self::STATUS_CREDIT;
            else return self::STATUS_INVOICE;
        }
        elseif(count($this->estimates)) {
            return self::STATUS_ESTIMATE;
        }
        else return self::STATUS_DRAFT;
    }
    
    /**
     * 
     * @return string Invoice or estimate number depending to the status of the document
     */
    public function getDocumentNumber() {
        switch($this->getStatus()) {
            case self::STATUS_ESTIMATE : return $this->estimates->last()->getEstimationNumber(); break;
            case self::STATUS_CREDIT : case self::STATUS_INVOICE : return $this->invoices->last()->getInvoiceNumber(); break;
            default : return ""; break;
        }
    }

    public function getDocumentDate() {
        switch($this->getStatus()) {
            case self::STATUS_ESTIMATE : return $this->estimates->last()->getDateEstimate(); break;
            case self::STATUS_CREDIT : case self::STATUS_INVOICE : return $this->invoices->last()->getInvoicesDate(); break;
            default : return $this->getCreationDate(); break;
        }
    }
    

    /**
     * Set customerName
     *
     * @param string $customerName
     * @return CommercialCommercialDocument
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * Get customerName
     *
     * @return string 
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return CommercialCommercialDocument
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return CommercialCommercialDocument
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return CommercialCommercialDocument
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return CommercialCommercialDocument
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CommercialCommercialDocument
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set freeComment
     *
     * @param text $freeComment
     * @return CommercialCommercialDocument
     */
    public function setFreeComment($freeComment)
    {
        $this->freeComment = $freeComment;
        return $this;
    }

    /**
     * Get freeComment
     *
     * @return text 
     */
    public function getFreeComment()
    {
        return $this->freeComment;
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
     * Set countryCode
     *
     * @param CoreCountries $countryCode
     * @return CommercialCommercialDocument
     */
    public function setCountryCode(\CoreCountries $countryCode = null)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * Get countryCode
     *
     * @return CoreCountries 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set project
     *
     * @param CommercialProject $project
     * @return CommercialCommercialDocument
     */
    public function setProject(\CommercialProject $project = null)
    {
        if($project != null) $project->addCommercialDocument($this);
        else {
            if($this->project) {
                $this->project->removeCommercialDocument($this);
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
     * Gets the value of DeliveryForms.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDeliveryForms()
    {
        return $this->deliveryForms;
    }

    /**
     * Sets the value of DeliveryForms.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $DeliveryForms the delivery documents
     *
     * @return self
     */
    public function setDeliveryForms(\Doctrine\Common\Collections\ArrayCollection $DeliveryForms)
    {
        $this->deliveryForms = $DeliveryForms;

        return $this;
    }

    /**
     * Gets the value of purchaseOrders.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPurchaseOrders()
    {
        return $this->purchaseOrders;
    }

    /**
     * Sets the value of purchaseOrders.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $purchaseOrders the purchase orders
     *
     * @return self
     */
    public function setPurchaseOrders(\Doctrine\Common\Collections\ArrayCollection $purchaseOrders)
    {
        $this->purchaseOrders = $purchaseOrders;

        return $this;
    }
    
    /**
     * Add a new article to the list
     *
     * @param \CommercialCommercialDocumentArticle $article
     * @return self
     */
    public function addArticle(\CommercialCommercialDocumentArticle $article)
    {        
        $this->articles[] = $article;
        $article->setDocument($this);
        return $this;
    }
    
    public function orderArticles() {
        
        $ordered = $this->articles->toArray();
        usort($ordered,function($article1,$article2){
            return $article1->getArticleOrder()>$article2->getArticleOrder();
        });

        $order = 1;
        
        foreach ($ordered as $currentArticle) {
            $currentArticle->setArticleOrder($order++, true);
        }
        

        $this->articles = new \Doctrine\Common\Collections\ArrayCollection($ordered);
    }
    
    /**
     * Return the list of associated articles
     * @return array(\CommercialCommercialDocumentArticle) List of articles
     */
    public function getArticles() {
        return $this->articles;
    }
    
    public function getAmountDf() {
        if(count($this->invoices)) {
            $totDf = $this->invoices->get(0)->getInvoiceTotalDf();
            if($this->invoices->get(0)->getInvoicesType() == \CommercialInvoice::TYPE_CREDIT) $totDf *= -1;
        }
        else {
            $totDf = 0;
            foreach ($this->articles as $amount) {
                $totDf += $amount->getTotSellPriceArticleDf();
            }
        }
        
        return $totDf;
    }
    
    public function getAmountTi() {
        if(count($this->invoices)) {
            $totTi = $this->invoices->get(0)->getInvoiceTotalTi();
            if($this->invoices->get(0)->getInvoicesType() == \CommercialInvoice::TYPE_CREDIT) $totTi *= -1;
        }
        else {
            $totTi = 0;
            foreach ($this->articles as $amount) {
                $totTi += $amount->getTotSellPriceArticleTi();
            }
        }
        return $totTi;
    }
    
    public function getBuyingAmountDf() {
        $totDf = 0;
        foreach ($this->articles as $amount) {
            $totDf += $amount->getPurchasingDfUnitPrice() * $amount->getQuantityArticle();
        }
        
        if(count($this->invoices)) {
            if($this->invoices[0]->getInvoicesType() == CommercialInvoice::TYPE_CREDIT) {
                $totDf *= -1;
            }
        }
        
        return $totDf;
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
        
        if(!($this->creatorContact instanceof \CoreContacts)) {
            $this->creatorContact = \IgestisSecurity::init()->contact;
        }
        
        $this->orderArticles();
    }
    
}


// ---------------------------------------------------------------------

class CommercialCommercialDocumentRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("d")
               ->from("CommercialCommercialDocument", "d")
               ->where("d.company = :company")
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
    
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        $criteria['company'] = \IgestisSecurity::init()->user->getCompany();
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
    
    public function findBySearchForm(\Igestis\Modules\Commercial\Forms\commercialDocumentSearchForm $searchForm) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("d")
               ->from("CommercialCommercialDocument", "d")
               ->where("d.company = :company")
               ->setParameter("company", $userCompany);
            
            $invoiceJoined = false;
            $estimateJoined = false;
            
            switch ($searchForm->getExported()) {
                case \Igestis\Modules\Commercial\Forms\commercialDocumentSearchForm::EXPORTED_NO :
                    $invoiceJoined = true;
                    $qb->leftJoin("d.invoices", "i")->andWhere("(i.exported = 0 or i.id is null)");
                    break;
                case \Igestis\Modules\Commercial\Forms\commercialDocumentSearchForm::EXPORTED_YES :
                    $invoiceJoined = true;
                    $qb->leftJoin("d.invoices", "i")->andWhere("i.exported = 1");
                    break;
            }
            
            switch ($searchForm->getPaid()) {
                case \Igestis\Modules\Commercial\Forms\commercialDocumentSearchForm::PAID_NO :
                    if(!$invoiceJoined)  $qb->leftJoin("d.invoices", "i");
                    $invoiceJoined = true;
                    $qb->andWhere("(i.paid = 0 or i.id is null)");
                    break;
                case \Igestis\Modules\Commercial\Forms\commercialDocumentSearchForm::PAID_YES :
                    if(!$invoiceJoined)  $qb->leftJoin("d.invoices", "i");
                    $invoiceJoined = true;
                    $qb->andWhere("i.paid = 1");
                    break;
            }
            
            switch ($searchForm->getState()) {
                case CommercialCommercialDocument::STATUS_CREDIT :
                    if(!$invoiceJoined)  $qb->leftJoin("d.invoices", "i");
                    $invoiceJoined = true;
                    $qb->andWhere("i.id is not null")->andWhere("i.invoicesType='credits'");
                    break;
                case CommercialCommercialDocument::STATUS_INVOICE :
                    if(!$invoiceJoined)  $qb->leftJoin("d.invoices", "i");
                    $invoiceJoined = true;
                    $qb->andWhere("i.id is not null")->andWhere("i.invoicesType='invoices'");
                    break;
                case CommercialCommercialDocument::STATUS_ESTIMATE :
                    if(!$invoiceJoined)  $qb->leftJoin("d.invoices", "i");
                    $invoiceJoined = true;
                    $qb->andWhere("i.id is null")
                       ->leftJoin("d.estimates", "e")->andWhere("e.id is not null");                    
                    break;
                case CommercialCommercialDocument::STATUS_DRAFT :
                    if(!$invoiceJoined)  $qb->leftJoin("d.invoices", "i");
                    $invoiceJoined = true;
                    $qb->andWhere("i.id is null")
                       ->leftJoin("d.estimates", "e")->andWhere("e.id is null");  
                    break;
            }  
            
            if($searchForm->getCustomer()) {
                $qb->andWhere("d.customerUser = :customer")
                   ->setParameter("customer", $searchForm->getCustomer());
            }
            
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();   
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
            $qb->select("d")
               ->from("CommercialCommercialDocument", "d")
               ->where("d.company = :company")
               ->andWhere("d.project is null")
               ->andWhere("d.customerUser = :customerUser")
               ->setParameter("company", $userCompany)
               ->setParameter("customerUser", $customerUser);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();   
    }

    
}