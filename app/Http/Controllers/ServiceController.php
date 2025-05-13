<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->paginate(6);

        return view('services.index', [
            'services' => $services
        ]);
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        
        return view('services.show', [
            'service' => $service
        ]);
    }
}
