<?php

use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    /**
     * https://gist.github.com/jaredh159/b7ace6fc9e0e44532d1d#file-prophecy-php
     */
    public function testComputePrice()
    {
        // Todo : on mock la méthode findAll() de notre product repository

        $productRepositoryMock = $this->prophesize(ProductRepositoryInterface::class);
        $productRepositoryMock->findAll()->willReturn([
            new Product(10.0), new Product(15.0), new Product(5.0)
        ]);
        $priceCalculator = new PriceCalculator($productRepositoryMock->reveal());

        $this->assertEquals($priceCalculator->computePrice(), 30.0);
    }
}
