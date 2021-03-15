<?php

namespace App\Http\Controllers\App;

use App\CacheKeys;
use App\Http\Controllers\Controller;
use App\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PackagePackagistCacheController extends Controller
{
    /**
     * @param Package $package
     * @return JsonResponse|RedirectResponse
     * @throws BindingResolutionException
     */
    public function destroy(Package $package)
    {
        Cache::forget(CacheKeys::packagistData($package->composer_name));

        if (request()->wantsJson()) {
            return response()->json(['status' => 'success']);
        } else {
            return redirect()->back();
        }
    }
}
