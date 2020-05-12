<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Psr\Http\Message\RequestInterface;
use Stefna\OpenApiRuntime\ServerConfigurationInterface;

abstract class AbstractServerConfiguration implements ServerConfigurationInterface
{
	abstract protected function getSecurityValue(): string;

	abstract protected function getSecuritySchema(string $ref): ?SecurityScheme;

	public function configureAuthentication(RequestInterface $request, string $securitySchemaRef): RequestInterface
	{
		$security = $this->getSecuritySchema($securitySchemaRef);
		if (!$security) {
			return $request;
		}
		return $security->configure($request, $this->getSecurityValue());
	}
}
