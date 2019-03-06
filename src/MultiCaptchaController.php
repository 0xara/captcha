<?php

namespace Mews\Captcha;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class CaptchaController
 * @package Mews\Captcha
 */
class MultiCaptchaController extends Controller
{

    /**
     * get CAPTCHA
     *
     * @param Captcha $captcha
     * @param Request $request
     * @param string $config
     * @return \Intervention\Image\ImageManager->response
     */
	public function getCaptcha(Captcha $captcha, Request $request, $config = 'default')
	{
	    if(!$name = $request->input('_ctk'))
        {
            abort(203);
        }

		if (ob_get_contents())
		{
			ob_clean();
		}
		return $captcha->create($config, $name);
	}

	/**
	 * get CAPTCHA api
	 *
	 * @param \Mews\Captcha\Captcha $captcha
	 * @param string $config
	 * @return \Intervention\Image\ImageManager->response
	 */
	public function getCaptchaApi(Captcha $captcha, $config = 'default')
	{
		return $captcha->create($config, true);
	}

}
