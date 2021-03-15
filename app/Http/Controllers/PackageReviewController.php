<?php

namespace App\Http\Controllers;

use App\Http\Resources\PackageDetailResource;
use App\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Exception;

class PackageReviewController extends Controller
{
    /**
     * @param mixed $namespace
     * @param mixed $name
     * @return View|Factory
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function create($namespace, $name)
    {
        $package = Package::where('composer_name', $namespace . '/' . $name)->firstOrFail();
        $userStarRating = $package->ratings->where('user_id', auth()->id())->first();

        return view('package-reviews.create', [
            'package' => PackageDetailResource::from($package),
            'userStarRating' => $userStarRating,
        ]);
    }
}
