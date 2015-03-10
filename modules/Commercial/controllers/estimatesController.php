<?php

namespace Igestis\Modules\Commercial;
/**
 * Articles management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class estimatesController extends \IgestisController {

    /**
     * Create a new estimate
     */
    public function newAction($documentId) {
        $document = $this->_em->find("CommercialCommercialDocument", $documentId);
        if(!$document) $this->context->throw404error();
        
        $this->_em->getConnection()->beginTransaction();         

        try {       
            if(count($document->getInvoices())) throw new \Exception(\Igestis\I18n\Translate::_("An invoice has already been generated for this project and is not editable anymore."));
            $estimateDocument = new Pdfs\GenerateEstimate($this->_em, $document, $this->context->getTwigEnvironnement(), "Commercial/pdfs/estimate.twig");
            $estimateDocument->setEstimateDate(\DateTime::createFromFormat("d/m/Y", $this->request->getPost("estimateDate")))
                             ->setValidUntil(\DateTime::createFromFormat("d/m/Y", $this->request->getPost("validUntil")));
            
            $estimateDocument->generate()->show(null, "F");
            $this->context->entityManager->commit();
            new \wizz(\Igestis\I18n\Translate::_("The estimate has been generated"), \wizz::$WIZZ_SUCCESS);
            $this->redirect(\ConfigControllers::createUrl("commercial_selling_document_edit", array("Id" => $documentId)));
        } catch (\Exception $e) {
            $this->context->entityManager->rollback();
            \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
            $this->redirect(\ConfigControllers::createUrl("commercial_selling_document_edit", array("Id" => $documentId)));
        }
        
    }
    
    /**
     * Download the document
     * @param int $Id Id of the document to look for the last estimate
     * @param int $forceDl
     * 0 => Inline loading
     * 1 => Forced download
     */
    public function downloadAction($Id, $forceDl) {
         $estimate = $this->_em->getRepository("CommercialEstimate")->find($Id);
         if(!$estimate) $this->context->throw404error();
         $filename = ConfigModuleVars::quotationsFolder() . "/" . $estimate->getCommercialDocument()->getCompany()->getId() . "/" . $estimate->getPathPdfFile();
         
         if(!is_file($filename) || !is_readable($filename)) $this->context->throw404error ();
         $this->context->renderFile($filename, $forceDl);
    }
    
    /**
     * Send the email to the customer
     * @param type $estimateId Id of the estimate to send to the customer
     */
    public function mailAction($estimateId) {
        
        // Create ajax response
        $ajaxRender = new \Igestis\Ajax\AjaxResult();     
        $ajaxRender->addScript('$("#SendQuotation").modal("hide");');
        
        // Get recipient from the POST form
        $email = $this->request->getPost("email");
        if(!is_email($email)) {
            $ajaxRender->addWizz(\Igestis\I18n\Translate::_("Please provide a valid email address"), \wizz::$WIZZ_ERROR)
                       ->setError(\Igestis\I18n\Translate::_("Please provide a valid email address"));
        }
        
        // Retrieve the estimate entity
        $estimate = $this->_em->getRepository("CommercialEstimate")->find($estimateId);        
        if(!$estimate) {
            $ajaxRender->addWizz(\Igestis\I18n\Translate::_("Estimate not found"), \wizz::$WIZZ_ERROR)
                       ->setError(\Igestis\I18n\Translate::_("Estimate not found"));
        }
        
        
        try {
            //  Start the mail process
            $message = \IgestisMailer::init();

            $html = $this->request->getPost('content');
            $company = $this->context->security->user->getCompany();
            
            $message
                // Give the message a subject
                ->setSubject(\Igestis\I18n\Translate::_("Your estimate"))
                // Set the From address with an associative array
                ->setFrom(array($company->getEmail() => $company->getName()))
                ->setBcc(array($company->getEmail() => $company->getName()))
                // Set the To addresses with an associative array
                ->setTo($email)
                // And optionally an alternative body
                ->addPart($html, 'text/html')
                // Attech the quotation pdf
                ->attach(\Swift_Attachment::fromPath(ConfigModuleVars::quotationsFolder() . "/" . $company->getId() . "/" . $estimate->getPathPdfFile(), "application/pdf"))                
            ;
            // Send the mail
            \IgestisMailer::send($message);
            
            // Return the ajax response
            $ajaxRender->addWizz(sprintf(\Igestis\I18n\Translate::_("The email has been sent to %s"), $email), \wizz::$WIZZ_SUCCESS)
                       ->setSuccessful("ok")
                       ->render();

        }
        catch(\Exception $e) {
            // Return the ajax error response if an error occurred during the mail process
            $ajaxRender->setError(\Igestis\I18n\Translate::_("Error during the mail delivrery"), $e->getMessage());
        }
    }

}
