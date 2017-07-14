<?php
namespace Litvinab\Bundle\RestApiTestBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DBCreateCommand extends Command
{
    const NAME = 'db:create';
    const DOCTRINE_CMD = 'doctrine:database:create';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Alias of "doctrine:database:create" command')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find(self::DOCTRINE_CMD);
        $command->run($input, $output);
    }
}