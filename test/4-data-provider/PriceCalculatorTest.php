<?php

use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    /**
     * https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers
     *
     * @dataProvider computePriceProvider
     */
    public function testComputePrice(array $products, float $expectedPrice)
    {
        // Todo : on teste le calcul du prix d'un produit
        // Todo : on teste le calcul du prix de deux produits
    }
}
