<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

interface ModelResponseFactoryInterface
{
	/**
	 * @return static
	 */
	public static function createFromResponse(array $data);
}
