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
	 *
	 */
	public function createJob($file)
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

		$result = $this->post('jobs', [
			'multipart' => [
				[ 'name' => 'file', 'contents' => $fh ],
			]
		]);

		return $result ? $result['data'] : false;
	}


}
