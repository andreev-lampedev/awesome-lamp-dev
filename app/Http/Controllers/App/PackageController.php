<?php

namespace App\Http\Controllers\App;

use App\Collaborator;
use App\Events\PackageCreated;
use App\Events\PackageUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\PackageFormRequest;
use App\Package;
use App\Tag;
use DateTime;
use Facades\App\Repo;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class PackageController extends Controller
{
    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function index()
    {
        return view('app.packages.index', [
            'packages' => Package::get(),
            'favoritePackages' => auth()->user()->favoritePackages(),
        ]);
    }

    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function create()
    {
        return view('app.packages.create', [
            'collaborators' => Collaborator::orderBy('name')->get(),
            'tags' => Tag::orderBy('slug')->get(),
        ]);
    }

    /**
     * @param PackageFormRequest $request
     * @return RedirectResponse
     * @throws RouteNotFoundException
     */
    public function store(PackageFormRequest $request)
    {
        $repo = Repo::fromRequest($request);

        // We disable syncing here and manually call ->searchable() after the tag
        // associations are established in the database so they can be indexed.
        $package = Package::withoutSyncingToSearch(function () use ($request, $repo) {
            // @todo: Kick off a sync operation and validate it's a real repo? grab name? geez there's a lot here that's sillly to make them enter manually
            $package = Package::create(array_merge(
                request()->only(['name', 'author_id', 'url', 'abstract', 'instructions']),
                [
                    'composer_name' => $request->getComposerName(),
                    'repo_url' => $repo->url(),
                    'readme_source' => $repo->source(),
                    'readme_format' => $repo->readmeFormat(),
                    'submitter_id' => auth()->user()->id,
                    'readme' => $repo->readme(),
                    'latest_version' => $repo->latestReleaseVersion(),
                ]
            ));

            $package->contributors()->sync(request()->input('contributors', []));
            $newTagsCreated = $this->createNewTags(request()->input('tags-new', []));
            $package->tags()->sync(array_merge(request()->input('tags', []), $newTagsCreated));

            return $package;
        });

        $package->refresh()->searchable();

        event(new PackageCreated($package));

        if (request('screenshots')) {
            $package->syncScreenshots(request()->input('screenshots', []));
        }

        return redirect()->route('app.packages.index');
    }

    /**
     * @param Package $package
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function edit(Package $package)
    {
        // @todo refactor like store above
        return view('app.packages.edit', [
            'package' => $package,
            'collaborators' => Collaborator::orderBy('name')->get(),
            'tags' => Tag::orderBy('slug')->get(), // @todo maybe group the types first?
            'screenshots' => $package->screenshots,
        ]);
    }

    /**
     * @param PackageFormRequest $request
     * @param Package $package
     * @return RedirectResponse
     * @throws RouteNotFoundException
     */
    public function update(PackageFormRequest $request, Package $package)
    {
        $repo = Repo::fromRequest($request);

        // We disable syncing here and manually call ->searchable() after the tag
        // associations are established in the database so they can be indexed.
        $package = Package::withoutSyncingToSearch(function () use ($package, $request, $repo) {
            $package->update(array_merge(
                request()->only(['name', 'author_id', 'url', 'abstract', 'instructions']),
                [
                    'composer_name' => $request->getComposerName(),
                    'repo_url' => $repo->url(),
                    'readme_source' => $repo->source(),
                    'readme_format' => $repo->readmeFormat(),
                    'readme' => $repo->readme(),
                    'latest_version' => $repo->latestReleaseVersion(),
                ]
            ));

            $package->contributors()->sync($request->input('contributors', []));
            $newTagsCreated = $this->createNewTags($request->input('tags-new', []));
            $package->tags()->sync(array_merge($request->input('tags', []), $newTagsCreated));

            $package->updateAvailabilityFromNewUrl();

            return $package;
        });

        $package->refresh()->searchable();
        event(new PackageUpdated($package));
        $package->syncScreenshots($request->input('screenshots', []));

        return redirect()->route('app.packages.index');
    }

    /**
     * @param mixed $newTags
     * @return mixed
     */
    private function createNewTags($newTags)
    {
        $created_at = $updated_at = new DateTime();

        $tagNames = collect($newTags)->map(function ($tag) {
            return strtolower($tag);
        });

        $existingTags = Tag::whereIn('name', $tagNames)->get();
        $tagsToCreate = $tagNames->diff($existingTags->pluck('name'));

        if ($tagsToCreate->isEmpty()) {
            return $existingTags->pluck('id')->toArray();
        }

        Tag::insert($tagsToCreate->map(function ($tag) use ($created_at, $updated_at) {
            return [
                'slug' => Str::slug($tag),
                'name' => $tag,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
        })->toArray());

        return $existingTags
            ->pluck('id')
            ->merge(Tag::whereIn('name', $tagsToCreate)->get()->pluck('id'))
            ->toArray();
    }
}
