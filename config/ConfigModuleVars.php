<?php
 // config/ConfigModulVars.php

// Le fichier de config se trouve dans le namespace du module
namespace Igestis\Modules\Commercial;

/* On définit les 3 constantes requises pour les modules 
 * (attention à bien préfixer vos variables avec le nom du module)
 */
define("COMMERCIAL_VERSION", "0.1-1");
define("COMMERCIAL_MODULE_NAME", "Commercial");
define("COMMERCIAL_TEXTDOMAIN", COMMERCIAL_MODULE_NAME .  COMMERCIAL_VERSION);

/**
 * Configuration of the module
 *
 * @author Gilles Hemmerlé
 */
class ConfigModuleVars {

    /**
     * @var String Numéro de version du module (obligatoire)
     */
    const version = COMMERCIAL_VERSION;
    /**
     *
     * @var String Nom du module (référence utilisé par le core pour la gestion des droits)
     */
    const moduleName = COMMERCIAL_MODULE_NAME;
    
    /**
     *
     * @var String Nom tel qu'affiché dans la gestion des droits
     */
    const moduleShowedName = "Commercial projects";
    
    /**
     *
     * @var String Le textdomain utilisé pour la gestion des traduction en fichiers .mo
     */
    const textDomain = COMMERCIAL_TEXTDOMAIN;
    
    /**
     *
     * @var String Folder where the pdf files will be moved.
     */
    const invoicesFolder = "/usr/share/igestis/documents/Commercial/invoices";
    
    /**
     *
     * @var String Folder where the pdf files will be moved.
     */
    const providersInvoicesFolder = "/usr/share/igestis/documents/Commercial/providersInvoices";
    
    /**
     *
     * @var String Folder where the pdf files will be moved.
     */
    const quotationsFolder = "/usr/share/igestis/documents/Commercial/quotations";
    
    /**
     *
     * @var String Folder where the pdf files will be moved.
     */
    const freeDocumentFolder = "/usr/share/igestis/documents/Commercial/freeDocuments";
    
    /**
     *
     * @var String Folder where the pdf files will be moved.
     */
    const interventionsDocumentFolder = "/usr/share/igestis/documents/Commercial/interventions";
    
    
}
