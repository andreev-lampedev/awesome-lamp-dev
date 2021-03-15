<?php

namespace App\Http\Controllers;

use App\Http\Resources\PackageDetailResource;
use App\Package;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class PackageController extends Controller
{
    /**
     * @return View|Factory
     * @throws BindingResolutionException
     */
    public function index()
    {
        return view('packages.index');
    }

    /**
     * @param mixed $namespace
     * @param mixed $name
     * @return View|Factory
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function show($namespace, $name)
    {
        $query = Package::where('composer_name', $namespace . '/' . $name);

        if (auth()->user() && auth()->user()->isAdmin()) {
            $query = Package::withoutGlobalScopes()->where('composer_name', $namespace . '/' . $name);
        }

        $package = $query->firstOrFail();

        return view('packages.show', [
            'package' => PackageDetailResource::from($package),
            'screenshots' => $package->screenshots,
            'packageOgImageUrl' => $package->og_image_public_url,
        ]);
    }

    /**
     * @param Package $package
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws RouteNotFoundException
     */
    public function showId(Package $package)
    {
        return redirect()->route('packages.show', [
            'namespace' => $package->composer_vendor,
            'name' => $package->composer_package,
        ]);
    }
}
