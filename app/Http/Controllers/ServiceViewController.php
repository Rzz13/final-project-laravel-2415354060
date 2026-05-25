<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceViewController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();

        return view('services', compact('services'));
    }
}
