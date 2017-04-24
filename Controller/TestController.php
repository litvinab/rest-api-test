<?php

namespace Litvinab\Bundle\RestApiTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\BufferedOutput;
use Litvinab\Bundle\RestApiTest\Command\ReloadTestDBCommand;
use Swagger\Annotations as SWG;

/**
 * Class TestController
 * @package DeviceCombination\Application\RestApi\Controller
 */
class TestController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/test/db/reload",
     *      summary="Recreate test database, reload data fixtures",
     *      tags={"test"},
     *      @SWG\Response(
     *              response=200,
     *              description="Success"
     *      ),
     *      @SWG\Response(
     *              response=404,
     *              description="request ran not under dev, test environment"
     *      )
     * )
     *
     * @return Response
     */
    public function reloadDBAction()
    {
        $kernel = $this->get('kernel');

        if($kernel->getEnvironment() !== 'test') {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new StringInput(ReloadTestDBCommand::NAME);
        $output = new BufferedOutput();

        $application->run($input, $output);

        return new Response($output->fetch());

    }
}
