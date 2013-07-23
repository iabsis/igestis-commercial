<?php

namespace Igestis\Modules\Commercial\EntityLogic;

/**
 * Description of CommercialArticle
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class CommercialArticleLogic {
    /**
     * Remove one or more entity depending to the $entity type
     * @param \Doctrine\ORM\EntityManager $em entity manager
     * @param \CommercialArticleCategory|array $entity
     */
    public static function remove($em, $entity) {
        if($entity instanceof \CommercialArticleCategory) {
            $em->remove($entity);
            return;
        }
        if(is_array($entity)) {
            foreach ($entity as $currentEntity) {
                if(!($currentEntity instanceof \CommercialArticleCategory)) continue;
                if($currentEntity->getArticle() == null || count($currentEntity->getArticle()) == 0) {
                    $em->remove($currentEntity);
                }
            }
            return;
        }
    }
}

?>
