<?php

namespace Igestis\Modules\Commercial;
/**
 * Articles management
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class ArticlesController extends \IgestisController {

    /**
     * Show the list of articles
     */
    public function indexAction() {
        $this->context->render("Commercial/pages/articlesList.twig", array(
            'data_table' =>  $this->_em->getRepository("CommercialArticle")->findAll()
        ));
    }
    

    /**
     * Delete the article
     */
    public function delAction($Id) {
        $CommercialArticle = $this->context->entityManager->getRepository("CommercialArticle")->find($Id);
        if(!$CommercialArticle) $this->context->throw404error();
        
        // Delete the purchasing article from the database
        try {
            $this->context->entityManager->remove($CommercialArticle);
            $this->context->entityManager->flush();
        } catch (\Exception $e) {
            // Show wizz to alert user that the purchasing article deletion has not realy been deleted
            \IgestisErrors::createWizz($e);
            $this->redirect(\ConfigControllers::createUrl("commercial_articles_index"));
        }

        // Show wizz to confirm the update
        new \wizz(_("The article has been successfully deleted"), \WIZZ::$WIZZ_SUCCESS);

        // Redirect to the list
        $this->redirect(\ConfigControllers::createUrl("commercial_articles_index"));
    }

    /**
     * Add an article
     */
    public function newAction() {
        $article = new \CommercialArticle;

        if(!$article) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity
            
            $this->context->entityManager->beginTransaction();
            
            // Set the new datas to the article
            $parser = new \IgestisFormParser();
            $article = $parser->FillEntityFromForm($article, $_POST);
            
            $article->ereaseCategories();
            $categories = $this->request->getPost("categoryLabel");
            if(trim($categories) != "") {
                $categoriesArray = explode(",", trim($categories));
                foreach ($categoriesArray as $currentCategory) {
                    $categoryToAdd = $this->context->entityManager->getRepository("CommercialArticleCategory")->find(trim($currentCategory));
                    if($categoryToAdd == null) {
                        $categoryToAdd = new \CommercialArticleCategory(trim($currentCategory));
                    }
                    $article->addCommercialArticleCategory($categoryToAdd);
                }
            }
            $article->setSellingAccount($this->context->entityManager->getRepository("CommercialSellingAccount")->find($this->request->getPost("sellingAccount")));            
            $article->setTaxRate($this->context->entityManager->getRepository("CommercialTaxeRate")->find($this->request->getPost("taxRate")));

            try {                
                $this->context->entityManager->persist($article);
                $this->context->entityManager->flush();  
                $this->context->entityManager->commit();
            } catch (\Exception $e) {
                $this->context->entityManager->rollback();
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
                $this->redirect(\ConfigControllers::createUrl("commercial_articles_index"));
            }

            // Show wizz to article the article update
            new \wizz(_("The article data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the article list
            $this->redirect(\ConfigControllers::createUrl("commercial_articles_index"));
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/ArticlesNew.twig", array(
            'form_data' => $article,
            "taxeRatesList" => $this->_em->getRepository("CommercialTaxeRate")->findAll(),
            "sellingAccountList" => $this->_em->getRepository("CommercialSellingAccount")->findAll(),
            "articleCategoriesList" => $this->_em->getRepository("CommercialArticleCategory")->findAll(),
        ));
    }
    
    /**
     * Get a form to edit or validate it if the form is received
     */
    public function editAction($Id) {        
        $article = $this->context->entityManager->getRepository("CommercialArticle")->find($Id);

        if(!$article) $this->context->throw404error();
        
        // If the form has been received, manage the form...
        if ($this->request->IsPost()) {
            // Check the form validity
            
            $this->context->entityManager->beginTransaction();
            
            // Set the new datas to the article
            $parser = new \IgestisFormParser();
            $article = $parser->FillEntityFromForm($article, $_POST);
            
            $article->ereaseCategories();
            $categories = $this->request->getPost("categoryLabel");
            if(trim($categories) != "") {
                $categoriesArray = explode(",", trim($categories));
                foreach ($categoriesArray as $currentCategory) {
                    $categoryToAdd = $this->context->entityManager->getRepository("CommercialArticleCategory")->find(trim($currentCategory));
                    if($categoryToAdd == null) {
                        $categoryToAdd = new \CommercialArticleCategory(trim($currentCategory));
                    }
                    $article->addCommercialArticleCategory($categoryToAdd);
                }
            }
            $article->setSellingAccount($this->context->entityManager->getRepository("CommercialSellingAccount")->find($this->request->getPost("sellingAccount")));            
            $article->setTaxRate($this->context->entityManager->getRepository("CommercialTaxeRate")->find($this->request->getPost("taxRate")));

            try {                
                $this->context->entityManager->persist($article);
                $this->context->entityManager->flush();  
                $this->context->entityManager->commit();
            } catch (\Exception $e) {
                $this->context->entityManager->rollback();
                \IgestisErrors::createWizz($e, \IgestisErrors::TYPE_ANY);
                $this->redirect(\ConfigControllers::createUrl("commercial_articles_index"));
            }

            // Show wizz to article the article update
            new \wizz(_("The article data has been successfully saved"), \WIZZ::$WIZZ_SUCCESS);

            // Redirect to the article list
            $this->redirect(\ConfigControllers::createUrl("commercial_articles_index"));
        }

        // If no form received, show the form
        $this->context->render("Commercial/pages/ArticlesEdit.twig", array(
            'form_data' => $article,
            "taxeRatesList" => $this->_em->getRepository("CommercialTaxeRate")->findAll(),
            "sellingAccountList" => $this->_em->getRepository("CommercialSellingAccount")->findAll(),
            "articleCategoriesList" => $this->_em->getRepository("CommercialArticleCategory")->findAll(),
        ));
    }    

}
