<?php

namespace Igestis\Modules\Commercial;
/**
 * Accounting management
 *
 * @author Gilles Hemmerlé
 */
class AccountingController extends \IgestisController {

    /**
     * Show the list of accountings
     */
    public function indexAction() {
        $this->context->render("Commercial/pages/accountingList.twig", array(
            'sellingAccounts' =>  $this->context->entityManager->getRepository("CommercialSellingAccount")->findAll(),
            'purchasingAccounts' =>  $this->context->entityManager->getRepository("CommercialPurchasingAccount")->findAll(),
            'vatAccounting' => $this->_em->getRepository("CommercialVatAccounting")->getCompanyConfig()
        ));
    }
    
    public function saveVatAccountingAction() {
        $vatAccounting = $this->_em->getRepository("CommercialVatAccounting")->getCompanyConfig();
        
        $ajaxRender = new \Igestis\Ajax\AjaxResult();
        
        try {
            $parser = new \IgestisFormParser();
            $parser->FillEntityFromForm($vatAccounting, $_POST);
            $this->_em->persist($vatAccounting);
            $this->_em->flush();
            
            $ajaxRender->setSuccessful("ok")
                       ->addWizz(\Igestis\I18n\Translate::_("The tax accouting values has been successfuly updated"), \wizz::$WIZZ_SUCCESS)
                       ->render();
        }
        catch(\Exception $e) {
            $ajaxRender->setError(\Igestis\I18n\Translate::_("An error has occurred during the record of the tax accounting"), $e);
        }
    }
    

    /**
     * Delete the purchasing account
     */
    public function purchasingAccountDelAction($Id) {
        $CommercialPurchasingAccount = $this->context->entityManager->getRepository("CommercialPurchasingAccount")->find($Id);
        if(!$CommercialPurchasingAccount) $this->context->throw404error();
        
        // Delete the purchasing account from the database
        try {
            $this->context->entityManager->remove($CommercialPurchasingAccount);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that the purchasing account deletion has not realy been deleted
            \IgestisErrors::createWizz($e);
            $this->redirect(\ConfigControllers::createUrl("commercial_accounting_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The purchasing account has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_accounting_index"));
    }

    /**a
     * Add a purchasing account
     */
    public function purchasingAccountNewAction() {
        if ($this->request->IsPost()) {
            // Check the form validity
            if (!$this->request->getPost("label"))
                $this->context->invalid_form(_("The account name is a required field"));
            
            if (!$this->request->getPost("accountNumber"))
                $this->context->invalid_form(_("The account number is a required field"));

            // Get the original account
            $account = new \CommercialPurchasingAccount();

            // Set the new datas to the account
            $parser = new \IgestisFormParser();
            $account = $parser->FillEntityFromForm($account, $_POST);

            // Save the account into the $account
            $this->context->entityManager->persist($account);
            $this->context->entityManager->flush();

            // Show wizz to confirm the account update
            new \wizz(_("The account data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the account list
            $this->redirect(ConfigControllers::createUrl("commercial_accounting_index"));
        }

        $this->context->render("Commercial/pages/accountingPurchasingNew.twig", array());
    }
    
    /**
     * Get a form to edit or validate it if the form is received
     */
    public function purchasingAccountEditAction($Id) {
        $account = $this->context->entityManager->getRepository("CommercialPurchasingAccount")->find($Id);
        if(!$account) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity
            if (!$this->request->getPost("label"))  $this->context->invalid_form(_("The account name is a required field"));            
            if (!$this->request->getPost("accountNumber"))  $this->context->invalid_form(_("The account number is a required field"));

            try {
                // Set the new datas to the account
                $parser = new \IgestisFormParser();
                $account = $parser->FillEntityFromForm($account, $_POST);
                $this->context->entityManager->persist($account);
                $this->context->entityManager->flush();      
            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
                $this->redirect(\ConfigControllers::createUrl("commercial_accounting_index"));
            }

            // Show wizz to confirm the account update
            new \wizz(_("The account data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the account list
            $this->redirect(\ConfigControllers::createUrl("commercial_accounting_index"));
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/accountingPurchasingEdit.twig", array(
            'form_data' => $account
        ));
    }
    
    /****************************************************************************************************************************************
     * Delete the selling account
     */
    public function sellingAccountDelAction($Id) {
        $CommercialSellingAccount = $this->context->entityManager->getRepository("CommercialSellingAccount")->find($Id);
        if(!$CommercialSellingAccount) $this->context->throw404error();
        
        // Delete the selling account from the database
        try {
            $this->context->entityManager->remove($CommercialSellingAccount);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that the selling account deletion has not realy been deleted
            \IgestisErrors::createWizz($e);
            $this->redirect(\ConfigControllers::createUrl("commercial_accounting_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The selling account has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_accounting_index"));
    }

    /**
     * Add a selling account
     */
    public function sellingAccountNewAction() {
        if ($this->request->IsPost()) {
            // Check the form validity
            if (!$this->request->getPost("label"))
                $this->context->invalid_form(_("The account name is a required field"));
            
            if (!$this->request->getPost("accountNumber"))
                $this->context->invalid_form(_("The account number is a required field"));

            // Get the original account
            $account = new \CommercialSellingAccount();

            // Set the new datas to the account
            $parser = new \IgestisFormParser();
            $account = $parser->FillEntityFromForm($account, $_POST);

            // Save the account into the $account
            $this->context->entityManager->persist($account);
            $this->context->entityManager->flush();

            // Show wizz to confirm the account update
            new \wizz(_("The account data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the account list
            $this->redirect(ConfigControllers::createUrl("commercial_accounting_index"));
        }

        $this->context->render("Commercial/pages/accountingSellingNew.twig", array());
    }
    
    /**
     * Get a form to edit or validate it if the form is received
     */
    public function sellingAccountEditAction($Id) {
        $account = $this->context->entityManager->getRepository("CommercialSellingAccount")->find($Id);
        if(!$account) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity
            if (!$this->request->getPost("label"))  $this->context->invalid_form(_("The account name is a required field"));            
            if (!$this->request->getPost("accountNumber"))  $this->context->invalid_form(_("The account number is a required field"));

            try {
                // Set the new datas to the account
                $parser = new \IgestisFormParser();
                $account = $parser->FillEntityFromForm($account, $_POST);
                $this->context->entityManager->persist($account);
                $this->context->entityManager->flush();      
            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
                $this->redirect(\ConfigControllers::createUrl("commercial_accounting_index"));
            }

            // Show wizz to confirm the account update
            new \wizz(_("The account data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the account list
            $this->redirect(\ConfigControllers::createUrl("commercial_accounting_index"));
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/accountingSellingEdit.twig", array(
            'form_data' => $account
        ));
    }

}
