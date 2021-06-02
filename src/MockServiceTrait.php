<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait MockServiceTrait
{
	/** @var \Closure|null */
	private $executeHandler;
	/** @var array<string, ResponseInterface> */
	private $responseMap = [];

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
}
