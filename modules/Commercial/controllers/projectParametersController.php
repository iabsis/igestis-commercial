<?php

namespace Igestis\Modules\Commercial;
/**
 * Controller for the commercial parameters  pages
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class projectParametersController extends \IgestisController {
    /**
     * Edit the company parameters
     */
    public function editAction() {
         $companyConfig = $this->context->entityManager->getRepository("CommercialCompanyConfig")->find(
                 $this->context->security->user->getCompany()->getId()
         );
         
         if ($this->request->IsPost()) {
            $ajaxResponse = new \Igestis\Ajax\AjaxResult();
            
            try {
                // Set the new datas to the account
                $parser = new \IgestisFormParser();
                $companyConfig->setProjectShowDocuments(false)
                              ->setProjectShowInterventions(false)
                              ->setProjectShowBuyingInvoices(false)
                              ->setProjectShowSalesDocuments(false);
                              

                $companyConfig = $parser->FillEntityFromForm($companyConfig, $_POST);
                $this->context->entityManager->persist($companyConfig);
                $this->context->entityManager->flush();
                
                
                
                $ajaxResponse
                        ->addWizz(\Igestis\I18n\Translate::_("Company configuration has been saved successfully"), \wizz::$WIZZ_SUCCESS)
                        ->setSuccessful("ok")
                        ->render();
            } catch (\Exception $exc) {
                 $ajaxResponse
                        ->addWizz(sprintf(\Igestis\I18n\Translate::_("Error during the configuration save. Error message : %s"), $exc->getMessage()), \wizz::$WIZZ_ERROR)
                        ->setError(\Igestis\I18n\Translate::_("Error during the configuration save"), $exc);
            }
        }
         
         $this->context->render("Commercial/pages/projectParametersConfig.twig", array(
            'form_data' =>  $companyConfig
        ));
    }
}
