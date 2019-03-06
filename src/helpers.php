<?php

if ( ! function_exists('captcha')) {

    /**
     * @param string $config
     * @param null $name
     * @return mixed
     */
    function captcha($config = 'default', $name = 'main')
    {
        return app('captcha')->create($config, $name);
    }
}

if ( ! function_exists('captcha_src')) {
    /**
     * @param string $config
     * @param null $name
     * @return string
     */
    function captcha_src($config = 'default', $name = 'main')
    {
        return app('captcha')->src($config, $name);
    }
}

if ( ! function_exists('captcha_img')) {

    /**
     * @param string $config
     * @param null $name
     * @return mixed
     */
    function captcha_img($config = 'default', $name = 'main')
    {
        return app('captcha')->img($config, $name);
    }
}

if ( ! function_exists('captcha_check')) {
    /**
     * @param $value
     * @param null $name
     * @return bool
     */
	function captcha_check($value, $name = 'main')
	{
		return app('captcha')->check($value, $name);
	}
}

if ( ! function_exists('captcha_api_check')) {
	/**
	 * @param $value
	 * @return bool
	 */
	function captcha_api_check($value, $key)
	{
		return app('captcha')->check_api($value, $key);
	}
}
