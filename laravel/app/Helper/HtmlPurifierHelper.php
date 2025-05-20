<?php

namespace App\Helper;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlPurifierHelper
{
    protected HtmlPurifier $purifier;
    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
        $config->set('HTML.Allowed', 'p,a[href|title|target],code,strong,i');
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
        ]);

        $this->purifier = new HTMLPurifier($config);
    }

    public function purify(?string $html): ?string
    {
        if ($html) {
            $html = preg_replace_callback(
                '/<a[^>]+href="(?!https?:\/\/)([^"]+)"/i',
                function ($matches) {
                    $url = trim($matches[1]);

                    // Уникаємо вставки 'javascript:' або інших небезпечних штук
                    if (!preg_match('/^(javascript|data|vbscript):/i', $url)) {
                        $safeUrl = 'https://' . ltrim($url, '/');
                        return str_replace($matches[1], $safeUrl, $matches[0]);
                    }

                    // Видалити потенційно небезпечні посилання
                    return str_replace('href="' . $matches[1] . '"', '', $matches[0]);
                },
                $html
            );
            $html = $this->purifier->purify($html);
        }
        return $html;
    }
}
