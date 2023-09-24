<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Observers\CustomCrawlerObserver;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;
use Spatie\Crawler\Crawler;
use App\Http\Controllers\Controller;
use GuzzleHttp\RequestOptions;
use App\Models\Website;
use Illuminate\Support\Facades\Validator;


class CustomCrawlerController extends Controller
{

    public function getData($id=null)
    {
        // fetch from mongodb all website rows or per id if it was passed 
        $data = (isset($id))?Website::where('_id',$id)->get():Website::all();
        return response()->json($data);
    }

    public function fetchContent(Request $request)
    {
        
        // validate fields 
        $validator = Validator::make($request->all(), [
            'target' => 'required|url',
            'depth'  => 'required|numeric',
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'success' => 0 , 
                'Message' => 'Error: Invalid input.' , 
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->safe()->only(['target', 'depth']);
        
        $targetUrl = rtrim($validated['target'],'/');
        $depth = $validated['depth'];

        // check if target url already crawled 
        $isExist = Website::where('guid', $targetUrl)->get();
    
        if(!$isExist->isEmpty()){
            return response()->json([
                'success' => 0 , 
                'Message' => 'Target Url already exists'
            ]);
        }
        


        Crawler::create([RequestOptions::ALLOW_REDIRECTS => true, RequestOptions::TIMEOUT => 30])
        ->acceptNofollowLinks()
        ->ignoreRobots()
        ->setMaximumDepth($depth)
        // ->setParseableMimeTypes(['text/html','text/plain'])
        ->setCrawlObserver(new CustomCrawlerObserver())
        ->setCrawlProfile(new CrawlInternalUrls($targetUrl))
        ->setMaximumResponseSize(1024 * 1024 * 2) // 2 MB maximum
        ->setTotalCrawlLimit(100) // limit defines the maximal count of urls to crawl
        ->setConcurrency(1) // all urls will be crawled one by one
        ->setDelayBetweenRequests(100)
        ->startCrawling($targetUrl);

        return response()->json([
            "success" => 1 ,
            "message" => $targetUrl.": crawled successfully"
        ]);
    }
}
