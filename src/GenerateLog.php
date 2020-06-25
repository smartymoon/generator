<?php
namespace Smartymoon\Generator;

class GenerateLog {
    static $content = '';

    static public function record($content)
    {
        self::$content .= $content."\n";
    }
}