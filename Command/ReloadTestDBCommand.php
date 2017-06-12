<?php
namespace Litvinab\Bundle\RestApiTestBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class ReloadTestDBCommand extends Command
{
    const NAME = 'db:test:reload';

    const FIXTURES_ONLY = 'fixtures-only';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Recreate test database, reload data fixtures')
            ->addOption(
                self::FIXTURES_ONLY,
                null,
                null,
                'reload fixtures only without database drop and schema creation'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixturesOnly = $input->getOption(self::FIXTURES_ONLY);

        if(!$fixturesOnly) {
            $this->runCommand('doctrine:database:drop', '--env=test --force', $output);
            $this->runCommand('doctrine:database:create', '--env=test', $output);
            $this->runCommand('doctrine:schema:update', '--env=test --force', $output);
        }

        $this->runCommand('doctrine:fixtures:load', '--env=test --no-interaction', $output);
    }

    /**
     * @param $commandString
     * @param $stringInput
     * @param $output
     */
    private function runCommand($commandString, $stringInput, $output)
    {
        $command = $this->getApplication()->find($commandString);

        $greetInput = new StringInput($stringInput);
        $greetInput->setInteractive(false);
        $command->run($greetInput, $output);
    }
}