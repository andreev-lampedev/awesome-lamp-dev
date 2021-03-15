<?php

namespace App\Http\Controllers\App;

use App\Collaborator;
use App\Events\CollaboratorClaimed;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\InvalidCastException;
use LogicException;
use Illuminate\Database\Eloquent\JsonEncodingException;
use InvalidArgumentException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class CollaboratorClaimController extends Controller
{
    /**
     * @param Collaborator $collaborator
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function create(Collaborator $collaborator)
    {
        return view('app.collaborators.claim', [
            'collaborator' => $collaborator,
        ]);
    }

    /**
     * @param Collaborator $collaborator
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws InvalidCastException
     * @throws LogicException
     * @throws JsonEncodingException
     * @throws InvalidArgumentException
     * @throws RouteNotFoundException
     */
    public function store(Collaborator $collaborator)
    {
        $collaborator->user()->associate(auth()->user())->save();

        event(new CollaboratorClaimed($collaborator, auth()->user()));

        return redirect()->route('app.collaborators.index');
    }
}
