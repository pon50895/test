<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

define('GEOCODEING_API_SECRET', getenv('GEOCODEING_API_SECRET'));
defune('GEOCODING_REDIS_LIFETIME', getenv('GEOCODING_REDIS_LIFETIME') OR 3600);

class LibGeoCodingAPI
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        Validator::make($request, [
            'return_format' => [
                'required',
                Rule::in(['xml', 'json']),
            ],
            'address' =>
            [
                'required',
            ]
        ]);

        // todo 先問redis

        $url = $this->getQuestUri($request);

        $response = Http::get($url);

        // todo 串redis
    }

    // todo check redis work?, and check fail handle
    private function checkRedis($address)
    {
        return Cache::store('redis')->Hget($address);
    }

    // todo check redis work?, and check put fail handle
    private function putRedis($key, $value)
    {
        Cache::store('redis')->Hput('address', 'result', 'EX', GEOCODING_REDIS_LIFETIME);
        return;
    }

    private function getQuestUri(Request $request)
    {
        $url= 'https://maps.googleapis.com/maps/api/geocode/';
        return $this->uriEncode($url . $request->return_format . '?' . $request->address . '&key=' . GEOCODEING_API_SECRET);
    }

    private function uriEncode(string $url)
    {
        return urlencode(preg_replace('/\s+/','+' , $url));
    }
}
