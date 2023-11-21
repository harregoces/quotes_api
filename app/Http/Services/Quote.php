<?php

/**
 * Quote.php
 * Description: This class represents a quote
 * Author: Hernan Arregoces
 */

namespace App\Http\Services;

use JsonSerializable;

class Quote implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $quote;

    /**
     * @var string
     */
    private $author;

    /**
     * @var boolean
     */
    private $cached = false;

    /**
     * Quote constructor.
     * @param string $quote
     * @param string $author
     */
    public function __construct(string $quote, string $author, int $id = null)
    {
        $this->quote = $quote;
        $this->author = $author;
        $this->id = $id;
    }

    /**
     * getQuote
     * Description: This method returns the quote
     * @return string
     */
    public function getQuote(): string
    {
        return $this->quote;
    }

    /**
     * getAuthor
     * Description: This method returns the author
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * setCached
     * Description: This method sets the cached value
     * @param boolean $cached
     */
    public function setCached(bool $cached): void
    {
        $this->cached = $cached;
    }

    /**
     * getCached
     * Description: This method returns the cached value
     * @return boolean
     */
    public function getCached(): bool
    {
        return $this->cached;
    }

    /**
     * jsonSerialize
     */
    public function jsonSerialize(): array
    {
        $returnResponse = [
            'quote' => $this->quote,
            'author' => $this->author,
            'cached' => $this->cached
        ];

        if($this->id) {
            $returnResponse['id'] = $this->id;
        }
        return $returnResponse;
    }
}
