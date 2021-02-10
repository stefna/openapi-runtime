<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

final class SecurityValue implements SecurityValueInterface
{
	/** @var string */
	private $value;

	public static function basic(string $username, string $password): self
	{
		return new self(base64_encode($username . ':' . $password));
	}

	public static function bearer(string $token): self
	{
		if (stripos($token, 'bearer ') === 0) {
			$token = substr($token, 7);
		}
		return new self($token);
	}

	public static function apiKey(string $key): self
	{
		return new self($key);
	}

	public static function raw(string $key): self
	{
		return new self($key);
	}

	public static function empty(): self
	{
		return new self('');
	}

	public function __construct(string $value)
	{
		$this->value = $value;
	}

	public function toString(): string
	{
		return $this->value;
	}
}
