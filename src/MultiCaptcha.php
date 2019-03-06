<?php
/**
 * Created by PhpStorm.
 * User: Alireza
 * Date: 3/6/2019
 * Time: 11:01 AM
 */

namespace Mews\Captcha;


class MultiCaptcha extends Captcha
{

    protected $name;

    /**
     * @param string $config
     * @param null $name
     * @param bool $api
     * @return \Intervention\Image\ImageManager
     */
    public function create($config = 'default', $name = null, $api = false)
    {
        $this->name = $name;

        return parent::create($config, $api);
    }

    /**
     * @return string
     */
    protected function generate()
    {
        $bag = parent::generate();

        $this->session->put('captchas.' . $this->name, [
            'sensitive' => $bag['sensitive'],
            'key'       => $bag['key']
        ]);

        return $bag;
    }

    /**
     * @param $value
     * @param null $name
     * @return bool
     */
    public function check($value, $name = null)
    {
        if(! $name) return false;

        $str = "captchas.{$name}";

        if ( ! $this->session->has($str))
        {
            return false;
        }

        $key = $this->session->get("{$str}.key");
        $sensitive = $this->session->get("{$str}.sensitive");

        if ( ! $sensitive)
        {
            $value = $this->str->lower($value);
        }


        $res = $this->hasher->check($value, $key);
        //  if verify pass,remove session
        if ($res) {
            $this->session->remove($str);
        }

        return $res;
    }

    public function src($config = null, $name = null)
    {
        return parent::src($config) . '&_ctk=' . self::makeToken($name);
    }

    /**
     * Generate captcha image html tag
     *
     * @param null $config
     * @param null $name
     * @param array $attrs HTML attributes supplied to the image tag where key is the attribute
     * and the value is the attribute value
     * @return string
     */
    public function img($config = null, $name = null, $attrs = [])
    {
        $attrs_str = '';
        foreach($attrs as $attr => $value){
            if ($attr == 'src'){
                //Neglect src attribute
                continue;
            }
            $attrs_str .= $attr.'="'.$value.'" ';
        }
        return '<img src="' . $this->src($config, $name) . '" '. trim($attrs_str).'>';
    }


    /**
     *
     *
     * @param string $name
     * @return string
     */
    public static function makeToken($name)
    {
        return md5(request()->fullUrl().($name ?: 'main'));
    }

}