<?php
if (!function_exists('array_field_name'))
{
    function array_field_name($key)
    {
        $key_name_parts = explode('.', $key);
        $res = $key_name_parts[0];
        foreach ($key_name_parts as $k => $part)
        {
            if ($k > 0)
            {
                $res .= '[' . $part . ']';
            }
        }

        return $res;
    }
}

if (!function_exists('validation_error_to_api_json'))
{
    function validation_error_to_api_json($errors)
    {
        $api_errors = [];
        foreach ($errors->getMessages() as $key => $value)
        {
            $key = array_field_name($key);
            $api_errors[ $key ] = array_values($value);
        }

        return $api_errors;
    }
}

function make_hash($password, $cost = '10')
{
    $options = array('cost' => $cost);
    $hash = password_hash($password, PASSWORD_BCRYPT, $options);
    return $hash;
}

function check_hash($password, $hash)
{
    return password_verify($password, $hash);
}


if (!function_exists('get_ProvinceCities_json'))
{
    function get_ProvinceCities_json()
    {
        $cities = \DB::table('ir_cities')
            ->join('ir_provinces', 'ir_provinces.id', '=', 'ir_cities.province_id')
            ->select('ir_cities.id', \DB::raw('CONCAT(ir_provinces.name, " :: ", ir_cities.name) AS text'))
            ->get()->toJson();
        return $cities;
    }
}

if (!function_exists('get_province_tree'))
{
    function get_province_tree($filter_province_ids = false, $filter_city_ids = false, $filter_town_ids = false, $filter_shahrak_ids = false)
    {
        $provinces = \App\Models\ProvinceCity\Province::with(
            [
                'cities' => function ($query) use ($filter_city_ids, $filter_town_ids) {
                    $query->select('id', 'name as text', 'province_id')
                        ->with(
                            [
                                'towns' => function ($query) use ($filter_town_ids) {
                                    $query->select('town.id', 'town.name as text', 'bakhsh_id');
                                    if ($filter_town_ids)
                                    {
                                        $query->whereIn('town.id', $filter_town_ids);
                                    }
                                }
                            ]);
                    if ($filter_city_ids)
                    {
                        $query->whereIn('city.id', $filter_city_ids);
                    }
                },
                'shahraks' => function ($query) use ($filter_shahrak_ids) {
                    $query->select('id', 'name as text', 'province_id');
                    if ($filter_shahrak_ids)
                    {
                        $query->whereIn('shahrak.id', $filter_shahrak_ids);
                    }

                }])->select('id', 'name as text');
        if ($filter_province_ids)
        {
            $provinces = $provinces->whereIn('id', $filter_province_ids);
        }
        return $provinces->get();


    }
}


if (!function_exists('PushNotification'))
{
    function PushNotification($users, $state = '1', $style = '1', $options = ['page' => '1'], $title = '', $text = 'برنامه برای شما فعال گردید', $bigtxt = '', $url = "www.google.com", $img = "")
    {
        $res = [];
        $fdata = new Artincms\LHS\Helpers\Classes\Fdata();
        $firebase = new Artincms\LHS\Helpers\Classes\Firebase();

        $notifi = array();
        //  state => 1  active user  , 2 webview
        $notifi['state'] = $state;
        //  style => 1 normal , 2 bigtext , 3 img
        $notifi['style'] = $style;
        $notifi['title'] = $title;
        $notifi['text'] = $text;
        $notifi['bigtxt'] = $bigtxt;
        $notifi['url'] = $url;
        $notifi['image'] = $img;

        //not is hide or show
        $Background = false;

        $jdata = array();
        $jdata['active'] = '1';
        //$jdata['page'] = $page . "";//1.profile 2.accepted 3.requested 4.orders
        if (isset($options) && is_array($options))
        {
            foreach ($options as $key => $option)
            {
                $jdata[$key] = $option . "";
            }
        }


        $fdata->setNotifi($notifi);
        $fdata->setBackground($Background);
        $fdata->setJdata($jdata);


        foreach ($users as $u)
        {
            $tokens = $u->Tokens;
            foreach ($tokens as $t)
            {
                if ($t->push_notification_id)
                {
                    $regId = $t->push_notification_id;
                    $res [] = $response = $firebase->sendData($regId, $fdata->getPush());
                }
            }
        }
        return $res;
    }
}
