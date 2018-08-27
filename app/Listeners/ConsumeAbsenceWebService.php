<?php

namespace App\Listeners;

use App\Events\SendingAbsenceToSap;
use App\Mail\SendingToSapMail;
use App\Soap\Request\GetConversionAmount;
use App\Soap\Response\GetConversionAmountResponse;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ConsumeAbsenceWebService implements ShouldQueue
{
    public $queue = 'web-services';
    public $tries = 1;

    protected $soapWrapper;

    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
    }

    public function retryUntil()
    {
        return now()->addSeconds(10);
    }

    public function handle(SendingAbsenceToSap $event)
    {
        // add should not called TWICE
        $this->soapWrapper->add('Currency', function ($service) {
            $service
                ->wsdl('http://currencyconverter.kowabunga.net/converter.asmx?WSDL')
                ->trace(true)
                ->classmap([
                    GetConversionAmount::class,
                    GetConversionAmountResponse::class,
                ]);
        });

        $response = $this->soapWrapper->call('Currency.GetConversionAmount', [
            new GetConversionAmount('USD', 'IDR', '2018-08-26', '1'),
        ]);

        $objDemo = new \stdClass();
        $objDemo->demo_one = $response->GetConversionAmountResult;
        $objDemo->demo_two = $response->GetConversionAmountResult;
        $objDemo->sender = 'SenderUserName';
        $objDemo->receiver = 'ReceiverUserName';

        Mail::to("receiver@example.com")->send(new SendingToSapMail($objDemo));
    }

    public function failed(SendingAbsenceToSap $event, $exception)
    {
        //
    }
}
