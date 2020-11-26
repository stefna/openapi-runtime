<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Stefna\OpenApiRuntime\ServerConfiguration\SecurityValueInterface;

interface WriteableServerConfigurationInterface extends ServerConfigurationInterface
{
	public function setSecurityValue(string $ref, SecurityValueInterface $value): void;
}
