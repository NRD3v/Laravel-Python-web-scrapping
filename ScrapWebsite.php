<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ScrapeWebsite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $url;
    private $markup;

    public function __construct($url, $markup)
    {
        $this->url = $url;
        $this->markup = $markup;
    }

    public function handle()
    {
        // Launch an HTTP request towards the website with Guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->url);

        // Extract data with Python-Shell (composer require anlutro/l4-settings)
        $process = new Process(['python', 'scrap.py', $response->getBody(), $this->markup]);
        $process->run();

        // Manage Python-Shell processus errors
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Get resulats from Python-Shell processus
        $result = $process->getOutput();

        // TODO: persist data into DB
        \Log::info('Scraped data: ' . $result);
    }
}
