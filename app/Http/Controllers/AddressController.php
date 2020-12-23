<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Library\LibGeoCodingAPI;

class AddressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function __construct(AddressController $addressController)
//    {
//        $this->controller = $addressController;
//    }

    public function GetAddress(Request $request)
    {
        $LibGeoCodingAPI = new LibGeoCodingAPI();

        return $LibGeoCodingAPI->get($request);

//        return response()->json($request, 200);

        // todo 接收資料

        // todo 查詢資料庫

        // todo 輸出
    }

    public function GetMultiAddress(Request $request)
    {
        $LibGeoCodingAPI = new LibGeoCodingAPI();

        // todo 接收資料

        // todo 查詢資料庫

        // todo 輸出
    }
}
