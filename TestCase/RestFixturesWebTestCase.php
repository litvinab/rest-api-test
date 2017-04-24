<?php

namespace Litvinab\Bundle\RestApiTestBundle\TestCase;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestFixturesWebTestCase extends FixturesWebTestCase
{
    /**
     * Request json to specified endpoint as authorized client
     *
     * @param $method
     * @param $endpoint
     * @param $json
     * @param $headers
     *
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    protected function requestJson($method, $endpoint, $json, array $headers = array())
    {
        $client = static::createClient();

        $predefinedHeaders = array(
            'CONTENT_TYPE' => 'application/json'
        );

        $headers = array_merge($predefinedHeaders, $headers);

        $client->request(
            $method,
            $endpoint,
            array(),
            array(),
            $headers,
            $json
        );

        return $client->getResponse();
    }

    /**
     * @param $endpoint
     * @param array $headers
     *
     * @return null|Response
     */
    protected function getJson($endpoint, $headers = array())
    {
        return $this->requestJson(Request::METHOD_GET, $endpoint, null, $headers);
    }

    /**
     * Post json to specified endpoint as authorized client
     *
     * @param $endpoint
     * @param $json
     * @param $headers
     *
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    protected function postJson($endpoint, $json, array $headers = array())
    {
        return $this->requestJson(Request::METHOD_POST, $endpoint, $json, $headers);
    }

    /**
     * Put json to specified endpoint
     *
     * @param $endpoint
     * @param $json
     * @param $headers
     *
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    protected function putJson($endpoint, $json, array $headers = array())
    {
        return $this->requestJson(Request::METHOD_PUT, $endpoint, $json, $headers);
    }

    /**
     * Delete json to specified endpoint
     *
     * @param $endpoint
     * @param $headers
     *
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    protected function deleteJson($endpoint, array $headers = array())
    {
        return $this->requestJson(Request::METHOD_DELETE, $endpoint, null, $headers);
    }

    /**
     * @param Response $response
     * @param $expectedStatusCode
     * @param $expectedContent
     */
    protected function assertResponse(Response $response, $expectedStatusCode, $expectedContent)
    {
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
    }

    /**
     * @param Response $response
     * @param $expectedStatusCode
     * @param $expectedJson
     */
    protected function assertJsonResponse(Response $response, $expectedStatusCode, $expectedJson)
    {
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent(), $response->getContent());
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }

    /**
     * @param Response $response
     * @param $expectedStatusCode
     */
    protected function assertResponseCode(Response $response, $expectedStatusCode)
    {
        $this->assertEquals($expectedStatusCode, $response->getStatusCode(), $response->getContent());
    }
}