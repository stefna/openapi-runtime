<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\Exceptions;

use Psr\Http\Message\ResponseInterface;

final class UnknownResponse extends \RuntimeException implements OpenApiResponseException
{
	/** @var ResponseInterface */
	private $response;

	public function __construct(ResponseInterface $response)
	{
		parent::__construct(sprintf('Unknown response (Status Code: %d)', $response->getStatusCode()));
		$this->response = $response;
	}

	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}
}
