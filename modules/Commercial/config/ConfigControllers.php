<?php
// config/ConfigControllers.php

// Le fichier de config se trouve dans le namespace du module
namespace Igestis\Modules\Commercial;

class ConfigControllers extends \IgestisConfigController {
    /**
     * Retourne un tableau (attention à garder la même syntaxe de tableau)
     * contenant la liste des routes du module.
     * @return Array Liste des routes de ce module
     */
    public static function get() {
        return  array(
            
            /***************** Section to manage the accounting ***************************************************************/
            array(
                'id' => 'commercial_accounting_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'accounting_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\AccountingController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            
            array(
                'id' => 'commercial_vat_accounting_update',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'accounting_update'
                ),
                'Controller' => '\Igestis\Modules\Commercial\AccountingController',
                'Action' => 'saveVatAccountingAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            
            array(
                'id' => 'commercial_purchasing_account_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'purchasing_account_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\AccountingController',
                'Action' => 'purchasingAccountNewAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            
            array(
                'id' => 'commercial_selling_account_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_account_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\AccountingController',
                'Action' => 'sellingAccountNewAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            
            array(
                'id' => 'commercial_purchasing_account_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'purchasing_accounting_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\AccountingController',
                'Action' => 'purchasingAccountEditAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            
            array(
                'id' => 'commercial_selling_account_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_accounting_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\AccountingController',
                'Action' => 'sellingAccountEditAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            
            array(
                'id' => 'commercial_purchasing_account_delete',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'purchasing_accounting_delete',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\AccountingController',
                'Action' => 'purchasingAccountDelAction',
                'Access' => array('COMMERCIAL:ADMIN'),
                'CsrfProtection' => true
            ),
            
            array(
                'id' => 'commercial_selling_account_delete',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_accounting_delete',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\AccountingController',
                'Action' => 'sellingAccountDelAction',
                'Access' => array('COMMERCIAL:ADMIN'),
                'CsrfProtection' => true
            ),
            
            /***************** Manage the configurations of the company *****************************************************/
            array(
                'id' => 'commercial_project_parameters_config',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'project_parameters_config'
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectParametersController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            
            /***************** Manage the configurations of the company *****************************************************/
            array(
                'id' => 'commercial_parameters_config',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'parameters_config'
                ),
                'Controller' => '\Igestis\Modules\Commercial\parametersController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            
            /***************** Manage the articles database *****************************************************************/
            array(
                'id' => 'commercial_articles_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'articles_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\ArticlesController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),            
            array(
                'id' => 'commercial_articles_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'articles_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\ArticlesController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_articles_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'articles_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\ArticlesController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_articles_del',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'articles_del',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\ArticlesController',
                'Action' => 'delAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL'),
                'CsrfProtection' => true
            ),
            
            
            /***************** Manage the taxe rates database **************************************************************/
            array(
                'id' => 'commercial_taxe_rates_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'taxe_rates_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\taxeRatesController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),            
            array(
                'id' => 'commercial_taxe_rates_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'taxe_rates_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\taxeRatesController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            array(
                'id' => 'commercial_taxe_rates_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'taxe_rates_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\taxeRatesController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN')
            ),
            array(
                'id' => 'commercial_taxe_rates_del',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'taxe_rates_del',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\taxeRatesController',
                'Action' => 'delAction',
                'Access' => array('COMMERCIAL:ADMIN'),
                'CsrfProtection' => true
            ),
            
            /***************** Manage the commercialprojects *****************************************************************/
            array(
                'id' => 'commercial_project_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'projects_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            array(
                'id' => 'commercial_project_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'project_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            array(
                'id' => 'commercial_project_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'project_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            array(
                'id' => 'commercial_project_update_time_credit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'update_time_credit'
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'editTimeCreditAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            array(
                'id' => 'commercial_project_del',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'project_del',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'delAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL'),
                'CsrfProtection' => true
            ),  
            
            array(
                'id' => 'commercial_project_link_commercial_document',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'link_commercial_document',
                    'projectId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'linkCommercialDocumentAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            
            array(
                'id' => 'commercial_project_unlink_commercial_document',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'unlink_commercial_document',
                    'ProjectId' => '{VAR}[0-9]+',
                    'CommercialDocumentId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'unlinkCommercialDocumentAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            
            array(
                'id' => 'commercial_project_link_intervention',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'link_intervention',
                    'projectId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'linkInterventionAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            
            array(
                'id' => 'commercial_project_unlink_intervention',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'unlink_intervention',
                    'ProjectId' => '{VAR}[0-9]+',
                    'InterventionId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'unlinkInterventionAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            
            array(
                'id' => 'commercial_project_link_buying_invoice',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'link_buying_invoice',
                    'projectId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'linkBuyingInvoiceAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            
            array(
                'id' => 'commercial_project_unlink_buying_invoice',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'unlink_buying_invoice',
                    'ProjectId' => '{VAR}[0-9]+',
                    'BuyingInvoiceId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'unlinkBuyingInvoiceAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            
            array(
                'id' => 'commercial_project_link_free_document',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'link_free_document',
                    'projectId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'linkFreeDocumentAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            array(
                'id' => 'commercial_free_document_download',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'free_document_dl_doc',
                    'Id' => '{VAR}[0-9]+',
                    'ForceDl' => '{VAR}[0-1]{1}',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'downloadAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),             
            array(
                'id' => 'commercial_project_refresh_free_documents',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'project_refresh_free_document',
                    'projectId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'refreshFreeDocumentsAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            
            array(
                'id' => 'commercial_project_unlink_free_document',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'unlink_free_document',
                    'ProjectId' => '{VAR}[0-9]+',
                    'FreeDocumentId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'unlinkFreeDocumentAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            array(
                'id' => 'commercial_project_edit_free_document',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'edit_free_document',
                    'FreeDocumentId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\projectsController',
                'Action' => 'editFreeDocumentAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            
            
            /***************** Manage the selling documents *****************************************************************/
            array(
                'id' => 'commercial_selling_document_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_document_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            array(
                'id' => 'commercial_selling_document_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_document_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            array(
                'id' => 'commercial_selling_document_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_document_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_selling_document_del',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_document_del',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'delAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_selling_save_new_article',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_document_save_new_article',
                    'documentId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'newArticleAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            array(
                'id' => 'commercial_selling_delete_article',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'selling_document_delete_article',
                    'articleId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'deleteArticleAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL'),
            ), 
            
            /**************************************************************/
            array(
                'id' => 'commercial_estimate_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'estimate',
                    'documentId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\estimatesController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            
            array(
                'id' => 'commercial_estimate_mail',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'estimate_mail',
                    'estimateId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\estimatesController',
                'Action' => 'mailAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            
            array(
                'id' => 'commercial_invoice_mail',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'estimate_mail',
                    'invoiceId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\invoicesController',
                'Action' => 'mailAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            
            array(
                'id' => 'commercial_invoice_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'invoice',
                    'documentId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\invoicesController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            
            array(
                'id' => 'commercial_estimate_dl_doc',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'estimate_dl_doc',
                    'Id' => '{VAR}[0-9]+',
                    'ForceDl' => '{VAR}[0-1]{1}',
                ),
                'Controller' => '\Igestis\Modules\Commercial\estimatesController',
                'Action' => 'downloadAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            
            array(
                'id' => 'commercial_invoice_dl_doc',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'invoice_dl_doc',
                    'Id' => '{VAR}[0-9]+',
                    'ForceDl' => '{VAR}[0-1]{1}',
                ),
                'Controller' => '\Igestis\Modules\Commercial\invoicesController',
                'Action' => 'downloadAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL', 'COMMERCIAL:COMP')
            ),  
            
            array(
                'id' => 'commercial_reorder_article',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'reorderArticle',
                    'documentId' => '{VAR}[0-9]+'
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'reorderArticleAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            
            array(
                'id' => 'commercial_document_search_article',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'commercial_document_search_item',
                    'SellingDocumentId' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'searchArticlesAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            
            
            /*************** Manage the interventions ************************************************************************/
            array(
                'id' => 'commercial_interventions_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'interventions_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\interventionsController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_interventions_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'interventions_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\interventionsController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_interventions_duplicate',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'interventions_duplicate'
                ),
                'Controller' => '\Igestis\Modules\Commercial\interventionsController',
                'Action' => 'duplicateAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_interventions_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'interventions_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\interventionsController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_interventions_show',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'interventions_show',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\interventionsController',
                'Action' => 'showAction',
                'Access' => array('AUTHENTICATED')
            ),  
            array(
                'id' => 'commercial_interventions_del',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'interventions_del',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\interventionsController',
                'Action' => 'delAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),              
            array(
                'id' => 'commercial_interventions_dl_doc',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'interventions_dl_doc',
                    'Id' => '{VAR}[0-9]+',
                    'ForceDl' => '{VAR}[0-1]{1}',
                ),
                'Controller' => '\Igestis\Modules\Commercial\interventionsController',
                'Action' => 'downloadAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  
            
            
            /*************** Manage the bank accounts ************************************************************************/
            array(
                'id' => 'commercial_bank_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'bank_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankAccountController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),  

            array(
                'id' => 'commercial_bank_account_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'bank_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankAccountController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            array(
                'id' => 'commercial_bank_account_del',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'bank_account_del',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankAccountController',
                'Action' => 'delAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            array(
                'id' => 'commercial_bank_account_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'bank_account_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankAccountController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),             
                
            /*************** Manage the bank accounts import *****************************************************************/
            array(
                'id' => 'commercial_bank_account_import_result',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'import_result'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankAccountController',
                'Action' => 'importResultAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            array(
                'id' => 'commercial_bank_account_import_result_show',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'import_result_show'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankAccountController',
                'Action' => 'importResultShowAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_bank_account_import_result_validation',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'import_result_validation'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankAccountController',
                'Action' => 'importResultValidationAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
                
            /*************** Manage the bank operations ************************************************************************/
            array(
                'id' => 'commercial_operations_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'bank_operations_index',
                    'AccountId' => '{VAR}[0-9]+'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankOperationController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_bank_operation_del',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'bank_operations_del',
                    'Id' => '{VAR}[0-9]+'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankOperationController',
                'Action' => 'delAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_bank_operation_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'bank_operations_edit',
                    'Id' => '{VAR}[0-9]+'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankOperationController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),            
            array(
                'id' => 'commercial_bank_operation_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'bank_operations_new'
                ),
                'Controller' => '\Igestis\Modules\Commercial\bankOperationController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            /***************** Manage the buying invoices database *****************************************************************/
            array(
                'id' => 'commercial_provider_invoices_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoices_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),            
            array(
                'id' => 'commercial_provider_invoices_edit',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoices_edit',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'editAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_provider_invoices_new',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoices_new',
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'newAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            array(
                'id' => 'commercial_provider_invoices_del',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoices_del',
                    'Id' => '{VAR}[0-9]+',
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'delAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL'),
                'CsrfProtection' => true
            ),
            array(
                'id' => 'commercial_provider_invoices_download',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoice_dl_doc',
                    'Id' => '{VAR}[0-9]+',
                    'ForceDl' => '{VAR}[0-1]{1}',
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'downloadAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL', 'COMMERCIAL:COMP')
            ), 
            array(
                'id' => 'commercial_provider_invoices_add_amount',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoice_add_amount',
                    'ProviderInvoiceId' => '{VAR}[0-9]+'
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'addAmountAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            
            array(
                'id' => 'commercial_provider_invoices_edit_amount',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoice_edit_amount',
                    'Id' => '{VAR}[0-9]+'
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'editAmountAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ),
            
            array(
                'id' => 'commercial_provider_invoices_del_amount',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoice_del_amount',
                    'Id' => '{VAR}[0-9]+'
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'delAmountAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            
            array(
                'id' => 'commercial_provider_invoices_refresh',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'provider_invoice_refresh'
                ),
                'Controller' => '\Igestis\Modules\Commercial\providerInvoicesController',
                'Action' => 'refreshAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
            ), 
            /***************** Manage the balance *****************************************************************/
            array(
                'id' => 'commercial_balance_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'balance_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\balanceController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL', 'COMMERCIAL:COMP')
            ),
            array(
                'id' => 'commercial_balance_details',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'balance_details',
                    'UserId' => '{VAR}[0-9]+'
                ),
                'Controller' => '\Igestis\Modules\Commercial\balanceController',
                'Action' => 'detailsAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL', 'COMMERCIAL:COMP')
            ),
            array(
                'id' => 'commercial_balance_set_document_paid',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'balance_set_document_paid',
                ),
                'Controller' => '\Igestis\Modules\Commercial\balanceController',
                'Action' => 'paidDocumentAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL', 'COMMERCIAL:COMP')
            ),
            
            /***************** Manage the invoices export *******************************************************/
            array(
                'id' => 'commercial_export_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'export_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\exportController',
                'Action' => 'indexAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:COMP')
            ),
            
            array(
                'id' => 'commercial_export_generate',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'export_generate'
                ),
                'Controller' => '\Igestis\Modules\Commercial\exportController',
                'Action' => 'exportGenerateAction',
                'Access' => array('COMMERCIAL:ADMIN', 'COMMERCIAL:COMP')
            ),
            
            
            /**************** Customer account ******************************************************************/
            array(
                'id' => 'commercial_my_account_selling_document_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'my_account_selling_document_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\sellingDocumentsController',
                'Action' => 'myAccountIndexAction',
                'Access' => array('AUTHENTICATED')
            ),  
            
            array(
                'id' => 'commercial_my_account_interventions_index',
                'Parameters' => array(
                    'Module' => 'Commercial',
                    'Action' => 'my_account_interventions_index'
                ),
                'Controller' => '\Igestis\Modules\Commercial\interventionsController',
                'Action' => 'myAccountIndexAction',
                'Access' => array('AUTHENTICATED')
            ),  
            
            
         );
    }
}