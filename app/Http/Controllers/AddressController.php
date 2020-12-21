<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Void_;

class AddressController extends Controller
{
    private $originAddress;
    private $returnArray;

    public function __construct()
    {
        $this->_initReturn();
    }

    private function _initReturn():void
    {
        $this->_setReturnElement('zip', NULL);
        $this->_setReturnElement('city', NULL);
        $this->_setReturnElement('area', NULL);
        $this->_setReturnElement('road', NULL);
        $this->_setReturnElement('lane', NULL);
        $this->_setReturnElement('alley', NULL);
        $this->_setReturnElement('no', NULL);
        $this->_setReturnElement('floor', NULL);
        $this->_setReturnElement('address', NULL);
        $this->_setReturnElement('filename', NULL);
        $this->_setReturnElement('latitude', NULL);
        $this->_setReturnElement('lontitue', NULL);
        $this->_setReturnElement('full_address', NULL);
    }

    private function _setReturnElement($key, $value):void
    {
        $this->returnArray[$key] = $value;
    }

    private function _getReturnElement($key = NULL):array
    {
        if ($key === NULL)
        {
            return $this->returnArray;
        }
        return isset($this->returnArray[$key]) ? $this->returnArray[$key] : NULL;
    }

    public function GetAddress(Request $request)
    {
        echo 'hello';
        dd('hello world!');
    }

    public function GetMultiAddress(Request $request)
    {

    }

    private function _PhaseAddress($input, $return)
    {

    }
}
