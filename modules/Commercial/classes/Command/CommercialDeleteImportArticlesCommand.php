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

class CommercialDeleteImportArticlesCommand extends Command {
    private $input;
    private $output;
    
    /**
     * Configure the shell arguments
     */
    protected function configure()
    {
        $this
            ->setName('Commercial:delete-import-articles')
            ->setDescription('Delete imports and linked articles')
            ->addOption(
                'import-ref',
                null,
                InputOption::VALUE_REQUIRED,
                'Reference name of the import to delete'
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
            $entityManager->beginTransaction();

            if ($input->getOption('import-ref')) {
                $output->writeln("Starting process");
                $imports = $entityManager->getRepository("CommercialImportArticles")->findBy(array(
                    'importLabel' => $input->getOption('import-ref')
                ));
                if (!count($imports)) {
                    $output->writeln("No imports found");
                }
                foreach ($imports as $currentImport) {
                    $output->writeLn("Deleting import number " . $currentImport->getId() . " which was imported the " . $currentImport->getImportDate()->format("d/m/Y H:i"));
                    $entityManager->remove($currentImport);
                    $entityManager->flush();

                }
            } else {
                $output->writeln("<error>Not enough arguments, type --help to know the possible options</error>");
                exit;
            }

            $entityManager->commit();
        } catch(\Exception $e) {
            $entityManager->rollback();
            $output->writeln("<error>" . $e->getMessage() . "</error>");
            exit(20);
        }
        

        $output->writeln("<info>Process complete</info>");
        
    }
}