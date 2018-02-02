<?php

interface ProductRepositoryInterface
{
    /**
     * @return array|Product[]
     */
    public function findAll(): array;
}
