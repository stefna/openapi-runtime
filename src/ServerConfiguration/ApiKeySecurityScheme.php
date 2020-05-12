<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Psr\Http\Message\RequestInterface;

final class ApiKeySecurityScheme implements SecurityScheme
{
	/** @var string */
	private $ref;
	/** @var string */
	private $name;
	/** @var string */
	private $in;

	public static function createFromSchemeArray(string $name, array $scheme): self
	{
		return new self($name, $scheme['name'], $scheme['in']);
	}

	public function __construct(string $ref, string $name, string $in)
	{
		$this->ref = $ref;
		$this->name = $name;
		$this->in = $in;
	}

	public function getRef(): string
	{
		return $this->ref;
	}

	public function getType(): string
	{
		return 'apiKey';
	}

	public function configure(RequestInterface $request, string $securityValue): RequestInterface
	{
		if ($this->in === 'header') {
			return $request->withHeader($this->name, $securityValue);
		}

		//todo handle more types of security

		return $request;
	}
}
