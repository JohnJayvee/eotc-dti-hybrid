<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        // \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        //     // add your listeners (aka providers) here
        //     'SocialiteProviders\\Graph\\GraphExtendSocialite@handle',
        // ],
        'send-transaction-processor-email' => [
            'App\Laravel\Listeners\SendEmailProcessorTransactionListener'
        ],
        'send-transaction-processor' => [
            'App\Laravel\Listeners\SendProcessorTransactionListener'
        ],
        'send-sms-processor' => [
            'App\Laravel\Listeners\SendProcessorReferenceListener'
        ],
        'send-sms-approved' => [
            'App\Laravel\Listeners\SendApprovedReferenceListener'
        ],
        'send-sms-declined' => [
            'App\Laravel\Listeners\SendDeclinedReferenceListener'
        ],
        'send-sms' => [
            'App\Laravel\Listeners\SendReferenceListener'
        ],
        'send-application' => [
            'App\Laravel\Listeners\SendApplicationListener'
        ],
        'send-eorurl' => [
            'App\Laravel\Listeners\SendEorUrlListener'
        ],
        'send-email-declined' => [
            'App\Laravel\Listeners\SendDeclinedEmailListener'
        ],
        'send-email-approved' => [
            'App\Laravel\Listeners\SendApprovedEmailListener'
        ],
         'send-email-certificate' => [
            'App\Laravel\Listeners\SendCertificateListener'
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
