<?php

namespace Igestis\Modules\Commercial\Forms;

/**
 * Class that represents the order search form values
 */
class InterventionsSearchForm {
    /**
     * User id of the searched customer
     * @var int $customerUser 
     */
    private $customerUser;
    
    /**
     * Contact Id of the searched employee
     * @var int $employeeContact 
     */
    private $employeeContact;
    /**
     * Intervention type
     * @var string type
     */
    private $type;
    
    /**
     *
     * @var \DateTime Minimum date of intervention
     */
    private $from;
    
    /**
     *
     * @var \DateTime Maximum date of intervention
     */
    private $to;

    /**
     * Constructor, initialize variables
     */
    public function __construct() {
        $this->setCustomerUser(null)
             ->setEmployeeContact(null)
             ->setType(null);
    }
    
    public function initFromGet() {
        if(!empty($_GET['interventionFrom'])) {
            $this->setFrom(\DateTime::createFromFormat("d/m/Y", $_GET['interventionFrom']));            
        }
        
        if(!empty($_GET['interventionTo'])) {
            $this->setTo(\DateTime::createFromFormat("d/m/Y", $_GET['interventionTo']));            
        }
        
        $this->setCustomerUser(empty($_GET['customerUser']) ? null : $_GET['customerUser'])
             ->setEmployeeContact(empty($_GET['employeeContact']) ? null : $_GET['employeeContact'])
             ->setType(empty($_GET['type']) ? null : $_GET['type']);
    }
    
    /**
     * Get the customer user id
     * @return int Id of the customer user
     */
    public function getCustomerUser() {
        return $this->customerUser;
    }

    /**
     * Set the searched customer user id
     * @param int Id of the customer user to search
     * @return self
     */
    public function setCustomerUser($customerUser) {
        $this->customerUser = $customerUser;
        return $this;
    }

    /**
     * Get the id of the searched employee contact
     * @return int Id of the employee contact to search
     */
    public function getEmployeeContact() {
        return $this->employeeContact;
    }

    /**
     * Set the id of the employee contact to search
     * @param int $employeeContact employee contact id to search
     * @return self
     */
    public function setEmployeeContact($employeeContact) {
        $this->employeeContact = $employeeContact;
        return $this;
    }

    /**
     * Get the searched intervention type
     * @return string intervention type
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set the intervention type
     * @param string $type intervention type
     * @return self
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * Get minimum intervention date 
     * @return \DateTime
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * Set minimum intervention date
     * @param \DateTime $from
     * @return \Igestis\Modules\Commercial\Forms\InterventionsSearchForm
     */
    public function setFrom(\DateTime $from) {
        $this->from = $from;
        return $this;
    }

    /**
     * Get maximum intervention date 
     * @return \DateTime
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * Set macimum intervention date
     * @param \DateTime $to
     * @return \Igestis\Modules\Commercial\Forms\InterventionsSearchForm
     */
    public function setTo(\DateTime $to) {
        $this->to = $to;
        return $this;
    }





}