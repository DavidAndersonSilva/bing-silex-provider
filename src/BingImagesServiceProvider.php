<?php

namespace Bing;

use Bing\BingImagesService;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\ServiceProviderInterface;


class BingImagesServiceProvider implements ServiceProviderInterface {

	private $bingServiceImage;

	public function register (Application $app) {
		$app['bing.images'] = $app->share(function ($query) use ($app) {

			$key = trim($app['bing.images.key']);
			if (empty($key)) {
				throw new \Exception("Bing: chave de aplicação não foi informada");
			}

			return new BingImagesService($key);
		});
	}

	public function boot (Application $app) { }

}