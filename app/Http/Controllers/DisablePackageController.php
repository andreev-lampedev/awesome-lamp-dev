<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;
use Illuminate\Contracts\Container\BindingResolutionException;

class DisablePackageController extends Controller
{
    /**
     * @param Package $package
     * @return RedirectResponse
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     */
    public function __invoke(Package $package)
    {
        $package->is_disabled = true;
        $package->save();

        return back()->with([
            'package' => $package,
            'status' => 'Package disabled: ' . $package->name,
        ]);
    }
}
