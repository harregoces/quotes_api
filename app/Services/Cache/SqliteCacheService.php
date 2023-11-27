<?php

namespace App\Services\Cache;
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
     *              Check if expiration_time is greater than 30 seconds
     * Parameter: string $key: key to fetch
     * Parameter: int $userid: userid to fetch
     * @param string $key
     * @param int $userid
     * @return string|null
     */
    public function get(string $key, int $userid): ?string
    {
        $this->clearExpired();
        $cache = Cache::where([
            'key' => $key,
            'userid' => $userid,
            ['expiration_time', '>=', time()]
        ])->first();
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
            $cache->expiration_time = time() + 30;
            $cache->save();
        } else {
            Cache::create(['key' => $key, 'userid' => $userid, 'value' => $value, 'expiration_time' => time() + 30]);
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

    /**
     * Clear cache key with expiration_time less than current time
     */
    private function clearExpired(): void
    {
        Cache::where('expiration_time', '<', time())->delete();
    }
}
