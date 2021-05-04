<?php

namespace CloudSystems\CloudTranscribe\Actions;

use CloudSystems\CloudTranscribe\Exceptions\BaseException;
use CloudSystems\CloudTranscribe\Exceptions\ValidationException;


trait ManagesJobs
{


	/**
	 * List all jobs
	 *
	 * @param  array $params Optional query parameters
	 * @return array of jobs.
	 *
	 */
	public function getJobs(array $params = [])
	{
		$query = [];
		$query = array_merge($query, $params);
		$query = array_filter($query, 'strlen');
		$results = $this->get("jobs", $query);
		return $results ? $results['data'] : [];
	}


	/**
	 * Get single job
	 *
	 * @param integer $params Get ID
	 * @return array of Job data.
	 *
	 */
	public function getJob($jobId)
	{
		$result = $this->get("jobs/{$jobId}");
		return $result ? $result['data'] : false;
	}


	/**
	 * Create a new job from a file.
	 *
	 * @param string|resource $file		Path to file or file resource.
	 * @param array $settings		Array of supported settings (channel_identification, show_speaker_labels, max_speaker_labels, lang)
	 *
	 */
	public function createJob($file, $settings = [])
	{
		$fh = null;

		switch (true) {
			case is_string($file):
				if ( ! is_file($file)) throw new BaseException("File is not a file.");
				$fh = fopen($file, 'r');
				break;
			case is_resource($file):
				$fh = $file;
				break;
		}

		if ( ! $fh) throw new BaseException("Could not open file for sending.");

		$fields = [];

		$fields[] = ['name' => 'file', 'contents' => $fh];

		if (isset($settings['channel_identification']) && $settings['channel_identification'] === true) {
			$fields[] = ['name' => 'channel_identification', 'contents' => 'true'];
		}

		if (isset($settings['show_speaker_labels']) && $settings['show_speaker_labels'] === true) {
			$fields[] = ['name' => 'show_speaker_labels', 'contents' => 'true'];
		}

		if (isset($settings['max_speaker_labels']) && $settings['max_speaker_labels'] === true) {
			$fields[] = ['name' => 'max_speaker_labels', 'contents' => (int) $settings['max_speaker_labels']];
		}

		if (isset($settings['lang']) && $settings['lang'] === true) {
			$fields[] = ['name' => 'lang', 'contents' => $settings['lang']];
		}

		$result = $this->post('jobs', [ 'multipart' => $fields ]);

		return $result ? $result['data'] : false;
	}


}
