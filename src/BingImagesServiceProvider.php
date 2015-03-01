<?php

namespace Bing;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\ServiceProviderInterface;


class BingImagesServiceProvider implements ServiceProviderInterface {

	private $api = 'https://api.datamarket.azure.com/Bing/Search/v1/Image';
	private $appId;

	public function register (Application $app) {
		$app['bing.images'] = $app->protect(function ($query) use ($app) {

			$key = trim($app['bing.images.key']);
			if (empty($key)) {
				throw new \Exception("Bing: chave de aplicação não foi informada");
			}

			$this->setKey($key);
			return $this->getResponse($query);
		});
	}

	public function boot (Application $app) {

	}

	private function setKey ($key) {
		$this->appId = $key;
	}

	private function getKey () {
		return $this->appId;
	}

	/**
	 * Autenticação
	 * @param $key string Chave de autenticação
	 * @return array Cabeçalho de aplicação
	 */
	private function getAuth ($key) {
		$auth = base64_encode("{$key}:{$key}");
		$data = array(
			'http' => array(
				'request_fulluri' => true,
				'ignore_errors' => true,
				'header' => "Authorization: Basic {$auth}"
			)
		);
		return $data;
	}

	/**
	 * Contexto de streaming
	 * @return resource Stream Context
	 */
	private function getContext () {
		$key = $this->getKey();
		$data = $this->getAuth($key);
		$context = stream_context_create($data);
		return $context;
	}

	/**
	 * Resposta do Serviço
	 * @param $query string Parâmetro de busca
	 * @return mixed Resposta do serviço
	 */
	private function getResponse ($query) {
		$query = urlencode("'{$query}'");
		$context = $this->getContext();
		$requestUrl = "{$this->api}?\$format=json&Query={$query}";
		$response = file_get_contents($requestUrl, 0, $context);
		return $response;
	}

}