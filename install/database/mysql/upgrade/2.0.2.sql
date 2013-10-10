DROP VIEW IF EXISTS `COMMERCIAL_VIEW_BANK_ASSOC_DOCS`;

CREATE ALGORITHM=UNDEFINED VIEW `COMMERCIAL_VIEW_BANK_ASSOC_DOCS` AS

select 
    `ao`.`operation_id` AS `operation_id`,
    'selling_document' AS `transaction_type`,
    `d`.`company_id` AS `company_id`,
    `i`.`id` AS `document_id`,
    `d`.`customer_user_id` AS `user_id`,
    `i`.`invoices_date` AS `transaction_date`,
    `i`.`path_pdf_file` AS `path`,
    `i`.`exported` AS `exported`,
    `i`.`paid` AS `paid`,
    if((`i`.`invoices_type` = 'credits'),(-(1) * `i`.`invoice_total_TI`),`i`.`invoice_total_TI`) AS `total_ti` 
from ((`COMMERCIAL_INVOICE` `i` 
left join `COMMERCIAL_COMMERCIAL_DOCUMENT` `d` on((`d`.`id` = `i`.`commercial_document_id`))) 
left join `COMMERCIAL_BANK_ASSOC_OPERATIONS` `ao` on((`ao`.`invoice_id` = `i`.`id`))) 
where (`ao`.`operation_id` is not null) 

union 

select `ao`.`operation_id` AS `operation_id`,
    'buying_document' AS `transaction_type`,
    `pi`.`company_id` AS `company_id`,
    `pi`.`id` AS `document_id`,
    `pi`.`provider_user_id` AS `user_id`,
    `pi`.`invoice_date` AS `transaction_date`,
    `pi`.`invoice_path` AS `path`,
    `pi`.`exported` AS `exported`,
    `pi`.`paid` AS `paid`,
    (-(1) * sum(`ia`.`amount_ti`)) AS `total_ti` 
from ((`COMMERCIAL_PROVIDER_INVOICE` `pi` left join `COMMERCIAL_PROVIDER_INVOICE_ASSOC_AMOUNTS` `ia` on((`ia`.`purchase_invoice_id` = `pi`.`id`))) 
left join `COMMERCIAL_BANK_ASSOC_OPERATIONS` `ao` on((`ao`.`provider_invoice_id` = `pi`.`id`))) 
where (`ao`.`operation_id` is not null) group by `pi`.`id` 

union 

select `ao`.`operation_id` AS `operation_id`,
    'manual_amounts' AS `transaction_type`,
    `a`.`CORE_COMPANIES_id` AS `company_id`,
    NULL AS `document_id`,
    `ao`.`concerned_user_id` AS `user_id`,
    `o`.`operation_date` AS `transaction_date`,
    NULL AS `path`,
    NULL AS `exported`,
    NULL AS `paid`,
    `ao`.`amount` AS `amountTi` 
from ((`COMMERCIAL_BANK_ASSOC_OPERATIONS` `ao` left join `COMMERCIAL_BANK_OPERATION` `o` on((`o`.`id` = `ao`.`operation_id`))) 
left join `COMMERCIAL_BANK_ACCOUNT` `a` on((`a`.`id` = `o`.`account_id`))) 
where (`ao`.`concerned_user_id` is not null and ao.invoice_id is null and provider_invoice_id is null);
