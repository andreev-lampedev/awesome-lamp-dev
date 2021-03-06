<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Jobs\SyncPackageRepositoryData;
use App\Package;
use Illuminate\Http\Request;

class RefreshPackageRepositoryController extends Controller
{
    /**
     * @param Package $package
     * @return void
     */
    public function __invoke(Package $package)
    {
        dispatch(new SyncPackageRepositoryData($package));
    }
}
