<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Assets extends Controller
{
    public function profilePicLayoutJs()
    {
        // Optional: put authentication or session check here
        // if (!session()->has('logged_in')) {
        //     throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        // }

        // Path to your JS file stored outside public directory
        $path = APPPATH . 'Resources/protected-js/profile-pic-layout.js';

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->response->setHeader('Content-Type', 'application/javascript')
                              ->setBody(file_get_contents($path));
    }
}
