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

use Gm\WpHasher\Gensalt\ExtendedGensalt;
use Gm\WpHasher\WpHasher;
use Gm\WpHasher\Exception\HasherException;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license https://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero v1.0 Universal
 * @package gm-wp-hasher
 */
final class ExtendedDesHasher implements HasherInterface
{
    /**
     * @inheritdoc
     */
    public function available()
    {
        return defined('CRYPT_EXT_DES') && CRYPT_EXT_DES === 1;
    }

    /**
     * @inheritdoc
     */
    public function buildGensalt($iterations)
    {
        return new ExtendedGensalt(WpHasher::randomBytes(16), $iterations);
    }

    /**
     * @inheritdoc
     */
    public function hash($input, $gensalt)
    {
        if (! is_string($gensalt) || strlen($gensalt) < 3) {
            $gensalt = WpHasher::randomBytes(3);
        }

        $hash = crypt($input, $gensalt);

        if (strlen($hash) !== 60) {
            throw new HasherException(sprintf('Corrupted crypt result in %s.', __METHOD__));
        }

        return $hash;
    }
}
