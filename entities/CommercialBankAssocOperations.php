<?php





/**
 * CommercialBankAssocOperations
 *
 * @Table(name="COMMERCIAL_BANK_ASSOC_OPERATIONS")
 * @Entity
 * @HasLifecycleCallbacks
 */
class CommercialBankAssocOperations
{
    /**
     * @var decimal $amount
     *
     * @Column(name="amount", type="decimal")
     */
    private $amount;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialBankOperation
     *
     * @ManyToOne(targetEntity="CommercialBankOperation")
     * @JoinColumns({
     *   @JoinColumn(name="operation_id", referencedColumnName="id")
     * })
     */
    private $operation;

    /**
     * @var CoreUsers
     *
     * @ManyToOne(targetEntity="CoreUsers")
     * @JoinColumns({
     *   @JoinColumn(name="concerned_user_id", referencedColumnName="id")
     * })
     */
    private $concernedUser;

    /**
     * @var CommercialInvoice
     *
     * @ManyToOne(targetEntity="CommercialInvoice")
     * @JoinColumns({
     *   @JoinColumn(name="invoice_id", referencedColumnName="id")
     * })
     */
    private $invoice;
    
    /**
     * @var CommercialProviderInvoice
     *
     * @ManyToOne(targetEntity="CommercialProviderInvoice")
     * @JoinColumns({
     *   @JoinColumn(name="provider_invoice_id", referencedColumnName="id")
     * })
     */
    private $providerInvoice;

    /**
     * Set amount
     *
     * @param decimal $amount
     * @return CommercialBankAssocOperations
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get amount
     *
     * @return decimal 
     */
    public function getAmount()
    {
        return $this->amount;
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
     * Set operation
     *
     * @param CommercialBankOperation $operation
     * @return CommercialBankAssocOperations
     */
    public function setOperation(\CommercialBankOperation $operation = null)
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * Get operation
     *
     * @return CommercialBankOperation 
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set concernedUser
     *
     * @param CoreUsers $concernedUser
     * @return CommercialBankAssocOperations
     */
    public function setConcernedUser(\CoreUsers $concernedUser = null)
    {
        $this->concernedUser = $concernedUser;
        return $this;
    }

    /**
     * Get concernedUser
     *
     * @return CoreUsers 
     */
    public function getConcernedUser()
    {
        return $this->concernedUser;
    }

    /**
     * Set invoice
     *
     * @param CommercialInvoice $invoice
     * @return CommercialBankAssocOperations
     */
    public function setInvoice(\CommercialInvoice $invoice = null)
    {
        $this->invoice = $invoice;
        if($invoice) $this->concernedUser = $invoice->getCommercialDocument()->getCustomerUser();
        return $this;
    }

    /**
     * Get invoice
     *
     * @return CommercialInvoice 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
    
    /**
     * Set invoice
     *
     * @param \CommercialProviderInvoice $invoice
     * @return CommercialBankAssocOperations
     */
    public function setProviderInvoice(\CommercialProviderInvoice $invoice = null)
    {
        $this->providerInvoice = $invoice;
        if($invoice) $this->concernedUser = $invoice->getProviderUser ();
        return $this;
    }

    /**
     * Get invoice
     *
     * @return \CommercialProviderInvoice 
     */
    public function getProviderInvoice()
    {
        return $this->providerInvoice;
    }
    
    /**
     * @PreUpdate
     * @PrePersist
     */
    public function PreUpdate() {
        if($this->invoice) {
            $this->invoice->setPaid(true);
        }
        if($this->providerInvoice) {
            $this->providerInvoice->setPaid(true);
        }
    }
    /**
     * @PreRemove
     */
    public function PreRemove() {
        if($this->invoice) {
            $this->invoice->setPaid(false);
        }
        
        if($this->providerInvoice) {
            $this->providerInvoice->setPaid(false);
        }
    }
}