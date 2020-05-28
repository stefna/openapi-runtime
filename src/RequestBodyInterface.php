<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

interface RequestBodyInterface
{
	public function toRequestData(): array;
}
