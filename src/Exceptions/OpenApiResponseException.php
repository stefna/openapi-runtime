<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\Exceptions;

use Psr\Http\Message\ResponseInterface;

interface OpenApiResponseException extends OpenApiException
{
	public function getResponse(): ResponseInterface;
}
