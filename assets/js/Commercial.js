/**
 * @author Gilles Hemmerlé <giloux@gmail.com>
 * @description Contain all igestis commercial modules specific methods
 */

var igestisCommercial = function() {
   var _public = {};
   
   _public.documents = {};
   _public.support = {};
   _public.common = {};
   _public.projects = {};
   _public.bank = {};
   _public.sellingDocument = {};
   _public.providerInvoices = {};
   
   return _public;
}();


/**
 * Return the number of minutes in integer format
 * @param {string} time Time to format HH:ii
 * @returns {int} Time in integer format
 */

igestisCommercial.common.TtI = function(time) {
    var time_format= /[0-9]{1,3}\:[0-9]{1,2}/;
    if(!time_format.test(time)) {
        return 0;
    }
    
    var period = time.split(":");
    return (parseInt(period[0], 10) * 60) + parseInt(period[1], 10); 
};

/**
 * Get a number of minutes and returnes at time format "HH:ii"
 * @param {int} time Number of minutes
 * @returns {String} Formatted time HH:ii
 */
igestisCommercial.common.ItT = function(time) {
    var minutes = 0;
    var heures = 0;
    heures = parseInt(time / 60, 10);
    minutes = time % 60;
    if(heures<10) heures = "0" + heures;
    if(minutes<10) minutes = "0" + minutes;
    
    return heures + ":" + minutes;
};

/**
 * Update html forms depending of current updated value
 * @param {jQuery} $field
 */
igestisCommercial.support.updateTimes = function($field) {
    var time = document.getElementById('id-startTime');
    var end = document.getElementById('id-endTime');
    var pause = document.getElementById('id-pause');
    var period = document.getElementById('id-period');
    switch($field.attr('name')) {
        case "startTime" : //start met à jour la durée
        case "endTime" : // end met à jour la durée
        case "pause" : // Pause met à jour la durée
            var duree = igestisCommercial.common.TtI(end.value) - igestisCommercial.common.TtI(time.value);
            if(duree < 0) duree = 1440 + duree;
            duree -= igestisCommercial.common.TtI(pause.value);
            period.value = igestisCommercial.common.ItT(duree);        
            break;
        case "period" : // Durée met à jour la fin
            var fin = igestisCommercial.common.TtI(time.value) + igestisCommercial.common.TtI(period.value) + igestisCommercial.common.TtI(pause.value);
            if( (fin) >1440) fin -= 1440;
            end.value = igestisCommercial.common.ItT(fin); 
            break;
    }
};


igestisCommercial.support.init = function() {
    $(function() {
       // Initialize the update helper for the time set
       $("#id-startTime, #id-endTime, #id-pause, #id-period").bind("keyup input paste", function() {
           igestisCommercial.support.updateTimes($(this));
       });
    });
};

igestisCommercial.bank.startImport = function() {
	var error = false;
	$("#banks-table").find('input[type=checkbox]:checked').parents("tr").find('input[type=text]').each(function() {
		if($(this).val() == "") {
			alert(translations.fillbankname);
			$(this).focus();
			error = true;
			return;
		}
	});
	
	if(error) return ;
	
	var operationsData = igestisCommercial.bank.pageValues.oOperationTable.$('input').serialize();
	var accountsData = igestisCommercial.bank.pageValues.oBankTable.$('input').serialize();
	
	var dataToSend = '';
	if(operationsData) dataToSend += operationsData;
	if(accountsData) dataToSend += "&" + accountsData;

	$("#formValues").val(dataToSend);
	$("#hiddenPostForm").submit();

};

igestisCommercial.bank.init = function(pageValues) {
	
	igestisCommercial.bank.pageValues = pageValues;
	igestisCommercial.bank.pageValues.oOperationTable.$('tr').unbind('click').bind('click', function() {
		var $checkbox = $(this).find('input[type=checkbox]:not(:disabled)');
		$checkbox.attr("checked", !$checkbox.attr("checked"));
	});
	return;
	
	$(".auto-check > tbody > tr").unbind('click').bind('click', function() {
		var $checkbox = $(this).find('input[type=checkbox]');
		$checkbox.attr("checked", !$checkbox.attr("checked"));
	});
	
	$(".auto-check > tbody input").unbind('click').bind('click', function(e) {
		e.stopPropagation();
	});
};

/**
 * 
 * @param {type} options
 * @returns {undefined}
 */
igestisCommercial.sellingDocument.init = function(options) {
    igestisCommercial.sellingDocument.options = {
        deleteLink : "",
        reorderingLink : ""
    } ;
    
    $.extend(igestisCommercial.sellingDocument.options, options);
    
    igestisCommercial.sellingDocument.initTable();
    
    $(function() {
       $('#SendInvoice textarea, #SendQuotation textarea').wysihtml5(); 
    });
};

igestisCommercial.sellingDocument.initTable = function() {
    // https://code.google.com/p/jquery-datatables-row-reordering/wiki/ServerSideIntegration
    $(function(){
        $('#listArticles').dataTable( {
            bPaginate: false,
            bLengthChange: false,
            bFilter: false,
            bSort: true,
            bInfo: false,
            bAutoWidth: true,
            aoColumnDefs: [
                { "bVisible": false, "aTargets": [ 0 ] },
                { "sWidth": '45%', "aTargets": [ 3 ] }
            ]
        }).rowReordering({ 
            sURL:igestisCommercial.sellingDocument.options.reorderingLink
        });
    });
};



igestisCommercial.sellingDocument.deleteItem = function(itemId) {
    bootbox.confirm(translations.deletethisarticle, translations.no, translations.yes, function(result) {

        if(result) {
            $.ajax({
                url: igestisCommercial.sellingDocument.options.deleteLink + itemId,
                dataType:'json', //type json
                success: function(result) {
                    igestisParseJsonAjaxResult(result);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    bootbox.alert(jqXHR.responseText);   
                }
            });
        }
         
    });
};

igestisCommercial.sellingDocument.newArticle = function () {
    igestisCommercial.sellingDocument.resetArticleModal();
    $("#new-item").modal("show");
};

igestisCommercial.sellingDocument.editArticle = function (articleId) {
    igestisCommercial.sellingDocument.resetArticleModal();
    
    var articleDatas = $("#tr-article-" + articleId).data('article');
    
    $("#id-itemRef").val(articleDatas.itemRef);
    $('#id-taxRate.select2').select2().select2('val', articleDatas.taxRate);
    $("#id-itemLabel").val(articleDatas.itemLabel);
    $("#id-comment").val(articleDatas.comment);
    $("#id-sellingDfUnitPrice").val(articleDatas.sellingDfUnitPrice);
    $("#id-purchasingDfUnitPrice").val(articleDatas.purchasingDfUnitPrice);
    $("#id-quantityArticle").val(articleDatas.quantityArticle);
    $('#id-sellingAccount').select2().select2('val', articleDatas.sellingAccount);
    
    igestisCommercial.sellingDocument.calculateTotalPrice();
    
    $("#modal-article-id").val(articleId);
    $("#new-item").modal("show");
};

igestisCommercial.sellingDocument.resetArticleModal = function() {
    $("#new-item input[type!='submit'], #new-item textarea").not("#id-taxRate").val('');
    igestisCommercial.sellingDocument.calculateTotalPrice();
    $('#id-sellingAccount').select2().select2('val', '');
    
    if($('#id-taxRate.select2').select2().length > 1) $('#id-taxRate.select2').select2().select2('val', '');
    
    $("#id-sellingDfUnitPrice, #id-quantityArticle, #id-purchasingDfUnitPrice").unbind("keyup").bind("keyup", function() {
        igestisCommercial.sellingDocument.calculateTotalPrice();
    });
};

igestisCommercial.sellingDocument.calculateTotalPrice = function() {
    var totalPrice = $("#id-sellingDfUnitPrice").val() * $("#id-quantityArticle").val();
    if(isNaN(totalPrice)) totalPrice = 0;
    var earningPrice = totalPrice - ($("#id-purchasingDfUnitPrice").val() * $("#id-quantityArticle").val());
    if(isNaN(earningPrice)) {
        earningPrice = 0;
    }
    
    $("#id-totSellPriceArticleTi").val(totalPrice.toFixed(2));
    $("#id-totEarningPriceArticleTi").val(earningPrice.toFixed(2));
};


igestisCommercial.sellingDocument.generateQuote = function() {
    $("#new-estimatte-modal").modal('show');
};

igestisCommercial.sellingDocument.generateInvoice = function() {
    $("#new-invoice-modal").modal('show');
};

igestisCommercial.sellingDocument.editContact = function () {
            $("#contactShow").hide();
            $("#contactEdit").show();
};

igestisCommercial.sellingDocument.revertContact = function () {
            $("#contactShow").show();
            $("#contactEdit").hide();
};

igestisCommercial.sellingDocument.clearContact = function () {
            $("#contactEdit").find("input[type=text]").each(function(){
                $(this).val("");
            });
            $("#customerName").hide();
            $("#mainContact").show();
};

igestisCommercial.projects.init = function(options) {
    igestisCommercial.projects.options = {
        freeDocumentRefreshLink : ""
    } ;
    
    $.extend(igestisCommercial.projects.options, options);
    
    $('body').on('hidden', '.modal', function () {
        $(this).removeData('modal');
    });
   
    $("#project-link-buying-invoice-modal button[type=submit], #project-link-commercial-document-modal button[type=submit], #project-link-intervention-modal button[type=submit]").unbind("click").bind("click", function() {
        var $modal = $(this).parents(".modal");
        var $form = $modal.find("form");
        var validationUrl = $modal.data("ajaxUrl");
        var dataToSend = $form.serialize();
        dataToSend += "&validForm=1";

        $.ajax({
              url: validationUrl,
              dataType:'json', //type json
              data: dataToSend,
              cache: false,
              type: "POST",
              success: function(result) {
                  igestisParseJsonAjaxResult(result);

              },
              error: function(jqXHR, textStatus, errorThrown) {
                  bootbox.alert(jqXHR.responseText);   
              },
              complete: function() {
                 $modal.modal("hide");
                 igestisInitTableHover();
              }
       });
   });
};


igestisCommercial.projects.refreshFreeDocuments = function(e, data) {
    var error = false;
    for(i = 0; i < data.result.files.length; i++) {
        if(data.result.files[i].error) {
            error = true;
            igestisWizz("<strong>" + data.result.files[i].name + " :</strong> " + data.result.files[i].error, "WIZZ_ERROR", "#free-documents-errors", true);
        }
    }

    
    $.ajax({
        url: igestisCommercial.projects.options.freeDocumentRefreshLink,
        dataType:'json', //type json
        cache: false,
        type: "POST",
        success: function(result) {
            igestisParseJsonAjaxResult(result);

        },
        error: function(jqXHR, textStatus, errorThrown) {
            bootbox.alert(jqXHR.responseText);   
        },
        complete: function() {
           igestisInitTableHover();
        }
     });
};


igestisCommercial.providerInvoices.init = function(options) {
    
    igestisCommercial.providerInvoices.options = {
        amountDeleteLink : "",
        refreshInvoicesListLink : ""
    } ;
    
    $.extend(igestisCommercial.providerInvoices.options, options);
    
    $('body').on('hidden', '.modal', function () {
        $(this).removeData('modal');
    });
    
    $('body').on('shown', '.modal', function () {
        $(this).find("select.select2").select2({
            allowClear: true
        });
    });
    
    
};

igestisCommercial.providerInvoices.refreshInvoicesList = function() {
    $.ajax({
        url: igestisCommercial.providerInvoices.options.refreshInvoicesListLink,
        dataType:'json', //type json
        cache: false,
        type: "POST",
        success: function(result) {
            igestisParseJsonAjaxResult(result);
            igestisInitTableHover();
            IgestisInitTable('#table_data');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            bootbox.alert(jqXHR.responseText);   
        },
        complete: function() {
           
        }
   });
};


igestisCommercial.providerInvoices.deleteAmount = function(amountId) {
    bootbox.confirm(translations.deleteProviderInvoiceAmount, translations.no, translations.yes, function(result) {
        if(result) {
            $.ajax({
                url: igestisCommercial.providerInvoices.options.amountDeleteLink + amountId,
                dataType:'json', //type json
                success: function(result) {
                    igestisParseJsonAjaxResult(result);
                    igestisInitTableHover();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    bootbox.alert(jqXHR.responseText);   
                }
            });
        }
    });
};


igestisCommercial.refreshAjaxForm = function() {
    var id = 500;
    $(".ajax-emulation-validation-iframe").remove();
    $(".ajax-emulation-validation").each(function() {
      var iframeName = "ajax-emulation-validation-iframe-" + (++id);
      var $iframe = $('<iframe id="' + iframeName + '" name="' + iframeName + '" class="ajax-emulation-validation-iframe"></iframe>');      
      $(this).attr("target", $iframe.attr("id"));
      $(this).append($iframe);
      
      $iframe.on("load", function() { 
          var $modal = $("#igestis-waiting-msg");
          if($modal.length !== 0)  {
              $modal.modal("hide");
          }
          // Manage the result 
          var jsonData = $.parseJSON($(this).contents().find("body").text());
          if(jsonData !== null) igestisParseJsonAjaxResult(jsonData);
      });
      
   });
};

igestisCommercial.checkUncheckAll = function(field) {
    if($(field).data("checked") === true) {
        $('input[type=checkbox]', $('#table_data').dataTable().fnGetNodes()).removeAttr("checked");
        $(field).data("checked", false);

    }
    else {
        $('input[type=checkbox]', $('#table_data').dataTable().fnGetNodes()).attr("checked", "checked");
        $(field).data("checked", true);
    }
    
};