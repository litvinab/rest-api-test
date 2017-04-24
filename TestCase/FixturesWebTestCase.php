<?php
namespace Litvinab\Bundle\RestApiTest\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Litvinab\Bundle\RestApiTest\Command\ReloadTestDBCommand;

class FixturesWebTestCase extends WebTestCase
{
    protected static $application;

    private static $container;

    protected function setUp()
    {
        self::bootKernel();
        self::$container = self::$kernel->getContainer();

        self::runCommand(ReloadTestDBCommand::NAME);
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