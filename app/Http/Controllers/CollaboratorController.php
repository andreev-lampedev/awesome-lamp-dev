<?php

namespace App\Http\Controllers;

use App\Collaborator;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CollaboratorController extends Controller
{
    /**
     * @return void
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function index()
    {
        abort(404);
    }

    /**
     * @param Collaborator $collaborator
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function show(Collaborator $collaborator)
    {
        return view('collaborators.show')
            ->with('collaborator', $collaborator);
    }
}
