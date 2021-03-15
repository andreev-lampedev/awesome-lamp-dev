<?php

namespace App\Http\Controllers\App;

use App\Collaborator;
use App\Events\CollaboratorCreated;
use App\Http\Controllers\Controller;
use App\Http\Remotes\GitHub;
use Github\Exception\RuntimeException as GitHubException;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class CollaboratorController extends Controller
{
    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function index()
    {
        return view('app.collaborators.index', [
            'unclaimed_collaborators' => Collaborator::doesntHave('user')->get(),
        ]);
    }

    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function create()
    {
        return view('app.collaborators.create');
    }

    /**
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     */
    public function store()
    {
        $input = request()->validate([
            'name' => 'required',
            'github_username' => 'required|unique:collaborators,github_username',
            'url' => 'nullable|url',
            'description' => '',
        ]);

        try {
            $githubData = $this->getCollaboratorGitHubData($input['github_username']);
        } catch (GitHubException $e) {
            return redirect()->back()->withInput()->withErrors([
                'github_username' => 'Sorry, but that is not a valid GitHub username.',
            ]);
        }

        $collaborator = Collaborator::create(array_merge($input, $githubData));

        event(new CollaboratorCreated($collaborator));

        return redirect()->route('app.collaborators.index');
    }

    /**
     * @param Collaborator $collaborator
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(Collaborator $collaborator)
    {
        return view('app.collaborators.edit', compact('collaborator'));
    }

    /**
     * @param Collaborator $collaborator
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws MassAssignmentException
     * @throws RouteNotFoundException
     */
    public function update(Collaborator $collaborator)
    {
        $input = request()->validate([
            'name' => 'required',
            'github_username' => [
                'required',
                Rule::unique('collaborators')->ignore($collaborator),
            ],
            'url' => 'nullable|url',
            'description' => '',
        ]);

        try {
            $githubData = ($collaborator->github_username != request('github_username'))
                ? $this->getCollaboratorGitHubData($input['github_username'])
                : [];
        } catch (GitHubException $e) {
            return redirect()->back()->withInput()->withErrors([
                'github_username' => 'Sorry, but that is not a valid GitHub username.',
            ]);
        }

        $collaborator = $collaborator->update(array_merge($input, $githubData));

        return redirect()->route('app.collaborators.index');
    }

    /**
     * @param mixed $username
     * @return array
     * @throws BindingResolutionException
     */
    private function getCollaboratorGitHubData($username)
    {
        return [
            'avatar' => app(GitHub::class)->user($username)['avatar_url'] ?? null,
        ];
    }
}
