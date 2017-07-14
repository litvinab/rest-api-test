<?php
namespace Litvinab\Bundle\RestApiTestBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class DBCacheClearCommand extends Command
{
    const NAME = 'db:cache-clear';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Clean database metadata, query, result cache at the same time');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runCommand($input, $output, 'doctrine:cache:clear-metadata');
        $this->runCommand($input, $output, 'doctrine:cache:clear-query');
        $this->runCommand($input, $output, 'doctrine:cache:clear-result');
    }

    /**
     * @param $input
     * @param $output
     * @param $commandName
     */
    private function runCommand($input, $output, $commandName)
    {
        $command = $this->getApplication()->find($commandName);
        $command->run($input, $output);
    }
}