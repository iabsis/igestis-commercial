<?php

namespace Igestis\Modules\Commercial\Exceptions;
/**
 * Description of ProviderInvoiceUploadException
 *
 * @author gillesh
 */
class ProviderInvoiceUploadException extends \Igestis\Exceptions\UploadException {
    private $providerInvoice = null;
    public function setExistingProviderInvoice(\CommercialProviderInvoice $invoice) {
        $this->providerInvoice = $invoice;
    }
    
    public function getExistingProviderInvoice () {
        return $this->providerInvoice;
    }
}
