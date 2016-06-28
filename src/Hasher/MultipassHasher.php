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

namespace Gm\WpHasher\Hasher;

use Gm\WpHasher\Gensalt\CustomGensalt;
use Gm\WpHasher\WpHasher;
use Gm\WpHasher\Exception\HasherException;


/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license https://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero v1.0 Universal
 * @package gm-wp-hasher
 */
final class MultipassHasher implements HasherInterface
{
    /**
     * @inheritdoc
     */
    public function available()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function buildGensalt($iterations)
    {
        return new CustomGensalt(WpHasher::randomBytes(16), $iterations);
    }

    /**
     * @inheritdoc
     */
    public function hash($input, $gensalt)
    {
        if (! is_string($gensalt) || strlen($gensalt) < 6) {
            $gensalt = WpHasher::randomBytes(6);
        }

        $hash = $this->encrypt($input, $gensalt);

        if (strlen($hash) !== 34) {
            throw new HasherException(sprintf('Corrupted crypt result in %s.', __METHOD__));
        }

        return $hash;
    }

    /**
     * @param string $input
     * @param string $gensalt
     * @return string
     */
    private function encrypt($input, $gensalt)
    {
        $output = '*0';
        if (substr($gensalt, 0, 2) == $output) {
            $output = '*1';
        }

        $id = substr($gensalt, 0, 3);
        # We use "$P$", phpBB3 uses "$H$" for the same thing
        if ($id != '$P$' && $id != '$H$') {
            return $output;
        }

        $count = strpos(WpHasher::CHARS, $gensalt[3]);
        if ($count < 7 || $count > 30) {
            return $output;
        }

        $count = 1 << $count;

        $salt = substr($gensalt, 4, 8);
        if (strlen($salt) != 8) {
            return $output;
        }

        $hash = md5($salt.$input, true);
        do {
            $hash = md5($hash.$input, true);
        } while (--$count);

        $output = substr($gensalt, 0, 12);
        $output .= WpHasher::encode64($hash, 16);

        return $output;
    }
}
