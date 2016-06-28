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

namespace Gm\WpHasher;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license https://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero v1.0 Universal
 * @package gm-wp-hasher
 */
class WpHasher
{
    const CHARS = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * @var string
     */
    private static $random;

    /**
     * @var int
     */
    private $iterations;

    /**
     * @var bool
     */
    private $forceMultipass;

    /**
     * @param int $length
     * @return string
     */
    public static function randomBytes($length)
    {
        self::$random or self::$random = microtime().uniqid(rand(), true);
        $length = is_numeric($length) ? (int)$length : 16;
        $output = '';
        if (@is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb'))) {
            $output = fread($fh, $length);
            fclose($fh);
        }

        if (strlen($output) >= $length) {
            return $output;
        }

        $output = '';
        $random = self::$random;
        for ($i = 0; $i < $length; $i += 16) {
            $random = md5(microtime().$random);
            $output .= pack('H*', md5($random));
        }
        $output = substr($output, 0, $length);

        return $output;
    }

    /**
     * @param string $input
     * @param int    $count
     * @return string
     */
    public static function encode64($input, $count)
    {
        $output = '';
        $i = 0;
        do {
            $value = ord($input[$i++]);
            $output .= self::CHARS[$value & 0x3f];
            if ($i < $count) {
                $value |= ord($input[$i]) << 8;
            }
            $output .= self::CHARS[($value >> 6) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            if ($i < $count) {
                $value |= ord($input[$i]) << 16;
            }
            $output .= self::CHARS[($value >> 12) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            $output .= self::CHARS[($value >> 18) & 0x3f];
        } while ($i < $count);

        return $output;
    }

    /**
     * @param int  $iterations
     * @param bool $forceMultipass
     */
    public function __construct($iterations = 8, $forceMultipass = true)
    {
        $this->iterations = ($iterations < 4 || $iterations > 31) ? $iterations : 8;
        $this->forceMultipass = $forceMultipass;
    }

    /**
     * @param string                                    $password
     * @param string|null                               $gensalt
     * @param \Gm\WpHasher\Hasher\HasherInterface|null $hasher
     * @return string
     */
    public function hash($password, $gensalt = null, Hasher\HasherInterface $hasher = null)
    {
        $hasher or $hasher = new Hasher\MultipassHasher();
        if (strlen($password) > 4096 || ! $hasher->available()) {
            return '*';
        }

        if (! is_string($gensalt) || ! $gensalt) {
            $gensaltObj = $hasher->buildGensalt($this->iterations);
            $gensalt = $gensaltObj->__toString();
        }

        try {
            $hash = $hasher->hash($password, $gensalt);
        } catch (Exception\HasherException $e) {
            $hash = '';
        }

        # Returning '*' on error is safe here, but would _not_ be safe
        # in a crypt(3)-like function used _both_ for generating new
        # hashes and for validating passwords against existing hashes.

        return $hash ? : '*';
    }

    /**
     * @param string                               $password
     * @param string                               $hash
     * @param \Gm\WpHasher\Hasher\HasherInterface $hasher
     * @return bool
     */
    public function check($password, $hash, Hasher\HasherInterface $hasher = null)
    {
        if (strlen($hash) <= 32) {
            return hash_equals($hash, md5($password));
        }

        if (strlen($password) > 4096) {
            return false;
        }

        $compare = $this->hash($password, $hash, $hasher);
        if ($compare[0] == '*') {
            $compare = crypt($password, $hash);
        }

        return $hasher && $hasher instanceof Hasher\MultipassHasher
            ? $compare === $hash
            : hash_equals($hash, $compare);
    }
}
