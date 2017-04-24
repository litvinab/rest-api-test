<?php
namespace Litvinab\Bundle\RestApiTest\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class ReloadTestDBCommand extends Command
{
    const NAME = 'db:test:reload';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Recreate test database, reload data fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runCommand('doctrine:database:drop', '--env=test --force', $output);
        $this->runCommand('doctrine:database:create', '--env=test', $output);
        $this->runCommand('doctrine:schema:update', '--env=test --force', $output);
        $this->runCommand('doctrine:fixtures:load', '--env=test --no-interaction', $output);
    }

    private function runCommand($commandString, $stringInput, $output)
    {
        $command = $this->getApplication()->find($commandString);

        $greetInput = new StringInput($stringInput);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);
    }
}