<?php

use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    /**
     * https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.exceptions
     */
    public function testComputePriceOnProductNegativePriceThrowException()
    {
        // Todo : on teste qu'une exception \DomainException est levée si un produit à un prix négatif.
    }
}
