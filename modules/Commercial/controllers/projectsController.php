<?php

namespace Igestis\Modules\Commercial;
/**
 * Projects management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class projectsController extends \IgestisController {

    /**
     * Show the list of projects
     */
    public function indexAction() {
        $searchForm = new Forms\projectSearchForm();
        $searchForm->initFromGet();

        $this->context->render("Commercial/pages/projectsList.twig", array(
            "searchForm" => $searchForm,
            'data_table' =>  $this->_em->getRepository("CommercialProject")->findFromSearchForm($searchForm)
        ));
    }

    /**
     * Display project list shared with the client
     */
    public function myAccountIndexAction() {
        $searchForm = new Forms\projectSearchForm();
        $searchForm->initFromGet();

        $this->context->render("Commercial/pages/clientProjectsList.twig", array(
            "searchForm" => $searchForm,
            'data_table' =>  $this->_em->getRepository("CommercialProject")->findFromSearchForm($searchForm)
        ));
    }

    public function showAction($Id)
    {

        $project = $this->_em->find("CommercialProject", $Id);

//        \Igestis\Utils\Dump::show($project);
//        exit();
// TODO Check this function for security
//        if (!$project || $project->getCustomerUser()->getId() != $this->context->security->user->getId()) {
//            $this->context->throw404error();
//        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/clientProjectsView.twig", array(
            'project' => $project
        ));
    }


    /**
     * Delete the project
     */
    public function delAction($Id) {
        $project = $this->context->entityManager->getRepository("CommercialProject")->find($Id);
        if(!$project) $this->context->throw404error();

        // Delete the purchasing article from the database
        try {
            $this->context->entityManager->remove($project);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that the purchasing article deletion has not realy been deleted
            //\IgestisErrors::createWizz($e);
            new \wizz(\Igestis\I18n\Translate::_("Unable to delete this project unless there is linked resources."), \wizz::$WIZZ_ERROR);
            $this->redirect(\ConfigControllers::createUrl("commercial_project_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The project has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_project_index"));
    }

    /**
     * Create a project
     */
    public function newAction() {
        $project = new \CommercialProject;

        if ($this->request->IsPost()) {

            // Create the new commercial document
            $parser = new \IgestisFormParser();
            $project = $parser->FillEntityFromForm($project, $_POST);
            $project->setCustomerUser($this->_em->getRepository("CoreUsers")->find($this->request->getPost("customerUser")));

            try {
                $this->context->entityManager->persist($project);
                $this->context->entityManager->flush();
                // Show wizz to article the article update
                new \wizz(_("The project has been successfully created"), \WIZZ::$WIZZ_SUCCESS);
                // Redirect to the article list
                $this->redirect(\ConfigControllers::createUrl("commercial_project_edit", array("Id" => $project->getId())));

            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY, \Igestis\I18n\Translate::_("An error has occurred during the project creation"));
                $this->redirect(\ConfigControllers::createUrl("commercial_project_index"));
            }
        }
        else {
            new \wizz(\Igestis\I18n\Translate::_("No data has been received for the project creation"), \WIZZ::$WIZZ_ERROR);
            $this->redirect(\ConfigControllers::createUrl("commercial_project_index"));
        }

    }

    /**
     * Open the form to edit the project
     * @param type $Id Id of the project to edit
     */
    public function editAction($Id) {

        $project = $this->_em->find("CommercialProject", $Id);

        if($this->request->IsPost()) {
            // Create the new commercial document
            $parser = new \IgestisFormParser();
            $project = $parser->FillEntityFromForm($project, $_POST);

            try {
                $this->context->entityManager->persist($project);
                $this->context->entityManager->flush();
                // Show wizz to article the project update
                new \wizz(_("The project has been successfully updated"), \WIZZ::$WIZZ_SUCCESS);
                $this->redirect(\ConfigControllers::createUrl("commercial_project_edit", array("Id" => $project->getId())));

            } catch (\Exception $e) {
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY, \Igestis\I18n\Translate::_("An error has occurred during the selling document update"));
                $this->redirect(\ConfigControllers::createUrl("commercial_project_edit", array("Id" => $project->getId())));
            }
        }

        /*$test = $this->_em->getRepository("CommercialCommercialDocument")->findBy(array("project" => $project));
        echo $test[0]->getCreditTime();
        \Igestis\Utils\Dump::show($test);
            exit;*/
        $this->context->render("Commercial/pages/projectsEdit.twig", array(
            "project" => $project,
            "commercialDocuments" => $this->_em->getRepository("CommercialCommercialDocument")->findBy(array("project" => $project)),
            "interventions" => $this->_em->getRepository("CommercialSupportIntervention")->findBy(array("project" => $project), array("date" => "DESC")),
            "freeDocuments" => $this->_em->getRepository("CommercialFreeDocument")->findBy(array("project" => $project)),
            "buyingInvoices" => $this->_em->getRepository("CommercialProviderInvoice")->findBy(array("project" => $project)),
        ));
    }

    public function linkCommercialDocumentAction($projectId) {
        $project = $this->_em->find("CommercialProject", $projectId);


        if($this->request->IsPost()) {

            $ajaxResponse = new \Igestis\Ajax\AjaxResult();
            if(!$project) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Project not found"));

            try {
                $documentIdToAdd = $this->request->getPost("selected");
                if($documentIdToAdd) {
                    foreach ($documentIdToAdd as $documentToAdd) {
                        $document = $this->_em->find("CommercialCommercialDocument", $documentToAdd);
                        $document->setProject($project);
                        $this->_em->persist($document);
                    }
                    $this->_em->flush();
                }
            } catch (\Exception $exc) {
                $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the project association") . $exc->getMessage());
            }

            $htmlContent = $this->context->render("Commercial/ajax/ProjectEditCommercialDocumentTableDiv.twig", array(
                "project" => $project,
                "commercialDocuments" => $this->_em->getRepository("CommercialCommercialDocument")->findBy(array("project" => $project)),
            ), true);

            $totalsContent = $this->context->render("Commercial/ajax/ProjectEditTotals.twig", array(
                "project" => $project,
            ), true);

            $ajaxResponse
                    ->addAssign('CommercialTableDiv', $htmlContent)
                    ->addAssign('totals-section', $totalsContent)
                    ->addScript('igestisCommercial.common.colorize();')
                    ->setSuccessful("ok")->render();

        }
        else {
            if(!$project) $this->context->throw404error ();

            $this->context->render("Commercial/ajax/ModalContentLinkCommercialDocument.twig", array(
                "availableCommercialDocuments" => $this->_em->getRepository("CommercialCommercialDocument")->getUnlinkedToProjectForCustomer($project->getCustomerUser())
            ));
        }
    }

    public function unlinkCommercialDocumentAction($ProjectId, $CommercialDocumentId) {

        $project = $this->_em->find("CommercialProject", $ProjectId);

        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        if(!$project) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Project not found"));

        $commercialDocument = $this->_em->find("CommercialCommercialDocument", $CommercialDocumentId);
        if($commercialDocument->getProject()->getId() != $ProjectId) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Wrong project and intervention association"));

        try {
            $commercialDocument->setProject(null);
            $this->_em->persist($commercialDocument);
            $this->_em->flush();
        } catch (Exception $exc) {
            $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the document unlink") . $exc->getMessage());
        }

        $htmlContent = $this->context->render("Commercial/ajax/ProjectEditCommercialDocumentTableDiv.twig", array(
            "project" => $project,
            "commercialDocuments" => $this->_em->getRepository("CommercialCommercialDocument")->findBy(array("project" => $project)),
        ), true);

        $totalsContent = $this->context->render("Commercial/ajax/ProjectEditTotals.twig", array(
                "project" => $project,
            ), true);

        $ajaxResponse->addAssign('totals-section', $totalsContent)->addAssign('CommercialTableDiv', $htmlContent)->addScript('igestisCommercial.common.colorize();')->setSuccessful("ok")->render();
    }

    public function linkInterventionAction($projectId) {
        $project = $this->_em->find("CommercialProject", $projectId);


        if($this->request->IsPost()) {

            $ajaxResponse = new \Igestis\Ajax\AjaxResult();
            if(!$project) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Project not found"));

            try {
                $interventionIdToAdd = $this->request->getPost("selected");
                if($interventionIdToAdd) {
                    foreach ($interventionIdToAdd as $interventionToAdd) {
                        $intervention = $this->_em->find("CommercialSupportIntervention", $interventionToAdd);
                        $intervention->setProject($project);
                        $this->_em->persist($intervention);
                    }
                    $this->_em->flush();
                }
            } catch (\Exception $exc) {
                $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the project association") . $exc->getMessage());
            }

            $htmlContent = $this->context->render("Commercial/ajax/ProjectEditInterventionTableDiv.twig", array(
                "project" => $project,
                "commercialDocuments" => $this->_em->getRepository("CommercialCommercialDocument")->findBy(array("project" => $project)),
                "interventions" => $this->_em->getRepository("CommercialSupportIntervention")->findBy(array("project" => $project)),
            ), true);

            $totalsContent = $this->context->render("Commercial/ajax/ProjectEditTotals.twig", array(
                "project" => $project,
            ), true);

            $ajaxResponse->addAssign('totals-section', $totalsContent)->addAssign('InterventionTableDiv', $htmlContent)->addScript('igestisCommercial.common.colorize();')->setSuccessful("ok")->render();

        }
        else {
            if(!$project) $this->context->throw404error ();

            $this->context->render("Commercial/ajax/ModalContentLinkIntervention.twig", array(
                "availableInterventions" => $this->_em->getRepository("CommercialSupportIntervention")->getUnlinkedToProjectForCustomer($project->getCustomerUser())
            ));
        }
    }

    public function unlinkInterventionAction($ProjectId, $InterventionId) {

        $project = $this->_em->find("CommercialProject", $ProjectId);

        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        if(!$project) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Project not found"));

        $intervention = $this->_em->find("CommercialSupportIntervention", $InterventionId);
        if($intervention->getProject()->getId() != $ProjectId) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Wrong project and intervention association"));

        try {
            $intervention->setProject(null);
            $this->_em->persist($intervention);
            $this->_em->flush();
        } catch (Exception $exc) {
            $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the intervention unlink") . $exc->getMessage());
        }

        $htmlContent = $this->context->render("Commercial/ajax/ProjectEditInterventionTableDiv.twig", array(
            "project" => $project,
            "commercialDocuments" => $this->_em->getRepository("CommercialSupportIntervention")->findBy(array("project" => $project)),
        ), true);

        $totalsContent = $this->context->render("Commercial/ajax/ProjectEditTotals.twig", array(
                "project" => $project,
            ), true);

        $ajaxResponse->addAssign('totals-section', $totalsContent)->addAssign('InterventionTableDiv', $htmlContent)->addScript('igestisCommercial.common.colorize();')->setSuccessful("ok")->render();
    }

    public function linkBuyingInvoiceAction($projectId) {
        $project = $this->_em->find("CommercialProject", $projectId);


        if($this->request->IsPost()) {

            $ajaxResponse = new \Igestis\Ajax\AjaxResult();
            if(!$project) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Project not found"));

            try {
                $buyingInvoicesIdToAdd = $this->request->getPost("selected");
                if($buyingInvoicesIdToAdd) {
                    foreach ($buyingInvoicesIdToAdd as $buyingInvoiceToAdd) {
                        $buyingInvoice = $this->_em->find("CommercialProviderInvoice", $buyingInvoiceToAdd);
                        $buyingInvoice->setProject($project);
                        $this->_em->persist($buyingInvoice);
                    }
                    $this->_em->flush();
                }
            } catch (\Exception $exc) {
                $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the project association") . $exc->getMessage());
            }

            $htmlContent = $this->context->render("Commercial/ajax/ProjectEditBuyingInvoiceTableDiv.twig", array(
                "project" => $project,
                "buyingInvoices" => $this->_em->getRepository("CommercialProviderInvoice")->findBy(array("project" => $project)),
            ), true);

            $totalsContent = $this->context->render("Commercial/ajax/ProjectEditTotals.twig", array(
                "project" => $project,
            ), true);

            $ajaxResponse->addAssign('totals-section', $totalsContent)->addAssign('BuyingInvoiceTableDiv', $htmlContent)->addScript('igestisCommercial.common.colorize();')->setSuccessful("ok")->render();

        }
        else {
            if(!$project) $this->context->throw404error ();

            $this->context->render("Commercial/ajax/ModalContentLinkBuyingInvoice.twig", array(
                "availableBuyingInvoices" => $this->_em->getRepository("CommercialProviderInvoice")->getUnlinkedToProject()
            ));
        }
    }

    public function unlinkBuyingInvoiceAction($ProjectId, $ProviderInvoiceId) {

        $project = $this->_em->find("CommercialProject", $ProjectId);

        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        if(!$project) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Project not found"));

        $providerInvoice = $this->_em->find("CommercialProviderInvoice", $ProviderInvoiceId);
        if($providerInvoice->getProject()->getId() != $ProjectId) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Wrong project and provider invoice association"));

        try {
            $providerInvoice->setProject(null);
            $this->_em->persist($providerInvoice);
            $this->_em->flush();
        } catch (Exception $exc) {
            $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the provider invoice unlink") . $exc->getMessage());
        }

        $htmlContent = $this->context->render("Commercial/ajax/ProjectEditBuyingInvoiceTableDiv.twig", array(
            "project" => $project,
            "buyingInvoices" => $this->_em->getRepository("CommercialProviderInvoice")->findBy(array("project" => $project)),
        ), true);

        $totalsContent = $this->context->render("Commercial/ajax/ProjectEditTotals.twig", array(
                "project" => $project,
            ), true);

        $ajaxResponse->addAssign('totals-section', $totalsContent)->addAssign('BuyingInvoiceTableDiv', $htmlContent)->addScript('igestisCommercial.common.colorize();')->setSuccessful("ok")->render();
    }

    public function linkFreeDocumentAction($ProjectId) {
        \Igestis\Utils\Debug::FileLogger("Try to upload a document for project id '$ProjectId'");
        $project = $this->_em->find("CommercialProject", $ProjectId);
        if(!$project) exit;

        $uploadHandler = new \Igestis\Utils\UploadHandler(array(
            "upload_dir" => ConfigModuleVars::freeDocumentFolder(). "/"
        ), false);

        $entityManager = $this->_em;

        $uploadHandler->setUploadedCallback(function($filePath = "") use($entityManager, $project) {
            \Igestis\Utils\Debug::FileLogger("new free document $filePath");

            $freeDocument = new \CommercialFreeDocument();
            $freeDocument->setProject($project)->setFilename($filePath);
            $entityManager->persist($freeDocument);
            $entityManager->flush();
        })->post();
    }

    public function unlinkFreeDocumentAction($ProjectId, $FreeDocumentId) {
        $project = $this->_em->find("CommercialProject", $ProjectId);

        $ajaxResponse = new \Igestis\Ajax\AjaxResult();
        if(!$project) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Project not found"));

        $freeDocument = $this->_em->find("CommercialFreeDocument", $FreeDocumentId);
        if($freeDocument->getProject()->getId() != $ProjectId) $ajaxResponse->setError (\Igestis\I18n\Translate::_("Wrong project and free document association"));

        try {
            $file = ConfigModuleVars::freeDocumentFolder() . "/" . $freeDocument->getFilename();
            if(is_file($file)) @unlink($file);
            $this->_em->remove($freeDocument);
            $this->_em->flush();
        } catch (Exception $exc) {
            $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the free document deletion") . $exc->getMessage());
        }

        $htmlContent = $this->context->render("Commercial/ajax/ProjectEditFreeDocumentTableDiv.twig", array(
            "project" => $project,
            "commercialDocuments" => $this->_em->getRepository("CommercialCommercialDocument")->findBy(array("project" => $project)),
            "freeDocuments" => $this->_em->getRepository("CommercialFreeDocument")->findBy(array("project" => $project)),
        ), true);


        $ajaxResponse->addAssign('FreeDocumentTableDiv', $htmlContent)->setSuccessful("ok")->render();
    }

    /**
     * Download the document
     * @param int $Id Id of the document to look for the last estimate
     * @param int $forceDl
     * 0 => Inline loading
     * 1 => Forced download
     */
    public function downloadAction($Id, $forceDl) {
         $document = $this->_em->find("CommercialFreeDocument", $Id);
         if(!$document) $this->context->throw404error();

         $filename = ConfigModuleVars::freeDocumentFolder() . "/" . $document->getFilename();

         if(!is_file($filename) || !is_readable($filename)) $this->context->throw404error ();
         $this->context->renderFile($filename, $forceDl);
    }

    public function refreshFreeDocumentsAction($ProjectId) {
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();

        $project = $this->_em->find("CommercialProject", $ProjectId);
        if(!$project) $ajaxResponse->setError(\Igestis\I18n\Translate::_("Project not found '$ProjectId'"));

        $htmlContent = $this->context->render("Commercial/ajax/ProjectEditFreeDocumentTableDiv.twig", array(
            "project" => $project,
            "commercialDocuments" => $this->_em->getRepository("CommercialCommercialDocument")->findBy(array("project" => $project)),
            "freeDocuments" => $this->_em->getRepository("CommercialFreeDocument")->findBy(array("project" => $project)),
        ), true);

        $ajaxResponse->addAssign('FreeDocumentTableDiv', $htmlContent)->setSuccessful("ok")->render();
    }

    public function editFreeDocumentAction($FreeDocumentId) {
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();

        $freeDocument = $this->_em->find("CommercialFreeDocument", $FreeDocumentId);
        if(!$freeDocument) $ajaxResponse->setError(\Igestis\I18n\Translate::_("Free document not found"));

        if($this->request->IsPost()) {

            try {
                $parser = new \IgestisFormParser();
                $parser->FillEntityFromForm($freeDocument, $_POST);

                $this->_em->persist($freeDocument);
                $this->_em->flush();

                $htmlContent = $this->context->render("Commercial/ajax/ProjectEditFreeDocumentTableDiv.twig", array(
                    "project" => $freeDocument->getProject(),
                    "freeDocuments" => $this->_em->getRepository("CommercialFreeDocument")->findBy(array("project" => $freeDocument->getProject())),
                ), true);

                $ajaxResponse->addScript('$("#project-edit-free-document-modal").modal("hide");')
                             ->addScript('igestisInitTableHover();')
                             ->addAssign("FreeDocumentTableDiv", $htmlContent)
                             ->setSuccessful("ok")->render();

            } catch (Exception $exc) {
                $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the free document edition") . $exc->getMessage());
            }

        }
        else {
            $this->context->render("Commercial/ajax/ModalContentEditFreeDocument.twig", array(
                "freeDocument" => $freeDocument,
            ));
        }


    }

    public function editTimeCreditAction() {
        $ajaxResponse = new \Igestis\Ajax\AjaxResult();

        try {
            $time = $this->request->getPost("timeCredit");
            $id = $this->request->getPost("timeCreditId");
            $projectId = $this->request->getPost("projectId");

            $timeCredit = $this->_em->getRepository("CommercialTimeCredit")->find($id);

            if (!$timeCredit) {
                $timeCredit = new \CommercialTimeCredit($this->_em->getRepository("CommercialCommercialDocument")->find($id));
            }

            $timeCredit->setCreditMinutes($time);
            $time = $timeCredit->getCreditMinutesAsString();

            $this->_em->persist($timeCredit);
            $this->_em->flush();

            $totalsContent = $this->context->render("Commercial/ajax/ProjectEditTotals.twig", array(
                "project" =>  $this->_em->getRepository("CommercialProject")->find($projectId),
            ), true);

            $ajaxResponse->addScript("igestisCommercial.editPopover.popover('hide');")
                         ->addScript("igestisCommercial.projects.updateTimeRow('$id', '$time');")
                         ->addAssign("totals-section", $totalsContent)
                         ->addScript('igestisCommercial.common.colorize();')
                         ->addWizz(\Igestis\I18n\Translate::_("Time saved"), \WIZZ::$WIZZ_SUCCESS, "#commercial-document-wizzs");
            $ajaxResponse->setSuccessful("ok")->render();
        } catch (\Exception $e) {

            $ajaxResponse->setError (\Igestis\I18n\Translate::_("Error during the time credit saving") . " " .  $e->getMessage());
        }
    }
}
