<?php


/**
 * CommercialCommercialDocumentArticle
 *
 * @Table(name="COMMERCIAL_COMMERCIAL_DOCUMENT_ARTICLE")
 * @Entity
 * @HasLifecycleCallbacks
 */
class CommercialCommercialDocumentArticle
{
    /**
     * @var boolean $isGroup
     *
     * @Column(name="is_group", type="boolean")
     */
    private $isGroup;

    /**
     * @var string $itemLabel
     *
     * @Column(name="item_label", type="string", length=500)
     */
    private $itemLabel;

    /**
     * @var string $itemRef
     *
     * @Column(name="item_ref", type="string", length=10)
     */
    private $itemRef;

    /**
     * @var decimal $purchasingDfUnitPrice
     *
     * @Column(name="purchasing_DF_unit_price", type="decimal")
     */
    private $purchasingDfUnitPrice;

    /**
     * @var decimal $sellingDfUnitPrice
     *
     * @Column(name="selling_DF_unit_price", type="decimal")
     */
    private $sellingDfUnitPrice;

    /**
     * @var decimal $taxRate
     *
     * @Column(name="tax_rate", type="decimal")
     */
    private $taxRate;

    /**
     * @var decimal $amountTax
     *
     * @Column(name="amount_tax", type="decimal")
     */
    private $amountTax;

    /**
     * @var decimal $quantityArticle
     *
     * @Column(name="quantity_article", type="decimal")
     */
    private $quantityArticle;

    /**
     * @var decimal $totSellPriceArticleDf
     *
     * @Column(name="tot_sell_price_article_DF", type="decimal")
     */
    private $totSellPriceArticleDf;

    /**
     * @var decimal $totSellPriceArticleTi
     *
     * @Column(name="tot_sell_price_article_TI", type="decimal")
     */
    private $totSellPriceArticleTi;

    /**
     * @var text $comment
     *
     * @Column(name="comment", type="text")
     */
    private $comment;
    
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
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialCommercialDocumentArticle
     *
     * @ManyToOne(targetEntity="CommercialCommercialDocumentArticle")
     * @JoinColumns({
     *   @JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var CommercialCommercialDocument
     *
     * @ManyToOne(targetEntity="CommercialCommercialDocument")
     * @JoinColumns({
     *   @JoinColumn(name="document_id", referencedColumnName="id")
     * })
     */
    private $document;
    
    /**
     * @var string $articleOrder
     *
     * @Column(name="article_order", type="integer")
     */
    private $articleOrder;
    
    
    
    /**
     * 
     * @param \CommercialArticle $article
     */
    public function __construct(\CommercialArticle $article=null) {
        $this->isGroup = false;
        if($article) {
            $this->setSellingAccount($article->getSellingAccount())
                 ->setComment($article->getDescription())
                 ->setItemLabel($article->getDesignation())
                 ->setItemRef($article->getCompanyRef())
                 ->setPurchasingDfUnitPrice($article->getPurchasingPriceDf())
                 ->setSellingDfUnitPrice($article->getSellingPriceDf());           
            
        }
        
    }


    /**
     * Set isGroup
     *
     * @param boolean $isGroup
     * @return CommercialCommercialDocumentArticle
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
     * Set itemLabel
     *
     * @param string $itemLabel
     * @return CommercialCommercialDocumentArticle
     */
    public function setItemLabel($itemLabel)
    {
        $this->itemLabel = $itemLabel;
        return $this;
    }

    /**
     * Get itemLabel
     *
     * @return string 
     */
    public function getItemLabel()
    {
        return $this->itemLabel;
    }

    /**
     * Set itemRef
     *
     * @param string $itemRef
     * @return CommercialCommercialDocumentArticle
     */
    public function setItemRef($itemRef)
    {
        $this->itemRef = $itemRef;
        return $this;
    }

    /**
     * Get itemRef
     *
     * @return string 
     */
    public function getItemRef()
    {
        return $this->itemRef;
    }

    /**
     * Set purchasingDfUnitPrice
     *
     * @param decimal $purchasingDfUnitPrice
     * @return CommercialCommercialDocumentArticle
     */
    public function setPurchasingDfUnitPrice($purchasingDfUnitPrice)
    {
        $purchasingDfUnitPrice = (float)  str_replace(",", ".", $purchasingDfUnitPrice);
        $this->purchasingDfUnitPrice = $purchasingDfUnitPrice;
        return $this;
    }

    /**
     * Get purchasingDfUnitPrice
     *
     * @return decimal 
     */
    public function getPurchasingDfUnitPrice()
    {
        return $this->purchasingDfUnitPrice;
    }

    /**
     * Set sellingDfUnitPrice
     *
     * @param decimal $sellingDfUnitPrice
     * @return CommercialCommercialDocumentArticle
     */
    public function setSellingDfUnitPrice($sellingDfUnitPrice)
    {
        $sellingDfUnitPrice = (float)  str_replace(",", ".", $sellingDfUnitPrice);
        $this->sellingDfUnitPrice = $sellingDfUnitPrice;
        return $this;
    }

    /**
     * Get sellingDfUnitPrice
     *
     * @return decimal 
     */
    public function getSellingDfUnitPrice()
    {
        return $this->sellingDfUnitPrice;
    }

    /**
     * Set taxRate
     *
     * @param decimal $taxRate
     * @return CommercialCommercialDocumentArticle
     */
    public function setTaxRate($taxRate)
    {
        $taxRate = (float)  str_replace(",", ".", $taxRate);
        $this->taxRate = $taxRate;
        return $this;
    }

    /**
     * Get taxRate
     *
     * @return decimal 
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * Set amountTax
     *
     * @param decimal $amountTax
     * @return CommercialCommercialDocumentArticle
     */
    public function setAmountTax($amountTax)
    {
        $amountTax = (float)  str_replace(",", ".", $amountTax);
        $this->amountTax = $amountTax;
        return $this;
    }

    /**
     * Get amountTax
     *
     * @return decimal 
     */
    public function getAmountTax()
    {
        return $this->amountTax;
    }

    /**
     * Set quantityArticle
     *
     * @param decimal $quantityArticle
     * @return CommercialCommercialDocumentArticle
     */
    public function setQuantityArticle($quantityArticle)
    {
        $quantityArticle = (float)  str_replace(",", ".", $quantityArticle);
        $this->quantityArticle = $quantityArticle;
        return $this;
    }

    /**
     * Get quantityArticle
     *
     * @return decimal 
     */
    public function getQuantityArticle()
    {
        return $this->quantityArticle;
    }

    /**
     * Set totSellPriceArticleDf
     *
     * @param decimal $totSellPriceArticleDf
     * @return CommercialCommercialDocumentArticle
     */
    public function setTotSellPriceArticleDf($totSellPriceArticleDf)
    {
        $totSellPriceArticleDf = (float)  str_replace(",", ".", $totSellPriceArticleDf);
        $this->totSellPriceArticleDf = $totSellPriceArticleDf;
        return $this;
    }

    /**
     * Get totSellPriceArticleDf
     *
     * @return decimal 
     */
    public function getTotSellPriceArticleDf()
    {
        return $this->totSellPriceArticleDf;
    }

    /**
     * Set totSellPriceArticleTi
     *
     * @param decimal $totSellPriceArticleTi
     * @return CommercialCommercialDocumentArticle
     */
    public function setTotSellPriceArticleTi($totSellPriceArticleTi)
    {
        $totSellPriceArticleTi = (float)  str_replace(",", ".", $totSellPriceArticleTi);
        $this->totSellPriceArticleTi = $totSellPriceArticleTi;
        return $this;
    }

    /**
     * Get totSellPriceArticleTi
     *
     * @return decimal 
     */
    public function getTotSellPriceArticleTi()
    {
        return $this->totSellPriceArticleTi;
    }

    /**
     * Set comment
     *
     * @param text $comment
     * @return CommercialCommercialDocumentArticle
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     *
     * @return text 
     */
    public function getComment()
    {
        return $this->comment;
    }


    /**
     * Set sellingAccount
     *
     * @param CommercialSellingAccount $sellingAccount
     * @return CommercialCommercialDocumentArticle
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
     * Set accountingNumber
     *
     * @param string $accountingNumber
     * @return CommercialCommercialDocumentArticle
     */
    public function setAccountingNumber($accountingNumber)
    {
        $this->accountingNumber = $accountingNumber;
        return $this;
    }

    /**
     * Get accountingNumber
     *
     * @return string 
     */
    public function getAccountingNumber()
    {
        return $this->accountingNumber;
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
     * Set parent
     *
     * @param CommercialCommercialDocumentArticle $parent
     * @return CommercialCommercialDocumentArticle
     */
    public function setParent(\CommercialCommercialDocumentArticle $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return CommercialCommercialDocumentArticle 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set document
     *
     * @param CommercialCommercialDocument $document
     * @return CommercialCommercialDocumentArticle
     */
    public function setDocument(\CommercialCommercialDocument $document = null)
    {
        $this->document = $document;
        return $this;
    }

    /**
     * Get document
     *
     * @return CommercialCommercialDocument 
     */
    public function getDocument()
    {
        return $this->document;
    }
    
    /**
     * Get the order of article in the invoice
     * @return int
     */
    public function getArticleOrder() {
        return $this->articleOrder;
    }

    /**
     * Set the order of the article in the document
     * @param type $articleOrder
     * @return \CommercialCommercialDocumentArticle
     */
    public function setArticleOrder($articleOrder, $noRefresh=false) {
        if($noRefresh) {
            $this->articleOrder = $articleOrder;
            return $this;
        }
        
        foreach ($this->document->getArticles() as $article) {         
            if($articleOrder > $this->articleOrder) {
                if($article->getArticleOrder() <= $articleOrder) $article->setArticleOrder($article->getArticleOrder() - 1, true);
            }
            else {
                if($article->getArticleOrder() >= $articleOrder) $article->setArticleOrder($article->getArticleOrder() + 1, true);
            }
            
        }
        $this->articleOrder = $articleOrder;

        $this->document->orderArticles();
        return $this;
    }

        
    /**
     * @PrePersist
     * @PreUpdate
     */
    public function preUpdate() {        
        $this->totSellPriceArticleDf = $this->sellingDfUnitPrice * $this->quantityArticle;
        $this->totSellPriceArticleTi = $this->totSellPriceArticleDf * (1 + ($this->taxRate/100));          
    }
    
    /**
     * @PrePersist
     */
    public function prePersist() {
        if($this->articleOrder == null) {
            $order = 0;
            foreach ($this->document->getArticles() as $currentArticle) {
                if($currentArticle != $this) {
                    $order = max($order, $currentArticle->getArticleOrder());
                }
            }
            
            $this->articleOrder = $order + 1;
        }
    }
    
    /**
     * Return a Json representation of the article
     * @param bool $espaceDoubleQUotes
     * @return string json data
     */
    public function getJsonData() {
        $array = array(
            "sellingAccount" => $this->sellingAccount->getId(),
            "comment" => $this->comment,
            "id" => $this->id,
            "itemLabel" => $this->itemLabel,
            "itemRef" => $this->itemRef,
            "purchasingDfUnitPrice" => $this->purchasingDfUnitPrice,
            "sellingDfUnitPrice" => $this->sellingDfUnitPrice,
            "taxRate" => $this->taxRate,
            "quantityArticle" => $this->quantityArticle
        );
        
        return json_encode($array);
    }
}