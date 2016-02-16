<?php


/**
 * CommercialProviderInvoiceAssocAmounts
 *
 * @Table(name="COMMERCIAL_PROVIDER_INVOICE_ASSOC_AMOUNTS")
 * @Entity(repositoryClass="CommercialProviderInvoiceAssocAmountsRepository")
 * @HasLifecycleCallbacks
 */
class CommercialProviderInvoiceAssocAmounts
{
    /**
     * @var decimal $amountDf
     *
     * @Column(name="amount_df", type="decimal")
     */
    private $amountDf;

    /**
     * @var decimal $amountTi
     *
     * @Column(name="amount_ti", type="decimal")
     */
    private $amountTi;

    /**
     * @var decimal $taxes
     *
     * @Column(name="taxes", type="decimal")
     */
    private $taxes;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CommercialProviderInvoice
     *
     * @ManyToOne(targetEntity="CommercialProviderInvoice")
     * @JoinColumns({
     *   @JoinColumn(name="purchase_invoice_id", referencedColumnName="id")
     * })
     */
    private $purchaseInvoice;

    /**
     * @var CommercialPurchasingAccount
     *
     * @ManyToOne(targetEntity="CommercialPurchasingAccount")
     * @JoinColumns({
     *   @JoinColumn(name="purchasing_account_id", referencedColumnName="id")
     * })
     */
    private $purchasingAccount;

    /**
     * @var string $accountNumber
     *
     * @Column(name="account_number", type="string")
     */
    private $accountNumber;

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
     * @return \CommercialProviderInvoiceAssocAmounts
     */
    public function setTaxAccountingNumber($taxAccountingNumber) {
        $this->taxAccountingNumber = $taxAccountingNumber;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAccountNumber() {
        return $this->accountNumber;
    }

    /**
     *
     * @param string $accountNumber
     * @return \CommercialProviderInvoiceAssocAmounts
     */
    public function setAccountNumber($accountNumber) {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    public function saveAccountNumber() {
        if(!$this->accountNumber) {
            $this->accountNumber = $this->getPurchasingAccount()->getAccountNumber();
        }
    }


    /**
     * Set amountDf
     *
     * @param decimal $amountDf
     * @return CommercialProviderInvoiceAssocAmounts
     */
    public function setAmountDf($amountDf)
    {
        $amountDf = (float)  str_replace(",", ".", $amountDf);
        $this->amountDf = $amountDf;
        return $this;
    }

    /**
     * Get amountDf
     *
     * @return decimal
     */
    public function getAmountDf()
    {
        return $this->amountDf;
    }

    /**
     * Set amountTi
     *
     * @param decimal $amountTi
     * @return CommercialProviderInvoiceAssocAmounts
     */
    public function setAmountTi($amountTi)
    {
        $amountTi = (float)  str_replace(",", ".", $amountTi);
        $this->amountTi = $amountTi;
        return $this;
    }

    /**
     * Get amountTi
     *
     * @return decimal
     */
    public function getAmountTi()
    {
        return $this->amountTi;
    }

    /**
     * Set taxes
     *
     * @param decimal $taxes
     * @return CommercialProviderInvoiceAssocAmounts
     */
    public function setTaxes($taxes)
    {
        $taxes = (float)  str_replace(",", ".", $taxes);
        $this->taxes = $taxes;
        return $this;
    }

    /**
     * Get taxes
     *
     * @return decimal
     */
    public function getTaxes()
    {
        return $this->taxes;
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
     * Set purchaseInvoice
     *
     * @param CommercialProviderInvoice $purchaseInvoice
     * @return CommercialProviderInvoiceAssocAmounts
     */
    public function setPurchaseInvoice(\CommercialProviderInvoice $purchaseInvoice = null)
    {
        $this->purchaseInvoice = $purchaseInvoice;
        return $this;
    }

    /**
     * Get purchaseInvoice
     *
     * @return CommercialProviderInvoice
     */
    public function getPurchaseInvoice()
    {
        return $this->purchaseInvoice;
    }

    /**
     * Set purchasingAccount
     *
     * @param CommercialPurchasingAccount $purchasingAccount
     * @return CommercialProviderInvoiceAssocAmounts
     */
    public function setPurchasingAccount(\CommercialPurchasingAccount $purchasingAccount = null)
    {
        $this->purchasingAccount = $purchasingAccount;
        return $this;
    }

    /**
     * Get purchasingAccount
     *
     * @return CommercialPurchasingAccount
     */
    public function getPurchasingAccount()
    {
        return $this->purchasingAccount;
    }

    /**
     * @PrePersist
     * @PreUpdate
     * @PreRemove
     */
    public function prePersistOrRemove()
    {
        if ($this->getAmountDf() == 0) {
            throw new \Exception(\Igestis\I18n\Translate::_("The duty free amount can't be 0"));
        }

        $amountDf = abs($this->getAmountDf());
        $amountTi = abs($this->getAmountTi());
        $amountTaxes = abs($this->getTaxes());

        if ($amountDf != 0 && $amountTi < $amountDf) {
            throw new \Exception(\Igestis\I18n\Translate::_("The duty free amount must be lower than the taxes included price."));
        }

        if ($amountTaxes != 0 && $amountTi < $amountTaxes) {
            throw new \Exception(\Igestis\I18n\Translate::_("The taxes amount must be lower than the taxes included price."));
        }


        if (round((float)$this->getAmountTi(), 2) != round((float)($this->getAmountDf() + $this->getTaxes()), 2))
        {

            throw new Exception(sprintf(\Igestis\I18n\Translate::_("The amount tax free (%s), amount taxes included (%s) and taxes (%s) does not match"),
                $this->getAmountDf(),
                $this->getAmountTi(),
                $this->getTaxes()
            ));
        }

        if($this->purchaseInvoice->isLocked()) throw new \Exception(\Igestis\I18n\Translate::_("This invoice is in read only mode. It has already been exported"));

    }

}

class CommercialProviderInvoiceAssocAmountsRepository extends Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("a")
               ->from("CommercialProviderInvoiceAssocAmounts", "a")
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
        if(!$result || $result->getPurchaseInvoice()->getCompany()->getId() != \IgestisSecurity::init()->user->getCompany()->getId()) return null;
        return $result;
    }

}
