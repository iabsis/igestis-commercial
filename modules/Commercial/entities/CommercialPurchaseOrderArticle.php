<?php


/**
 * CommercialPurchaseOrderArticle
 *
 * @Table(name="COMMERCIAL_PURCHASE_ORDER_ARTICLE")
 * @Entity
 */
class CommercialPurchaseOrderArticle
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
     * @var string $accountingLabel
     *
     * @Column(name="accounting_label", type="string", length=20)
     */
    private $accountingLabel;

    /**
     * @var string $accountingNumber
     *
     * @Column(name="accounting_number", type="string", length=12)
     */
    private $accountingNumber;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialPurchaseOrder
     *
     * @ManyToOne(targetEntity="CommercialPurchaseOrder")
     * @JoinColumns({
     *   @JoinColumn(name="purchase_order", referencedColumnName="id")
     * })
     */
    private $purchaseOrder;

    /**
     * @var CommercialPurchaseOrderArticle
     *
     * @ManyToOne(targetEntity="CommercialPurchaseOrderArticle")
     * @JoinColumns({
     *   @JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;
    
    /**
     * @var string $articleOrder
     *
     * @Column(name="article_order", type="integer")
     */
    private $articleOrder;
    
    /**
     * @var string $taxAccountingNumber
     *
     * @Column(name="tax_accounting_number", type="string")
     */
    private $taxAccountingNumber;
    
    /**
     * 
     * @return string
     */
    public function getTaxAccountingNumber($defaultAccount = "") {
        if($defaultAccount && !$this->taxAccountingNumber) {
            $this->taxAccountingNumber = $defaultAccount;
        }
        return $this->taxAccountingNumber;
    }

    /**
     * 
     * @param string $taxAccountingNumber
     * @return self
     */
    public function setTaxAccountingNumber($taxAccountingNumber) {
        $this->taxAccountingNumber = $taxAccountingNumber;
        return $this;
    }



    /**
     * Set isGroup
     *
     * @param boolean $isGroup
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * @return CommercialPurchaseOrderArticle
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
     * Set accountingLabel
     *
     * @param string $accountingLabel
     * @return CommercialPurchaseOrderArticle
     */
    public function setAccountingLabel($accountingLabel)
    {
        $this->accountingLabel = $accountingLabel;
        return $this;
    }

    /**
     * Get accountingLabel
     *
     * @return string 
     */
    public function getAccountingLabel()
    {
        return $this->accountingLabel;
    }

    /**
     * Set accountingNumber
     *
     * @param string $accountingNumber
     * @return CommercialPurchaseOrderArticle
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
     * Set invoice
     *
     * @param CommercialInvoice $invoice
     * @return CommercialPurchaseOrderArticle
     */
    public function setPurchaseOrder(\CommercialPurchaseOrder $purchaseOrder = null)
    {
        $this->purchaseOrder = $purchaseOrder;
        return $this;
    }

    /**
     * Get invoice
     *
     * @return CommercialInvoice 
     */
    public function getPurchaseOrder()
    {
        return $this->purchaseOrder;
    }

    /**
     * Set parent
     *
     * @param CommercialPurchaseOrderArticle $parent
     * @return CommercialPurchaseOrderArticle
     */
    public function setParent(\CommercialPurchaseOrderArticle $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return CommercialPurchaseOrderArticle 
     */
    public function getParent()
    {
        return $this->parent;
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