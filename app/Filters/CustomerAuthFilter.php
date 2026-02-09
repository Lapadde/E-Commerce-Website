<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CustomerAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if customer is logged in
        if (!session()->get('customer_logged_in')) {
            // If this is a POST request to cart/add, save the product data
            if ($request->getMethod() === 'POST' && strpos($request->getUri()->getPath(), '/cart/add') !== false) {
                // Get IncomingRequest to access getPost() method
                $incomingRequest = service('request');
                
                $productId = $incomingRequest->getPost('product_id');
                $quantity = $incomingRequest->getPost('quantity') ?? 1;
                
                if ($productId) {
                    session()->set('pending_cart_item', [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);
                }
            }
            
            // Store intended URL for redirect after login (use referrer or shop page)
            $referrer = $request->getHeaderLine('Referer');
            $redirectUrl = $referrer ?: '/shop';
            session()->set('redirect_after_login', $redirectUrl);
            
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

