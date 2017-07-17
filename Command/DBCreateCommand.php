<?php
namespace Litvinab\Bundle\RestApiTestBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DBCreateCommand extends Command
{
    const NAME = 'db:create';
    const DOCTRINE_CMD = 'doctrine:database:create';
    const DOCTRINE_CMD_FLAGS = '--if-not-exists';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription(sprintf('Alias of "%s %s" command', self::DOCTRINE_CMD, self::DOCTRINE_CMD_FLAGS));
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find(self::DOCTRINE_CMD);

        $stringInput = new StringInput(self::DOCTRINE_CMD_FLAGS);
        $stringInput->setInteractive(false);
        $command->run($stringInput, $output);
    }
}