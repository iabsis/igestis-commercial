<?php

namespace Igestis\Modules\Commercial\Forms;

/**
 * Class that represents the order search form values
 */
class projectSearchForm
{

    /**
     * User id of the searched customer
     * @var int $customerUser 
     */
    private $customerUser;

    private $status;
    
    const STATUS_CLOSED = 1;
    const STATUS_OPENED = 0;
    const STATUS_ALL = -1;

    /**
     * Constructor, initialize variables
     */
    public function __construct()
    {
        $this->setStatus(self::STATUS_CLOSED)
             ->setCustomerUser(null);
    }
    
    /**
     * Initialize the form value from _get values
     */
    public function initFromGet()
    {
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
        } else {
            $status = self::STATUS_OPENED;
        }

        $this->setCustomerUser(empty($_GET['customerUser']) ? null : $_GET['customerUser']);
        $this->setStatus($status);
    }
    
    /**
     * Return which project to show
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set which operations show
     * @param int $status
     * @return \Igestis\Modules\Commercial\Forms\projectSearchForm
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the customer user id
     * @return int Id of the customer user
     */
    public function getCustomerUser()
    {
        return $this->customerUser;
    }

    /**
     * Set the searched customer user id
     * @param int Id of the customer user to search
     * @return self
     */
    public function setCustomerUser($customerUser)
    {
        $this->customerUser = $customerUser;
        return $this;
    }
}
