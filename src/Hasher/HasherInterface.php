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

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license https://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero v1.0 Universal
 * @package gm-wp-hasher
 */
interface HasherInterface
{
    /**
     * @return bool
     */
    public function available();

    /**
     * @param int $iterations
     * @return \Gm\WpHasher\Gensalt\GensaltInterface
     */
    public function buildGensalt($iterations);

    /**
     * @param string $input
     * @param string $gensalt
     * @return string
     * @throws \Gm\WpHasher\Exception\HasherException
     */
    public function hash($input, $gensalt);
}
