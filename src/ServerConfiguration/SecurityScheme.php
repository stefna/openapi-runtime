<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Psr\Http\Message\RequestInterface;

interface SecurityScheme
{
	public function getRef(): string;

	public function getType(): string;

	public function configure(RequestInterface $request, string $securityValue): RequestInterface;

	/**
	 * @return static
	 */
	public static function createFromSchemeArray(string $name, array $scheme);
}
