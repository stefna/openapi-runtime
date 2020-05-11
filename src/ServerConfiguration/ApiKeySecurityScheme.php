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

	public function getName(): string
	{
		return $this->name;
	}

	public function getIn(): string
	{
		return $this->in;
	}
}
