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
     * Parameter: string $quote: quote to marshal
     * @param string $quote
     * @return array
     */
    public function marshal(string $quote): array;
}
