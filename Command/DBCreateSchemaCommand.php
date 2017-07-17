<?php
namespace Litvinab\Bundle\RestApiTestBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class DBCreateSchemaCommand extends Command
{
    const NAME = 'db:create-schema';
    const DOCTRINE_CMD = 'doctrine:schema:create';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription(sprintf('Alias of "%s" command', self::DOCTRINE_CMD));
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find(self::DOCTRINE_CMD);
        $command->run($input, $output);
    }
}