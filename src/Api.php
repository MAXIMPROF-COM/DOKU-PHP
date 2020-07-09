<?php

namespace Maximprof\Doku;

use GuzzleHttp\Client;

/**
 * Class Api
 * Api for DOKU communications
 *
 * @package Maximprof\Doku
 */
class Api {

	public static $proxy = '';

	/**
	 * @param $data
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public static function prePayment($data) {
		$data['req_basket'] = Library::formatBasket($data['req_basket']);

		return self::getResponse(Doku::getPrePaymentUrl(), $data);
	}

	/**
	 * @param $data
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public static function payment($data) {
		$data['req_basket'] = Library::formatBasket($data['req_basket']);

		return self::getResponse(Doku::getPaymentUrl(), $data);
	}

	/**
	 * @param $data
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public static function directPayment($data) {
		return self::getResponse(Doku::getDirectPaymentUrl(), $data);
	}

	/**
	 * @param $data
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public static function generatePaycode($data) {
		return self::getResponse(Doku::getGenerateCodeUrl(), $data);
	}

	/**
	 * @param $url
	 * @param $data
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	private static function getResponse($url, $data) {
		$client = new Client();
		$options = [
			'form_params' => ['data' => json_encode($data)],
		];
		if ($proxy != '') {
			$options['proxy'] = $proxy;
		}
		$response = $client->post($url, $options);
		return $response;
	}
}
