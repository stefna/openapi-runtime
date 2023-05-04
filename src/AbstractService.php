<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Stefna\HttpClient\HttpFactory;
use Stefna\OpenApiRuntime\Exceptions\MalformedResponse;
use Stefna\OpenApiRuntime\Exceptions\RequestFailed;

abstract class AbstractService implements LoggerAwareInterface
{
	use LoggerAwareTrait;

	protected ClientInterface $client;
	protected ServerConfigurationInterface $serverConfiguration;
	private RequestFactoryInterface $requestFactory;
	private ?ResponseInterface $lastResponse = null;
	private ?RequestInterface $lastRequest = null;

	/**
	 * @return static
	 */
	public static function create(ServerConfigurationInterface $serverConfiguration, HttpFactory $factory = null)
	{
		// this logic kinda don't make sense but run with it
		if ($factory || class_exists(HttpFactory::class)) {
			$requestFactory = $factory ?? new HttpFactory();
			$client = $requestFactory->createClient();
		}
		else {
			$factory = $factory ?? new Factory();
			$requestFactory = $factory->createRequestFactory();
			$client = $factory->createPsr18Client();
		}

		return new static($serverConfiguration, $client, $requestFactory);
	}

	public function __construct(
		ServerConfigurationInterface $serverConfiguration,
		ClientInterface $client,
		RequestFactoryInterface $requestFactory
	) {
		$this->serverConfiguration = $serverConfiguration;
		$this->client = $client;
		$this->requestFactory = $requestFactory;
	}

	public function getServerConfiguration(): ServerConfigurationInterface
	{
		return $this->serverConfiguration;
	}

	/**
	 * @return static
	 */
	public function withServerConfiguration(ServerConfigurationInterface $serverConfiguration)
	{
		$self = clone $this;
		$self->serverConfiguration = $serverConfiguration;
		return $self;
	}

	protected function doRequest(EndpointInterface $endpoint): ResponseInterface
	{
		$endpointUri = $this->serverConfiguration->getBaseUri() . $endpoint->getPath();
		$request = $this->requestFactory->createRequest($endpoint->getMethod(), $endpointUri);

		$uri = $request->getUri();

		$queryParams = $endpoint->getQueryParams();
		$headerParams = method_exists($endpoint, 'getHeaders') ? $endpoint->getHeaders() : [];
		$bodyParams = $endpoint->getRequestBody();
		if ($queryParams) {
			$uri = $uri->withQuery(http_build_query($queryParams));
		}
		if ($headerParams) {
			foreach ($headerParams as $key => $value) {
				$request = $request->withHeader($key, $value);
			}
		}
		if ($bodyParams) {
			$body = $request->getBody();
			$body->rewind();
			$body->write(json_encode($bodyParams, JSON_THROW_ON_ERROR));
			$body->rewind();
			$request = $request->withHeader('Content-Type', 'application/json');
			$request = $request->withBody($body);
		}
		$request = $request->withUri($uri);

		$request = $this->serverConfiguration->configureAuthentication($request, $endpoint);

		try {
			$this->lastRequest = $request;
			$response = $this->executeRequest($request);
			$this->lastResponse = $response;
			return $response;
		}
		catch (ClientExceptionInterface $e) {
			$this->logger && $this->logger->info('Error talking to api', [
				'exception' => $e,
			]);
		}

		throw new RequestFailed('Failed to query api', 0, $e ?? null);
	}

	protected function executeRequest(RequestInterface $request): ResponseInterface
	{
		return $this->client->sendRequest($request);
	}

	protected function parseResponse(ResponseInterface $response)
	{
		$body = (string)$response->getBody();
		if (!$body) {
			return [];
		}
		$json = json_decode($body, true);
		if ($json === null) {
			throw new MalformedResponse($response);
		}
		return $json;
	}

	public function getLastResponse(): ?ResponseInterface
	{
		return $this->lastResponse;
	}

	public function getLastRequest(): ?RequestInterface
	{
		return $this->lastRequest;
	}
}
