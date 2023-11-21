<?php
/**
 * cacheServiceInterface.php
 * Description: This interface defines the contract for all cache services
 * Author: Hernan Arregoces
 */
namespace App\Http\Interfaces;

interface CacheServiceInterface
{

    const TEN_SECURE_QUOTES_KEY = 'ten_secure_quotes';
    const FIVE_QUOTES_KEY = 'five_quotes';
    const TODAY_QUOTE_KEY = 'today_quote';

    /**
     * get
     * Description: This method fetches a value from cache
     * Parameter: string $key: key to fetch
     * Parameter: int $userid: userid to fetch
     * @param string $key
     * @param int $userid
     * @return string|null
     */
    public function get(string $key, int $userid): ?string;

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
    public function set(string $key, int $userid, string $value): void;

    /**
     * clear
     * Description: This method clears a value from cache
     * Parameter: string $key: key to clear
     * Parameter: string $userid: userid to clear
     */
    public function clear(string $key, int $userid): void;
}
