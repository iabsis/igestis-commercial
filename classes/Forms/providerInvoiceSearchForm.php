<?php

namespace Igestis\Modules\Commercial\Forms;

/**
 * Class that represents the order search form values
 */
class providerInvoiceSearchForm {
    private $assigned;
    
    const ASSIGNED_YES = 1;
    const ASSIGNED_NO = 0;
    const ASSIGNED_ALL = -1;

    /**
     * Constructor, initialize variables
     */
    public function __construct() {
        $this->setAssigned(self::ASSIGNED_NO);
    }
    
    /**
     * Initialize the form value from _get values
     */
    public function initFromGet() {        
        $this->setAssigned(empty($_GET['assigned']) ? null : $_GET['assigned']);
    }
    
    /**
     * Return which operations show
     * @return integer 
     */
    public function getAssigned() {
        return $this->assigned;
    }

    /**
     * Set which operations show
     * @param int $assigned
     * @return \Igestis\Modules\Commercial\Forms\InterventionsSearchForm
     */
    public function setAssigned($assigned) {
        $this->assigned = $assigned;
        return $this;
    }



}