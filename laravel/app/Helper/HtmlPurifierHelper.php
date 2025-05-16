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
        $config->set('HTML.Allowed', 'a[href|title],code,strong,i');

        $this->purifier = new HTMLPurifier($config);
    }

    public function purify(?string $html): ?string
    {
        if ($html) {
            return $this->purifier->purify($html);
        }
        return null;
    }
}
