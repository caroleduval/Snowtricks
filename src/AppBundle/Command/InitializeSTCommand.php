<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class InitializeSTCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:initialize-SN')

            // the short description shown while running "php bin/console list"
            ->setDescription('Initializes application with Tricks data.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to set several tricks and the category table...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            "Initialization of SnowTricks application  ..."
        ]);

        $app = $this->getApplication();

       # Créer la base de données
        $createdbCmd = $app->find('doctrine:database:create');
        $createdbInput = new ArrayInput(['command' => 'doctrine:database:create']);
        $createdbCmd->run($createdbInput, $output);
        $output->writeln([
            "SnowTricks database has been created."
        ]);


        # Créer les tables
        $createtablesCmd = $app->find('doctrine:schema:update');
        $createtablesInput = new ArrayInput(['command' => 'doctrine:schema:update', '--force' => true]);
        $createtablesCmd->run($createtablesInput, $output);
        $output->writeln([
            "Database tables have been created."
        ]);


        # Charger les données
        $loaddataCmd = $app->find('app:import-fixtures');
        $loaddataInput = new ArrayInput(['command' => 'app:import-fixtures']);
        $loaddataCmd->run($loaddataInput, $output);
        $output->writeln([
            "Datas have been charged in the application."
        ]);

        $output->writeln([
            "SnowTricks has been initialized ! Enjoy !"
        ]);
    }
}