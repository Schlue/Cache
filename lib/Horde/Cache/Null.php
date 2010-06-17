<?php
/**
 * The Horde_Cache_Null:: class provides a null implementation of the Horde
 * caching system.
 *
 * Copyright 2006-2007 Duck <duck@obala.net>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author   Duck <duck@obala.net>
 * @category Horde
 * @package  Cache
 */
class Horde_Cache_Null extends Horde_Cache_Base
{
    /**
     * Attempts to retrieve a piece of cached data and return it to
     * the caller.
     *
     * @param string $key        Cache key to fetch.
     * @param integer $lifetime  Lifetime of the key in seconds.
     *
     * @return mixed  Cached data, or false if none was found.
     */
    public function get($key, $lifetime = 1)
    {
        return false;
    }

    /**
     * Attempts to store an object to the cache.
     *
     * @param string $key        Cache key (identifier).
     * @param mixed $data        Data to store in the cache.
     * @param integer $lifetime  Data lifetime.
     */
    public function set($key, $data, $lifetime = null)
    {
    }

    /**
     * Checks if a given key exists in the cache, valid for the given
     * lifetime.
     *
     * @param string $key        Cache key to check.
     * @param integer $lifetime  Lifetime of the key in seconds.
     *
     * @return boolean  Existence.
     */
    public function exists($key, $lifetime = 1)
    {
        return false;
    }

    /**
     * Expire any existing data for the given key.
     *
     * @param string $key  Cache key to expire.
     *
     * @return boolean  Success or failure.
     */
    public function expire($key)
    {
        return false;
    }

}
