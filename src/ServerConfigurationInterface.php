<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Message\RequestInterface;

interface ServerConfigurationInterface
{
	public function getBaseUri(): string;

	public function configureAuthentication(RequestInterface $request, string $securitySchema): RequestInterface;
}
