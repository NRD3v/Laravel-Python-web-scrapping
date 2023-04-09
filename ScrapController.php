<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapeWebsite;
use Illuminate\Http\Request;

class ScrapController extends Controller
{
    public function scrap(Request $request)
    {
        // Verify mandatory parameters are supplied
        $url = $request->input('url');
        $markup = $request->input('markup');

        if (empty($url) || empty($markup)) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        // Add job in Laravel queue
        ScrapeWebsite::dispatch($url, $markup);

        return response()->json(['message' => 'Scraping job added to queue'], 200);
    }
}
