<?php

namespace CloudSystems\CloudTranscribe;

use GuzzleHttp\Client as HttpClient;

use CloudSystems\CloudTranscribe\Exceptions\BaseException;


class CloudTranscribe
{


	use MakesHttpRequests,
		Actions\ManagesWebhooks,
		Actions\ManagesJobs;


	/**
	 * API Key
	 *
	 * @var string
	 *
	 */
	private $apiKey;


	/**
	 * API Secret
	 *
	 * @var string
	 *
	 */
	private $apiSecret;


	/**
	 * The Guzzle HTTP Client instance.
	 *
	 * @var \GuzzleHttp\Client
	 *
	 */
	private $guzzle;


	/**
	 * Create a new instance
	 *
	 * @param  string $apiKey
	 * @param  string $apiSecret
	 * @param  \GuzzleHttp\Client $guzzle
	 * @return void
	 *
	 */
	public function __construct(array $config)
	{
		$this->init($config);
	}


	public function init(array $config)
	{
		$this->apiKey = $config['api_key'] ?? null;
		if (empty($this->apiKey)) {
			throw new BaseException("No API key provided.");
		}

		$this->apiSecret = $config['api_secret'] ?? null;
		if (empty($this->apiSecret)) {
			throw new BaseException("No API secret provided.");
		}

		$uri = $config['uri'] ?? null;
		if (empty($uri)) {
			throw new BaseException("No API URI provided.");
		}

		$this->webhookSignatureKey = $config['webhook_signature_key'] ?? null;

		$guzzleDefaults = [
			'base_uri' => $uri,
			'auth' => [$this->apiKey, $this->apiSecret],
			'timeout' => $config['timeout'] ?? 30,
			'allow_redirects' => false,
			'headers' => [
			  'Content-Type' => 'application/json',
			  'Accept' => 'application/json',
			],
		];

		$guzzleOptions = $config['guzzle_options'] ?? [];

		$this->guzzle = new HttpClient(array_merge($guzzleDefaults, $guzzleOptions));
	}


	public function ping()
	{
		return $this->get('ping');
	}


}
