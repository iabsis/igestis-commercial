<?php

namespace Igestis\Modules\Commercial\Forms;

/**
 * Class that represents the order search form values
 */
class commercialDocumentSearchForm {
    private $exported;
    private $paid;
    private $state;
    /**
     *
     * @var \CoreUsers
     */
    private $customer;
    
    /**
    * @var \Doctrine\ORM\EntityManager Entitymanager to access to the doctrine entities
    */
    private $entityManager;
    
    const EXPORTED_YES = 1;
    const EXPORTED_NO = 0;
    const EXPORTED_ALL = -1;
    
    const PAID_YES = 1;
    const PAID_NO = 0;
    const PAID_ALL = -1;
    
    const STATUS_ALL = 0;

    /**
     * Constructor, initialize variables
     */
    public function __construct($entityManager=null) {
        $this->entityManager = $entityManager;
        
        $this->setExported(self::EXPORTED_NO)
             ->setPaid(self::EXPORTED_ALL)
             ->setState(self::STATUS_ALL)
             ->setCustomer(null);
    }
    
    /**
     * Initialize the form value from _get values
     */
    public function initFromGet() {        
        if(isset($_GET['paid'])) {
            $paid = $_GET['paid'];
        }
        else $paid = self::PAID_ALL;

        $this->setExported(empty($_GET['exported']) ? self::EXPORTED_NO : $_GET['exported'])
             ->setPaid($paid)
             ->setState(empty($_GET['state']) ? self::STATUS_ALL : $_GET['state'])
             ;
        
        if($this->entityManager) {
            $this->setCustomer(empty($_GET['customerId']) ? null : $this->entityManager->getRepository("CoreUsers")->find($_GET['customerId']));
        }
    }
    
    /**
     * 
     * @return \CoreUsers
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * 
     * @param \CoreUsers $customer
     */
    public function setCustomer(\CoreUsers $customer=null) {
        $this->customer = $customer;
    }

                
    /**
     * 
     * @return integer
     */
    public function getExported() {
        return $this->exported;
    }

    /**
     * 
     * @param integer $exported
     * @return \Igestis\Modules\Commercial\Forms\commercialDocumentSearchForm
     */
    public function setExported($exported) {
        $this->exported = $exported;
        return $this;
    }

    /**
     * 
     * @return integer
     */
    public function getPaid() {
        return $this->paid;
    }

    /**
     * 
     * @param integer $paid
     * @return \Igestis\Modules\Commercial\Forms\commercialDocumentSearchForm
     */
    public function setPaid($paid) {
        $this->paid = $paid;
        return $this;
    }

    /**
     * 
     * @return integer
     */
    public function getState() {
        return $this->state;
    }

    /**
     * 
     * @param integer $state
     * @return \Igestis\Modules\Commercial\Forms\commercialDocumentSearchForm
     */
    public function setState($state) {
        $this->state = $state;
        return $this;
    }




}