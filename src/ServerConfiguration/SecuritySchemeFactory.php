<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Stefna\OpenApiRuntime\Exceptions\UnknownSecuritySchema;

final class SecuritySchemeFactory
{
	/**
	 * @return SecurityScheme
	 */
	public static function createFromSchemeArray(string $name, array $scheme): SecurityScheme
	{
		if ($scheme['type'] === 'apiKey') {
			return ApiKeySecurityScheme::createFromSchemeArray($name, $scheme);
		}
		if ($scheme['type'] === 'http') {
			return HttpSecurityScheme::createFromSchemeArray($name, $scheme);
		}
		throw new UnknownSecuritySchema($name);
	}
}
