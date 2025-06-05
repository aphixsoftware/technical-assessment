<?php

namespace App\Entity;


class Cart
{
    private $items = []; // ['productId' => ['product' => Product, 'quantity' => int]]

    private $totals = [
        'net' => 0.0,
        'tax' => 0.0,
        'gross' => 0.0
    ];

    public function addProduct(Product $product, int $quantity = 1)
    {
        $id = $product->productId;
        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] += $quantity;
        } else {
            $this->items[$id] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
        $this->recalculate();
    }


    public function removeProduct($productId)
    {
        unset($this->items[$productId]);
        $this->recalculate();
    }

    public function getProducts()
    {
        $products = [];
        foreach ($this->items as $item) {
            $product = $item['product'];
            $product->quantity = $item['quantity'];
            $products[] = $product;
        }
        return $products;
    }


    public function getTotals(): array
    {
        return $this->totals;
    }

    public function recalculate(float $taxRate = 0.2): void
    {
        $netTotal = 0.0;
        foreach ($this->items as $item) {
            $netTotal += $item['product']->unitPrice * $item['quantity'];
        }
        $taxTotal = $this->calculateTax($taxRate, $netTotal);
        $grossTotal = $netTotal + $taxTotal;
        $this->totals = [
            'net' => $netTotal,
            'tax' => $taxTotal,
            'gross' => $grossTotal
        ];
    }

    public function calculateTax(float $taxRate, float $netTotal)
    {
        return $netTotal * $taxRate;
    }


    // For session storage
    public function toArray()
    {
        $data = [];
        foreach ($this->items as $id => $item) {
            $data[$id] = [
                'product' => [
                    'productId' => $item['product']->productId,
                    'productName' => $item['product']->productName,
                    'unitPrice' => $item['product']->unitPrice,
                    'imageUrl' => $item['product']->imageUrl,
                ],
                'quantity' => $item['quantity']
            ];
        }
        return [
            'items' => $data,
            'totals' => $this->totals
        ];
    }


    public static function fromArray(array $data)
    {
        $cart = new self();
        if (isset($data['items'])) {
            foreach ($data['items'] as $id => $item) {
                $product = new Product();
                $product->productId = $item['product']['productId'];
                $product->productName = $item['product']['productName'];
                $product->unitPrice = $item['product']['unitPrice'];
                $product->imageUrl = $item['product']['imageUrl'];
                $cart->addProduct($product, $item['quantity']);
            }
            if (isset($data['totals'])) {
                $cart->totals = $data['totals'];
            } else {
                $cart->recalculate();
            }
        } else {
            // Backward compatibility for old format
            foreach ($data as $id => $item) {
                $product = new Product();
                $product->productId = $item['product']['productId'];
                $product->productName = $item['product']['productName'];
                $product->unitPrice = $item['product']['unitPrice'];
                $product->imageUrl = $item['product']['imageUrl'];
                $cart->addProduct($product, $item['quantity']);
            }
            $cart->recalculate();
        }
        return $cart;
    }
}
