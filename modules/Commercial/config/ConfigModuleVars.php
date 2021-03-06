<?php

namespace Igestis\Modules\Commercial;

/**
 * Configuration of the module
 *
 * @author Gilles Hemmerlé <gilles.h@iabsis.com>
 */
class ConfigModuleVars
{
    private static $version = null;
    private static $params;

    public static function initConfigVars()
    {
        if (empty(static::$params)) {
            self::initFromIniFile();
        }
    }

    public static function configFileFound()
    {
        return is_file(__DIR__ . "/config.ini") && is_readable(__DIR__ . "/config.ini");
    }

    public static function initFromIniFile()
    {
        
        self::$params =  parse_ini_file(__DIR__ . "/default-config.ini");
        if (self::configFileFound()) {
            self::$params = array_merge(
                self::$params,
                parse_ini_file(__DIR__ . "/config.ini")
            );

            if (!parse_ini_file(__DIR__ . "/config.ini")) {
                throw new \Igestis\Exceptions\ConfigException(\Igestis\I18n\Translate::_("The commercial config.ini file contains errors"));
            }
        }
    }

    /**
     * Return current module version
     * @return string Current module version
     */
    public static function version()
    {
        self::initConfigVars();
        if (self::$version === null) {
            self::$version = file_get_contents(__DIR__ . "/../version");
        }
        return self::$version;
        return empty(self::$params['DEBUG_MODE']) ? false : (bool)self::$params['DEBUG_MODE'];
    }

    /**
     * Return the module internal name
     * @return string module internal name
     */
    public static function moduleName()
    {
        return "Commercial";
    }

    /**
     * Return the module displayed name
     * @return string module displayed name
     */
    public static function moduleShowedName()
    {
        return "Commercial projects";
    }

    /**
     * Return the text domain for the module
     * @return string Text domain
     */
    public static function textDomain()
    {
        return self::moduleName() . self::version();
    }

    /**
     * Generate folder depending of the / in prefix or not
     * @param  string $folder A folder name
     * @return string         The full folder name from the root of the server
     */
    private function dataFolder($folder)
    {
        self::initConfigVars();
        if (!preg_match("#^/#", $folder)) {
            $folder = \ConfigIgestisGlobalVars::dataFolder() . "/" . $folder;
        }
        return $folder;
    }

    /**
     * Return the invoices pdf folder
     * @return string Invoices pdf folder
     */
    public static function invoicesFolder()
    {
        return self::dataFolder(self::$params['INVOICES_FOLDER']);
    }

    /**
     * Return the delivery form pdf folder
     * @return string delivery form pdf folder
     */
    public static function deliveryFormFolder()
    {
        return self::dataFolder(self::$params['DELIVERY_FORM_FOLDER']);
    }

    /**
     * Return the purchase orders pdf folder
     * @return string purchase orders pdf folder
     */
    public static function purchaseOrderFolder()
    {
        return self::dataFolder(self::$params['PURCHASE_ORDER_FOLDER']);
    }

    /**
     * Return the providers invoices pdf folder
     * @return string Providers invoices pdf folder
     */
    public static function providersInvoicesFolder()
    {
        return self::dataFolder(self::$params['PROVIDERS_INVOICES_FOLDER']);
    }

    /**
     * Return the quotations pdf folder
     * @return string Quotations pdf folder
     */
    public static function quotationsFolder()
    {
        return self::dataFolder(self::$params['QUOTATION_FOLDER']);
    }

    /**
     * Return the free documents folder
     * @return string Free documents folder
     */
    public static function freeDocumentFolder()
    {
        return self::dataFolder(self::$params['FREE_DOCUMENT_FOLDER']);
    }

    /**
     * Return the interventions attachments folder
     * @return string Interventions attachments folder
     */
    public static function interventionsDocumentFolder()
    {
        return self::dataFolder(self::$params['INTERVENTIONS_DOCUMENT_FOLDER']);
    }

    public function showMenuProjects() {
        return self::$params['MENU_PROJECTS'];
    }

    public function showMenuSalesDocuments() {
        return self::$params['MENU_SALES_DOCUMENTS'];
    }

    public function showMenuPurchasesInvoices() {
        return self::$params['MENU_PURCHASES_INVOICES'];
    }

    public function showMenuArticlesDb() {
        return self::$params['MENU_ARTICLES_DB'];
    }

    public function showMenuInterventions() {
        return self::$params['MENU_INTERVENTIONS'];
    }

    public function showMenuUsersBalance() {
        return self::$params['MENU_USERS_BALANCE'];
    }

    public function showMenuBankAccounts() {
        return self::$params['MENU_BANK_ACCOUNTS'];
    }

    public function showMenuExport() {
        return self::$params['MENU_EXPORT'];
    }

    public function showSubmenuNewIntervention() {
        return self::$params['SUBMENU_NEW_INTERVENTION'];
    }

    public function showSubmenuNewCommercialElement() {
        return self::$params['SUBMENU_NEW_COMMERCIAL_ELEMENT'];
    }

    public function showSubmenuNewProject() {
        return self::$params['SUBMENU_NEW_PROJECT'];
    }

    public function showSubmenuProjects() {
        return self::$params['SUBMENU_PROJECTS'];
    }

    public function showSubmenuSalesDocuments() {
        return self::$params['SUBMENU_SALES_DOCUMENTS'];
    }

    public function showSubmenuPurchasesInvoices() {
        return self::$params['SUBMENU_PURCHASES_INVOICES'];
    }

    public function showSubmenuArticlesDatabase() {
        return self::$params['SUBMENU_ARTICLES_DATABASE'];
    }

    public function showSubmenuInterventions() {
        return self::$params['SUBMENU_INTERVENTIONS'];
    }

    public function showSubmenuUsersBalance() {
        return self::$params['SUBMENU_USERS_BALANCE'];
    }

    public function showSubmenuBankAccounts() {
        return self::$params['SUBMENU_BANK_ACCOUNTS'];
    }

    public function showSubmenuExport() {
        return self::$params['SUBMENU_EXPORT'];
    }

    public function showSubmenuTaxeRates() {
        return self::$params['SUBMENU_TAXE_RATES'];
    }

    public function showSubmenuAccounting() {
        return self::$params['SUBMENU_ACCOUNTING'];
    }

    public function showSubmenuProjectParameters() {
        return self::$params['SUBMENU_PROJECT_PARAMETERS'];
    }

    public function showSubmenuCommercialParameters() {
        return self::$params['SUBMENU_COMMERCIAL_PARAMETERS'];
    }

}
