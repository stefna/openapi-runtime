<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Psr\Http\Message\RequestInterface;
use Stefna\OpenApiRuntime\ServerConfigurationInterface;

abstract class AbstractServerConfiguration implements ServerConfigurationInterface
{
	abstract protected function getSecurityValue(): string;

	abstract protected function getSecuritySchema(): ?SecurityScheme;

	public function configureAuthentication(RequestInterface $request): RequestInterface
	{
		$security = $this->getSecuritySchema();
		if (!$security) {
			return $request;
		}

		if ($security->getIn() === 'header') {
			return $request->withHeader($security->getName(), $this->getSecurityValue());
		}

		//todo handle more types of security

		return $request;
	}
}
