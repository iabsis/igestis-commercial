<?php



/**
 * CommercialTaxeRate
 *
 * @Table(name="COMMERCIAL_TAXE_RATE")
 * @Entity(repositoryClass="CommercialTaxeRateRepository")
 * @HasLifecycleCallbacks
 */
class CommercialTaxeRate
{
    /**
     * @var string $label
     *
     * @Column(name="label", type="string", length=50)
     */
    private $label;

    /**
     * @var string $accountNo
     *
     * @Column(name="value", type="decimal")
     */
    private $value;

    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * Set label
     *
     * @param string $label
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set value
     *
     * @param string $accountNo
     * @return self
     */
    public function setValue($value)
    {
        $value = (float)  str_replace(",", ".", $value);

        $this->value = $value;
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * Set company
     *
     * @param CoreCompanies $company
     * @return self
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
     * @PrePersist
     * @PreUpdate
     */
    public function prePersist() {           
        
        // Setting company
        if($this->company == null) {
            $this->company = \IgestisSecurity::init()->user->getCompany();
        }
    }
    
    public function showInList() {
        return $this->label . " (" . $this->value . " %)";
    }
}

class CommercialTaxeRateRepository extends \Doctrine\ORM\EntityRepository {

    public function findAll() {
        try {
            $userCompany = \IgestisSecurity::init()->user->getCompany();
            $qb = $this->_em->createQueryBuilder();
            $qb->select("t")
               ->from("CommercialTaxeRate", "t")
               ->where("t.company = :company")
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

}