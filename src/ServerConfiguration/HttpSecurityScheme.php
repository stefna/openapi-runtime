<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Psr\Http\Message\RequestInterface;

final class HttpSecurityScheme implements SecurityScheme
{
	/** @var string */
	private $ref;
	/** @var string */
	private $type;
	/** @var string */
	private $schema;

	public static function createFromSchemaArray(string $name, array $schema)
	{
		return new self($name, $schema['type'], $schema['schema']);
	}

	public function __construct(string $name, string $type, string $schema)
	{
		$this->ref = $name;
		$this->type = $type;
		$this->schema = $schema;
	}

	public function getRef(): string
	{
		return $this->ref;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function configure(RequestInterface $request, string $securityValue): RequestInterface
	{
		if ($this->schema === 'basic') {
			return $request->withHeader('Authorization', 'Basic ' . $securityValue);
		}
		if ($this->schema === 'bearer') {
			return $request->withHeader('Authorization', 'Bearer ' . $securityValue);
		}
		return $request;
	}
}
