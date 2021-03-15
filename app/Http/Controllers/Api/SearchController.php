<?php

namespace App\Http\Controllers\Api;

use App\CacheKeys;
use App\Http\Controllers\Controller;
use App\Http\Resources\Package as PackageResource;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SearchController extends Controller
{
    const CACHE_LENGTH = 10;

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws ValidationException
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'q' => 'required|min:3',
        ]);

        return PackageResource::collection($this->searchFor($request->input('q')));
    }

    /**
     * @param mixed $q
     * @return mixed
     */
    private function searchFor($q)
    {
        return Cache::remember(CacheKeys::packageSearchResults($q), self::CACHE_LENGTH, function () use ($q) {
            return Package::search($q)->get()->load(['tags', 'author']);
        });
    }
}
