<?php

namespace Mews\Captcha;

use Illuminate\Support\ServiceProvider;

/**
 * Class CaptchaServiceProvider
 * @package Mews\Captcha
 */
class MultiCaptchaServiceProvider extends ServiceProvider {

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        // Publish configuration files
        $this->publishes([
            __DIR__.'/../config/captcha.php' => config_path('captcha.php')
        ], 'config');

        // HTTP routing
        if (strpos($this->app->version(), 'Lumen') !== false) {
	        $this->app->get('captcha[/api/{config}]', 'Mews\Captcha\LumenMultiCaptchaController@getCaptchaApi');
	        $this->app->get('captcha[/{config}]', 'Mews\Captcha\LumenMultiCaptchaController@getCaptcha');
        } else {
            if ((double) $this->app->version() >= 5.2) {
	            $this->app['router']->get('captcha/api/{config?}', '\Mews\Captcha\MultiCaptchaController@getCaptchaApi')->middleware('web');
	            $this->app['router']->get('captcha/{config?}', '\Mews\Captcha\MultiCaptchaController@getCaptcha')->middleware('web');
            } else {
	            $this->app['router']->get('captcha/api/{config?}', '\Mews\Captcha\MultiCaptchaController@getCaptchaApi');
	            $this->app['router']->get('captcha/{config?}', '\Mews\Captcha\MultiCaptchaController@getCaptcha');
            }
        }

	    // Validator extensions
	    $this->app['validator']->extend('captcha', function($attribute, $value, $parameters, $validator)
	    {
            $name = array_get($validator->getData(), config('captcha.token_name','_ctk'), null);

		    return captcha_check($value, $name);
	    });

	    // Validator extensions
	    $this->app['validator']->extend('captcha_api', function($attribute, $value, $parameters)
	    {
		    return captcha_api_check($value, $parameters[0]);
	    });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge configs
        $this->mergeConfigFrom(
            __DIR__.'/../config/captcha.php', 'captcha'
        );

        // Bind captcha
        $this->app->bind('captcha', function($app)
        {
            return new MultiCaptcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }

}
