<?php

namespace App\Observers;

use DOMDocument;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\Website;


class CustomCrawlerObserver extends CrawlObserver
{
    private $content;
    private $target;


    public function __construct(){
        $this->content = NULL;
    }

    public function willCrawl(UriInterface $url):void
    {
        Log::info("willCrawl: ",["url"=>$url]);
        if(!isset($this->target)) $this->target = rtrim($url->__toString(),'/');
    }

    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ):void

    
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());

        $content = $doc->saveHTML();
        $content1 = mb_convert_encoding($content,'UTF-8',mb_detect_encoding($content,'UTF-8, ISO-8859-1',true));
        // strip java scripts , styles , tags , white spaces , line breaks 
        $content2 = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content1);
        $content3 = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content2);
        $content4 = str_replace('<',' <',$content3);
        $content5 = strip_tags($content4);
        $content6 = str_replace( '  ', ' ', $content5 );
        $content7 = preg_replace('/\s+/S', " ", $content6);
        $html = html_entity_decode($content7);
        
        // append html to content 
        $this->content .= $html;
     }

     // catch the error of crawler 
     public function crawlFailed(
        UriInterface $url , 
        RequestException $requestException , 
        ?UriInterface $foundOnUrl = null 
     ):void
     {
        Log::error('crawlFailed: ',['url'=>$url,'error'=>$requestException->getMessage()]);
     }

     // when the crawl has ended 
     public function finishedCrawling():void
     {
        Log::info("finishedCrawling : ");

        Log::debug($this->target);
        
        
        //dd($this->target,$this->content);

       try {
            $website = new Website();
            $website->guid = $this->target;
            $website->data = $this->content;
            $website->save();
       }
       catch(\Exception $e){
            $message = $e->getMessage();
            dd($message);
       }
     }
}
