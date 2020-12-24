<?php

namespace App\Http\Library;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;


define('GEOCODEING_API_SECRET', getenv('GEOCODEING_API_SECRET'));
define('GEOCODING_REDIS_LIFETIME', getenv('GEOCODING_REDIS_LIFETIME') OR 3600);

class LibGeoCodingAPI
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        try {
            $validResult = $request->validate([
                "return_format" => ["required", "regex:/^(json|xml)$/i"],
                "address" => "required",
            ]);
        } catch (ValidationException $exception) {
            // 取得 laravel Validator 實例
            $validatorInstance = $exception->validator;
            // 取得錯誤資料
            $errorMessageData = $validationInstance->getMessageBag();
            // 取得驗證錯誤訊息
            $errorMessages = $errorMessageData->getMessages();
        }

        // 全形轉半形
        $request->address = $this->converToHalf($request->address);

        // get data in redis if exists
        $redisData = $this->checkRedis($this->uriEncode($request->address));
        if ($redisData)
        {
            return $redisData;
        }

        try {
            $url = $this->getQuestUri($request);
            $response = Http::get($url);
        } catch (Exception $e) {
            abort(500, 'get api fail');
        }

        if ($response['status'] != "OK")
        {
            abort(500, $response['status']);
        }
        else
        {
            $this->putRedis($this->uriEncode($request->address), json_encode($response['results']));
        }

        return json_encode($response['results']);
    }

    private function checkRedis($address)
    {
        //todo check redis work
        return Redis::get($address);
    }

    private function putRedis($key, $value)
    {
        //todo check redis work
        Redis::set('address', 'result', NULL, 'EX', GEOCODING_REDIS_LIFETIME);
        return;
    }

    private function getQuestUri(Request $request)
    {
        $url= 'https://maps.googleapis.com/maps/api/geocode/';
        return $url . $request->return_format . '?address=' . $this->uriEncode($request->address)  . '&language=zh-TW&key=' . GEOCODEING_API_SECRET;
    }

    private function uriEncode(string $url)
    {
        return urlencode(preg_replace('/\s+/','+' , $url));
    }

    public function converToHalf($string)
    {
        if (gettype($string) != 'string'){return '';}
        $dbc = array(
            '０' , '１' , '２' , '３' , '４' ,
            '５' , '６' , '７' , '８' , '９' ,
            'Ａ' , 'Ｂ' , 'Ｃ' , 'Ｄ' , 'Ｅ' ,
            'Ｆ' , 'Ｇ' , 'Ｈ' , 'Ｉ' , 'Ｊ' ,
            'Ｋ' , 'Ｌ' , 'Ｍ' , 'Ｎ' , 'Ｏ' ,
            'Ｐ' , 'Ｑ' , 'Ｒ' , 'Ｓ' , 'Ｔ' ,
            'Ｕ' , 'Ｖ' , 'Ｗ' , 'Ｘ' , 'Ｙ' ,
            'Ｚ' , 'ａ' , 'ｂ' , 'ｃ' , 'ｄ' ,
            'ｅ' , 'ｆ' , 'ｇ' , 'ｈ' , 'ｉ' ,
            'ｊ' , 'ｋ' , 'ｌ' , 'ｍ' , 'ｎ' ,
            'ｏ' , 'ｐ' , 'ｑ' , 'ｒ' , 'ｓ' ,
            'ｔ' , 'ｕ' , 'ｖ' , 'ｗ' , 'ｘ' ,
            'ｙ' , 'ｚ' , '－' , '　' , '：' ,
            '．' , '，' , '／' , '％' , '＃' ,
            '！' , '＠' , '＆' , '（' , '）' ,
            '＜' , '＞' , '＂' , '＇' , '？' ,
            '［' , '］' , '｛' , '｝' , '＼' ,
            '｜' , '＋' , '＝' , '＿' , '＾' ,
            '￥' , '￣' , '｀'
        );

        $sbc = array( //半形
              '0', '1', '2', '3', '4',
              '5', '6', '7', '8', '9',
              'A', 'B', 'C', 'D', 'E',
              'F', 'G', 'H', 'I', 'J',
              'K', 'L', 'M', 'N', 'O',
              'P', 'Q', 'R', 'S', 'T',
              'U', 'V', 'W', 'X', 'Y',
              'Z', 'a', 'b', 'c', 'd',
              'e', 'f', 'g', 'h', 'i',
              'j', 'k', 'l', 'm', 'n',
              'o', 'p', 'q', 'r', 's',
              't', 'u', 'v', 'w', 'x',
              'y', 'z', '-', ' ', ':',
              '.', ',', '/', '%', ' #',
              '!', '@', '&', '(', ')',
              '<', '>', '"', '\'','?',
              '[', ']', '{', '}', '\\',
              '|', ' ', '=', '_', '^',
              '￥','~', '`'
        );

        return str_replace( $dbc, $sbc, $string );
    }
}
