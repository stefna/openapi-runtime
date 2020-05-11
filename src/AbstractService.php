<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Stefna\OpenApiRuntime\Exceptions\RequestFailed;

abstract class AbstractService implements LoggerAwareInterface
{
	use LoggerAwareTrait;

	/** @var ClientInterface */
	protected $client;
	/** @var ServerConfigurationInterface */
	private $serverConfiguration;
	/** @var RequestFactoryInterface */
	private $requestFactory;

	/**
	 * @return static
	 */
	public static function create(ServerConfigurationInterface $serverConfiguration, FactoryInterface $factory = null)
	{
		$factory = $factory ?? new Factory();

		return new static($serverConfiguration, $factory->createPsr18Client(), $factory->createRequestFactory());
	}

	public function __construct(ServerConfigurationInterface $serverConfiguration, ClientInterface $client, RequestFactoryInterface $requestFactory)
	{
		$this->serverConfiguration = $serverConfiguration;
		$this->client = $client;
		$this->requestFactory = $requestFactory;
	}

	protected function doRequest(EndpointInterface $endpoint): ResponseInterface
	{
		$endpointUri = $this->serverConfiguration->getBaseUri() . $endpoint->getPath();
		$request = $this->requestFactory->createRequest($endpoint->getMethod(), $endpointUri);

		$uri = $request->getUri();

		$queryParams = $endpoint->getQueryParams();
		$bodyParams = $endpoint->getRequestBody();
		if ($queryParams) {
			$uri = $uri->withQuery(http_build_query($queryParams));
		}
		if ($bodyParams) {
			$body = $request->getBody();
			$body->rewind();
			$body->write((string)json_encode($bodyParams));
			$body->rewind();
			$request = $request->withHeader('Content-Type', 'application/json');
			$request = $request->withBody($body);
		}
		$request = $request->withUri($uri);

		$request = $this->serverConfiguration->configureAuthentication($request);

		try {
			return $this->client->sendRequest($request);
		}
		catch (ClientExceptionInterface $e) {
			$this->logger && $this->logger->info('Error talking to api', [
				'exception' => $e,
			]);
		}

		throw new RequestFailed('Failed to query api', 0, $e ?? null);
	}

	protected function parseResponse(ResponseInterface $response)
	{
		//todo implement
		return null;
	}
}
