<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Message\ResponseInterface;

/**
 * @deprecated use ModelResponseFactoryInterface
 */
interface ModelInterface extends RequestBodyInterface
{
	/**
	 * @return static
	 */
	public static function fromResponse(array $data, ResponseInterface $response);
}
