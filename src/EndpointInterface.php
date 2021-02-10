<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

interface EndpointInterface
{
	// not add due to bc
	//public function getHeaders(): array;
	public function getQueryParams(): array;
	public function getRequestBody(): array;
	public function getPath(): string;
	public function getMethod(): string;
	public function getDefaultSecurity(): string;
	public function getSecurity(): array;
}
