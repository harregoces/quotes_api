<?php

/**
 * sqliteCacheService.php
 * Description: This class implements the cacheServiceInterface
 * Author: Hernan Arregoces
 * implement: cacheServiceInterface
 */
namespace App\Http\Services;
use App\Http\Interfaces\CacheServiceInterface;
use App\Models\Cache;

/**
 * Class sqliteCacheService
 * Description: This class implements the cacheServiceInterface using the App\Models\Cache model, we use that table in sqlite to store the cache
 * Author: Hernan Arregoces
 */
class SqliteCacheService implements CacheServiceInterface
{
    /**
     * get
     * Description: This method fetches a value from cache table
     * Parameter: string $key: key to fetch
     * Parameter: int $userid: userid to fetch
     * @param string $key
     * @param int $userid
     * @return string|null
     */
    public function get(string $key, int $userid): ?string
    {
        $cache = Cache::where(['key' => $key, 'userid' => $userid])->first();
        if ($cache) {
            return $cache->value;
        }
        return null;
    }

    /**
     * set
     * Description: This method sets a value in cache
     * Parameter: string $key: key to set
     * Parameter: int $userid: userid to set
     * Parameter: string $value: value to set
     * @param string $key
     * @param int $userid
     * @param string $value
     * @return void
     */
    public function set(string $key, int $userid, string $value): void
    {
        $cache = Cache::where(['key' => $key, 'userid' => $userid])->first();
        if ($cache) {
            $cache->value = $value;
            $cache->save();
        } else {
            Cache::create(['key' => $key, 'userid' => $userid, 'value' => $value]);
        }
    }

    /**
     * clear
     * Description: This method cleans the cache for a given userid
     * Parameter: int $userid: userid to clean
     * @param string $key
     * @param int $userid
     * @return void
     */
    public function clear(string $key, int $userid): void
    {
        Cache::where(['key' => $key, 'userid' => $userid])->delete();
    }
}
