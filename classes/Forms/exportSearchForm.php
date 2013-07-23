<?php

namespace Igestis\Modules\Commercial\Forms;

/**
 * Class that represents the order search form values
 */
class exportSearchForm {
    private $exported;
    
    const EXPORTED_YES = 1;
    const EXPORTED_NO = 0;
    const EXPORTED_ALL = -1;

    /**
     * Initialize the form value from _get values
     */
    public function initFromGet() {        
        if(isset($_GET['exported'])) {
            $exported = $_GET['exported'];
        }
        else $exported = self::EXPORTED_NO;

        $this->setExported($exported);
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



}