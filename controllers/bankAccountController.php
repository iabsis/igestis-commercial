<?php

namespace Igestis\Modules\Commercial;

use Igestis\I18n\Translate;
use Igestis\Utils\Dump;
use Igestis\Modules\Commercial\Common\StringManipulation;



/**
 * Bank accounts management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class bankAccountController extends \IgestisController {
    /**
     * Show the list of articles
     */
    public function indexAction() {
        $this->context->render("Commercial/pages/bankAccountList.twig", array(
            'data_table' =>  $this->_em->getRepository("CommercialBankAccount")->findAll()
        ));
    }
    
    /**
     * Delete the bank account
     */
    public function delAction($Id) {
        $account = $this->_em->find("CommercialBankAccount", $Id);
        if(!$account) $this->context->throw404error();
        
        // Delete the purchasing article from the database
        try {
            $this->context->entityManager->remove($account);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that thebank account deletion has not realy been deleted
            \IgestisErrors::createWizz($e);
            $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The bank account has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
    }
    
    /**
     * Proceed to the import of the ofx file transmitted by the user
     */
    public function importResultAction() {
    	try {
    		$ofxParser = new Import\OfXParser($_FILES['ofxFile']['tmp_name'], new Import\OfxParserDb());
    		$ofxParser->parse();
    	} catch (\Exception $e) {
    		// Show wizz to confirm the update
    		\IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY, sprintf(Translate::_("An error has occurred while parsing the file : %s"), $e->getMessage()));
    		
    		// Redirect to the list
    		$this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
    	}
    	
    	
    	// Redirect to the list
    	$this->redirect(\ConfigControllers::createUrl("commercial_bank_account_import_result_show"));        
    }
    
    /**
     * Show import result to let user select which data he want to import
     */
    public function importResultShowAction() {
        
        $importedRows = $this->_em->getRepository("CommercialBankTmpAccount")->findCurrentUserLastImport();
        if($importedRows == null) {
            // Show wizz to article the article update
            new \wizz(Translate::_("There is no operation to import."), \WIZZ::$WIZZ_WARNING);
            
            // Redirect to the article list
            $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
        }
        $this->context->render("Commercial/pages/importResultShow.twig", array(
            "importedAccounts" => $importedRows
        ));
    }
    
    public function importResultValidationAction() {
        $request = StringManipulation::convertUrlQuery($_POST['formValues']);
        $this->_em->getConnection()->beginTransaction();
        
        try {
            // Manage the new account creation
            foreach ($request['selectedAccounts'] as $selectedTmpAccount) {
                $selectedTmpAccountEntity = $this->_em->getRepository("CommercialBankTmpAccount")->find($selectedTmpAccount);
                if($selectedTmpAccountEntity) {
                    $newAccount = $selectedTmpAccountEntity->createNewAccount();
                    $newAccount->setBankName($request['bankName-' . $selectedTmpAccountEntity->getId()]);
                    $this->_em->persist($newAccount);
                    $this->_em->flush();
                }
            }
            
            foreach($request['selectedOperations'] as $selectedTmpOperation) {
                $selectedTmpOperationEntity = $this->_em->getRepository("CommercialBankTmpOperation")->find($selectedTmpOperation);
                if($selectedTmpOperationEntity) {
                    $newOperation = $selectedTmpOperationEntity->createNewOperation();
                    $this->_em->persist($newOperation);
                    $this->_em->flush();
                }
            }
            
            $this->_em->getConnection()->commit();
            
            new \wizz(Translate::_("The selected data has been successfully imported"), \WIZZ::$WIZZ_SUCCESS);            
            $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
            
        } catch (\Exception $e) {
            $this->_em->getConnection()->rollback();
            
            \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY, sprintf(Translate::_("An error has occurred during the import process : %s"), $e->getMessage()));     
            $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
        } 
               
    }
    

    /**
     * Add a bank account
     */
    public function newAction() {
        $account = new \CommercialBankAccount;

        if(!$account) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity            
            
            // Set the new datas to the article
            $parser = new \IgestisFormParser();
            $account = $parser->FillEntityFromForm($account, $_POST); 

            try {                
                $this->context->entityManager->persist($account);
                $this->context->entityManager->flush();  
            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
                $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
            }

            // Show wizz to article the article update
            new \wizz(_("The new bank account has been successfully created"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the article list
            $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
        }
        else{
            // Show wizz to article the article update
            new \wizz(Translate::_("No data received."), \WIZZ::$WIZZ_ERROR);

            // Redirect to the article list
            $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
        }
    }
    
    /**
     * Get a form to edit or validate it if the form is received
     */
    public function editAction($Id) {        
        $account = $this->context->entityManager->getRepository("CommercialBankAccount")->find($Id);

        if(!$account) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {       
            Dump::show($account);
            // Set the new datas to the entity
            $parser = new \IgestisFormParser();
            $account = $parser->FillEntityFromForm($account, $_POST); 


            try {                
                $this->context->entityManager->persist($account);
                $this->_em->flush();
                
                new \wizz(Translate::_("The bank account data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);
                $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
                
            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_MYSQL, sprintf(Translate::_("An error has occurred during the bank account update : %s"), $e->getMessage()));    
                $this->redirect(\ConfigControllers::createUrl("commercial_bank_index"));
            }
            
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/bankAccountEdit.twig", array(
            'form_data' => $account,
        ));
    }    

}
