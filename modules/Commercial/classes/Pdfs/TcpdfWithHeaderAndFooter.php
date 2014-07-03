<?php
namespace Igestis\Modules\Commercial\Pdfs;
/**
 * Description of TcpdfWithHeaderAndFooter
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class TcpdfWithHeaderAndFooter extends \Igestis\Modules\Tcpdf\Tcpdf {

	
	/**
	 *
	 * @var \PdfHeaderHtml
	 */
	private $PdfHeaderHtml;
	
	/**
	 *
	 * @var \PdfHeaderHtml
	 */
	private $PdfFooterHtml;

	
	/**
	 *
	 * @param \Set PDF Header Html $html
	 */
	function setHeaderContent($html) {
		$this->PdfHeaderHtml = $html;
	}
	
	/**
	 *
	 * @param \Set PDF Footer Html $html
	 */
	function setFooterContent($html) {
		$this->PdfFooterHtml = $html;
	}
	
	//Page header
	public function Header() {
		$this->SetFont('helvetica', '', 9);
		$this->setY(10);
		$this->writeHTML($this->PdfHeaderHtml);
	}

	// Page footer
	public function Footer() {
		$this->SetFont('helvetica', '', 9);
		$this->setY(-20);
		$this->writeHTML($this->PdfFooterHtml);
	}

}