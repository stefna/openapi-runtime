<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Psr\Http\Message\RequestInterface;

interface SecurityScheme
{
	public const NO_SECURITY = '__NO_SECURITY__';

	public function getRef(): string;

	public function getType(): string;

	public function configure(RequestInterface $request, ?SecurityValueInterface $securityValue): RequestInterface;

	/**
	 * @return static
	 */
	public static function createFromSchemeArray(string $name, array $scheme);
}
