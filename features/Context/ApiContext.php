<?php

declare(strict_types=1);

namespace App\Features\Context;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ApiContext implements Context, \ArrayAccess
{
    use ArrayAccessTrait;

    /**
     * @var array
     */
    private $requestOptions = [];

    /**
     * @var string
     */
    private $content = '';

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var array
     */
    private $server = [];

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $method;

    /**
     * @var bool
     */
    private $force;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Request a path
     *
     * @param string $path   The path to request
     * @param string $method The HTTP method to use
     *
     * @return self
     *
     * @When I request :path
     * @When I request :path using HTTP :method
     */
    public function requestPath($path, $method = null)
    {
        $this->setRequestPath($this->parseExpressionLanguageTemplate($path));
        if (null === $method) {
            $this->setRequestMethod('GET', false);
        } else {
            $this->setRequestMethod($method);
        }

        return $this->sendRequest();
    }

    /**
     * Update the path of the request
     *
     * @param string $path The path to request
     *
     * @return self
     */
    private function setRequestPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * Update the HTTP method of the request
     *
     * @param string $method The HTTP method
     * @param bool   $force  Force the HTTP method. If set to false the method set CAN be
     *                       overridden (this occurs for instance when adding form parameters to the
     *                       request, and not specifying HTTP POST for the request)
     *
     * @return self
     */
    private function setRequestMethod(string $method, bool $force = true)
    {
        $this->method = $method;
        $this->force = $force;
    }

    private function sendRequest()
    {
        $this->client->request($this->method, $this->path, $this->parameters, $this->files, $this->server, $this->content);
        $response = $this->client->getResponse();
        $this['response'] = [
            'status' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            'content' => $response->getContent(),
        ];
    }

    /**
     * Assert the HTTP response code
     *
     * @param int $code The HTTP response code
     *
     * @throws \Assert\AssertionFailedException
     *
     * @Then the response code is :code
     */
    public function assertResponseCodeIs(int $code)
    {
        $this->requireResponse();
        Assertion::same(
            $actual = $this['response']['status'],
            $expected = $this->validateResponseCode($code),
            sprintf('Expected response code %d, got %d.', $expected, $actual)
        );
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    private function requireResponse()
    {
        Assertion::keyIsset($this, 'response', 'The request has not been made yet, so no response object exists.');
    }

    /**
     * @param int $code
     *
     * @return int
     *
     * @throws \Assert\AssertionFailedException
     */
    private function validateResponseCode(int $code)
    {
        Assertion::range($code, 100, 599, sprintf('Response code must be between 100 and 599, got %d.', $code));

        return $code;
    }

    /**
     * Set the request body to a string
     *
     * @param resource|string|PyStringNode $body The content to set as the request body
     *
     * @return self
     *
     * @Given the request body is:
     */
    public function setRequestBody(string $body)
    {
        if (!empty($this->requestOptions['multipart']) || !empty($this->requestOptions['form_params'])) {
            throw new \InvalidArgumentException(
                'It\'s not allowed to set a request body when using multipart/form-data or form parameters.'
            );
        }
        $this->content = $this->parseExpressionLanguageTemplate($body);

        return $this;
    }

    /**
     * Considers all substrings surrounded by {{ }} as Expression Language expressions and evaluates them
     *
     * @param string $template
     *
     * @return string|string[]|null
     */
    private function parseExpressionLanguageTemplate(string $template)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        return preg_replace_callback('/\-\<(.*?)\>\-/U', function ($matches) use ($propertyAccessor) {
            return $propertyAccessor->getValue($this, trim($matches[1]));
        }, $template);
    }

    /**
     * @Given the request :header header is :value
     */
    public function setRequestHeader($header, $value)
    {
        $this->server[strtoupper('HTTP_' . $header)] = $value;
    }

    /**
     * @Then the response header :header is :expected
     *
     * @throws \Assert\AssertionFailedException
     */
    public function assertResponseHeaderIs($header, $expected)
    {
        $this->requireResponse();
        Assertion::same(
            $actual = $this['response']['headers'][$header],
            $expected,
            sprintf('Expected response header %s, got %s.', $expected, $actual)
        );
    }
}
