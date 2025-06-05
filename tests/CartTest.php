<?php
// tests/CartTest.php

use PHPUnit\Framework\TestCase;
use App\Entity\Cart;
use App\Entity\Product;

class CartTest extends TestCase
{
    private function createProduct($id, $name = 'Test', $price = 10.0, $image = 'img.png')
    {
        $product = new Product();
        $product->productId = $id;
        $product->productName = $name;
        $product->unitPrice = $price;
        $product->imageUrl = $image;
        return $product;
    }

    public function testAddProduct()
    {
        $cart = new Cart();
        $product = $this->createProduct(1);
        $cart->addProduct($product, 2);
        $products = $cart->getProducts();
        $this->assertCount(1, $products);
        $this->assertEquals(2, $products[0]->quantity);
    }

    public function testRemoveProduct()
    {
        $cart = new Cart();
        $product = $this->createProduct(1);
        $cart->addProduct($product, 2);
        $cart->removeProduct(1);
        $products = $cart->getProducts();
        $this->assertCount(0, $products);
    }

    public function testRecalculateTotals()
    {
        $cart = new Cart();
        $cart->addProduct($this->createProduct(1, 'A', 20.0), 1);
        $cart->addProduct($this->createProduct(2, 'B', 30.0), 2);
        $totals = $cart->getTotals();
        $this->assertEquals(80.0, $totals['net']);
        $this->assertEquals(16.0, $totals['tax']); // 20% tax
        $this->assertEquals(96.0, $totals['gross']);
    }

    public function testToArrayAndFromArray()
    {
        $cart = new Cart();
        $cart->addProduct($this->createProduct(1, 'A', 5.0), 3);
        $array = $cart->toArray();
        $newCart = Cart::fromArray($array);
        $this->assertEquals($cart->getTotals(), $newCart->getTotals());
        $this->assertEquals($cart->toArray(), $newCart->toArray());
    }
}

