<?php

namespace Igestis\Modules\Commercial;

use Igestis\I18n\Translate;



/**
 * Bank accounts management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class bankOperationController extends \IgestisController {
    /**
     * Show the list of operations
     * @param int $AccountId Id of the account to parse
     */
    public function indexAction($AccountId) {
        $account = $this->_em->find("CommercialBankAccount", $AccountId);
        if(!$account) $this->context->throw404error();
        
        $searchForm = new Forms\bankOperationsSearchForm();
        $searchForm->initFromGet();
        
        $operations = $this->_em->getRepository("CommercialBankOperation")->findFromSearchForm($account, $searchForm);
        $this->context->render("Commercial/pages/bankOperationsList.twig", array(
            "account" => $account,
            "searchForm" => $searchForm,
            "operationsList" => $operations
        ));
    }
    
    
    /**
     * Delete the bank operation
     */
    public function delAction($Id) {
        $operation = $this->_em->find("CommercialBankOperation", $Id);
        if(!$operation) $this->context->throw404error();
    
        // Delete the purchasing article from the database
        try {
            $this->context->entityManager->remove($operation);
            $this->context->entityManager->flush();
            // Show wizz to confirm the update
            new \wizz(Translate::_("The operation has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);
        } catch (\Exception $e) {
            // Show wizz to alert user that thebank account deletion has not realy been deleted
            \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_MYSQL, sprintf(Translate::_("An error has occurred during the bank operation deletion : %s"), $e->getMessage())); 
        }
    
        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_operations_index", array("AccountId" => $operation->getAccount()->getId())));
    }
    
    /**
     * Create a new operation
     */
    public function newAction() {
        $operation = new \CommercialBankOperation();
        $account = $this->_em->find("CommercialBankAccount", $this->request->getPost("accountId"));
        if(!$account) $this->context->throw404error();
        
        
        if ($this->request->IsPost()) {
        
            // Create the new commercial document
            $parser = new \IgestisFormParser();
            $operation = $parser->FillEntityFromForm($operation, $_POST);
            $operation->setAccount($account);
            $operation->setOperationDate(\DateTime::createFromFormat("d/m/Y", $this->request->getPost("operationDate")));
        

            try {
                $this->context->entityManager->persist($operation);
                $this->context->entityManager->flush();
                // Show wizz to article the article update
                new \wizz(_("The project has been successfully created"), \WIZZ::$WIZZ_SUCCESS);
                // Redirect to the article list
                $this->redirect(\ConfigControllers::createUrl("commercial_operations_index", array("AccountId" => $account->getId())));
        
            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_MYSQL, sprintf(Translate::_("An error has occurred during the bank operation creation : %s"), $e->getMessage()));
                $this->redirect(\ConfigControllers::createUrl("commercial_operations_index", array("AccountId" => $account->getId())));
            }
        }
        else {
            new \wizz(Translate::_("No data has been received for the operation creation"), \WIZZ::$WIZZ_ERROR);
            $this->redirect(\ConfigControllers::createUrl("commercial_operations_index", array("AccountId" => $account->getId())));
        }
    }

    /**
     * Edit an operation
     * @param int $Id
     */
    public function editAction($Id) {
        $operation = $this->_em->find("CommercialBankOperation", $Id);
        if(!$operation) $this->context->throw404error();
        
        if($this->request->IsPost()) {
            $ajaxResponse  =  new \Igestis\Ajax\AjaxResult();
            $this->_em->beginTransaction();
            
            try {
                //$operation = new \CommercialBankOperation;
                $operation->emptyAssocs();                
                $this->_em->persist($operation);
                $this->_em->flush();
                
                if(!empty($_POST['notAssignable']) && $_POST['notAssignable'] == 1) {
                    unset($_POST['selected-invoices']);
                    unset($_POST['selected-provider-invoices']);
                    unset($_POST['associatedUser']);
                    $operation->setNotAssign(true);
                }
                
                if(isset($_POST['selected-invoices'])) {
                    foreach($_POST['selected-invoices'] as $sellingInvoiceId) {
                        $assoc = new \CommercialBankAssocOperations();
                        $assoc->setInvoice($this->_em->find("CommercialInvoice", $sellingInvoiceId));
                        $operation->addAssoc($assoc);
                    }
                }
                
                if(isset($_POST['selected-provider-invoices'])) {
                    foreach($_POST['selected-provider-invoices'] as $providerInvoiceId) {
                        $assoc = new \CommercialBankAssocOperations();
                        $assoc->setProviderInvoice($this->_em->find("CommercialProviderInvoice", $providerInvoiceId));
                        $operation->addAssoc($assoc);
                    }
                }
                
                if(isset($_POST['associatedUser'])) {
                    foreach ($_POST['associatedUser'] as $key => $associatedUserId) {
                        if(empty($associatedUserId)) continue;
                        $assoc = new \CommercialBankAssocOperations();
                        $assoc->setConcernedUser($this->_em->find("CoreUsers", $associatedUserId))
                              ->setAmount($_POST['amount'][$key]);
                        $operation->addAssoc($assoc);
                    }
                }
                
                
                $this->_em->persist($operation);
                $this->_em->flush();
                $this->_em->commit();
            }
            catch (\Exception $e) {
                $this->_em->rollback();
                $ajaxResponse->addWizz(Translate::_("Error during the operation association process"));
                $ajaxResponse->setError($e->getMessage(), \Igestis\Utils\Dump::get($e));
            }
            
            
            if($this->request->getPost("next")) {
                $nextUnassignedOperation = $this->_em->getRepository("CommercialBankOperation")->findNextNonAssignedOperation($operation->getAccount());
                if($nextUnassignedOperation)  {
                    $ajaxResponse->setRedirection(\ConfigControllers::createUrl("commercial_bank_operation_edit", array("Id" => $nextUnassignedOperation->getId())));
                }
                else {
                    new \wizz(Translate::_("There is no more unassigned operation"), \wizz::$WIZZ_INFO);
                    $ajaxResponse->setRedirection(\ConfigControllers::createUrl("commercial_operations_index", array("AccountId" => $operation->getAccount()->getId())));
                }
                
            }
            else {
                $ajaxResponse->setRedirection(\ConfigControllers::createUrl("commercial_operations_index", array("AccountId" => $operation->getAccount()->getId())));
            }
            
            $ajaxResponse->addWizz(Translate::_("The association has been successfully saved"), \wizz::$WIZZ_SUCCESS)
                         ->setSuccessful("ok")
                         ->render();
            
            
        }
        
        $this->context->render("Commercial/pages/bankOperationsEdit.twig", array(
            "operation" => $operation,
            "sameAmoutProviderInvoicesList" => $this->_em->getRepository("CommercialProviderInvoice")->findAssociableToOperation($operation),
            "sameAmoutSellingInvoicesList"  => $this->_em->getRepository("CommercialInvoice")->findAssociableToOperation($operation),
            "usersList" => $this->_em->getRepository("CoreUsers")->findAll(false)
        ));
    }


}
