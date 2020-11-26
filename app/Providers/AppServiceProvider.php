<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Laravel\Services\CustomValidator;
use Illuminate\Support\Facades\Validator;
use Schema;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use URL;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        If(env('APP_ENV') !== 'local') { 
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });

        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
