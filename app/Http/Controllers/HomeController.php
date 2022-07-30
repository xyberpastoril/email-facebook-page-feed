<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;
use App\Mail\Api\LatestFeed;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    private $posts;
    private $html;

    public function __construct()
    {
        $options = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                        "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
                        "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
            )
            );

        // fetch webpage content
        $context = stream_context_create($options);
        $html = HtmlDomParser::file_get_html('https://mbasic.facebook.com/leyeco4', false, $context);

        // Find all images
        // echo $html;
        $posts = [];
        $key = 0;

        foreach($html->find('div[role=article].fl') as $element)
        {
            // echo $element;

            /**
             * Get the Post Text
             */
            // echo $element->children[1]; // div - ft
            try {
                $text = clone $element->children[1];
            } catch(\Exception $e) {
                $text = null;
            }

            /**
             * Link card [broken due to missing css]
             */
            // echo $element->children[2]->children[0]; // a fz ga
            // echo $element->children[2]->children[0]->children[0]; // div - gd de ge
            // echo $element->children[2]->children[0]->children[0]->children[0]; // table - cp dw
            // echo $element->children[2]->children[0]->children[0]->children[0]->children[0]; // tbody
            // echo $element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]; // tr
            // echo $element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]->children[0]; // td - 1

            /**
             * Get a fresh link away from Facebook Tracking
             */
            try {
                $test = explode('?u=', $element->children[2]->children[0]->attr['href']);
                $test = explode('&', $test[1]);
                $test = urldecode($test[0]);
                $link_url = $test;
            } catch(\Exception $e) {
                $link_url = null;
            }

            
            /**
             * Get a fresh link of cover photo
             */
            // var_dump($element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]->children[0]->children[0]->attr['src']);
            try {
                $test2 = explode('?url=', $element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]->children[0]->children[0]->attr['src']);
                $test2 = explode('&', $test2[1]);
                $test2 = urldecode($test2[0]);
                $link_photo_url = $test2;
            } catch(\Exception $e) {
                $link_photo_url = null;
            }

            // echo $element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]->children[1]; // td - 2
            // echo $element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]->children[1]->children[0]; // h3

            // Inner Text Title of Card
            // echo($element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]->children[1]->children[0]->nodes[0]);
            try {
                $title = clone $element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]->children[1]->children[0]->nodes[0];
            } catch (\Exception $e) {
                $title = null;
            }



            // echo "<img src='{$test2}'>";
            // dd($element->children[2]->children[0]->children[0]->children[0]->children[0]->children[0]);

            // echo $element->children[2]->children[0]->children[0]->children[0]->children[0]->children[1];

            // exit();
            // $test = $element->find('ft', 0);
            // echo $test;
            $posts[] = [
                'text' => $text,
                'link_url' => $link_url,
                'link_photo_url' => $link_photo_url,
                'title' => $title,
            ];

            $key++;
        }
            // dd($element);
            // foreach($element->find('ft') as $e)
                // echo $element;
            // echo($element);

        // foreach($html->find('div[role=article] fl fz.ga') as $element)
        //     echo($element);

        // return response()->json($posts);
        // return view('test', [
        //     'posts' => $posts,
        // ]);
        $this->posts = $posts;
        $this->html = $html;
    }

    public function index()
    {
        echo $this->html;
    }

    public function preview()
    {
        return view('test', [
            'posts' => $this->posts,
        ]);
    }

    public function send()
    {
        Mail::to('test@example.com')->send(new LatestFeed($this->posts));
    }
}
