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
	private $scheme;

	public static function createFromSchemeArray(string $name, array $scheme)
	{
		return new self($name, $scheme['type'], $scheme['schema']);
	}

	public function __construct(string $name, string $type, string $schema)
	{
		$this->ref = $name;
		$this->type = $type;
		$this->scheme = $schema;
	}

	public function getRef(): string
	{
		return $this->ref;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function configure(RequestInterface $request, ?SecurityValueInterface $securityValue): RequestInterface
	{
		if (!$securityValue) {
			//todo log?
			return $request;
		}
		if ($this->scheme === 'basic') {
			return $request->withHeader('Authorization', 'Basic ' . $securityValue->toString());
		}
		if ($this->scheme === 'bearer') {
			return $request->withHeader('Authorization', 'Bearer ' . $securityValue->toString());
		}
		return $request;
	}
}
