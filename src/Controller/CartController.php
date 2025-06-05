<?php
namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private function getCart(SessionInterface $session): Cart
    {
        $cartData = $session->get('cart', []);
        return !empty($cartData) ? Cart::fromArray($cartData) : new Cart();
    }

    private function saveCart(SessionInterface $session, Cart $cart)
    {
        $session->set('cart', $cart->toArray());
    }

    /**
     * @Route("/cart", name="cart_view", methods={"GET"})
     */
    public function view(Request $request, SessionInterface $session): Response
    {
        $cart = $this->getCart($session);
        $products = $cart->getProducts();
        $totals = $cart->getTotals();

        return $this->render('cart.html.twig', [
            'products' => $products,
            'totals' => $totals
        ]);
    }

    /**
     * @Route("/cart/add", name="cart_add", methods={"POST"})
     */
    public function add(Request $request, SessionInterface $session): Response
    {
        $cart = $this->getCart($session);

        $productId = $request->request->get('productId');
        $productName = $request->request->get('productName');
        $unitPrice = $request->request->get('unitPrice');
        $imageUrl = $request->request->get('imageUrl');
        $quantity = (int) $request->request->get('quantity', 1);

        $product = new Product();
        $product->productId = $productId;
        $product->productName = $productName;
        $product->unitPrice = $unitPrice;
        $product->imageUrl = $imageUrl;

        $cart->addProduct($product, $quantity);
        $this->saveCart($session, $cart);

        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart/remove", name="cart_remove", methods={"POST"})
     */
    public function remove(Request $request, SessionInterface $session): Response
    {
        $cart = $this->getCart($session);

        $productId = $request->request->get('productId');
        $cart->removeProduct($productId);

        $this->saveCart($session, $cart);

        return $this->redirectToRoute('cart_view');
    }
}
