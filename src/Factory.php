<?php declare(strict_types=1);

namespace Stefna\OpenApiRuntime;

use Psr\Http\Client\ClientInterface;
use Buzz\Browser as BuzzClient;
use Buzz\Client\Curl;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Laminas\Diactoros\RequestFactory as LaminasRequestFactory;
use Laminas\Diactoros\ResponseFactory as LaminasResponseFactory;
use Laminas\Diactoros\StreamFactory as LaminasStreamFactory;
use Nyholm\Psr7\Factory\Psr17Factory as NyholmFactory;
use Psr\Http\Message\StreamFactoryInterface;

class Factory implements FactoryInterface
{
	public function createPsr18Client(): ClientInterface
	{
		if (class_exists(BuzzClient::class)) {
			return $this->createBuzzClient();
		}

		throw new \RuntimeException('No request factory found. Try installing "kriswallsmith/buzz"');
	}

	private function createBuzzClient(): ClientInterface
	{
		$requestFactory = $this->createRequestFactory();
		$curl = new Curl($this->createResponseFactory(), [
			'curl' => [
				\CURLOPT_USERAGENT => 'Stefna Open Api Runtime 1.5.0',
			],
		]);
		return new BuzzClient($curl, $requestFactory);
	}

	public function createRequestFactory(): RequestFactoryInterface
	{
		if (class_exists(LaminasRequestFactory::class)) {
			return new LaminasRequestFactory();
		}
		if (class_exists(NyholmFactory::class)) {
			return new NyholmFactory();
		}

		throw new \RuntimeException('No request factory found. Try installing "laminas/laminas-diactoros"');
	}

	public function createResponseFactory(): ResponseFactoryInterface
	{
		if (class_exists(LaminasResponseFactory::class)) {
			return new LaminasResponseFactory();
		}
		if (class_exists(NyholmFactory::class)) {
			return new NyholmFactory();
		}

		throw new \RuntimeException('No response factory found. Try installing "laminas/laminas-diactoros"');
	}

	public function createStreamFactory(): StreamFactoryInterface
	{
		if (class_exists(LaminasStreamFactory::class)) {
			return new LaminasStreamFactory();
		}
		if (class_exists(NyholmFactory::class)) {
			return new NyholmFactory();
		}

		throw new \RuntimeException('No stream factory found. Try installing "laminas/laminas-diactoros"');
	}
}
