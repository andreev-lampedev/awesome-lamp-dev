<?php

namespace App\Http\Controllers\InternalApi;

use App\Http\Controllers\Controller;
use App\Package;
use App\Review;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use willvincent\Rateable\Rating;

class ReviewsController extends Controller
{
    /**
     * @return string[]
     * @throws BindingResolutionException
     */
    public function store()
    {
        request()->validate([
            'package_id' => [
                'required',
                'exists:App\Package,id',
            ],
            'review' => 'required',
        ]);

        Package::findOrFail(request('package_id'))
            ->addReview(
                Rating::where('rateable_id', request('package_id'))->where('user_id', auth()->id())->first()->id,
                request('review')
            );

        return ['status' => 'success', 'message' => 'Review created successfully'];
    }

    /**
     * @return string[]
     * @throws BindingResolutionException
     */
    public function update()
    {
        request()->validate([
            'package_id' => [
                'required',
                'exists:App\Package,id',
            ],
            'review' => 'required',
        ]);

        Package::findOrFail(request('package_id'))->updateReview(request('review'));

        return ['status' => 'success', 'message' => 'Review edited successfully'];
    }

    /**
     * @param Review $review
     * @return Response|ResponseFactory
     * @throws Exception
     * @throws BindingResolutionException
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response('Success', 200);
    }
}
