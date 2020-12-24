<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class ZipcodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conn = Storage::disk('local');
        $dictionary = $conn->allFiles('zipcode');
        foreach($dictionary as $count => $file_path)
        {
            $check_city_json = preg_match('/^zipcode\/0\/0\.json$/', $file_path);
//            $check_street_json = preg_match('/^zipcode\/[1-9]\/[1-9][0-9][0-9](-[1-3])?\.json$/', $file_path);

//            if ($check_street_json)
//            {
//                $this->_save_area($conn, $file_path);
//                break;
//            }

            if ($check_city_json)
            {
                $this->_save_city($conn, $file_path);
                break;
            }
        }
    }

    private function _save_city($conn, $file_path)
    {
        $array = $this->_read_file($conn, $file_path);
        foreach($array as $row)
        {
            $city = $row['city'];
            foreach($row['data'] as $area)
            {
                $zip_code  = $area['zip'];
                $area_name = $area['area'];
                //                echo $city . "\n";
//                echo 'zipcode/' . substr($area['filename'], 0, 1) . '/' .  $area['filename'] . '.json' . "\n";
                $file_area = $this->_read_file($conn, 'zipcode/' . substr($area['filename'], 0, 1) . '/' .  $area['filename'] . '.json');
                foreach($file_area as $street)
                {
                    $input = array(
                        'city'      => $city,
                        'file_name' => $area['filename'] . '.json',
                        'zip_code'  => $zip_code,
                        'area'      => $area_name,
                        'street'    => $street['name'],
                        'spelling'  => $street['abc'],
                    );

                    DB::table('zipcodes')->insert($input);
                }
            }
        }
    }

    private function _read_file($conn, $file_path)
    {
        $json = [];
        $file = $conn->get($file_path);
        $json = json_decode($file, TRUE);
        return $json;
    }
}
