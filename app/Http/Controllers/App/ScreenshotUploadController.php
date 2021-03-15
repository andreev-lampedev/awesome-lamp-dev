<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Screenshot;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScreenshotUploadController extends Controller
{
    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function store()
    {
        request()->validate([
            'screenshot' => ['image', 'max:2048'],
        ]);

        $screenshot = Screenshot::create([
            'uploader_id' => auth()->id(),
            'path' => request('screenshot')->store('screenshots'),
        ]);

        return response()->json($screenshot, 201);
    }

    /**
     * @param Screenshot $screenshot
     * @return JsonResponse
     * @throws Exception
     * @throws BindingResolutionException
     */
    public function destroy(Screenshot $screenshot)
    {
        $screenshot->delete();

        return response()->json([], 200);
    }
}
