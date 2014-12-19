<?php

/**
 * CommercialTimeCredit
 *
 * @Table(name="COMMERCIAL_TIME_CREDIT")
 * @Entity(repositoryClass="CommercialTimeCreditRepository")
 */
class CommercialTimeCredit
{
    /**
     * @var string $nextInvoiceId
     *
     * @Column(name="credit_minutes", type="integer")
     */
    private $creditMinutes;

    /**
     * @var CommercialCommercialDocument
     * @Id
     * @OneToOne(targetEntity="CommercialCommercialDocument")
     * @JoinColumns({
     *   @JoinColumn(name="commercial_document_id", referencedColumnName="id")
     * })
     */
    private $commercialDocument;
    
    /**
     * 
     * @param \CoreCompanies $company
     */
    public function __construct(\CommercialCommercialDocument $document, $time=0) {
        $this->creditMinutes = $time;
        $this->commercialDocument = $document;
    }

    /**
     * Return the credit time
     * @return [type] [description]
     */
    public function getCreditMinutes() {
        return $this->creditMinutes;
    }
    /**
     * Set the credit time
     * @param [type] $creditHours [description]
     */
    public function setCreditMinutes($creditMinutes) {

        if ($creditMinutes == (string)(int)$creditMinutes) {
            $this->creditMinutes = $creditMinutes;
        }
        else {
            if (preg_match("/^[0-9]+\:[0-9]+(\:[0-9]+)?$/", $creditMinutes)) {
                $aTime = explode(":", $creditMinutes);
                $this->creditMinutes = $aTime[0] * 60 + $aTime[1];
            }
        }
        
        return $this;
    }

    public static function getTimeString($intTime) {
        $hours = (int)($intTime / 60);
        $minutes = $intTime % 60;

        return $hours . ":" . ($minutes < 10 ? "0" : "") . $minutes;
    }

    public function getCreditMinutesAsString() {
        return self::getTimeString($this->creditMinutes);
    }

    /**
     * Get the linked commercial document
     * @return [type] [description]
     */
    public function getCommercialDocument() {
        return $this->commercialDocument;
    }

    /**
     * Set the linked commercial document
     * @param [type] $commercialDocument [description]
     */
    public function setCommercialDocument($commercialDocument) {
        $this->commercialDocument = $commercialDocument;
        return $this;
    }

    public function __toString() {
        return $this->getCreditMinutesAsString();
    }
}

// -----------------------------------------------------------

/**
* 
*/
class CommercialTimeCreditRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLinkedTimeCredit(CommercialCommercialDocument $commercialDocument) {
        try {
            $qb = $this->_em->createQueryBuilder();
            $qb->select("tc")
               ->from("CommercialTimeCredit", "tc")
               ->where("tc.commercialDocument = :commercialDocument")
               ->setParameter("commercialDocument", $commercialDocument);
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        $linkedCommercialTimeCredit = $qb->getQuery()->getOneOrNullResult();  
        if ($linkedCommercialTimeCredit === null) {
            $linkedCommercialTimeCredit = new CommercialTimeCredit($commercialDocument);
        } 

        return $linkedCommercialTimeCredit;
    }
}