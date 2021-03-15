<?php

namespace App\Http\Controllers\InternalApi;

use App\Events\PackageRated;
use App\Exceptions\SelfAuthoredRatingException;
use App\Http\Controllers\Controller;
use App\Package;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use willvincent\Rateable\Rating;

class RatingsController extends Controller
{
    /**
     * @return Response|ResponseFactory|string[]
     * @throws BindingResolutionException
     */
    public function store()
    {
        request()->validate([
            'package_id' => [
                'required',
                'exists:App\Package,id',
            ],
            'rating' => 'required',
        ]);

        try {
            auth()->user()->ratePackage(request('package_id'), request('rating'));
        } catch (SelfAuthoredRatingException $e) {
            return response([
                'status' => 'error',
                'message' => 'A package cannot be rated by its author',
            ], 422);
        }

        event(new PackageRated(request('package_id')));

        return ['status' => 'success', 'message' => 'Rating created successfully'];
    }
}
