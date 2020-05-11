<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\ServerConfiguration;

use Psr\Http\Message\RequestInterface;

interface SecurityScheme
{
	public function getRef(): string;

	public function getName(): string;

	public function getIn(): string;
}
