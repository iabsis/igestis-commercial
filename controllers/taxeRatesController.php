<?php

namespace Igestis\Modules\Commercial;
/**
 * Articles management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class taxeRatesController extends \IgestisController {

    /**
     * Show the list of articles
     */
    public function indexAction() {
        $this->context->render("Commercial/pages/taxeRatesList.twig", array(
            'data_table' =>  $this->_em->getRepository("CommercialTaxeRate")->findAll()
        ));
    }
    

    /**
     * Delete the article
     */
    public function delAction($Id) {
        $CommercialTaxeRate = $this->context->entityManager->getRepository("CommercialTaxeRate")->find($Id);
        if(!$CommercialTaxeRate) $this->context->throw404error();
        
        // Delete the entity from the database
        try {
            $this->context->entityManager->remove($CommercialTaxeRate);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that the purchasing article deletion has not realy been deleted
            \IgestisErrors::createWizz($e);
            $this->redirect(\ConfigControllers::createUrl("commercial_taxe_rates_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The taxe rate has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_taxe_rates_index"));
    }

    /**
     * Add an article
     */
    public function newAction() {
        $taxeRate = new \CommercialTaxeRate;

        if(!$taxeRate) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity            
            
            // Set the new datas to the article
            $parser = new \IgestisFormParser();
            $parser->FillEntityFromForm($taxeRate, $_POST); 

            try {                
                $this->context->entityManager->persist($taxeRate);
                $this->context->entityManager->flush();  
            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
                $this->redirect(\ConfigControllers::createUrl("commercial_taxe_rates_index"));
            }

            // Show wizz to article the article update
            new \wizz(_("The taxe rate data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the article list
            $this->redirect(\ConfigControllers::createUrl("commercial_taxe_rates_index"));
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/taxeRatesNew.twig", array(
            'form_data' => $taxeRate,
        ));
    }
    
    /**
     * Get a form to edit or validate it if the form is received
     */
    public function editAction($Id) {        
        $taxeRate = $this->context->entityManager->getRepository("CommercialTaxeRate")->find($Id);

        if(!$taxeRate) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity
                        
            // Set the new datas to the article
            $parser = new \IgestisFormParser();
            $parser->FillEntityFromForm($taxeRate, $_POST);
            

            try {                
                $this->context->entityManager->persist($taxeRate);
                $this->context->entityManager->flush();  
            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
                $this->redirect(\ConfigControllers::createUrl("commercial_taxe_rates_index"));
            }

            // Show wizz to article the article update
            new \wizz(_("The taxe rate data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the article list
            $this->redirect(\ConfigControllers::createUrl("commercial_taxe_rates_index"));
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/taxeRatesEdit.twig", array(
            'form_data' => $taxeRate
        ));
    }    

}
