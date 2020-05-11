<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\Exceptions;

use Psr\Http\Message\ResponseInterface;

final class NotAuthorized extends \RuntimeException implements OpenApiResponseException
{
	/** @var ResponseInterface */
	private $response;

	public function __construct(?string $message, ResponseInterface $response)
	{
		parent::__construct($message ?? 'Not valid authorization');
		$this->response = $response;
	}

	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}
}
