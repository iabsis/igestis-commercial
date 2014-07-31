<?php

namespace Igestis\Modules\Commercial;
/**
 * Balance controller
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class exportController extends \IgestisController {

    /**
     * Show the list of invoices to export
     */
    public function indexAction() {
        $searchForm = new Forms\exportSearchForm();
        $searchForm->initFromGet();
        
        
        $this->context->render("Commercial/pages/exportList.twig", array(
            'searchForm' =>  $searchForm,
            'invoicesList' => $this->_em->getRepository("CommercialViewCommercialDocs")->findFromSearchForm($searchForm),
            'commercialConfig' => $this->_em->getRepository("CommercialCompanyConfig")->getCompanyConfig()
        ));
    }
    
    public function exportGenerateAction() {
        
        try {
            $this->_em->beginTransaction();
            $invoicesExport = new EntityLogic\invoicesExportLogic($this->context, $this->_em);
            
            $dataToSend = $this->request->getPost('dataToSend');
                    
            if(!$dataToSend) {
                throw new \Exception(\Igestis\I18n\Translate::_("No invoice selected"));
            }
            
            $invoicesList = null;
            // Parse the url formatted posted variables into the $invoicesList variable
            parse_str($dataToSend, $invoicesList);
            
            if(!isset($invoicesList['export']) || !count($invoicesList['export'])) {
                throw new Exception(\Igestis\I18n\Translate::_("Malformed request"));
            }
            
            foreach ($invoicesList['export'] as $currentInvoice) {                
                list($type, $id) = explode("-", $currentInvoice);
                
                switch($type) {
                    case "selling_document" :
                        $document = $this->_em->find("CommercialInvoice", $id);
                        break;
                    case "buying_document" :
                        $document = $this->_em->find("CommercialProviderInvoice", $id);
                        break;
                }
                
                if(!$document) {
                    throw new \Exception(\Igestis\I18n\Translate::_("At least one of the selected invoice has not been found."));
                }     
                
                $invoicesExport->addInvoice($document);                
            }
            
            $fileContent = $invoicesExport->generateExportFile();            
            $this->_em->commit();
            $this->context->rederContent($fileContent, "export.txt");
            
        }
        catch(\Exception $e) {
            $this->_em->rollback();
            \Igestis\Utils\Dump::show($e); exit;
            \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY, sprintf("Error during the file generation : %s", $e->getMessage()));
            $this->redirect(\ConfigControllers::createUrl("commercial_export_index"));
        }

    }
}