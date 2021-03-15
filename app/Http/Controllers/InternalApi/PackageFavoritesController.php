<?php

namespace App\Http\Controllers\InternalApi;

use App\Http\Controllers\Controller;
use App\Package;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class PackageFavoritesController extends Controller
{
    /**
     * @param Package $package
     * @return Response|ResponseFactory
     * @throws BindingResolutionException
     */
    public function store(Package $package)
    {
        auth()->user()->favoritePackage($package->id);

        return response(['status' => 'success', 'message' => 'Favorite created successfully'], 201);
    }

    /**
     * @param Package $package
     * @return Response|ResponseFactory
     * @throws BindingResolutionException
     */
    public function destroy(Package $package)
    {
        auth()->user()->unfavoritePackage($package->id);

        return response(['status' => 'success', 'message' => 'Favorite removed successfully'], 200);
    }
}
