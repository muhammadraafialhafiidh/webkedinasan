<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\VisitorStatisticService;

class CmsVisitorStatisticController extends Controller
{
    public function index(VisitorStatisticService $service)
    {
        $data = $service->getCmsFullStatistics();

        return view('cms.statistics.index', $data);
    }

    public function live(VisitorStatisticService $service)
    {
        return response()->json($service->getCmsFullStatistics());
    }
}
