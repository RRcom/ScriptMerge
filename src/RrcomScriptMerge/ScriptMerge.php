<?php
namespace RrcomScriptMerge;

use RrcomScriptMerge\Minifier\CSSmin;
use RrcomScriptMerge\Minifier\JSMin;

class ScriptMerge {

    const TYPE_JAVASCRIPT = 0;
    const TYPE_CSS = 1;

    /**
     * Merge multiple script (css or javascript) into single file and optionally minify the output
     * @param int $type One of the type constant of RrcomScriptMerge/ScriptMerge
     * @param string $scriptFilesArray An Array of script absolute file location
     * @param string $outputFile The location and name of the output merge file
     * @param bool $minify Set to true to minify the output
     * @return int|bool Number of bytes written or false on failure
     */
    public function merge($type, $scriptFilesArray, $outputFile, $minify = false)
    {
        $final = '';
        foreach($scriptFilesArray as $script) {
            $file = realpath($script);
            if($file) {
                $final .= file_get_contents($file)."\n";
            }
        }
        if($minify) {
            if($type == self::TYPE_CSS) {
                $final = CSSmin::process($final);

            } elseif($type == self::TYPE_JAVASCRIPT) {
                $final = JSMin::minify($final);
            }
        }
        return file_put_contents($outputFile, $final);
    }

}