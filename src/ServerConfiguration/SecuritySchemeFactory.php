<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Stefna\OpenApiRuntime\Exceptions\UnknownSecuritySchema;

final class SecuritySchemeFactory
{
	/**
	 * @return SecurityScheme
	 */
	public static function createFromSchemaArray(string $name, array $schema): SecurityScheme
	{
		if ($schema['type'] === 'apiKey') {
			return ApiKeySecurityScheme::createFromSchemaArray($name, $schema);
		}
		if ($schema['type'] === 'http') {
			return HttpSecurityScheme::createFromSchemaArray($name, $schema);
		}
		throw new UnknownSecuritySchema($name);
	}
}
