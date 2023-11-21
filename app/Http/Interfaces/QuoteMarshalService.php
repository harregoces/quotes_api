<?php

/**
 * QuoteMarshalService.php
 * Description: Interface for all quote marshal services
 * Author: Hernan Arregoces
 */

namespace App\Http\Interfaces;

interface QuoteMarshalService
{
    /**
     * marshal
     * Description: This method marshals a quote
     * Parameter: string $quotesString: quote in string to marshal
     * @param string $quotesString
     * @return array
     */
    public function marshal(string $quotesString): array;
}
