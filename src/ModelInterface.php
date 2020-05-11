<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Message\ResponseInterface;

interface ModelInterface
{
	public function toRequestData(): array;

	/**
	 * @return static
	 */
	public static function fromResponse(array $data, ResponseInterface $response = null);
}
