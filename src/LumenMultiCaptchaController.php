<?php

namespace Mews\Captcha;

use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Routing\Controller;

/**
 * Class CaptchaController
 * @package Mews\Captcha
 */
class LumenMultiCaptchaController extends Controller
{

    /**
     * get CAPTCHA
     *
     * @param \Mews\Captcha\Captcha $captcha
     * @param string $config
     * @return \Intervention\Image\ImageManager->response
     */
    public function getCaptcha(Captcha $captcha, Request $request, $config = 'default')
    {
        if(!$name = $request->input('_ctk'))
        {
            abort(203);
        }

        return $captcha->create($config, $name);
    }

}
