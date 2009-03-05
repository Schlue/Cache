<?php
/**
 * The Horde_Cache_Memcache:: class provides a memcached implementation of the
 * Horde caching system.
 *
 * Copyright 2006-2007 Duck <duck@obala.net>
 * Copyright 2007-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author   Duck <duck@obala.net>
 * @author   Michael Slusarz <slusarz@horde.org>
 * @category Horde
 * @package  Horde_Cache
 */
class Horde_Cache_Memcache extends Horde_Cache_Base
{
    /**
     * Horde_memcache object.
     *
     * @var Horde_Memcache
     */
    protected $_memcache;

    /**
     * Cache results of expire() calls (since we will get the entire object
     * on an expire() call anyway).
     */
    protected $_expirecache = array();

    /**
     * Construct a new Horde_Cache_Memcache object.
     *
     * @param array $params  Parameter array.
     */
    public function __construct($params = array())
    {
        $this->_memcache = &Horde_Memcache::singleton();

        parent::__construct($params);
    }

    /**
     * Attempts to retrieve cached data from the memcache and return it to
     * the caller.
     *
     * @param string $key        Cache key to fetch.
     * @param integer $lifetime  Lifetime of the data in seconds.
     *
     * @return mixed  Cached data, or false if none was found.
     */
    public function get($key, $lifetime = 1)
    {
        if (isset($this->_expirecache[$key])) {
            return $this->_expirecache[$key];
        }

        $key_list = array($key);
        if (!empty($lifetime)) {
            $key_list[] = $key . '_e';
        }

        $res = $this->_memcache->get($key_list);

        if ($res === false) {
            unset($this->_expirecache[$key]);
        } else {
            // If we can't find the expire time, assume we have exceeded it.
            if (empty($lifetime) ||
                (($res[$key . '_e'] !== false) && ($res[$key . '_e'] + $lifetime > time()))) {
                $this->_expirecache[$key] = $res[$key];
            } else {
                $res[$key] = false;
                $this->expire($key);
            }
        }

        return $res[$key];
    }

    /**
     * Attempts to store data to the memcache.
     *
     * @param string $key        Cache key.
     * @param mixed $data        Data to store in the cache.
     * @param integer $lifetime  Data lifetime.
     *
     * @return boolean  True on success, false on failure.
     */
    public function set($key, $data, $lifetime = null)
    {
        $lifetime = $this->_getLifetime($lifetime);
        return ($this->_memcache->set($key . '_e', time(), $lifetime) === false)
            ? false
            : $this->_memcache->set($key, $data, $lifetime);
    }

    /**
     * Checks if a given key exists in the cache.
     *
     * @param string $key        Cache key to check.
     * @param integer $lifetime  Lifetime of the key in seconds.
     *
     * @return boolean  Existence.
     */
    public function exists($key, $lifetime = 1)
    {
        return ($this->_get($key, $lifetime) !== false);
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
        unset($this->_expirecache[$key]);
        $this->_memcache->delete($key . '_e');
        return $this->_memcache->delete($key);
    }

}
