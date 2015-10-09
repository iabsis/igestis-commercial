<?php

namespace Igestis\Modules\Commercial\EntityLogic;

/**
 * This class generate the export file for the passed invoices
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class DbalArticleCreation {
    public static function createArticle($dbalConnexion, $arrayRepresentation)
    {
        $categories = array();
        if (!empty($arrayRepresentation['categories'])) {
            $categories = array_map('trim', explode(',', $arrayRepresentation['categories']));
            unset($arrayRepresentation['categories']);
        }
        
        $dbalConnexion->insert("COMMERCIAL_ARTICLE", $arrayRepresentation);
    }
}
