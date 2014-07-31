<?php

namespace Igestis\Modules\Commercial;
/**
 * Interventions management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class interventionsController extends \IgestisController {

    /**
     * Show the list of projects
     */
    public function indexAction() {
        $searchForm = new Forms\InterventionsSearchForm();
        $searchForm->initFromGet();
         
        $this->context->render("Commercial/pages/interventionsList.twig", array(
            'customersList' => $this->_em->getRepository("CoreUsers")->findAll(),
            'data_table' =>  $this->_em->getRepository("CommercialSupportIntervention")->findAllBySearchForm($searchForm),
            'totalTime' =>   $this->_em->getRepository("CommercialSupportIntervention")->findAllBySearchForm($searchForm, true),
            'employeesList' => $this->_em->getRepository("CoreContacts")->getEmployeesList(false, true),
            'searchForm' => $searchForm,
            'typesList' => $this->_em->getRepository("CommercialSupportIntervention")->findAllTypes()
        ));
    }
    
    public function myAccountIndexAction() {
        $searchForm = new Forms\InterventionsSearchForm($this->_em);
        $searchForm->initFromGet();
        $searchForm->setCustomerUser($this->context->security->user);
         
        $this->context->render("Commercial/pages/myAccountInterventionsList.twig", array(
            'data_table' =>  $this->_em->getRepository("CommercialSupportIntervention")->findAllBySearchForm($searchForm),
            'totalTime' =>   $this->_em->getRepository("CommercialSupportIntervention")->findAllBySearchForm($searchForm, true),
            'searchForm' => $searchForm,
            'typesList' => $this->_em->getRepository("CommercialSupportIntervention")->findAllTypes()
        ));
    }

    

    /**
     * Delete the intervention
     * @param int $Id Id of the intervention to delete
     */
    public function delAction($Id) {
        $intervention = $this->context->entityManager->getRepository("CommercialSupportIntervention")->find($Id);
        if(!$intervention) $this->context->throw404error();
        
        // Delete the purchasing article from the database
        try {
            if (is_file(ConfigModuleVars::interventionsDocumentFolder . "/" . $intervention->getFileName())) {
                if (!@unlink(ConfigModuleVars::interventionsDocumentFolder . "/" . $intervention->getFileName())) {
                    throw new \Exception(\Igestis\I18n\Translate::_("Failed to delete the previous file."));
                }
            }
            $this->context->entityManager->remove($intervention);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that the intervention deletion has not realy been deleted
            \IgestisErrors::createWizz($e);
            $this->redirect(\ConfigControllers::createUrl("commercial_interventions_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The intervention has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_interventions_index"));
    }

    /**
     * Create a new intervention
     */
    public function newAction() {
        $intervention = new \CommercialSupportIntervention;

        $ajaxResponse = new \Igestis\Ajax\AjaxResult();        

        if(!$intervention) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity
            
            if(!preg_match("#^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$#", $this->request->getPost("date"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The date format is invalid" ));
            if(!preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $this->request->getPost("startTime"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The start time format is invalid" ));
            if(!preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $this->request->getPost("endTime"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The end time format is invalid" ));
            if(!preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $this->request->getPost("pause"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The pause time format is invalid" ));
            if(!preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $this->request->getPost("period"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The elapsed time format is invalid" ));
            
            $this->context->entityManager->beginTransaction();            

            
            try {
                
                if(!is_dir(ConfigModuleVars::interventionsDocumentFolder)) {
                    if(!@mkdir(ConfigModuleVars::interventionsDocumentFolder)) {
                        throw new \Exception(sprintf(\Igestis\I18n\Translate::_("Unable to create the folder : '%s'"), ConfigModuleVars::interventionsDocumentFolder));
                    }
                }
                switch ($this->request->getPost("getFile")) {
                    case "scanner" :     
                        $filename = uniqid("", true) . ".jpg";                         
                        \Igestis\Utils\Scanner::launchScanner($this->request->getPost("selectedScanner"), ConfigModuleVars::interventionsDocumentFolder, $filename);
                        $intervention->setFileName($filename);
                        break;
                    case "file" :
                        if (!is_file($_FILES['file']['tmp_name']))
                            throw new \Exception(sprintf(\Igestis\I18n\Translate::_("Unable to find the file '%s'."), $_FILES['file']['tmp_name']));
                        
                        $pathinfo = pathinfo($_FILES['file']['name']);                        
                        $filename = uniqid("", true) . "." . $pathinfo['extension'];                        
                        move_uploaded_file($_FILES['file']['tmp_name'], ConfigModuleVars::interventionsDocumentFolder . "/" . $filename);
                        
                        $intervention->setFileName($filename);
                        break;
                    case "delete" :
                        if (is_file(ConfigModuleVars::interventionsDocumentFolder . "/" . $intervention->getFileName())) {
                            if (!@unlink(ConfigModuleVars::interventionsDocumentFolder . "/" . $intervention->getFileName())) {
                                throw new \Exception(\Igestis\I18n\Translate::_("Failed to delete the previous file."));
                            }
                        }
                        $intervention->setFileName(null);
                        break;
                }
            } catch (\Exception $exc) {
                $ajaxResponse->setError($exc->getMessage());
            }
            
            if($this->request->getGet("projectId")) {
                $project = $this->_em->find("CommercialProject", $this->request->getGet("projectId"));
                if(!$project) throw new \Exception(\Igestis\I18n\Translate::_("Unknown project"));
                $intervention->setProject($project);
            }

            
            // Set the new datas to the article
            //$parser = new \IgestisFormParser();
            //$intervention = $parser->FillEntityFromForm($intervention, $_POST);
            $startTime = \DateTime::createFromFormat("d/m/Y H:i", $this->request->getPost("date") . " " . ($this->request->getPost("startTime") ? $this->request->getPost("startTime") : "00:00"));
            $endTime = clone $startTime;
            
            $intervention->setPeriod($this->request->getPost("period"))
                         ->setPause($this->request->getPost("pause"));
            
            $nbMinutes = $intervention->getPeriod() + $intervention->getPause();
            $endTime->add(new \DateInterval("PT" . $nbMinutes . "M" ));

            if($this->request->getGet("projectId")) {
                $intervention->setCustomerUser($project->getCustomerUser()); 
            }
            else {
                $intervention->setCustomerUser($this->context->entityManager->getRepository("CoreUsers")->find($this->request->getPost("customerUser"))); 
            }
             
            
            if($this->context->security->module_access("COMMERCIAL") == "ADMIN") {
                $intervention->setWorkerContact($this->context->entityManager->getRepository("CoreContacts")->find($this->request->getPost("workerContact")));  
            }
            
            $intervention
                ->setTitle($this->request->getPost("title"))
                ->setDescription($this->request->getPost("description"))
                ->setType($this->request->getPost("type"))
                ->setDate($startTime)
                ->setEnd($endTime)
                ->setPause($this->request->getPost("pause"))
                ->setPeriod($this->request->getPost("period"));
            

            try {                
                $this->context->entityManager->persist($intervention);
                $this->context->entityManager->flush();  
                $this->context->entityManager->commit();
            } catch (\Exception $e) {
                $this->context->entityManager->rollback();
                $ajaxResponse->setError(\Igestis\I18n\Translate::_("An error occured during the intervention save."));
            }
            
            // Show wizz to article the article update
            new \wizz(_("The intervention data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);
            
            if($this->request->getPost('saveAndNew') == 1) {
                $redirection  = \ConfigControllers::createUrl("commercial_interventions_new") . "&projectId=" . $this->request->getGet("projectId");
            }
            else {
                if($this->request->getGet("projectId")) {
                    $redirection = \ConfigControllers::createUrl("commercial_project_edit", array("Id" => $this->request->getGet("projectId")));
                }
                else {
                    $redirection = \ConfigControllers::createUrl("commercial_interventions_index");
                }
            }
            
            $ajaxResponse->setRedirection($redirection)
                         ->setSuccessful(true)
                         ->render();
            
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/interventionsNew.twig", array(
            'form_data' => $intervention,
            'project' => $this->_em->find("CommercialProject", $this->request->getGet("projectId")),
            'customersList' => $this->_em->getRepository("CoreUsers")->findAll(),
            'employeesList' => $this->_em->getRepository("CoreContacts")->getEmployeesList(false, true),
            'interventionsTypeList' => $this->_em->getRepository("CommercialSupportIntervention")->findAllTypes(true),
        ));
    }
    
    public function showAction($Id) {
        $intervention = $this->_em->find("CommercialSupportIntervention", $Id);
        if(!$intervention || $intervention->getCustomerUser()->getId() != $this->context->security->user->getId()) {
            $this->context->throw404error();
        }
        
        // If no form received, show the form
        $this->context->render("Commercial/pages/myAccountInterventionsShow.twig", array(
            'form_data' => $intervention
        ));
    }
    
    /**
     * Edit an intervention
     * @param int $Id Id of the intervention to edit
     */
    public function editAction($Id) {
        $intervention = $this->_em->find("CommercialSupportIntervention", $Id);
        
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        

        if(!$intervention) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity
            
            if(!preg_match("#^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$#", $this->request->getPost("date"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The date format is invalid" ));
            if(!preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $this->request->getPost("startTime"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The start time format is invalid" ));
            if(!preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $this->request->getPost("endTime"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The end time format is invalid" ));
            if(!preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $this->request->getPost("pause"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The pause time format is invalid" ));
            if(!preg_match("#^(|[0-9]{1,3}\:[0-9]{1,2})$#", $this->request->getPost("period"))) $ajaxResponse->setError(\Igestis\I18n\Translate::_("The elapsed time format is invalid" ));
            
            $this->context->entityManager->beginTransaction();            

            
            try {
                switch ($this->request->getPost("getFile")) {
                    case "scanner" :     
                        $filename = uniqid("", true) . ".jpg"; 
                        \Igestis\Utils\Scanner::launchScanner($this->request->getPost("selectedScanner"), ConfigModuleVars::interventionsDocumentFolder, $filename);
                        $intervention->setFileName($filename);
                        break;
                    case "file" :
                        if (!is_file($_FILES['file']['tmp_name']))
                            throw new \Exception(sprintf(\Igestis\I18n\Translate::_("Unable to find the file '%s'."), $_FILES['file']['tmp_name']));
                        
                        $pathinfo = pathinfo($_FILES['file']['name']);                        
                        $filename = uniqid("", true) . "." . $pathinfo['extension'];                        
                        move_uploaded_file($_FILES['file']['tmp_name'], ConfigModuleVars::interventionsDocumentFolder . "/" . $filename);
                        
                        $intervention->setFileName($filename);
                        break;
                    case "delete" :
                        if (is_file(ConfigModuleVars::interventionsDocumentFolder . "/" . $intervention->getFileName())) {
                            if (!@unlink(ConfigModuleVars::interventionsDocumentFolder . "/" . $intervention->getFileName())) {
                                throw new \Exception(\Igestis\I18n\Translate::_("Failed to delete the previous file."));
                            }
                        }
                        $intervention->setFileName(null);
                        break;
                }
            } catch (\Exception $exc) {
                $ajaxResponse->setError($exc->getMessage());
            }

            
            // Set the new datas to the article
            //$parser = new \IgestisFormParser();
            //$intervention = $parser->FillEntityFromForm($intervention, $_POST);
            $startTime = \DateTime::createFromFormat("d/m/Y H:i", $this->request->getPost("date") . " " . ($this->request->getPost("startTime") ? $this->request->getPost("startTime") : "00:00"));
            $endTime = clone $startTime;
            
            $intervention->setPeriod($this->request->getPost("period"))
                         ->setPause($this->request->getPost("pause"));
            
            $nbMinutes = $intervention->getPeriod() + $intervention->getPause();
            $endTime->add(new \DateInterval("PT" . $nbMinutes . "M" ));

            $intervention->setCustomerUser($this->context->entityManager->getRepository("CoreUsers")->find($this->request->getPost("customerUser")));  
            $intervention->setWorkerContact($this->context->entityManager->getRepository("CoreContacts")->find($this->request->getPost("workerContact")));  
            $intervention
                ->setTitle($this->request->getPost("title"))
                ->setDescription($this->request->getPost("description"))
                ->setType($this->request->getPost("type"))
                ->setDate($startTime)
                ->setEnd($endTime)
                ->setPause($this->request->getPost("pause"))
                ->setPeriod($this->request->getPost("period"));
            

            try {                
                $this->context->entityManager->persist($intervention);
                $this->context->entityManager->flush();  
                $this->context->entityManager->commit();
            } catch (\Exception $e) {
                $this->context->entityManager->rollback();
                $ajaxResponse->setError(\Igestis\I18n\Translate::_("An error occured during the intervention save." ));
            }
            
            // Show wizz to article the article update
            new \wizz(_("The intervention data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);
            
            $ajaxResponse->setRedirection(\ConfigControllers::createUrl("commercial_interventions_index"))
                         ->setSuccessful(true)
                         ->render();
            
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/interventionsEdit.twig", array(
            'form_data' => $intervention,
            'project' => $intervention->getProject() ? $this->_em->find("CommercialProject", $intervention->getProject()) : null,
            'customersList' => $this->_em->getRepository("CoreUsers")->findAll(),
            'employeesList' => $this->_em->getRepository("CoreContacts")->getEmployeesList(false, true),
            'interventionsTypeList' => $this->_em->getRepository("CommercialSupportIntervention")->findAllTypes(true),
        ));
    }
    
    /**
     * Download the document
     * @param int $Id Id of the intervention to check the attached document
     * @param int $forceDl
     * 0 => Inline loading
     * 1 => Forced download
     */
    public function downloadAction($Id, $forceDl) {
         $intervention = $this->_em->find("CommercialSupportIntervention", $Id);
         if(!$intervention) $this->context->throw404error();
         $filename = ConfigModuleVars::interventionsDocumentFolder . "/" . $intervention->getFilename();
         
         if(!is_file($filename) || !is_readable($filename)) $this->context->throw404error ();
         $this->context->renderFile($filename, $forceDl);
    }

}
