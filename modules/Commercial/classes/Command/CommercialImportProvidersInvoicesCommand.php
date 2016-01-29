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

use Igestis\Modules\Commercial\ConfigModuleVars;

class CommercialImportProvidersInvoicesCommand extends Command
{
    private $input;
    private $output;

    /**
     * Configure the shell arguments
     */
    protected function configure()
    {
        $this
            ->setName('Commercial:import-purchase-invoices')
            ->setDescription('Import pdf file contained in the folder into the providers invoices list')
            ->addOption(
                'folder',
                null,
                InputOption::VALUE_REQUIRED,
                'Source folder to import invoices from'
            )
            ->addOption(
                'company-id',
                null,
                InputOption::VALUE_REQUIRED,
                'Id of the company to add the invoice'
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

        $entityManager = \Application::getEntityManager();

        try {
            $folder = $input->getOption('folder');
            if (empty($folder)) {
                throw new \Exception("Please specify a folder with --folder");
            }

            $companyID = $input->getOption('company-id');
            if (!$companyID) {
                throw new \Exception("Please specify the company id with --company-id");
            }

            $company = $entityManager->getRepository('CoreCompanies')->find($input->getOption('company-id'));
            if (!$company) {
                throw new \Exception(sprintf("Company not found"));
            }

            if (!is_dir($folder)) {
                throw new \Exception(sprintf("'%s' is not a folder", $input->getOption('folder')));
            }

            if (!is_writable($folder)) {
                throw new \Exception(sprintf("'%s' is not a writable", $input->getOption('folder')));
            }

            if (!is_readable($folder)) {
                throw new \Exception(sprintf("'%s' is not a readable", $input->getOption('folder')));
            }

            $filesList = scandir($folder);
            foreach ($filesList as $currentFile) {
                if (preg_match('/\.pdf$/i', $currentFile)) {
                    $output->writeln(sprintf("<info>Importing file '%s'</info>", $currentFile));
                    $originalFile = $folder . '/' . $currentFile;

                    $md5 = md5_file($originalFile);
                    $alreadyExists = $entityManager->getRepository('CommercialProviderInvoice')->findOneBy(
                        array("fileMd5Hash" => $md5)
                    );

                    if ($alreadyExists) {
                        $output->writeln("<error>A pdf wih the same MD5 hash does already exist. Ignoring the file</error>");
                    } else {
                        do {
                            $filename = uniqid() . ".pdf";
                        } while (is_file(ConfigModuleVars::providersInvoicesFolder() . '/' . $filename));

                        copy($originalFile, ConfigModuleVars::providersInvoicesFolder() . '/' . $filename);

                        $providerInvoice = new \CommercialProviderInvoice();
                        $providerInvoice->setInvoicePath($filename);
                        $providerInvoice->setFileMd5Hash($md5);
                        $providerInvoice->setCompany($company);
                        $entityManager->persist($providerInvoice);
                        $entityManager->flush();
                        $output->writeln(sprintf("'%s' file imported", $currentFile));
                    }

                    if (unlink($originalFile)) {
                        $output->writeln(sprintf("'%s' file deleted", $currentFile));
                    } else {
                        $output->writeln(sprintf("An error occured when trying to remove '%s' file", $currentFile));
                    }
                }
            }
        } catch (\Exception $e) {
            $output->writeln("<error>" . $e->getMessage() . "</error>");
            exit(1);
        }


        $output->writeln("<info>Process complete</info>");

    }
}
