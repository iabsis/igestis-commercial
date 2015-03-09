<?php

/**
 * CommercialArticleCategory
 *
 * @Table(name="COMMERCIAL_ARTICLE_CATEGORY")
 * @Entity(repositoryClass="CommercialArticleCategoryRepository")
 */
class CommercialArticleCategory
{
    /**
     * @var string $label
     *
     * @Column(name="label", type="string")
     * @Id
     * @GeneratedValue(strategy="NONE")
     */
    private $label;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToMany(targetEntity="CommercialArticle", mappedBy="categoryLabel", cascade={"persist"})
     */
    private $article;

    public function __construct($label=null)
    {
        $this->article = new \Doctrine\Common\Collections\ArrayCollection();
        if($label) $this->label = $label;
    }
    
    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add article
     *
     * @param CommercialArticle $article
     * @return CommercialArticleCategory
     */
    public function addCommercialArticle(\CommercialArticle $article)
    {
        if($this->article->contains($article)) return $this;
        $this->article[] = $article;
        $article->addCommercialArticleCategory($this);
        return $this;
    }

    /**
     * Get article
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getArticle()
    {
        return $this->article;
    }
    
    public function __toString() {
        return $this->label;
    }
}

// ----------------------------------------------------------------

class CommercialArticleCategoryRepository extends \Doctrine\ORM\EntityRepository {

    /**
     * Return unused categories
     * @param \Doctrine\ORM\EntityManager $em
     * @return array|\CommercialArticleCategory
     * @throws Exception
     */
    public function getUnused(\Doctrine\ORM\EntityManager $em) {
        try {
            
            $qb = $em->createQueryBuilder();
            $qb->select("c")
               ->from("CommercialArticleCategory", "c")
               ->leftJoin("c.article", "a")
               ->having("count(a) = 0")
               ->groupBy("c");
            return $qb->getQuery()->getResult();
        }
        catch (\Exception $e) {
            throw $e;
        }
        
        return $qb->getQuery()->getResult();        
    }

}