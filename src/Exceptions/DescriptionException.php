<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime\Exceptions;

use Psr\Http\Message\ResponseInterface;

class DescriptionException extends \RuntimeException implements OpenApiResponseException
{
	/** @var ResponseInterface */
	private $response;

	private $model;

	/**
	 * @return static
	 */
	public static function fromModel($model, string $message, ResponseInterface $response)
	{
		$self = new static($message, $response);
		$self->model = $model;
		return $self;
	}

	public function __construct(string $message, ResponseInterface $response)
	{
		parent::__construct($message);
		$this->response = $response;
	}

	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}

	public function getModel()
	{
		return $this->model;
	}
}
