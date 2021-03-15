<?php

namespace App\Http\Controllers;

use App\Stats;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * @param Stats $stats
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function __invoke(Stats $stats)
    {
        return view('stats', ['stats' => $stats]);
    }
}
