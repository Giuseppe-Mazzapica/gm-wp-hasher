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
final class ExtendedGensalt implements GensaltInterface
{
    /**
     * @var string
     */
    private $input = '*';

    /**
     * @var int
     */
    private $iterations = 8;

    /**
     * @param string $input
     * @param int    $iterations
     */
    public function __construct($input, $iterations)
    {
        is_string($input) and $this->input = $input;
        is_numeric($iterations) and $this->iterations = (int)$iterations;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        $count = min($this->iterations + 8, 24);
        # This should be odd to not reveal weak DES keys, and the
        # maximum valid value is (2**24 - 1) which is odd anyway.
        $count = (1 << $count) - 1;

        $output = '_';
        $output .= WpHasher::CHARS[$count & 0x3f];
        $output .= WpHasher::CHARS[($count >> 6) & 0x3f];
        $output .= WpHasher::CHARS[($count >> 12) & 0x3f];
        $output .= WpHasher::CHARS[($count >> 18) & 0x3f];
        $output .= WpHasher::encode64($this->input, 3);

        return $output;
    }
}
