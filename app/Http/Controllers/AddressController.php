<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Library\LibGeoCodingAPI;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Zipcode;

class AddressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function GetAddress(Request $request)
    {
        // init
        $return = array(
            "zip"          => null,
            "city"         => null,
            "area"         => null,
            "road"         => null,
            "lane"         => null,
            "alley"        => null,
            "no"           => null,
            "floor"        => null,
            "address"      => null,
            "filename"     => null,
            "latitude"     => null,
            "lontitue"     => null,
            "full_address" => null,
        );

        $LibGeoCodingAPI = new LibGeoCodingAPI();

        $data = json_decode($LibGeoCodingAPI->get($request), TRUE)[0];

        foreach($data['address_components'] as $component)
        {
            if ($component['types'][0] == "subpremise")
            {
                $return["floor"] = $component["long_name"];
            }

            if ($component['types'][0] == "street_number")
            {
                $return["no"] = $component["long_name"];
            }

            if ($component['types'][0] == "route")
            {
                //路+巷+弄
                $return["road"] = '';

                $search = array(
                    '路' => -1,
                    '街' => -1,
                    '段' => -1,
                    '巷' => -1,
                    '弄' => -1
                );

                foreach($search as $type => $index)
                {
                    $test_index = mb_strpos($component["long_name"], $type);
                    if ($test_index !== FALSE)
                    {
                        $search[$type] = $test_index;
                    }
                }

                $start = 0;
                foreach($search as $type => $index)
                {
                    if ($index > 0)
                    {
                        if (in_array($type, array('路', '街', '段')))
                        {
                            $return["road"] .= mb_substr($component["long_name"], $start, $index - $start + 1, "utf-8");
                            $start          = $index + 1;
                        }

                        if ($type === '巷')
                        {
                            $return["lane"] .= mb_substr($component["long_name"], $start, $index - $start, "utf-8");
                            $start          = $index + 1;
                        }

                        if ($type === '弄')
                        {
                            $return["alley"] .= mb_substr($component["long_name"], $start, $index - $start, "utf-8");
                            $start           = $index + 1;
                        }
                    }
                }
            }

            if ($component['types'][0] == "administrative_area_level_3")
            {
                $return["area"] = $component["long_name"];
            }

            if ($component['types'][0] == "administrative_area_level_1")
            {
                $return["city"] = $component["long_name"];
            }

            if ($component['types'][0] == "postal_code")
            {
                $return["zip"] = $component["long_name"];
            }
        }

        $return['latitude'] = $data['geometry']['location']['lat'];
        $return['lontitue'] = $data['geometry']['location']['lng'];

        $OtherInfo = $LibGeoCodingAPI->converToHalf($request->address);

        $return['address']      = $OtherInfo; // todo 考慮備註問題 考慮BXX|2號|2号|1樓|1F 後面可能接 -|(|[|{

        $return['full_address'] = str_replace(array("f", "F"), array("樓", "樓"), $data['formatted_address']);

        // 查詢filename
        $file_name = DB::table('zipcodes')
                        ->where('area', $return['area'])
                        ->where('city', str_replace( "台", "臺", $return['city']))
                        ->first()->file_name;

        $return['filename'] = $file_name;

        return response()->json($return, 200);

    }
}
