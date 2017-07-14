<?php
namespace Litvinab\Bundle\RestApiTestBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class DBLoadFixturesCommand extends Command
{
    const NAME = 'db:load-fixtures';
    const DOCTRINE_CMD = 'doctrine:fixtures:load';
    const DOCTRINE_CMD_FLAGS = '--no-interaction';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription(sprintf('Alias of %s', self::DOCTRINE_CMD));
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