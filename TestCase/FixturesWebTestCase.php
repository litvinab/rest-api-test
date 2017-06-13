<?php
namespace Litvinab\Bundle\RestApiTestBundle\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Litvinab\Bundle\RestApiTestBundle\Command\ReloadTestDBCommand;

class FixturesWebTestCase extends WebTestCase
{
    protected static $application;

    private static $container;

    protected function setUp()
    {
        self::bootKernel();
        self::$container = self::$kernel->getContainer();
    }

    /**
     * Reload test database with data fixtures
     */
    protected function reloadDb()
    {
        $commandString = sprintf('%s --%s', ReloadTestDBCommand::NAME, ReloadTestDBCommand::FIXTURES_ONLY);
        self::runCommand($commandString);
    }

    protected function getContainer()
    {
        return self::$container;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Get repository
     *
     * @param $name
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository($name)
    {
        return $this->getEntityManager()->getRepository($name);
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);
        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();
            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }
        return self::$application;
    }
}