<?php

namespace Igestis\Modules\Commercial;
/**
 * Buying invoices management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class providerInvoicesController extends \IgestisController {

    /**
     * Show the list of buying invoices
     */
    public function indexAction() {
        $searchForm = new Forms\providerInvoiceSearchForm();
        $searchForm->initFromGet();
        
        $this->context->render("Commercial/pages/providerInvoicesList.twig", array(
            "searchForm" => $searchForm,
            'providersList' => $this->_em->getRepository("CoreContacts")->getSuppliersList(),
            'data_table' =>  $this->_em->getRepository("CommercialProviderInvoice")->findFromSearchForm($searchForm)
        ));
    }
    
    public function newAction() {
        $uploadHandler = new \Igestis\Utils\UploadHandler(array(
            "upload_dir" => ConfigModuleVars::providersInvoicesFolder. "/"
        ), false);
        
        $entityManager = $this->_em;
        
        $uploadHandler->setUploadedCallback(function($filePath = "") use($entityManager) {
            igestis_logger("new object");
            $providerInvoice = new \CommercialProviderInvoice();
            $providerInvoice->setInvoicePath($filePath);
            $entityManager->persist($providerInvoice);
            $entityManager->flush();
            igestis_logger("new object finished");
        })->post();
    }
    

    /**
     * Delete the commercial document
     */
    public function delAction($Id) {
        $CommercialProviderInvoice = $this->context->entityManager->getRepository("CommercialProviderInvoice")->find($Id);
        if(!$CommercialProviderInvoice) $this->context->throw404error();
        
        // Delete the purchasing article from the database
        try {
            $this->context->entityManager->remove($CommercialProviderInvoice);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that the purchasing article deletion has not realy been deleted
            \IgestisErrors::createWizz($e);
            $this->redirect(\ConfigControllers::createUrl("commercial_provider_invoices_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The provider invoice has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_provider_invoices_index"));
    }

    
    /**
     * Open the form to edit the document
     * @param type $Id Id of the document to edit
     */
    public function editAction($Id) {        
        
        
        $document = $this->_em->find("CommercialProviderInvoice", $Id);
        
        if($this->request->IsPost()) {
            
            // Create the new commercial document
            $parser = new \IgestisFormParser();
            $parser->FillEntityFromForm($document, $_POST);
            
            $document->setProviderUser($this->_em->find("CoreUsers", $this->request->getPost('providerUser')));
            $document->setInvoicePaymentType($this->_em->find("CommercialSoldType", $this->request->getPost('soldType')));
            $document->setInvoiceDate(\DateTime::createFromFormat("d/m/Y", $this->request->getPost('invoiceDate')));
            
            $ajaxRender = new \Igestis\Ajax\AjaxResult();
            

            try {                
                $this->context->entityManager->persist($document);
                $this->context->entityManager->flush();  
                
                // Show wizz to article the document update
                new \wizz(_("The document has been successfully updated"), \WIZZ::$WIZZ_SUCCESS);
                $ajaxRender->setSuccessful(_("The document has been successfully updated"))
                           ->setRedirection(\ConfigControllers::createUrl("commercial_provider_invoices_index", array("Id" => $document->getId())))
                           ->render();                
            } catch (\Exception $e) {
                $ajaxRender
                        ->addWizz($e->getMessage(), \wizz::$WIZZ_ERROR)
                        ->setError($e->getMessage());                
            }
        }
  
        $this->context->render("Commercial/pages/providerInvoicesEdit.twig", array(
            "providerInvoice" => $document,
            "soldTypeList" => $this->_em->getRepository("CommercialSoldType")->findAll(),
            "usersList" => $this->_em->getRepository("CoreUsers")->findAll(false)
        ));
    }
    
    /**
     * Download the document
     * @param int $Id Id of the document to look for the last estimate
     * @param int $forceDl
     * 0 => Inline loading
     * 1 => Forced download
     */
    public function downloadAction($Id, $forceDl) {      
         
         $invoice = $this->_em->getRepository("CommercialProviderInvoice")->find($Id);
         if(!$invoice) $this->context->throw404error();
         $filename = ConfigModuleVars::providersInvoicesFolder . "/" . $invoice->getInvoicePath();
         if(!is_file($filename) || !is_readable($filename)) $this->context->throw404error ();
         $this->context->renderFile($filename, $forceDl);
    }
    
    public function addAmountAction($ProviderInvoiceId) {
        $invoice = $this->_em->getRepository("CommercialProviderInvoice")->find($ProviderInvoiceId);
        
        if($this->request->IsPost()) {            
            $ajaxResponse = new \Igestis\Ajax\AjaxResult();
            
            try {
                if(!$invoice) throw new Exception(\Igestis\I18n\Translate::_("Invoice unknown"));
                $invoiceAmount = new \CommercialProviderInvoiceAssocAmounts;
                $invoice->addAmount($invoiceAmount);
                $parser = new \IgestisFormParser();
                $parser->FillEntityFromForm($invoiceAmount, $_POST);                        
                $invoiceAmount->setPurchasingAccount($this->_em->find("CommercialPurchasingAccount", $this->request->getPost('purchasingAccount')));
                if(!$this->request->getPost('taxes')) {
                    $invoiceAmount->setTaxes($invoiceAmount->getAmountTi() -  $invoiceAmount->getAmountDf());
                }
                
                if(!$this->request->getPost('amountDf')) {
                    $invoiceAmount->setAmountDf($invoiceAmount->getAmountTi() -  $invoiceAmount->getTaxes());
                }
                
                $this->_em->persist($invoice);
                $this->_em->flush();
                
                $htmlContent = $this->context->render("Commercial/ajax/ProviderInvoicesEditAmountTableDiv.twig", array(
                    "providerInvoice" => $invoice,
                ), true);
                
                $ajaxResponse->addScript('$("#provider-invoice-edit-amount-modal").modal("hide");')
                             ->addScript('igestisInitTableHover();')
                             ->addAssign("ProviderInvoicesEditAmountTableDiv", $htmlContent)
                             ->setSuccessful("ok")->render();    
                
            } catch (\Exception $exc) {
                $ajaxResponse->addScript('$("#provider-invoice-edit-amount-modal").modal("hide");');
                $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the amount adding process"), $exc->getMessage());
            }
            
        }
        else {
            if(!$invoice) $this->context->throw404error ();
            $this->context->render("Commercial/ajax/ModalContentProviderInvoiceAmountAdd.twig", array(
                "providerInvoice" => $invoice,
                "purchaisingAccountsList" => $this->_em->getRepository("CommercialPurchasingAccount")->findAll()
            ));
        }        
    }
    
    public function editAmountAction($Id) {
        $invoiceAmount = $this->_em->getRepository("CommercialProviderInvoiceAssocAmounts")->find($Id);
        if($invoiceAmount) $invoice = $invoiceAmount->getPurchaseInvoice();        
        
        if($this->request->IsPost()) {            
            $ajaxResponse = new \Igestis\Ajax\AjaxResult();
            
            try {
                if(!$invoice || !$invoiceAmount) throw new Exception(\Igestis\I18n\Translate::_("Invoice unknown"));
                $parser = new \IgestisFormParser();
                $parser->FillEntityFromForm($invoiceAmount, $_POST);                        
                $invoiceAmount->setPurchasingAccount($this->_em->find("CommercialPurchasingAccount", $this->request->getPost('purchasingAccount')));
                if(!$this->request->getPost('taxes')) {
                    $invoiceAmount->setTaxes($invoiceAmount->getAmountTi() -  $invoiceAmount->getAmountDf());
                }
                
                if(!$this->request->getPost('amountDf')) {
                    $invoiceAmount->setAmountDf($invoiceAmount->getAmountTi() -  $invoiceAmount->getTaxes());
                }
                
                $this->_em->persist($invoice);
                $this->_em->flush();
                
                $htmlContent = $this->context->render("Commercial/ajax/ProviderInvoicesEditAmountTableDiv.twig", array(
                    "providerInvoice" => $invoice,
                ), true);
                
                $ajaxResponse->addScript('$("#provider-invoice-edit-amount-modal").modal("hide");')
                             ->addScript('igestisInitTableHover();')
                             ->addAssign("ProviderInvoicesEditAmountTableDiv", $htmlContent)
                             ->setSuccessful("ok")->render();    
                
            } catch (\Exception $exc) {
                $ajaxResponse->addScript('$("#provider-invoice-edit-amount-modal").modal("hide");');
                $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the amount adding process"), $exc->getMessage());
            }
            
        }
        else {
            if(!$invoice || !$invoiceAmount) $this->context->throw404error ();
            $this->context->render("Commercial/ajax/ModalContentProviderInvoiceAmountEdit.twig", array(
                "providerInvoice" => $invoice,
                "providerInvoiceAmount" => $invoiceAmount,
                "purchaisingAccountsList" => $this->_em->getRepository("CommercialPurchasingAccount")->findAll()
            ));
        }        
    }
    
    public function delAmountAction($Id) {
        
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();  
        
        try {
            $invoiceAmount = $this->_em->getRepository("CommercialProviderInvoiceAssocAmounts")->find($Id);
            if(!$invoiceAmount) throw new Exception(\Igestis\I18n\Translate::_("Invoice amount unknown"));
            $invoice = $invoiceAmount->getPurchaseInvoice();
            if(!$invoice) throw new Exception(\Igestis\I18n\Translate::_("Invoice unknown"));
            
            $invoice->removeAmount($invoiceAmount);
            $this->_em->remove($invoiceAmount);
            $this->_em->persist($invoice);
            $this->_em->flush();
            
            $htmlContent = $this->context->render("Commercial/ajax/ProviderInvoicesEditAmountTableDiv.twig", array(
                "providerInvoice" => $invoice,
            ), true);
            
            $ajaxResponse->addScript('$("#provider-invoice-edit-amount-modal").modal("hide");')
                         ->addScript('igestisInitTableHover();')
                         ->addAssign("ProviderInvoicesEditAmountTableDiv", $htmlContent)
                         ->setSuccessful("ok")->render();    
            
        } catch (\Exception $exc) {
            $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the amount remove process"), $exc->getMessage());
        }
        
    }
    
    
    public function refreshAction() {
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();        
        
        
        $htmlContent = $this->context->render("Commercial/ajax/ProviderInvoicesListTableDiv.twig", array(
            "data_table" => $this->_em->getRepository("CommercialProviderInvoice")->findAll(),           
        ), true);

        $ajaxResponse->addAssign('BuyingInvoiceTableDiv', $htmlContent)->setSuccessful("ok")->render();        
    }

}
