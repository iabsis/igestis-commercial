<?php

/**
 * Description of CommercialViewUserSold
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 *
 * 
 * @Entity (repositoryClass="CommercialViewUserSoldRepository")
 * @Table(name="COMMERCIAL_VIEW_USER_SOLD")
 */
class CommercialViewUserSold {
    const USER_TYPE_EMPLOYEE = "employee";
    const USER_TYPE_CUSTOMER = "client";
    const USER_TYPE_SUPPLIER = "supplier";

    
    /**
     * @var string $userLabel
     * @Column(type="string", name="user_label")
     */
    private $userLabel;

    /**
     * @var string $userType
     * @Column(type="string", name="user_type")
     */
    private $userType;

    /**
     * @Column(type="string", name="tva_number")
     * @var string $tvaNumber
     */
    private $tvaNumber;

    /**
     * @Column(type="boolean", name="tva_invoice")
     * @var boolean $tvaInvoice
     */
    private $tvaInvoice;

    /**
     * @Column(type="string", name="rib")
     * @var string $rib
     */
    private $rib;

    /**
     * @Column(type="string", name="account_code")
     * @var string $accountCode
     */
    private $accountCode;

    /**
     * @Column(type="boolean", name="is_active")
     * @var boolean $isActive
     */
    private $isActive;

    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string", name="client_type_code")
     * @var CoreClientTypeCode
     */
    private $clientTypeCode;

    /**
     * @JoinColumn(name="company_id", referencedColumnName="id")
     * @OneToOne(targetEntity="CoreCompanies")
     * @var CoreCompanies
     */
    private $company; 
    /**
     * @Column(type="string", name="sold")
     * @var float Solde de la balance pour l'utilisateur 
     */    
    private $sold;
    
    /**
     * @OneToMany(targetEntity="CoreContacts", mappedBy="user",cascade={"all"}, orphanRemoval=true)
     * @OrderBy({"mainContact" = "DESC", "lastname" = "ASC", "firstname" = "ASC"})
     */
    private $contacts;
    
    public function __construct() {
        $this->contacts = new Doctrine\Common\Collections\ArrayCollection();

        // Default values
        $this->tvaNumber = "";
        $this->tvaInvoice = true;
        $this->accountCode = "";
        $this->isActive = true;
    }
    
    /**
     * Set userLabel
     *
     * @param string $userLabel
     * @return CoreUsers
     */
    public function setUserLabel($userLabel)
    {
        $this->userLabel = $userLabel;
        return $this;
    }

    /**
     * Get userLabel
     *
     * @return string 
     */
    public function getUserLabel()
    {
        return $this->userLabel;
    }

    /**
     * Set userType
     *
     * @param string $userType
     * @return CoreUsers
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
        return $this;
    }

    /**
     * Get userType
     *
     * @return string 
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set tvaNumber
     *
     * @param string $tvaNumber
     * @return CoreUsers
     */
    public function setTvaNumber($tvaNumber)
    {
        $this->tvaNumber = $tvaNumber;
        return $this;
    }

    /**
     * Get tvaNumber
     *
     * @return string 
     */
    public function getTvaNumber()
    {
        return $this->tvaNumber;
    }

    /**
     * Set tvaInvoice
     *
     * @param boolean $tvaInvoice
     * @return CoreUsers
     */
    public function setTvaInvoice($tvaInvoice)
    {
        $this->tvaInvoice = $tvaInvoice ? true : false;
        return $this;
    }

    /**
     * Get tvaInvoice
     *
     * @return boolean 
     */
    public function getTvaInvoice()
    {
        return $this->tvaInvoice;
    }

    /**
     * Set rib
     *
     * @param string $rib
     * @return CoreUsers
     */
    public function setRib($rib)
    {
        $this->rib = $rib;
        return $this;
    }

    /**
     * Get rib
     *
     * @return string 
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set accountCode
     *
     * @param string $accountCode
     * @return CoreUsers
     */
    public function setAccountCode($accountCode)
    {
        $this->accountCode = $accountCode;
        return $this;
    }

    /**
     * Get accountCode
     *
     * @return string 
     */
    public function getAccountCode()
    {
        return $this->accountCode;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return CoreUsers
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
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
     * Set clientTypeCode
     *
     * @param CoreClientType $clientTypeCode
     * @return CoreUsers
     */
    public function setClientTypeCode(\CoreClientType $clientTypeCode = null)
    {
        $this->clientTypeCode = $clientTypeCode;
        return $this;
    }

    /**
     * Get clientTypeCode
     *
     * @return CoreClientType 
     */
    public function getClientTypeCode()
    {
        return $this->clientTypeCode;
    }

    /**
     * Set company
     *
     * @param CoreCompanies $company
     * @return CoreUsers
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
     * Get list of contacts
     * @return @return Doctrine\Common\Collections\Collection List of contacts
     */
    public function getContacts() {
        return $this->contacts;
    }   
    
    /**
     * 
     * @return float
     */
    public function getSold() {
        return $this->sold;
    }

    /**
     * 
     * @param float $sold
     * @return \CommercialViewUserSold
     */
    public function setSold($sold) {
        $this->sold = $sold;
        return $this;
    }


    
}

class CommercialViewUserSoldRepository extends \Doctrine\ORM\EntityRepository {

    public function __toString() {
        return $this->userLabel;
    }
    
    public function findAll($includeDisabledUsers=true) {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("u")
               ->from("CommercialViewUserSold", "u")
               ->where("u.company = :company")
               ->setParameter("company", $userCompany);
            if(!$includeDisabledUsers) {
                $qb->andWhere("u.isActive = 1");
            }
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();         
    }

}