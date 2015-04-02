<?php
// config/ConfigInitModule.php

// Le fichier de config se trouve dans le namespace du module
namespace Igestis\Modules\Commercial;

/* 
 * La classe ConfigInitModule sera lancée par le coeur de l'application à différents moments,
 * afin de  rapatrier la liste des droits ou les entrées du menu.
 * Il est conseillé d'implémenter les interfaces ConfigMenuInterface et
 * ConfigRightsListInterface afin que votre logiciel puisse aisément vous aider à compléter
 * les méthodes abstraites.
 */
class ConfigInitModule implements \Igestis\Interfaces\ConfigMenuInterface, \Igestis\Interfaces\ConfigRightsListInterface, \Igestis\Interfaces\ConfigSidebarInterface {
    /**
     * Renvoie un tableau contenant la liste des droits possibles dans l'application.
     * Il doit absolument respecter le formatage ici montré.
     */
    public static function getRightsList() {
        $module =   array(
            /* MODULE_NAME 
             * contient la référence de l'application que le core a besoin de connaitre */
            "MODULE_NAME" => ConfigModuleVars::moduleName(),
            /* MODULE_FULL_NAME 
             * contient le nom de l'application tel qu'affiché dans la gestion des droits */
            "MODULE_FULL_NAME" => \Igestis\I18n\Translate::_(ConfigModuleVars::moduleShowedName()),
            /* RIGHTS_LIST 
             * contient la liste des droits qu'on va définir plus bas */
            "RIGHTS_LIST" => NULL);
        
        /* On définit maintenant la liste des droits */
        $module['RIGHTS_LIST'] =  array(
            /* Premier droit "None"*/
            array(
                "CODE" => "NONE",
                "NAME" => \Igestis\I18n\Translate::_("None", ConfigModuleVars::textDomain()),
                "DESCRIPTION" => \Igestis\I18n\Translate::_("Doesn't allow the access to sales projects", ConfigModuleVars::textDomain())
            ),
            array(
                "CODE" => "ADMIN",
                "NAME" => \Igestis\I18n\Translate::_("Administrator"),
                "DESCRIPTION" => \Igestis\I18n\Translate::_("Allow to create and delete projects, create assets, invoices and estimations, and export invoices and assets", ConfigModuleVars::textDomain())
            ),
            array(
                "CODE" => "EMPL",
                "NAME" => \Igestis\I18n\Translate::_("Employee", ConfigModuleVars::textDomain()),
                "DESCRIPTION" => \Igestis\I18n\Translate::_("Allow to create and delete projects, create assets, invoices and estimations", ConfigModuleVars::textDomain())
            ),
            array(
                "CODE" => "COMP",
                "NAME" => \Igestis\I18n\Translate::_("Accountant", ConfigModuleVars::textDomain()),
                "DESCRIPTION" => \Igestis\I18n\Translate::_("Allow to export invoices and assets", ConfigModuleVars::textDomain())
            )
        );
        
        return $module;
    }

    /* Ajoute au menu les différentes url, inutile de faire des vérifications des droits, 
     * le core ne les affichera automatiquement que pour les personnes aillant le droit 
     * d'accéder à la page.
     */
    public static function menuSet(\Application $context, \IgestisMenu &$menu) {
        /**
         * $menu->additem prend 3 paramètres
         * - Le nom du menu de la racine dans lequel placer l'entrée (ici dans le menu Modules)
         * - Le nom de l'entrée dans le menu (ici on crée l'entrée tickets dans le menu Modules)
         * - La route à lancer
         */
        $menu->addItem(
                \Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Projects", ConfigModuleVars::textDomain()), 
                "commercial_project_index"
        );
        
        $menu->addItem(
        		\Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
        		\Igestis\I18n\Translate::_("Sales documents", ConfigModuleVars::textDomain()),
        		"commercial_selling_document_index"
        );
        
        $menu->addItem(
        		\Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
        		\Igestis\I18n\Translate::_("Purchases invoices", ConfigModuleVars::textDomain()),
        		"commercial_provider_invoices_index"
        );
        
        $menu->addItem(
        		\Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
        		\Igestis\I18n\Translate::_("Articles database", ConfigModuleVars::textDomain()),
        		"commercial_articles_index"
        );
        
        $menu->addItem(
        		\Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
        		\Igestis\I18n\Translate::_("Interventions", ConfigModuleVars::textDomain()),
        		"commercial_interventions_index"
        );
        
        $menu->addItem(
        		\Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
        		\Igestis\I18n\Translate::_("Users balance", ConfigModuleVars::textDomain()),
        		"commercial_balance_index"
        );
        
        $menu->addItem(
        		\Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
        		\Igestis\I18n\Translate::_("Bank accounts", ConfigModuleVars::textDomain()),
        		"commercial_bank_index"
        );
        
        $menu->addItem(
        		\Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
        		\Igestis\I18n\Translate::_("Export", ConfigModuleVars::textDomain()),
        		"commercial_export_index"
        );

        if (true) { //$context->security->user && $context->security->user->getUserType() == \CoreUsers::USER_TYPE_EMPLOYEE) {
            $menu->addItem(
                \Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
                \Igestis\I18n\Translate::_("My projects", ConfigModuleVars::textDomain()),
                "commercial_my_account_project_index"
            );

            $menu->addItem(
                \Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
                \Igestis\I18n\Translate::_("My interventions", ConfigModuleVars::textDomain()),
                "commercial_my_account_interventions_index"
            );

            $menu->addItem(
                \Igestis\I18n\Translate::_("Commercial", ConfigModuleVars::textDomain()),
                \Igestis\I18n\Translate::_("My commercial documents", ConfigModuleVars::textDomain()),
                "commercial_my_account_selling_document_index"
            );
        }
        
    }

    /**
     * Add the entries to the module sidebar
     * @param \Application $context
     * @param \IgestisSidebar $sidebar
     */
    public static function sidebarSet(\Application $context, \IgestisSidebar &$sidebar) {

        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Quick links", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("New intervention", ConfigModuleVars::textDomain()), 
                "commercial_interventions_new");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Quick links", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("New commercial element", ConfigModuleVars::textDomain()), 
                new \Igestis\Types\SidebarJavascriptOnClick("create_or_duplicate_document(null);", null),
                false,
                array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
                );
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Quick links", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("New project", ConfigModuleVars::textDomain()), 
                new \Igestis\Types\SidebarJavascriptOnClick("create_or_duplicate_project(null);", null),
                false,
                array('COMMERCIAL:ADMIN', 'COMMERCIAL:EMPL')
                );
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Navigation", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Projects", ConfigModuleVars::textDomain()), 
                "commercial_project_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Navigation", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Sales documents", ConfigModuleVars::textDomain()), 
                "commercial_selling_document_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Navigation", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Purchases invoices", ConfigModuleVars::textDomain()), 
                "commercial_provider_invoices_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Navigation", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Articles database", ConfigModuleVars::textDomain()), 
                "commercial_articles_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Navigation", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Interventions", ConfigModuleVars::textDomain()),  
                "commercial_interventions_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Navigation", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Users balance", ConfigModuleVars::textDomain()), 
                "commercial_balance_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Navigation", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Bank accounts", ConfigModuleVars::textDomain()), 
                "commercial_bank_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Navigation", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Export", ConfigModuleVars::textDomain()), 
                "commercial_export_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Administration", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Taxe rates", ConfigModuleVars::textDomain()), 
                "commercial_taxe_rates_index");
        
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Administration", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Accounting", ConfigModuleVars::textDomain()), 
                "commercial_accounting_index");
        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Administration", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Project parameters", ConfigModuleVars::textDomain()), 
                "commercial_project_parameters_config");  

        $sidebar->addItem(
                \Igestis\I18n\Translate::_("Administration", ConfigModuleVars::textDomain()), 
                \Igestis\I18n\Translate::_("Commercial parameters", ConfigModuleVars::textDomain()), 
                "commercial_parameters_config");        

    }
}

