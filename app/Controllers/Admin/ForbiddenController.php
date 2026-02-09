<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ForbiddenController extends BaseController
{
    public function index()
    {
        // Set HTTP status code to 403
        return $this->response->setStatusCode(403)
            ->setBody(view('errors/html/error_403'));
    }
}

