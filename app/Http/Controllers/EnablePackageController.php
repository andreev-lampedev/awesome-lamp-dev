<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;
use Illuminate\Contracts\Container\BindingResolutionException;

class EnablePackageController extends Controller
{
    /**
     * @param Package $package
     * @return RedirectResponse
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     */
    public function __invoke(Package $package)
    {
        $package->is_disabled = false;
        $package->save();

        return back()->with([
            'package' => $package,
            'status' => 'Package enabled: ' . $package->name,
        ]);
    }
}
