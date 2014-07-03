<?php

namespace Igestis\Modules\Commercial\Pdfs;

/**
 * This class allow to generate 
 *
 * @author Gilles Hemmerlé
 */
abstract class GenerateCommercialDocument {
   /**
     *
     * @var string 
     */
    protected $src = null;
    /**
     *
     * @var \Igestis\Modules\Commercial\Pdfs\TcpdfWithHeaderAndFooter
     */
    protected $tcpdfObject = null;
    
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    /**
     *
     * @var boolean Save on the database or not 
     */
    protected $saveMode = false;
    
    /**
     *
     * @var array occurences to replace on the html renderer 
     */
    protected $replacements = array();
    
    /**
     *
     * @var \Igestis\Modules\Commercial\Interfaces\HtmlRendererInterface
     */
    protected $htmlRenderer = null;
    
    /**
     *
     * @var string Html renderer template file target
     */
    protected $htmlRendererFile = null;
    
    /**
     *
     * @var \DateTime
     */    
    protected $documentDate = null;
    
    /**
     *
     * @var string $filename Name of the file to generate
     */
    protected $filename = null;
    
    
    protected $documentEntity = null;
    

    
    /**
     * 
     * @param \EntityManager $entityManager
     * @param type $src
     * @param HtmlRendererInterface $htmlRender Renderer for the html
     */
    public function __construct(\EntityManager $entityManager, $src = null, $htmlRenderer=null, $htmlRendererFile=null) {
        ini_set ('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $this->entityManager = $entityManager;
        $this->htmlRenderer = $htmlRenderer;
        $this->htmlRendererFile = $htmlRendererFile;
        
        if($src !== null) $this->setSource($src);
    }

    
    public function setHtmlRender($htmlRenderer, $templateFile) {
        $this->htmlRenderer = $htmlRenderer;
        $this->htmlRendererFile = $templateFile;
    }
    
    /**
     * Set the form src
     * @param int $src
     * @throws \Exception
     */
    public function setSource($src) {
        $this->src = $src;
    }
    
    /**
     * Force an invoice date
     * @param \DateTime $date
     * @return \Igestis\Modules\Commercial\GenerateInvoice
     */
    public function setDocumentDate(\DateTime $date) {
        $this->documentDate = $date;
        return $this;
    }
    
    /**
     * Set the save mode to true or false, return the current saveMode
     * @param bool $saveMode
     * @return self
     */
    public function saveMode($saveMode = null) {
        if($saveMode === null) return $saveMode;
        else {
            $this->saveMode = (bool) $saveMode;
            return $this;
        }
        
    }


    /**
     * Generate the pdf from the selected value on the form
     * @return \Igestis\Modules\Commercial\GenerateInvoice
     */
    public abstract function generate();
    
    
    protected function addReplacements(array $replacements) {
        $this->replacements = array_merge($this->replacements, $replacements);
    }
    
    protected function generateHeader() {
    	$template = $this->htmlRenderer->loadTemplate($this->htmlRendererFile);
    	return $template->renderBlock('header', $this->replacements);
    }
    
    protected function generateFooter() {
    	$template = $this->htmlRenderer->loadTemplate($this->htmlRendererFile);
    	return $template->renderBlock('footer', $this->replacements);
    }

    protected function generateHtml() {
        if(!count($this->replacements)) return null;
        if(!$this->htmlRenderer instanceof \Rssify\Interfaces\HtmlRenderer && !$this->htmlRenderer instanceof \Twig_Environment) {
            $message = \Igestis\I18n\Translate::_("The html render must implement the interface of the same name or to be type of 'Twig_Environment', now '%s'");
            throw new \Exception(sprintf($message, get_class($this->htmlRenderer)));
        }

        $template = $this->htmlRenderer->loadTemplate($this->htmlRendererFile);
        return $template->renderBlock('content', $this->replacements);
    }
    
    protected function generateTerms() {
        if(!count($this->replacements)) return null;
        if(!$this->htmlRenderer instanceof \Rssify\Interfaces\HtmlRenderer && !$this->htmlRenderer instanceof \Twig_Environment) {
            $message = \Igestis\I18n\Translate::_("The html render must implement the interface of the same name or to be type of 'Twig_Environment', now '%s'");
            throw new \Exception(sprintf($message, get_class($this->htmlRenderer)));
        }

        $template = $this->htmlRenderer->loadTemplate($this->htmlRendererFile);
        return trim($template->renderBlock('terms', $this->replacements));
    }
    
    /**
     * Show the generated file
     * @param string $filaneme
     * @param string $mode
     * @return \TpGroupedInvoices Group of invoice generated
     */
    public function show($filename = 'fichier.pdf', $mode = 'D') {
        // for testing, without saving into the db : $this->tcpdfObject->Output("bla.pdf", "D"); exit;
        if($this->saveMode) {
            $fileInfos = pathinfo($filename);  
            $this->filename = $fileInfos['basename'];
        }
        

        $this->tcpdfObject->Output($filename, $mode);
    }
}