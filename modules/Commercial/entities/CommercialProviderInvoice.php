<?php

/**
 * CommercialProviderInvoice
 *
 * @Table(name="COMMERCIAL_PROVIDER_INVOICE")
 * @Entity(repositoryClass="CommercialProviderInvoiceRepository")
 * @HasLifecycleCallbacks
 */
class CommercialProviderInvoice
{
    /**
     * @var date $invoiceDate
     *
     * @Column(name="invoice_date", type="date")
     */
    private $invoiceDate;

    /**
     * @var string $invoiceNum
     *
     * @Column(name="invoice_num", type="string", length=50)
     */
    private $invoiceNum;

    /**
     * @var $invoicePaymentType
     *
     * @ManyToOne(targetEntity="CommercialSoldType")
     * @JoinColumns({
     *   @JoinColumn(name="invoice_payment_type", referencedColumnName="code")
     * })
     */
    private $invoicePaymentType;

    /**
     * @var boolean $exported
     *
     * @Column(name="exported", type="boolean")
     */
    private $exported;

    /**
     * @var string $invoicePath
     *
     * @Column(name="invoice_path", type="string", length=100)
     */
    private $invoicePath;

    /**
     * @var boolean $paid
     *
     * @Column(name="paid", type="boolean")
     */
    private $paid;

    /**
     * @var string $fileMd5Hash hash of the file to check if the same one has already been uploaded
     *
     * @Column(name="file_md5_hash", type="string", length=64)
     */
    private $fileMd5Hash;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var CoreUsers
     *
     * @ManyToOne(targetEntity="CoreUsers")
     * @JoinColumns({
     *   @JoinColumn(name="provider_user_id", referencedColumnName="id")
     * })
     */
    private $providerUser;

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
     * @var CommercialProject
     *
     * @ManyToOne(targetEntity="CommercialProject")
     * @JoinColumns({
     *   @JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection()
     *
     * @OneToMany(targetEntity="CommercialProviderInvoiceAssocAmounts", mappedBy="purchaseInvoice", cascade={"all"})
     */
    private $amounts;

    /**
     * Tell if the provider invoice is still editable or already exported
     * @var bool
     */
    private $locked;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection List  of the associated rows
     * @OneToMany(targetEntity="CommercialBankAssocOperations", mappedBy="providerInvoice", cascade={"all"}, orphanRemoval=true)
     */
    private $bankAssocs;

    private $changed;

    public function __construct() {
        $this->exported = false;
        $this->amounts = new Doctrine\Common\Collections\ArrayCollection();
        $this->bankAssocs = new Doctrine\Common\Collections\ArrayCollection();
        $this->paid = false;
    }

    /**
     * Add the amount to the provider invoice
     * @param \CommercialProviderInvoiceAssocAmounts $amount
     * @return \CommercialProviderInvoice
     */
    public function addAmount(\CommercialProviderInvoiceAssocAmounts $amount) {
        $this->changed = true;
        $this->amounts->add($amount);
        $amount->setPurchaseInvoice($this);
        return $this;
    }

    /**
     * Remove the amount from the provider invoice
     * @param \CommercialProviderInvoiceAssocAmounts $amount
     * @return \CommercialProviderInvoice
     */
    public function removeAmount(\CommercialProviderInvoiceAssocAmounts $amount) {
        $this->changed = true;
        $this->amounts->removeElement($amount);
        return  $this;
    }

    /**
     * List of amounts associated to the invoice
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getAmounts() {
        return $this->amounts;
    }

    /**
     * Set invoiceDate
     *
     * @param date $invoiceDate
     * @return CommercialProviderInvoice
     */
    public function setInvoiceDate($invoiceDate)
    {
        $this->changed = true;
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    /**
     * Get invoiceDate
     *
     * @return date
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * Set invoiceNum
     *
     * @param string $invoiceNum
     * @return CommercialProviderInvoice
     */
    public function setInvoiceNum($invoiceNum)
    {
        $this->changed = true;
        $this->invoiceNum = $invoiceNum;
        return $this;
    }

    /**
     * Get invoiceNum
     *
     * @return string
     */
    public function getInvoiceNum()
    {
        return $this->invoiceNum;
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
     * Set invoicePaymentType
     *
     * @param string $invoicePaymentType
     * @return CommercialProviderInvoice
     */
    public function setInvoicePaymentType($invoicePaymentType)
    {
        $this->changed = true;
        $this->invoicePaymentType = $invoicePaymentType;
        return $this;
    }

    /**
     * Get invoicePaymentType
     *
     * @return string
     */
    public function getInvoicePaymentType()
    {
        return $this->invoicePaymentType;
    }

    /**
     * Set exported
     *
     * @param boolean $exported
     * @return CommercialProviderInvoice
     */
    public function setExported($exported)
    {
        $this->changed = true;
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
     * Set invoicePath
     *
     * @param string $invoicePath
     * @return CommercialProviderInvoice
     */
    public function setInvoicePath($invoicePath)
    {
        $this->changed = true;
        $this->invoicePath = $invoicePath;
        return $this;
    }

    /**
     * Get invoicePath
     *
     * @return string
     */
    public function getInvoicePath()
    {
        return $this->invoicePath;
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
     * Set providerUser
     *
     * @param CoreUsers $providerUser
     * @return CommercialProviderInvoice
     */
    public function setProviderUser(\CoreUsers $providerUser = null)
    {
        $this->changed = true;
        if($providerUser) {
            foreach ($this->bankAssocs as $assoc) {
                $assoc->setConcernedUser($providerUser);
            }
        }
        else {
            //$this->bankAssocs->clear();
        }

        $this->providerUser = $providerUser;
        return $this;
    }

    /**
     * Get providerUser
     *
     * @return \CoreUsers
     */
    public function getProviderUser()
    {
        return $this->providerUser;
    }

    /**
     *
     * @return string
     */
    public function getFileMd5Hash() {
        return $this->fileMd5Hash;
    }

    /**
     *
     * @param string $fileMd5Hash
     * @return \CommercialProviderInvoice
     */
    public function setFileMd5Hash($fileMd5Hash) {
        $this->fileMd5Hash = $fileMd5Hash;
        return $this;
    }


    /**
     * Set company
     *
     * @param CoreCompanies $company
     * @return CommercialProviderInvoice
     */
    public function setCompany(\CoreCompanies $company = null)
    {
        $this->changed = true;
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
     * Set project
     *
     * @param CommercialProject $project
     * @return CommercialProviderInvoice
     */
    public function setProject(\CommercialProject $project = null)
    {
        $this->changed = true;
        if($project != null) $project->addProviderInvoice($this);
        else {
            if($this->project) {
                $this->project->removeProviderInvoice($this);
            }
        }
        $this->project = $project;
        return $this;
    }

    /**
     * Return the status of the provider invoice
     * @return bool true if already exported, false else
     */
    public function isLocked() {
        return $this->locked;
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
     * @PostLoad
     */
    public function PostLoad() {
        $this->locked = (bool)$this->exported;
        $this->changed = false;
    }

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function prePersist() {
        // Check if another provider invoice has already the same  reference
        $_em = \Application::getEntityMaanger();

        if($this->changed && $_em->getRepository("CommercialProviderInvoice")->findOtherProviderInvoiceWithSameReference($this)) {
            throw new \Exception(\Igestis\I18n\Translate::_("This provider invoice number already exists"));
        }

        if($this->changed && $this->locked) throw new \Exception(\Igestis\I18n\Translate::_("This invoice is in read only mode. It has already been exported"));

        // Setting company
        if($this->company == null) {
            $this->company = \IgestisSecurity::init()->user->getCompany();
        }

        if(count($this->bankAssocs) && !$this->paid) {
            throw new \Exception(Igestis\I18n\Translate::_("This document is associated to one or more bank operation. You cannot change the payment status"));
        }

        //exit;
    }
    /**
     * @PreRemove
     */
    public function PreRemove() {
        if(count($this->bankAssocs)) throw new \Exception(\Igestis\I18n\Translate::_("This invoice is already linked to a bank operation"));
        if($this->locked) throw new \Exception(\Igestis\I18n\Translate::_("This invoice is in read only mode. It has already been exported"));
    }

    public function getAmountDf() {
        $totDf = 0;
        foreach ($this->amounts as $amount) {
            $totDf += $amount->getAmountDf();
        }
        return $totDf;
    }

    public function getAmountTi() {
        $totTi = 0;
        foreach ($this->amounts as $amount) {
            $totTi += $amount->getAmountTi();
        }
        return $totTi;
    }


    public function export() {
        $companyVatAccounting = \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::getVatAccountig();
        $this->setExported(true);
        $string = "";
        $totTaxes = 0;

        foreach ($this->amounts as $amount) {
            if(!isset($exportableData[$amount->getAccountNumber()])) {
                $exportableData[$amount->getAccountNumber()] = array(
                    "taxAccountingNumber" => "",
                    "accountingNumber" => "",
                    "articleDf" => 0,
                    "articleTi" =>0,
                    "articleTaxes" => 0
                );
            }
            $exportableData[$amount->getAccountNumber()]["taxAccountingNumber"] = $amount->getTaxAccountingNumber($companyVatAccounting->getBuyingVatAccount());
            $exportableData[$amount->getAccountNumber()]["accountingNumber"] = $amount->getAccountNumber();
            $exportableData[$amount->getAccountNumber()]["articleDf"] += $amount->getAmountDf();
            $exportableData[$amount->getAccountNumber()]["articleTi"] += $amount->getAmountTi();
            $exportableData[$amount->getAccountNumber()]["articleTaxes"] += $amount->getTaxes();

            $totTaxes += $amount->getTaxes();
        }

        $totTi = 0;
        $lastCurrentData = null;
        /*
        foreach($this->amounts as $amount) {
            $amount->saveAccountNumber();

            $string .= \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::exportLineFormatter(
                $this->id,
                \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::TYPE_BUYING,
                $this->getProviderUser()->getAccountCode(),
                $amount->getTaxAccountingNumber($companyVatAccounting->getBuyingVatAccount()),
                $amount->getAccountNumber(),
                $this->invoiceNum,
                $this->invoiceDate,
                $amount->getAmountDf(),
                0,
                0,
                $this->getProviderUser()->getUserLabel()
            );

            $string .= \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::exportLineFormatter(
                $this->id,
                \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::TYPE_BUYING,
                $this->getProviderUser()->getAccountCode(),
                $amount->getTaxAccountingNumber($companyVatAccounting->getBuyingVatAccount()),
                $amount->getAccountNumber(),
                $this->invoiceNum,
                $this->invoiceDate,
                0,
                $amount->getAmountTi(),
                0,
                $this->getProviderUser()->getUserLabel()
            );

            $totTaxes += $amount->getTaxes();
        }
        */
        foreach ($exportableData as $accountingNumber => $currentData) {
            $string .= \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::exportLineFormatter(
                $this->id,
                \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::TYPE_BUYING,
                $this->getProviderUser()->getAccountCode(),
                $currentData['taxAccountingNumber'],
                $accountingNumber,
                $this->invoiceNum,
                $this->invoiceDate,
                $currentData['articleDf'],
                0,
                0,
                $this->getProviderUser()->getUserLabel()
            );

            $totTi += $currentData['articleTi'];
            $lastCurrentData = $currentData;

        }


        $string .= \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::exportLineFormatter(
            $this->id,
            \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::TYPE_BUYING,
            $this->getProviderUser()->getAccountCode(),
            $amount->getTaxAccountingNumber($companyVatAccounting->getBuyingVatAccount()),
            $amount->getAccountNumber(),
            $this->invoiceNum,
            $this->invoiceDate,
            0,
            0,
            $totTaxes,
            $this->getProviderUser()->getUserLabel()
        );


        $string .= \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::exportLineFormatter(
            $this->id,
            \Igestis\Modules\Commercial\EntityLogic\invoicesExportLogic::TYPE_BUYING,
            $this->getProviderUser()->getAccountCode(),
            $amount->getTaxAccountingNumber($companyVatAccounting->getBuyingVatAccount()),
            $accountingNumber,
            $this->invoiceNum,
            $this->invoiceDate,
            0,
            $totTi,
            0,
            $this->getProviderUser()->getUserLabel()
        );

        return $string;
    }

}

// ------------------------------------------------------------------------

class CommercialProviderInvoiceRepository extends Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("pi")
               ->from("CommercialProviderInvoice", "pi")
               ->where("pi.company = :company")
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

    /**
     *
     * @param \Igestis\Modules\Commercial\Forms\providerInvoiceSearchForm $searchForm
     */
    public function findFromSearchForm(\Igestis\Modules\Commercial\Forms\providerInvoiceSearchForm $searchForm) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("pi")
               ->from("CommercialProviderInvoice", "pi")
               ->where("pi.company = :company")
               ->leftJoin("pi.amounts", "amounts")
               ->setParameter("company", $userCompany);

            switch($searchForm->getAssigned()) {
                case Igestis\Modules\Commercial\Forms\providerInvoiceSearchForm::ASSIGNED_NO :
                    $qb->andWhere("amounts.id is null");
                    break;
                case Igestis\Modules\Commercial\Forms\providerInvoiceSearchForm::ASSIGNED_YES :
                    $qb->andWhere("amounts.id is not null");
                    break;

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
    public function getUnlinkedToProject() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("pi")
               ->from("CommercialProviderInvoice", "pi")
               ->where("pi.company = :company")
               ->andWhere("pi.project is null")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }

        return $qb->getQuery()->getResult();
    }

    public function findAssociableToOperation(\CommercialBankOperation $operation) {
        try {
            $providerIds = array();
            foreach($operation->getAssocs() as $assoc) {
                if($assoc->getProviderInvoice()) $providerIds[] = $assoc->getProviderInvoice ();
            }

            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("pi")
               ->from("CommercialProviderInvoice", "pi")
               ->where("(pi.company = :company and pi.paid=0)")
               ->leftJoin("pi.amounts", "amounts")
               ->groupBy("pi.id")
               ->setParameter("company", $userCompany)
               ->having("SUM(amounts.amountTi) = :totalTi")
               ->setParameter("totalTi", -1 * $operation->getOperationAmount());

            if(count($providerIds)) {
                $qb->orWhere('pi.id in (:providersIds)')
                   ->orHaving('pi.id in (:providersIds)')
                   ->setParameter("providersIds", $providerIds);
            }

        }
        catch (\Exception $e) {
            throw $e;
        }

        return $qb->getQuery()->getResult();
    }

    public function findOtherProviderInvoiceWithSameReference(\CommercialProviderInvoice $providerInvoice) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("pi")
               ->from("CommercialProviderInvoice", "pi")
               ->where("pi.company = :company")
               ->andWhere("pi.invoiceNum = :invoiceNum")
               ->setParameter("invoiceNum", $providerInvoice->getInvoiceNum())
               ->setParameter("company", $userCompany);

            if($providerInvoice->getId()) {
                $qb->andWhere("pi.id != :invoiceId")->setParameter("invoiceId", $providerInvoice->getId());
            }
        }
        catch (\Exception $e) {
            throw $e;
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

}
