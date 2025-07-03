<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MainFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the user is logged in and has the admin role
        if (!session()->has("isLoggedIn") || (session()->has("role") && session()->get("role") != "MAIN")) {
            return redirect()->to('login');
        }

        // // If the user is logged in and has the admin role, allow the request to proceed
        // return $request;
    }



    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No need for this in this example
    }
}
