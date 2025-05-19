<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Affiche la page principale de configuration.
     */
    public function index()
    {
        return view('admin.configuration.index');
    }
} 