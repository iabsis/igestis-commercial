<?php

namespace Igestis\Modules\Commercial;
/**
 * Articles management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class PurchaseOrderController extends \IgestisController {

    /**
     * Create a new estimate
     */
    public function updateAction($sellingDocumentId) {
        $document = $this->_em->find("CommercialCommercialDocument", $sellingDocumentId);
        if(!$document) $this->context->throw404error();
        
        $this->_em->getConnection()->beginTransaction();
        
        try {
            
            $purchaseOrder = new Pdfs\GeneratePurchaseOrder($this->_em, $document, $this->context->getTwigEnvironnement(), "Commercial/pdfs/purchase_order.twig");
            
            $purchaseOrder->generate()->show(null, 'F');
            $this->context->entityManager->commit();
            new \wizz(\Igestis\I18n\Translate::_("The purchase order has been generated"), \wizz::$WIZZ_SUCCESS);
            $this->redirect(\ConfigControllers::createUrl("commercial_selling_document_edit", array("Id" => $sellingDocumentId)));
        } catch (\Exception $e) {
            $this->context->entityManager->rollback();
            \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
            $this->redirect(\ConfigControllers::createUrl("commercial_selling_document_edit", array("Id" => $sellingDocumentId)));
        }
        
    }
    
    /**
     * Download the document
     * @param int $Id Id of the document to look for the last estimate
     * @param int $forceDl
     * 0 => Inline loading
     * 1 => Forced download
     */
    public function downloadAction($Id, $forceDl)
    {
         $purchaseOrder = $this->_em->getRepository("CommercialPurchaseOrder")->find($Id);
         $currentUserId = $this->context->security->user->getId();
         $isEmployee = ($this->context->security->user->getUserType() == \CoreUsers::USER_TYPE_EMPLOYEE);

         if (!$purchaseOrder || (!$isEmployee && $purchaseOrder->getCommercialDocument()->getCustomerUser()->getId() != $currentUserId))
         {
             $this->context->throw404error();
         }

         if(!$purchaseOrder) $this->context->throw404error();
         $filename = ConfigModuleVars::purchaseOrderFolder() . "/" . $purchaseOrder->getCommercialDocument()->getCompany()->getId() . "/" . $purchaseOrder->getPathPdfFile();
         if(!is_file($filename) || !is_readable($filename)) $this->context->throw404error ();
         $this->context->renderFile($filename, $forceDl);
    }
    
}

