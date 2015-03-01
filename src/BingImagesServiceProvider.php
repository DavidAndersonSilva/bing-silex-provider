<?php

namespace Bing;

use BingImagesServiceProvider;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\ServiceProviderInterface;


class BingImagesServiceProvider implements ServiceProviderInterface {

	private $api = 'https://api.datamarket.azure.com/Bing/Search/v1/Image';
	private $bingServiceImage;

	public function register (Application $app) {
		$app['bing.images'] = $app->protect(function ($query) use ($app) {

			$key = trim($app['bing.images.key']);
			if (empty($key)) {
				throw new \Exception("Bing: chave de aplicação não foi informada");
			}

			$this->bingServiceImage = new BingImagesServiceProvider($key);
			return $this->getResponse($query);
		});
	}

	public function boot (Application $app) { }

	private function getResponse ($query) {
		return $this->getResponse($query);
	}

}