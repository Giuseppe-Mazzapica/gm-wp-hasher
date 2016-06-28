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

use Gm\WpHasher\WpHasher;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license https://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero v1.0 Universal
 * @package gm-wp-hasher
 */
final class CustomGensalt implements GensaltInterface
{
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
        $output = '$P$';
        $output .= WpHasher::CHARS[min($this->iterations + 5, 30)];
        $output .= WpHasher::encode64($this->random, 6);

        return $output;
    }
}
