<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class EmailController extends Controller
{
    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function create()
    {
        return view('app.email.create');
    }

    /**
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws ValidationException
     * @throws RouteNotFoundException
     */
    public function store()
    {
        $this->validate(request(), [
            'email' => 'required|email|unique:users,email',
        ]);

        auth()->user()->update(['email' => request('email')]);

        return redirect()->route('home');
    }
}
