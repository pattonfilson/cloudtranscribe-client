<?php

namespace CloudSystems\CloudTranscribe\Actions;

use CloudSystems\CloudTranscribe\Exceptions\BaseException;
use CloudSystems\CloudTranscribe\Exceptions\WebhookException;


trait ManagesWebhooks
{


	/**
	 * Verify incoming webhook.
	 *
	 *
	 */
	public function verifyWebhook(array $headers, string $body)
	{
		if ( ! $this->webhookSignatureKey) {
			throw new WebhookException('No Webhook Signature Key configured.');
		}

		$headers = array_change_key_case($headers, CASE_LOWER);
		$ctwId = $headers['ctw-id'] ?? null;
		$ctwTimestamp = $headers['ctw-timestamp'] ?? null;
		$ctwSignature = $headers['ctw-signature'] ?? null;

		if (empty($ctwId)) throw new WebhookException('Header does not contain ctw-id value.');
		if (empty($ctwTimestamp)) throw new WebhookException('Header does not contain ctw-timestamp value.');
		if (empty($ctwSignature)) throw new WebhookException('Header does not contain ctw-signature value.');

		$sigSource = "{$ctwId}.{$ctwTimestamp}.{$body}";
		$calcSignature = base64_encode(hash_hmac('sha256', $sigSource, $this->webhookSignatureKey));
		if ($calcSignature !== $ctwSignature) {
			throw new WebhookException('Webhook signature failed validation.');
		}

		$leeway = 5;
		$now = time();
		$timeLo = $now - ($leeway * MINUTE);
		$timeHi = $now + ($leeway * MINUTE);

		if ($ctwTimestamp < $timeLo || $ctwTimestamp > $timeHi) {
			throw new WebhookException('Webhook timestamp is not in the allowed range.');
		}

		return $ctwId;
	}


}
