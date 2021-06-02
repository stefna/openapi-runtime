<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

trait MockServiceTrait
{
	/** @var \Closure|null */
	private $executeHandler;
	/** @var array<string, ResponseInterface> */
	private $responseMap = [];
	/** @var ResponseFactoryInterface */
	private $responseFactory;
	/** @var StreamFactoryInterface */
	private $streamFactory;

	public function setExecuteHandler(\Closure $handler)
	{
		$this->executeHandler = $handler;
	}

	public function addToResponseMap(string $path, ResponseInterface $response): self
	{
		$this->responseMap[$path] = $response;
		return $this;
	}

	protected function executeRequest(RequestInterface $request): ResponseInterface
	{
		if (!$this->responseFactory) {
			$factory = new Factory();
			$this->responseFactory = $factory->createResponseFactory();
			$this->streamFactory = $factory->createStreamFactory();
		}

		if ($this->executeHandler) {
			$handler = \Closure::bind($this->executeHandler, $this);
			return $handler($request);
		}
		$pathKey = $request->getUri()->getPath();
		if (array_key_exists($pathKey, $this->responseMap)) {
			return $this->responseMap[$pathKey];
		}

		throw new class ('No handler or response found for path') extends \RuntimeException implements ClientExceptionInterface {};
	}

	private function createResponseFromArray(array $data): ResponseInterface
	{
		$stream = $this->streamFactory->createStream((string)json_encode($data));
		return $this->responseFactory->createResponse()->withBody($stream);
	}
}
