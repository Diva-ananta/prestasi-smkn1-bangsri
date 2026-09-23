<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ApiDocumentationController extends Controller
{
    public function index()
    {
        return view('admin.api-documentation.index');
    }
}