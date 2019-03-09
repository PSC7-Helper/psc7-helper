<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 *
 * @param mixed $text
 */

/**
 * __
 * Use __('hello_world') to translate yout text to "Hallo Welt"
 * It is necessary that the global array LANG contains the text to be translated beforehand.
 *
 * @param string $text
 * @param string $text
 *
 * @return string
 * @return string
 */
function __($text)
{
    if (isset($GLOBALS['LANG']) && array_key_exists($text, $GLOBALS['LANG'])) {
        return $GLOBALS['LANG'][$text];
    }

    return $text;
}

/**
 * dunp.
 */
function dump()
{
    echo '<pre>';
    foreach (func_get_args() as $arg) {
        if (is_array($arg) || is_object($arg)) {
            print_r($arg);
        } else {
            var_dump($arg);
        }
    }
    echo '</pre>';
}
