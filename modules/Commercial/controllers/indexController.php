<?php
// controllers/indexController.php

// Les controleurs dans le namespace du module
namespace Igestis\Modules\Commercial;

/**
 * Contrôleur permettant de lancer les différentes actions sur les tickets
 */
class indexController extends \IgestisController {
    /**
     * Affiche le tableau avec la liste des tickets
     */  
    
    public function indexAction() {       
        $this->context->render("Commercial/pages/indexAction.twig", array(
            "table_data" => null
        ));
    }
    
}