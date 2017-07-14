<?php

namespace Litvinab\Bundle\RestApiTestBundle\Controller;

use Litvinab\Bundle\RestApiTestBundle\Command\DBLoadFixturesCommand;
use Litvinab\Bundle\RestApiTestBundle\Service\RequestAccessChecker;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\BufferedOutput;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class TestController
 * @package DeviceCombination\Application\RestApi\Controller
 */
class TestController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/test/db/reload",
     *      summary="Reload data fixtures",
     *      tags={"test"},
     *      @SWG\Response(
     *              response=200,
     *              description="Success"
     *      )
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function reloadDBAction(Request $request)
    {
        /** @var RequestAccessChecker $requestAccessChecker */
        $requestAccessChecker = $this->get('rest_api_test.request_access_checker');

        if (!$requestAccessChecker->isAccessGranted($request)) {
            // 404 was thrown to prevent detection of current bundle on the servers
            throw new NotFoundHttpException();
        }

        $kernel = $this->get('kernel');

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $commandString = DBLoadFixturesCommand::NAME;

        $input = new StringInput($commandString);
        $output = new BufferedOutput();

        $application->run($input, $output);

        return new Response($output->fetch());
    }
}
