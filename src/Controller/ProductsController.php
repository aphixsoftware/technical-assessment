<?php
// src/Controller/ProductsController.php
namespace App\Controller;

use App\Framework\Singleton;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends AbstractController
{

    public function index(): Response
    {
        $client = HttpClient::create();

        $response = $client->request(
            'GET',
            'https://63187261f6b281877c6c9805.mockapi.io/api/v1/products'
        );

        $results = $response->toArray();

        return $this->render(
            'products.html.twig',
            ["products" => $results, "features" => Singleton::getInstance()->features]
        );
    }
}
