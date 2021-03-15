<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function index()
    {
        return view('admin', [
            'enabled_packages' => Package::all(),
            'disabled_packages' => Package::withoutGlobalScopes()->where('is_disabled', true)->get(),
        ]);
    }
}
