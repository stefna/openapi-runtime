<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Message\ResponseInterface;

interface ModelInterface extends RequestBodyInterface
{
	/**
	 * @return static
	 */
	public static function fromResponse(array $data, ResponseInterface $response);
}
