<?php
/*
 * This file is part of the gm-wp-hasher package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file contains code from "Portable PHP password hashing framework"
 * (http://www.openwall.com/phpass/) originally developed by Solar Designer <solar at openwall.com>
 * in 2004-2006, placed in the public domain, then revised in subsequent years by WordPress
 * (http://wordpress.org) developers, still public domain
 */

namespace Gm\WpHasher\Gensalt;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license https://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero v1.0 Universal
 * @package gm-wp-hasher
 */
final class BlowfishGensalt implements GensaltInterface
{
    const CHARS = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    /**
     * @var string
     */
    private $random = '*';

    /**
     * @var int
     */
    private $iterations = 8;

    /**
     * @param string $random
     * @param int    $iterations
     */
    public function __construct($random, $iterations)
    {
        is_string($random) and $this->random = $random;
        is_numeric($iterations) and $this->iterations = (int)$iterations;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        $output = '$2a$';
        $output .= chr(ord('0') + $this->iterations / 10);
        $output .= chr(ord('0') + $this->iterations % 10);
        $output .= '$';

        $i = 0;
        do {
            $c1 = ord($this->random[$i++]);
            $output .= self::CHARS[$c1 >> 2];
            $c1 = ($c1 & 0x03) << 4;
            if ($i >= 16) {
                $output .= self::CHARS[$c1];
                break;
            }

            $c2 = ord($this->random[$i++]);
            $c1 |= $c2 >> 4;
            $output .= self::CHARS[$c1];
            $c1 = ($c2 & 0x0f) << 2;

            $c2 = ord($this->random[$i++]);
            $c1 |= $c2 >> 6;
            $output .= self::CHARS[$c1];
            $output .= self::CHARS[$c2 & 0x3f];
        } while (1);

        return $output;
    }
}
