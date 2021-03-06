<?php
namespace Common\Utils;

use Common\Persistence\UtilsSession;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class Captcha
{
    /**
     * 图片验证码
     * @link https://github.com/Gregwar/Captcha
     *
     * phraseBuilder 里面的数字代表出现几个字符
     * setMaxBehindLines 图片数字后面的遮盖线
     * setMaxFrontLines 图片数字前面的遮盖线
     * output 中的数字代表图片质量
     */
    public static function render()
    {
        $phraseBuilder = new PhraseBuilder(4);
        $builder = new CaptchaBuilder(null, $phraseBuilder);
        $builder->setMaxBehindLines(1);
        $builder->setMaxFrontLines(1);
        $builder->build();

        self::storePhrase($builder->getPhrase());

        header('Content-type: image/jpeg');
        $builder->output(100);
    }

    private static function storePhrase(string $phrase) : bool
    {
        $session = new UtilsSession();
        return $session->save('captcha', $phrase);
    }

    public static function validate(string $phrase)
    {
        $session = new UtilsSession();
        return $phrase == $session->get('captcha');
    }
}
