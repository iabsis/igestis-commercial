<?php

/**
 * Description of CommercialViewUserSOld
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 * 
 * @Entity (repositoryClass="CommercialViewBankAssocDocRepository")
 * @Table(name="COMMERCIAL_VIEW_BANK_ASSOC_DOCS")
 * 
 */
class CommercialViewBankAssocDocs {
    const TYPE_SELLING_DOUCMENT = 'selling_document';
    const TYPE_BUYING_DOCUMENT = 'buying_document';
    const TYPE_MANUAL_AMOUNTS = 'manual_amounts';
    
    /**
     * @Column(type="integer", name="operation_id")
     * @var integer Type of transaction 
     * @Id
     */
    private  $operationId;

    /**
     * @Column(type="string", name="transaction_type")
     * @var string Type of transaction 
     * @Id
     */
    private $transactionType;
    /**
     * @JoinColumn(name="company_id", referencedColumnName="id")
     * @OneToOne(targetEntity="CoreCompanies")
     * @var \CoreCompanies
     */
    private $company;
    /**
     *
     * @var int Id du document 
     * @Column(type="integer", name="document_id")
     * @Id
     */
    private $documentId;
    /**
     * @var CoreUsers Associated user
     * @JoinColumn(name="user_id")
     * @ManyToOne(targetEntity="CoreUsers", inversedBy="contacts")
     * @Id
     */
    private $user;
    /**
     *
     * @var \DateTime Date de transaction
     * @Column(type="date", name="transaction_date")
     * 
     */
    private $transactionDate;
    /**
     * 
     * @var string Path of the document 
     * @Column(type="string", name="path")
     */
    private $path;
    /**
     *
     * @var bool Document has been exported or not
     * @Column(type="boolean", name="exported")
     */
    private $exported;
    /**
     *
     * @var bool Document has been paid or not 
     * @Column(type="boolean", name="paid")
     */
    private $paid;
    /**
     *
     * @var float Total amount of the sold 
     * @Column(type="decimal", name="total_ti")
     */
    private $totalTi;
    
    /**
     * 
     * @return int
     */
    public function getOperationId() {
        return $this->operationId;
    }

    /**
     * 
     * @param int $operationId
     * @return \CommercialViewBankAssocDocs
     */
    public function setOperationId($operationId) {
        $this->operationId = $operationId;
        return $this;
    }

        
    /**
     * 
     * @return type
     */
    public function getTransactionType() {
        return $this->transactionType;
    }

    /**
     * 
     * @param type $transactionType
     * @return \CommercialViewBankAssocDocs
     */
    public function setTransactionType($transactionType) {
        $this->transactionType = $transactionType;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * 
     * @param \CoreCompanies $company
     * @return \CommercialViewBankAssocDocs
     */
    public function setCompany(\CoreCompanies $company) {
        $this->company = $company;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getDocumentId() {
        return $this->documentId;
    }

    /**
     * 
     * @param type $documentId
     * @return \CommercialViewBankAssocDocs
     */
    public function setDocumentId($documentId) {
        $this->documentId = $documentId;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * 
     * @param CoreUsers $user
     * @return \CommercialViewBankAssocDocs
     */
    public function setUser(CoreUsers $user) {
        $this->user = $user;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getTransactionDate() {
        return $this->transactionDate;
    }

    /**
     * 
     * @param \DateTime $transactionDate
     * @return \CommercialViewBankAssocDocs
     */
    public function setTransactionDate(\DateTime $transactionDate) {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * 
     * @param type $path
     * @return \CommercialViewBankAssocDocs
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getExported() {
        return $this->exported;
    }

    /**
     * 
     * @param type $exported
     * @return \CommercialViewBankAssocDocs
     */
    public function setExported($exported) {
        $this->exported = $exported;
        return $this;
    }
    /**
     * 
     * @return type
     */
    public function getPaid() {
        return $this->paid;
    }

    /**
     * 
     * @param type $paid
     * @return \CommercialViewBankAssocDocs
     */
    public function setPaid($paid) {
        $this->paid = $paid;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getTotalTi() {
        return $this->totalTi;
    }

    /**
     * 
     * @param type $totalTi
     * @return \CommercialViewBankAssocDocs
     */
    public function setTotalTi($totalTi) {
        $this->totalTi = $totalTi;
        return $this;
    }


}

// ----------------------------------------------------------------------------------

class CommercialViewBankAssocDocRepository extends \Doctrine\ORM\EntityRepository {
    function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        $userCompany = \IgestisSecurity::init()->user->getCompany();
        $criteria = array_merge($criteria, array("company" => $userCompany));
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}