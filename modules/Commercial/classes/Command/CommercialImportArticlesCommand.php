<?php
namespace Igestis\Modules\Commercial\Command;

/**
 * Description of command
 *
 * @author gilles
 */

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

use Igestis\Modules\Commercial\Parsers\ImportProductCsv;
use Igestis\Modules\Commercial\EntityLogic\DbalArticleCreation;

class CommercialImportArticlesCommand extends Command {
    private $input;
    private $output;
    
    /**
     * Configure the shell arguments
     */
    protected function configure()
    {
        $this
            ->setName('Commercial:import-articles')
            ->setDescription('Import articles from a flat file')
            ->addArgument(
                'import-label',
                InputArgument::REQUIRED,
                'The import label is a reference in order to retrieve your import in the import list (ie: Ingram Micro 01/10/2015)'
            )
            ->addArgument(
                'company-id',
                InputArgument::REQUIRED,
                'The company id who will receive the articles'
            )
            ->addArgument(
                'csv-file-target',
                InputArgument::REQUIRED,
                'Target to the file which contains data'
            )
            ->addOption(
                'delimiter',
                null,
                InputOption::VALUE_REQUIRED,
                'Csv Delimiter',
                ','
            )
            ->addOption(
                'enclosure',
                null,
                InputOption::VALUE_REQUIRED,
                'Csv enclosure',
                '"'
            )
            ->addOption(
                'column-provider-ref',
                null,
                InputOption::VALUE_REQUIRED,
                'Provider reference column number'
            )
            ->addOption(
                'column-igestis-ref',
                null,
                InputOption::VALUE_REQUIRED,
                'iGestis reference column number'
            )
            ->addOption(
                'column-short-description',
                null,
                InputOption::VALUE_REQUIRED,
                'Product title column number'
            )
            ->addOption(
                'column-product-description',
                null,
                InputOption::VALUE_REQUIRED,
                'Description of the product column number'
            )
            ->addOption(
                'column-buying-price',
                null,
                InputOption::VALUE_REQUIRED,
                'Buying price column number'
            )
            ->addOption(
                'column-selling-price',
                null,
                InputOption::VALUE_REQUIRED,
                'Selling price column number'
            )
            ->addOption(
                'column-vat',
                null,
                InputOption::VALUE_REQUIRED,
                'Tax rate column number'
            )
            ->addOption(
                'column-id-account',
                null,
                InputOption::VALUE_REQUIRED,
                'Account id'
            )
            ->addOption(
                'column-category',
                null,
                InputOption::VALUE_REQUIRED,
                'Account id'
            )
        ;

    }

    /**
     * Execute the shell script to generate the pdf
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        
        $output->writeln("Starting import");

        $entityManager = \Application::getEntityMaanger();
        $dbalConnexion = $entityManager->getConnection();
        $dbalConnexion->getConfiguration()->setSQLLogger(null);

        $company = $entityManager->getRepository("CoreCompanies")->find($input->getArgument('company-id'));
        if (!$company) {
            $output->writeln("<error>Company Id not found</error>");
            exit(1);
        }

        $importLabel = $input->getArgument('import-label');
        if (!$importLabel) {
            $output->writeln("<error>Import label is empty</error>");
            exit(1);
        }

        $csvFile = $input->getArgument('csv-file-target');
        $enclosure = $input->getOption('enclosure');
        $delimiter = $input->getOption('delimiter');

        try {
            $entityManager->beginTransaction();
            $import = new \CommercialImportArticles($company, $importLabel);
            $entityManager->persist($import);
            $entityManager->flush();

            $csvImport = new ImportProductCsv;
            $csvImport->SetColumns(
                array(
                    "manufacturer_ref" => $input->getOption('column-provider-ref'),
                    "company_ref" => $input->getOption('column-igestis-ref'),
                    "designation" => $input->getOption('column-short-description'),
                    "description" => $input->getOption('column-product-description'),
                    "purchasing_price_df" => $input->getOption('column-buying-price'),
                    "selling_price_df" => $input->getOption('column-selling-price'),
                    "taxe_rate_id" => $input->getOption('column-vat'),
                    "selling_account_id" => $input->getOption('column-id-account'),
                    "categories" => $input->getOption('column-category'),
                )
            );

            $csvImport->loadCsv($csvFile, $delimiter, $enclosure);

            while ($csvData = $csvImport->next()) {
                $csvData["import_id"] = $import->getId();
                $csvData["company_id"] = $company->getId();

                $output->writeln("Parsing line " . $csvImport->getCurrentLine());
                DbalArticleCreation::createArticle(
                    $dbalConnexion,
                    $csvData
                );
            }


            $entityManager->commit();
        }
        catch(\Exception $e) {
            $entityManager->rollback();
            $output->writeln("<error>" . $e->getMessage() . "</error>");
            exit(20);
        }

        $output->writeln("<info>Import complete</info>");
        
    }
}