<?php

namespace Igestis\Modules\Commercial;
/**
 * Projects management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class sellingDocumentsController extends \IgestisController {

    /**
     * Show the list of commercial documents
     */
    public function indexAction() {
        $searchForm = new Forms\commercialDocumentSearchForm();
        $searchForm->initFromGet();
        $this->context->render("Commercial/pages/sellingDocumentsList.twig", array(
            "searchForm" => $searchForm,
            'data_table' =>  $this->_em->getRepository("CommercialCommercialDocument")->findBySearchForm($searchForm)
        ));
    }
    

    /**
     * Delete the commercial document
     */
    public function delAction($Id) {
        $commercialDocument = $this->context->entityManager->getRepository("CommercialCommercialDocument")->find($Id);
        if(!$commercialDocument) $this->context->throw404error();
        
        // Delete the p$commercialDocument from the database
        try {
            $this->context->entityManager->remove($commercialDocument);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that the purchasing article deletion has not realy been deleted
            \IgestisErrors::createWizz($e);
            $this->redirect(\ConfigControllers::createUrl("commercial_selling_document_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The commercial document has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_selling_document_index"));
    }

    /**
     * Create a commercial document
     */
    public function newAction() {
        
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        if ($this->request->IsPost()) {
            
            $cloneFromId = $this->request->getPost("copyFrom");
            $cloneFrom = null;
            if($cloneFromId) $cloneFrom = $this->_em->find("CommercialCommercialDocument", $cloneFromId);            
            $document = new \CommercialCommercialDocument($cloneFrom);

            // Create the new commercial document
            $parser = new \IgestisFormParser();
            $parser->FillEntityFromForm($document, $_POST);
            $document->setCustomerUser($this->_em->getRepository("CoreUsers")->find($this->request->getPost("customerUser")));
            
            try {                
                if($this->request->getPost("projectId")) {
                    $project = $this->_em->find("CommercialProject", $this->request->getPost("projectId"));
                    if(!$project) throw new \Exception(\Igestis\I18n\Translate::_("Unknown project"));
                    $document->setProject($project)->setCustomerUser($project->getCustomerUser());
                }
                $this->context->entityManager->persist($document);
                $this->context->entityManager->flush();  
                // Show wizz to article the document update               
                new \wizz(_("The document has been successfully created"), \WIZZ::$WIZZ_SUCCESS);
                 $ajaxResponse->setRedirection(\ConfigControllers::createUrl("commercial_selling_document_edit", array("Id" => $document->getId())))
                              ->setSuccessful("ok")
                              ->render();                
            } catch (\Exception $e) {
                $ajaxResponse
                        ->addWizz(sprintf(\Igestis\I18n\Translate::_("An error occurred during the project creation : %s"), $e->getMessage()), \wizz::$WIZZ_ERROR, "#selling-document-wizz")
                        ->setError(sprintf(\Igestis\I18n\Translate::_("An error occurred during the project creation : %s"), $e->getMessage()), $e);           
            }            
        }
        else {
            $ajaxResponse->setError(\Igestis\I18n\Translate::_("No datas has been received for the selling document creation"));
        }

    }
    
    /**
     * Open the form to edit the document
     * @param type $Id Id of the document to edit
     */
    public function editAction($Id) {           
        
        $document = $this->_em->find("CommercialCommercialDocument", $Id);
        
        if($this->request->IsPost()) {
            
            // Create the new commercial document
            $parser = new \IgestisFormParser();
            $document = $parser->FillEntityFromForm($document, $_POST);
            $document->setCountryCode($this->_em->getRepository("CoreCountries")->find($this->request->getPost("countryCode")));
            //\Igestis\Utils\Dump::show($document); exit;
            try {                
                $this->context->entityManager->persist($document);
                $this->context->entityManager->flush();  
                // Show wizz to article the document update
                new \wizz(_("The document has been successfully updated"), \WIZZ::$WIZZ_SUCCESS);
                $this->redirect(\ConfigControllers::createUrl("commercial_selling_document_edit", array("Id" => $document->getId())));
                
            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY, \Igestis\I18n\Translate::_("An error occurred during the selling document update"));
                $this->redirect(\ConfigControllers::createUrl("commercial_selling_document_edit", array("Id" => $document->getId())));
            }         
        }
        
        $customersList = $this->_em->find("CoreUsers", $Id);
        
        $companyConfig = $this->_em->getRepository("CommercialCompanyConfig")->getCompanyConfig();        
        $estimationValidUntil = new \DateTime;
        $estimationValidUntil->add(new \DateInterval('P' . $companyConfig->getEstimateExpirationDays() . "D"));
        $invoiceValidUntil = new \DateTime;
        $invoiceValidUntil->add(new \DateInterval('P' . $companyConfig->getInvoicePaymentLimit() . "D"));
        
        $this->context->render("Commercial/pages/sellingDocumentsEdit.twig", array(
            "sellingDocument" => $document,
            "taxeRatesList" => $this->_em->getRepository("CommercialTaxeRate")->findAll(),
            "customersList" => $customersList,
            "sellingAccount" => $this->_em->getRepository("CommercialSellingAccount")->findAll(),
            "countriesList" => $this->_em->getRepository("CoreCountries")->findAll(),
            "soldTypeList" => $this->_em->getRepository("CommercialSoldType")->findAll(),
            "today" => new \DateTime,
            "companyConfig" => $companyConfig,
            "estimationValidUntil" => $estimationValidUntil,
            "invoiceValidUntil" => $invoiceValidUntil
        ));
    }
    
    /**
     * Valid ajax form to add a new article to a selling document
     * @param int $documentId
     */
    public function newArticleAction($documentId) {
        $document = $this->_em->find("CommercialCommercialDocument", $documentId);
        
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        if(!$document) $ajaxResponse->setError(\Igestis\I18n\Translate::_("Unable to find document"));
        
        if($this->request->getPost('articleId')) {
            $documentArticle = $this->_em->find("CommercialCommercialDocumentArticle", $this->request->getPost('articleId'));
            if($documentArticle->getDocument()->getId() != $document->getId()) {
                $ajaxResponse->setError(\Igestis\I18n\Translate::_("Unable to find article"));
            }
        }
        else {
            $documentArticle = new \CommercialCommercialDocumentArticle;
            $document->addArticle($documentArticle);
        }

        $parser = new \IgestisFormParser();
        $parser->FillEntityFromForm($documentArticle, $_POST);
        
        $sellingAccount = $this->request->getPost("sellingAccount");
        $documentArticle->setSellingAccount($sellingAccount ? $this->_em->find("CommercialSellingAccount", $sellingAccount) : null);
        
        
        $ajaxResponse->addScript('igestisInitTableHover()')
                     ->addScript('igestisCommercial.sellingDocument.initTable();');
        
        try {
            $this->context->entityManager->persist($document);
            $this->context->entityManager->flush();  
            
            $html = $this->context->render("Commercial/ajax/TableSellingDocumentArticles.twig", array("sellingDocument" => $document), true);
            $amountsHtml = $this->context->render("Commercial/ajax/sellingDocumentEditAmountsBlock.twig", array("sellingDocument" => $document), true);

            // Show wizz to article the document update
            $ajaxResponse->addScript('$("#new-item").modal("hide");')
                         ->addWizz(\Igestis\I18n\Translate::_("Article successfully saved"), \wizz::$WIZZ_SUCCESS, "#wizz-articles")         
                         ->setSuccessful("ok")
                         ->addAssign("articles-list-div", $html)
                         ->addAssign("amounts-ajax-content", $amountsHtml)
                         ->render();

        } catch (\Exception $e) {
           $ajaxResponse->addScript('$("#new-item").modal("hide");');
           $ajaxResponse->addWizz(\Igestis\I18n\Translate::_($e->getMessage()), \wizz::$WIZZ_ERROR, "#wizz-articles");
           $ajaxResponse->setError($e->getMessage());
        }         
    }
    
    public function searchArticlesAction($SellingDocumentId) {
        $sellingDocument = $this->_em->find("CommercialCommercialDocument", $SellingDocumentId);
        
        
        if($this->request->IsPost()) {
            
            $ajaxResponse = new \Igestis\Ajax\AjaxResult();
            if(!$sellingDocument) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Selling document not found"));
            
            try {
                foreach ($_POST as $key => $quantity) {
                    $matches = null;
                    if(preg_match('/^selected\-([0-9]+)$/', $key, $matches)) {
                        $articleId = $matches[1][0];
                        $article = $this->_em->find("CommercialArticle", $articleId);                        
                        $newArticle = new \CommercialCommercialDocumentArticle($article);
                        $newArticle->setQuantityArticle($quantity);   
                        
                        if($sellingDocument->getCustomerUser()->getTvaInvoice()) {
                            $newArticle->setTaxRate($article->getTaxRate()->getValue());
                        }
                        $sellingDocument->addArticle($newArticle);
                    }
                }
                
                $this->context->entityManager->persist($sellingDocument);
                $this->context->entityManager->flush();  
                
                
                $html = $this->context->render("Commercial/ajax/TableSellingDocumentArticles.twig", array("sellingDocument" => $sellingDocument), true);
                $amountsHtml = $this->context->render("Commercial/ajax/sellingDocumentEditAmountsBlock.twig", array("sellingDocument" => $sellingDocument), true);

                // Show wizz to article the document update
                $ajaxResponse->addScript('igestisInitTableHover()')
                             ->addScript('igestisCommercial.sellingDocument.initTable();')
                             ->addScript('$("#new-item").modal("hide");')
                             ->addWizz(\Igestis\I18n\Translate::_("Article successfully saved"), \wizz::$WIZZ_SUCCESS, "#wizz-articles")         
                             ->setSuccessful("ok")
                             ->addAssign("articles-list-div", $html)
                             ->addAssign("amounts-ajax-content", $amountsHtml)
                             ->render();
            } catch (\Exception $exc) {
                $ajaxResponse->addScript('$("#new-item").modal("hide");');
                $ajaxResponse->addWizz(\Igestis\I18n\Translate::_($exc->getMessage()), \wizz::$WIZZ_ERROR, "#wizz-articles");
                $ajaxResponse->setError($exc->getMessage());
            }

        }
        else {
            if(!$sellingDocument) $this->context->throw404error ();
            
            $this->context->render("Commercial/ajax/ModalCommercialDocumentSearchArticle.twig", array(
                "articlesList" => $this->_em->getRepository("CommercialArticle")->findAll()          
            ));
        }
    }
    
    /**
     * Valid ajax request to delete the requested article
     * @param int $articleId
     */
    public function deleteArticleAction($articleId) {
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        
        $article = $this->_em->find("CommercialCommercialDocumentArticle", $articleId);
        
        if(!$article) {
            $ajaxResponse
                ->addWizz(\Igestis\I18n\Translate::_("Article not found"), \wizz::$WIZZ_ERROR, "#wizz-articles")
                ->setError(\Igestis\I18n\Translate::_("Article not found"));
        }
        
        if($article->getDocument()->getCompany()->getId() != $this->context->security->user->getCompany()->getId()) {
            $ajaxResponse
                ->addWizz(\Igestis\I18n\Translate::_("Not access to this article"), \wizz::$WIZZ_ERROR, "#wizz-articles")
                ->setError(\Igestis\I18n\Translate::_("Not access to this article"));
        }
        
        $ajaxResponse->addScript('igestisInitTableHover()')
                     ->addScript('igestisCommercial.sellingDocument.initTable();');
        
        try {
            $this->_em->remove($article);
            $this->_em->flush();
            
            $html = $this->context->render("Commercial/ajax/TableSellingDocumentArticles.twig", array("sellingDocument" => $article->getDocument()), true);
            $amountsHtml = $this->context->render("Commercial/ajax/sellingDocumentEditAmountsBlock.twig", array("sellingDocument" => $article->getDocument()), true);
            
            $ajaxResponse
                ->addWizz(\Igestis\I18n\Translate::_("Article successfully removed"), \wizz::$WIZZ_SUCCESS, "#wizz-articles")
                ->setSuccessful(\Igestis\I18n\Translate::_("ok"))
                ->addAssign("articles-list-div", $html)
                ->addAssign("amounts-ajax-content", $amountsHtml)
                ->render();
        } catch (\Exception $exc) {
            $ajaxResponse
                ->addWizz(\Igestis\I18n\Translate::_($exc->getMessage()), \wizz::$WIZZ_ERROR, "#wizz-articles")
                ->setError(\Igestis\I18n\Translate::_($exc->getMessage()));
        }
    }
    
    /**
     * Script where the datatable Reordering
     * POST [id('tr-article-30'), fromPosition(3), toPosition(4), direction('back'), group('')]
     * @param type $documentId
     */
    public function reorderArticleAction($documentId) {
        
        $document = $this->_em->find("CommercialCommercialDocument", $documentId);
        if(!$document) $this->context->throw404error ();
        
        $articleId = preg_replace("/tr\-article\-/", "", $this->request->getPost('id'));
        
        $article = $this->_em->find("CommercialCommercialDocumentArticle", $articleId);
        if($document != $article->getDocument()) $this->context->throw404error ();
        $article->setArticleOrder($this->request->getPost('toPosition'));
        
        $this->_em->persist($document);
        $this->_em->flush();
        
    }

}
