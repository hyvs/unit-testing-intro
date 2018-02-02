<?php

class PriceCalculator
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function computePrice(): float
    {
        $products = $this->productRepository->findAll();

        $price = 0.0;
        foreach ($products as $product) {
            if ($product->getPrice() <= 0.0) {
                throw new \DomainException('Negative price are not allowed.');
            }

            $price += $product->getPrice();
        }

        return $price;
    }
}
