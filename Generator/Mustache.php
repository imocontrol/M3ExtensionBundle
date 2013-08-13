<?php
/*
 * This file is part of the IMOControl project and is a fork of the SonataEasyExtensBundle.
 * which is (c) by Thomas Rabaix <thomas.rabaix@sonata-project.org>.
 * 
 * Modfications done and copyright by:
 * (c) Michael Ofner <michael@imocontrol.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace IMOControl\M3\ExtensionBundle\Generator;

class Mustache
{
    /**
     * @param       $string
     * @param array $parameters
     *
     * @return mixed
     */
    public static function replace($string, array $parameters)
    {
        $replacer = function ($match) use ($parameters) {
            return isset($parameters[$match[1]]) ? $parameters[$match[1]] : $match[0];
        };

        return preg_replace_callback('/{{\s*(.+?)\s*}}/', $replacer, $string);
    }

    /**
     * @param string $file
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function replaceFromFile($file, array $parameters)
    {
        return self::replace(file_get_contents($file), $parameters);
    }
}
