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

        $this->assertEquals(10.0 /*$priceCalculator->computePrice()*/, 30.0);
    }
}
