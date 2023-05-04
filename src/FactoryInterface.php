<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * @deprecated
 */
interface FactoryInterface
{
	public function createPsr18Client(): ClientInterface;

	public function createRequestFactory(): RequestFactoryInterface;

	public function createResponseFactory(): ResponseFactoryInterface;
}
