<?php

namespace Igestis\Modules\Commercial;
/**
 * Balance controller
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class balanceController extends \IgestisController {

    /**
     * Show the balance for each user
     */
    public function indexAction() {
        $this->context->render("Commercial/pages/balanceList.twig", array(
            'customersList' => $this->_em->getRepository("CommercialViewUserSold")->findAll(true)
        ));
    }
    
    public function detailsAction($UserId) {
        if(!$this->_em->find("CoreUsers", $UserId)) {
            $this->context->throw404error();
        }       

        $this->context->render("Commercial/pages/balanceDetails.twig", array(
            'bankAssocs' => $this->_em->getRepository("CommercialViewBankAssocDocs")->findBy(array("user" => $UserId)),
            'commercialDocs' => $this->_em->getRepository("CommercialViewCommercialDocs")->findBy(array("user" => $UserId)),
            'userSold' => $this->_em->getRepository("CommercialViewUserSold")->find($UserId)
        ));
    }
    
    
    public function paidDocumentAction() {
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        
        try {
            switch($_POST['type']) {
                case "selling_document" :
                    $document = $this->_em->find("CommercialInvoice", $_POST['documentId']);
                    break;
                case "buying_document":
                    $document = $this->_em->find("CommercialProviderInvoice", $_POST['documentId']);
                    break;
            }
            
            if(!$document) {
                throw new \Exception(\Igestis\I18n\Translate::_("Document not found"));
            }
            
            if($_POST['paid'] == 1) {
                $document->setPaid(true);
            }
            else {
                $document->setPaid(false);
            }
            
            $this->_em->persist($document);
            $this->_em->flush();

                    
            $ajaxResponse->addWizz(\Igestis\I18n\Translate::_("The paid status has been updated"), \wizz::$WIZZ_SUCCESS)
                ->setSuccessful("ok")
                ->render();
        } catch (\Exception $exc) {
            $ajaxResponse->setError(sprintf(\Igestis\I18n\Translate::_("An error has occurred during the paid status change : %s"), $exc->getMessage()), $exc);
        }





        
    }
}