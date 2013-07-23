<?php

/**
 * Description of CommercialViewCommercialDocs
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 * 
 * @Entity (repositoryClass="CommercialViewCommercialDocsRepository")
 * @Table(name="COMMERCIAL_VIEW_COMMERCIAL_DOCS")
 * 
 */
class CommercialViewCommercialDocs {
    const TYPE_SELLING_DOUCMENT = 'selling_document';
    const TYPE_BUYING_DOCUMENT = 'buying_document';
    const TYPE_MANUAL_AMOUNTS = 'manual_amounts';
    
    /**
     *
     * @var bool Document has been associated to a bank operation
     * @Column(type="boolean", name="associated")
     */
    private $associated;
    
    /**
     * @var string Type of transaction 
     * @Column(type="string", name="transaction_type") 
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
     * @var float Total amount of the sold 
     * @Column(type="string", name="document_number")
     */
    private $documentNumber;
    
    /**
     * 
     * @return bool
     */
    public function getAssociated() {
        return $this->associated;
    }
        
    /**
     * 
     * @return string
     */
    public function getDocumentNumber() {
        return $this->documentNumber;
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
     * @return type
     */
    public function getCompany() {
        return $this->company;
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
     * @return type
     */
    public function getUser() {
        return $this->user;
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
     * @return type
     */
    public function getPath() {
        return $this->path;
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
     * @return type
     */
    public function getPaid() {
        return $this->paid;
    }    

    /**
     * 
     * @return type
     */
    public function getTotalTi() {
        return $this->totalTi;
    } 

}

// ----------------------------------------------------------------------------------

class CommercialViewCommercialDocsRepository extends \Doctrine\ORM\EntityRepository {
    function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        $userCompany = \IgestisSecurity::init()->user->getCompany();
        $criteria = array_merge($criteria, array("company" => $userCompany));
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
    
    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("vcd")
               ->from("CommercialViewCommercialDocs", "vcd")
               ->where("vcd.company = :company")
               ->setParameter("company", $userCompany);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();        
        
    }
    
    public function findFromSearchForm(Igestis\Modules\Commercial\Forms\exportSearchForm $searchForm) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("vcd")
               ->from("CommercialViewCommercialDocs", "vcd")
               ->where("vcd.company = :company")
               ->andWhere("vcd.totalTi is not null")
               ->setParameter("company", $userCompany);
            
            switch ($searchForm->getExported()) {
                case Igestis\Modules\Commercial\Forms\exportSearchForm::EXPORTED_NO :
                    $qb->andWhere("vcd.exported = 0");
                    break;
                case Igestis\Modules\Commercial\Forms\exportSearchForm::EXPORTED_YES :
                    $qb->andWhere("vcd.exported = 1");
                    break;
            }
            
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();   
    }
}