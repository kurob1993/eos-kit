<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Message;
use GuzzleHttp\Client;

class SendNotifToSso implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $nik;
    protected $title;
    protected $body;
    protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($nik, $title, $body, $url)
    {
        $this->nik = $nik;
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $body = json_encode(
            [
                'nik' => $this->nik,
                "title" => $this->title,
                "body" =>  $this->body,
                "url" => $this->url,
                "module" => 'SMART'
            ]
        );

        $client = new Client();
        $response = $client->request('POST', config('emss.notif_sso.link'), [
            'body' => $body,
            'headers' => ['Content-Type' => 'application/json'],
        ])->getBody();
    }
}
