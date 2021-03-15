<?php

namespace App\Http\Controllers;

use App\Http\Remotes\GitHub;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class PackageIdeaController extends Controller
{
    /**
     * @param GitHub $github
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function __invoke(GitHub $github)
    {
        $ideas = collect($github->packageIdeaIssues());

        return view('package-ideas', [
            'claimed_ideas' => $this->claimedIdeas($ideas),
            'unclaimed_ideas' => $this->unclaimedIdeas($ideas),
        ]);
    }

    /**
     * @param mixed $ideas
     * @return mixed
     */
    private function claimedIdeas($ideas)
    {
        return $ideas->filter(function ($idea) {
            return $this->hasChallengeAcceptedLabel($idea);
        });
    }

    /**
     * @param mixed $ideas
     * @return mixed
     */
    private function unclaimedIdeas($ideas)
    {
        return $ideas->reject(function ($idea) {
            return $this->hasChallengeAcceptedLabel($idea);
        });
    }

    /**
     * @param mixed $idea
     * @return bool
     */
    private function hasChallengeAcceptedLabel($idea)
    {
        return collect($idea['labels'])->filter(function ($label) {
            return $label['name'] == 'challenge-accepted';
        })->count() > 0;
    }
}
