<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Stats;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * @param Request $request
     * @param Stats $stats
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function __invoke(Request $request, Stats $stats)
    {
        return response()->json([
            'package_count' => $stats->packageCount(),
            'packagist_download_count' => $stats->packagistDownloadsCount(),
            'github_star_count' => $stats->githubStarsCount(),
            'nova_latest_version' => $stats->novaLatestVersion(),
            'collaborator_count' => $stats->collaboratorsCount(),
            'rating_count' => $stats->ratingsCount(),
            'average_rating' => $stats->globalAverageRating(),
        ]);
    }
}
