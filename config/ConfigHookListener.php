<?php

/**
 * Hook listener for the roundcube module
 *
 * @author Gilles Hemmerlé
 */
namespace Igestis\Modules\Commercial;

class ConfigHookListener implements \Igestis\Interfaces\HookListenerInterface  {
    public static function listen($HookName, \Igestis\Types\HookParameters $params = null) {
        switch ($HookName) {           
            case "finalRendering" :
                $replacements = $params->get("replacements");
                if(!isset($replacements['customersList'])) {
                    $replacements['customersList'] = \Application::getInstance()->entityManager->getRepository("CoreUsers")->findAll(false);
                }
                $params->set("replacements", $replacements);
                break;
            
            default:
                break;
        }
        
        return false;
    }
}