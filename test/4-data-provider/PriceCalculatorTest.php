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
        $productRepositoryMock = $this->prophesize(ProductRepositoryInterface::class);
        $productRepositoryMock->findAll()->willReturn($products);
        $priceCalculator = new PriceCalculator($productRepositoryMock->reveal());

        $this->assertEquals($priceCalculator->computePrice(), $expectedPrice);
    }

    public function computePriceProvider()
    {
        return [
            ['1 produit' =>
                [
                    new Product(4.0)
                ], 4.0
            ],
            ['2 produits' =>
                [
                    new Product(4.0),
                    new Product(6.0)
                ], 10.0
            ],
        ];
    }
}
