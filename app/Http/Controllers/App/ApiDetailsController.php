<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class ApiDetailsController extends Controller
{
    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function __invoke()
    {
        return view('app.api_details');
    }
}
