<?php





/**
 * CommercialEstimateArticle
 *
 * @Table(name="COMMERCIAL_ESTIMATE_ARTICLE")
 * @Entity
 */
class CommercialEstimateArticle
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
     * @var CommercialEstimateArticle
     *
     * @ManyToOne(targetEntity="CommercialEstimateArticle")
     * @JoinColumns({
     *   @JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

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
     * @var string $articleOrder
     *
     * @Column(name="article_order", type="integer")
     */
    private $articleOrder;


    /**
     * Set isGroup
     *
     * @param boolean $isGroup
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * Set taxRate
     *
     * @param decimal $taxRate
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * @return CommercialEstimateArticle
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
     * @param CommercialEstimateArticle $parent
     * @return CommercialEstimateArticle
     */
    public function setParent(\CommercialEstimateArticle $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return CommercialEstimateArticle 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set estimate
     *
     * @param CommercialEstimate $estimate
     * @return CommercialEstimateArticle
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
     * 
     * @return int
     */
    public function getArticleOrder() {
        return $this->articleOrder;
    }

    /**
     * 
     * @param int $articleOrder
     * @return \CommercialEstimateArticle
     */
    public function setArticleOrder($articleOrder) {
        $this->articleOrder = $articleOrder;
        return $this;
    }


}