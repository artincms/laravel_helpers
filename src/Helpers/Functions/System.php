<?php

if (!function_exists('d'))
{
    function d(...$args)
    {
        http_response_code(500);
        dd($args);
    }
}

if (!function_exists('LHS_Generate_location_picker_modal'))
{
    function LHS_Generate_location_picker_modal($modal_id, $lat_input_id, $long_input_id, $map_area_id = 'map_area_id', $input_address = 'input_address', $btn_title = 'انتخاب موقعیت مکانی', $modal_title = 'انتخاب موقعیت مکان مورد نظر :', $map_area_width = '100%', $map_area_height = '420px', $marker_radius = 20)
    {
        $res['btn_open_modal'] = '<button class="btn btn-light" data-target="#' . $modal_id . '" data-toggle="modal" type="button"><i class="fa fa-map-marker"></i> ' . $btn_title . '</button>';
        $res['modal_content'] = view('common.location_picker', compact('modal_id', 'btn_title', 'modal_title', 'lat_input_id', 'long_input_id', 'map_area_id', 'input_address', 'map_area_width', 'map_area_height', 'marker_radius'))->render();

        return $res;
    }
}
if (!function_exists('LHS_JDate'))
{
    function LHS_JDate($format = "Y/m/d-H:i", $numberType = "fa", $stamp = false, $convert = null, $jalali = false, $timezone = "Asia/Tehran")
    {
        if ($stamp == false)
        {
            $stamp = time();
        }
        $date = new jDateTime();
        $res = $date->date($format, $stamp);
        if ($numberType != "fa")
        {
            $res = ConvertNumbersFatoEn($res);
        }

        return $res;
    }
}
if (!function_exists('LHS_Date_GtoJ'))
{
    function LHS_Date_GtoJ($GDate = null, $Format = "Y/m/d-H:i", $convert = true)
    {
//        return $GDate;
        if ($GDate == '-0001-11-30 00:00:00' || $GDate == null)
        {
            return '--/--/----';
        }
        $date = new jDateTime($convert, true, 'Asia/Tehran');
        $time = is_numeric($GDate) ? strtotime(date('Y-m-d H:i:s', $GDate)) : strtotime($GDate);

        return $date->date($Format, $time);

    }
}
if (!function_exists('LHS_Date_JtoG'))
{
    function LHS_Date_JtoG($jDate, $delimiter = '/', $to_string = false, $with_time = false, $input_format = 'Y/m/d H:i:s')
    {
        $jDate = ConvertNumbersFatoEn($jDate);
        $parseDateTime = jDateTime::parseFromFormat($input_format, $jDate);
        $r = jDateTime::toGregorian($parseDateTime['year'], $parseDateTime['month'], $parseDateTime['day']);
        if ($to_string)
        {
            if ($with_time)
            {
                $r = $r[0] . $delimiter . $r[1] . $delimiter . $r[2] . ' ' . $parseDateTime['hour'] . ':' . $parseDateTime['minute'] . ':' . $parseDateTime['second'];
            }
            else
            {
                $r = $r[0] . $delimiter . $r[1] . $delimiter . $r[2];
            }
        }

        return ($r);
    }
}
if (!function_exists('LHS_CreateDateRange'))
{
    function LHS_CreateDateRange($startDate, $endDate, $format = "Y-m-d")
    {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date)
        {
            $range[] = $date->format($format);
        }

        return $range;
    }
}
if (!function_exists('LHS_EnCode'))
{

    function LHS_EnCode($var)
    {
        return $var;
        $EncryptString = new \App\Helpers\Classes\EncryptString;
        $result = $EncryptString->encode($var);

        return trim($result);
    }

}
if (!function_exists('LHS_DeCode'))
{
    function LHS_DeCode($var)
    {
        return $var;
        $EncryptString = new \App\Helpers\Classes\EncryptString;
        $result = $EncryptString->decode($var);

        return trim($result);
    }
}
if (!function_exists('ConvertNumbersEntoFa'))
{
    function ConvertNumbersEntoFa($matches)
    {
        $farsi_array = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english_array = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($english_array, $farsi_array, $matches);
    }
}
if (!function_exists('LHS_ConvertNumbersFatoEn'))
{
    function LHS_ConvertNumbersFatoEn($matches)
    {
        $farsi_array = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english_array = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($farsi_array, $english_array, $matches);
    }
}
if (!function_exists('LHS_BuildTree'))
{

    /**
     * @param string $flat_array
     *     sometimes a boolean, sometimes a string (or, could have just used "mixed")
     * @param $pidKey
     * @param int $parent
     * @param string $idKey
     * @param string $children_key
     * @return mixed
     */
    function LHS_BuildTree($flat_array, $pidKey, $parent = 0, $idKey = 'id', $children_key = 'children')
    {
        if (empty($flat_array))
        {
            return [];
        }
        $grouped = [];
        foreach ($flat_array as $sub)
        {
            $grouped[ $sub[ $pidKey ] ][] = $sub;
        }

        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey, $children_key) {
            foreach ($siblings as $k => $sibling)
            {
                $id = $sibling[ $idKey ];
                if (isset($grouped[ $id ]))
                {
                    $sibling[ $children_key ] = $fnBuilder($grouped[ $id ]);
                }
                $siblings[ $k ] = $sibling;
            }

            return $siblings;
        };
        $tree = $fnBuilder($grouped[ $parent ]);

        return $tree;
    }

}
if (!function_exists('LHS_Human_date'))
{
    function LHS_Human_date($timestamp, $past = true)
    {
        $t = abs(time() - $timestamp) / 60;
        switch (true)
        {
            case $t > 548 * 24 * 60;
                $r = JDate('Y-m-d', 'fa', $timestamp);
                break;
            case $t > 12 * 30 * 24 * 60;
                $r = '1 ' . trans('date.year'); //'1 سال';
                break;
            case $t > 9 * 30 * 24 * 60;
                $r = '9 ' . trans('date.month'); //'9 ماه';
                break;
            case $t > 6 * 30 * 24 * 60;
                $r = '6 ' . trans('date.month'); //'6 ماه';
                break;
            case $t > 3 * 30 * 24 * 60;
                $r = '3 ' . trans('date.month'); //'3 ماه';
                break;
            case $t > 30 * 24 * 60;
                $r = '1 ' . trans('date.month'); //'1 ماه';
                break;
            case $t > 4 * 7 * 24 * 60;
                $r = '4 ' . trans('date.week'); //'1 هفته';
                break;
            case $t > 3 * 7 * 24 * 60;
                $r = '3 ' . trans('date.week'); //'1 هفته';
                break;
            case $t > 2 * 7 * 24 * 60;
                $r = '2 ' . trans('date.week'); //'1 هفته';
                break;
            case $t > 7 * 24 * 60;
                $r = '1 ' . trans('date.week'); //'1 هفته';
                break;
            case $t > 6 * 24 * 60;
                $r = '6 ' . trans('date.day'); //'1 روز';
                break;
            case $t > 5 * 24 * 60;
                $r = '5 ' . trans('date.day'); //'1 روز';
                break;
            case $t > 4 * 24 * 60;
                $r = '4 ' . trans('date.day'); //'1 روز';
                break;
            case $t > 3 * 24 * 60;
                $r = '3 ' . trans('date.day'); //'1 روز';
                break;
            case $t > 2 * 24 * 60;
                $r = '2 ' . trans('date.day'); //'1 روز';
                break;
            case $t > 24 * 60;
                $r = '1 ' . trans('date.day'); //'1 روز';
                break;
            case $t > 12 * 60;
                $r = '12 ' . trans('date.hour'); //'12 ساعت';
                break;
            case $t > 11 * 60;
                $r = '11 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 10 * 60;
                $r = '10 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 9 * 60;
                $r = '9 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 8 * 60;
                $r = '8 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 7 * 60;
                $r = '7 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 6 * 60;
                $r = '6 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 5 * 60;
                $r = '5 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 4 * 60;
                $r = '4 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 3 * 60;
                $r = '3 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 2 * 60;
                $r = '2 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 60;
                $r = '1 ' . trans('date.hour'); //'ساعت';
                break;
            case $t > 30;
                $r = trans('date.halfhour'); //'نیم ساعت';
                break;
            case $t > 15;
                $r = trans('date.aquarter'); //'یک ربع';
                break;
            case $t > 1;
                $r = trans('date.daghayeghi'); //'دقایقی';
                break;
            case $t > 0;
                $r = trans('date.lahazati'); //'لحظاتی';
                break;
        }
        if ($past)
        {
            $r = $r . ' ' . trans('date.past');
        }

        return ($r);
    }
}
if (!function_exists('LHS_Get_true_captcha'))
{
    function LHS_Get_true_captcha($section)
    {
        $session_name = 'captcha_' . $section;
        if (session()->has($session_name))
        {
            return session($session_name);
        }

        return false;
    }
}
if (!function_exists('LHS_Check_captcha'))
{
    function LHS_Check_captcha($section, $value)
    {
        $session_name = 'captcha_' . $section;
        if (session()->has($session_name))
        {
            if (session($session_name) == $value)
            {
                session()->forget($session_name);

                return true;
            }
        }

        return false;
    }
}
if (!function_exists('LHS_Merge_two_excel_file'))
{
    function LHS_Merge_two_excel_file($first_file_path, $second_file_path, $output_file_path, $output_file_type = 'Xlsx', $debug = false)
    {
        try
        {
            $memory_usage_logs = [];
            //ini_set('memory_limit', '1024M');
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($output_file_type);
            $objPHPExcel1 = \PhpOffice\PhpSpreadsheet\IOFactory::load($first_file_path);
            $memory_usage_logs['after_load_first_file'] = number_format(memory_get_usage());
            $objPHPExcel2 = \PhpOffice\PhpSpreadsheet\IOFactory::load($second_file_path);
            $memory_usage_logs['after_load_second_file'] = number_format(memory_get_usage());
            $objPHPExcel1->getActiveSheet()->fromArray(
                $objPHPExcel2->getActiveSheet()->toArray(),
                null,
                'A' . ($objPHPExcel1->getActiveSheet()->getHighestRow() + 1)
            );
            $memory_usage_logs['after_add_second_file_rows_to_first_file'] = number_format(memory_get_usage());
            $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel1, 'Xlsx');
            $objWriter->save($output_file_path);
            $memory_usage_logs['after_save_output_file'] = number_format(memory_get_usage());
            if ($debug)
            {
                return $memory_usage_logs;
            }
            else
            {
                return true;
            }
        } catch (Throwable $e)
        {
            if ($debug)
            {
                return $e->getMessage();
            }
            else
            {
                return false;
            }
        }
    }
}
if (!function_exists('LHS_Create_excel_file_from_data_array'))
{
    function LHS_Create_excel_file_from_data_array($arr_data, $output_file_path, $output_file_type = 'Xlsx', $debug = false)
    {
        try
        {
            $writer_class_address = "\\PhpOffice\\PhpSpreadsheet\\Writer\\" . $output_file_type;
            $memory_usage_logs = [];
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $writer = new $writer_class_address($spreadsheet);
            $sheet = $spreadsheet->getActiveSheet()->fromArray($arr_data);
            $memory_usage_logs['after_load_array_data'] = number_format(memory_get_usage());
            $res = $writer->save($output_file_path);
            $memory_usage_logs['after_save_output_file'] = number_format(memory_get_usage());
            $spreadsheet->__destruct();
            $memory_usage_logs['after_destroy_spreadsheet_object'] = number_format(memory_get_usage());
            if ($debug)
            {
                return $memory_usage_logs;
            }
            else
            {
                return true;
            }
        } catch (Throwable $e)
        {
            if ($debug)
            {
                return $e->getMessage();
            }
            else
            {
                return false;
            }
        }
    }
}
if (!function_exists('LHS_Create_excel_with_chunk_data'))
{
    function LHS_Create_excel_with_chunk_data($model_object, $output_file_path, $output_file_type = 'Xlsx', $debug = false)
    {
        try
        {
            $i = 0;
            $result = [];
            $writer_class_address = "\\PhpOffice\\PhpSpreadsheet\\Writer\\" . $output_file_type;
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $writer = new $writer_class_address($spreadsheet);
            $model_object::chunk(10000, function ($data) use (&$i, &$result, &$spreadsheet, &$writer) {
                $arr_data = $data->toArray();
                $result['after_to_array_data'] = number_format(memory_get_usage());
                $sheet = $spreadsheet->getActiveSheet()->fromArray(
                    $arr_data,
                    null,
                    'A' . ($spreadsheet->getActiveSheet()->getHighestRow() + 1)
                );
                $result['after_add_to_spreadsheet_data'] = number_format(memory_get_usage());
            });
            $result['after_do_all_chunks_data'] = number_format(memory_get_usage());
            $writer->save($output_file_path);
            $result['after_save_output_file'] = number_format(memory_get_usage());
            $spreadsheet->__destruct();
            $result['after_destroy_obj'] = number_format(memory_get_usage());
            if ($debug)
            {
                return $result;
            }
            else
            {
                return true;
            }
        } catch (Throwable $e)
        {
            if ($debug)
            {
                return $e->getMessage();
            }
            else
            {
                return false;
            }
        }
    }
}
if (!function_exists('LHS_Create_excel_with_chunk_data_from_query'))
{
    function LHS_Create_excel_with_chunk_data_from_query($connection_options, $query_str, $output_file_path, $output_file_type = 'Xlsx', $debug = false)
    {
        try
        {
            $time_start = microtime(true);

            Config::set('database.connections.dynamic_mysql.host', $connection_options['host']);
            Config::set('database.connections.dynamic_mysql.port', $connection_options['port']);
            Config::set('database.connections.dynamic_mysql.username', $connection_options['username']);
            Config::set('database.connections.dynamic_mysql.password', $connection_options['password']);
            Config::set('database.connections.dynamic_mysql.database', $connection_options['database']);

            $result = [];
            $writer_class_address = "\\PhpOffice\\PhpSpreadsheet\\Writer\\" . $output_file_type;
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $writer = new $writer_class_address($spreadsheet);

            $i = 1;
            $max = 10000;
            $time_start_db = microtime(true);
            $arr_data = \DB::connection('dynamic_mysql')->select($query_str, [0, $max]);
            $result[0]['execute_time_first_query'] = (microtime(true) - $time_start_db);
            $result[0]['after_execute_first_query'] = number_format(memory_get_usage());
            while (count($arr_data) > 0)
            {
                $offset = (($i - 1) * $max);
                $start = ($offset == 0 ? 0 : ($offset + 1));
                $arr_data = \DB::connection('dynamic_mysql')->select($query_str, [$start, $max]);
                $result[ $i ][ 'after_get_' . $i . '_' . $max . '_item' ] = number_format(memory_get_usage());
                $json = json_encode($arr_data);
                $arr_data = json_decode($json, true);
                $result[ $i ]['after_to_array_obj_to_array_array_data'] = number_format(memory_get_usage());
                $sheet = $spreadsheet->getActiveSheet()->fromArray(
                    $arr_data,
                    null,
                    'A' . ($spreadsheet->getActiveSheet()->getHighestRow() + 1)
                );
                $result[ $i ]['after_add_to_spreadsheet_data'] = number_format(memory_get_usage());
                $i++;
            }
            $result[ ++$i ]['after_do_all_chunks_data'] = number_format(memory_get_usage());
            $writer->save($output_file_path);
            $result[ ++$i ]['after_save_output_file'] = number_format(memory_get_usage());
            $spreadsheet->__destruct();
            $result[ ++$i ]['after_destroy_obj'] = number_format(memory_get_usage());
            if ($debug)
            {
                $result[ ++$i ] = 'Total execution time in seconds: ' . (microtime(true) - $time_start);

                return $result;
            }
            else
            {
                return true;
            }
        } catch (Throwable $e)
        {
            if ($debug)
            {
                return $e->getMessage();
            }
            else
            {
                return false;
            }
        }
    }
}
if (!function_exists('LHS_GetDistance'))
{
    function LHS_GetDistance($lat_1, $long_1, $lat_2, $long_2, $mode = 'driving,walking', $language = 'fa-IR')
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $lat_1 . "," . $long_1 . "&destinations=" . $lat_2 . "," . $long_2 . "&mode=" . $mode . "&language=" . $language;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        if ($response_a['status'] == 'OK')
        {
            $res['dist'] = $response_a['rows'][0]['elements'][0]['distance'];
            $res['time'] = $response_a['rows'][0]['elements'][0]['duration'];
            $res['status'] = $response_a['rows'][0]['elements'][0]['status'];
        }
        else
        {
            $res = false;
        }
    }
}
if (!function_exists('LHS_Generate_location_picker_modal'))
{
    function LHS_Generate_location_picker_modal($modal_id, $lat_input_id, $long_input_id, $map_area_id = 'map_area_id', $input_address = 'input_address', $btn_title = 'انتخاب موقعیت مکانی', $modal_title = 'انتخاب موقعیت مکان مورد نظر :', $map_area_width = '100%', $map_area_height = '420px', $marker_radius = 20)
    {
        $res['btn_open_modal'] = '<button class="btn btn-light" data-target="#' . $modal_id . '" data-toggle="modal" type="button"><i class="fa fa-map-marker"></i> ' . $btn_title . '</button>';
        $res['modal_content'] = view('common.location_picker', compact('modal_id', 'btn_title', 'modal_title', 'lat_input_id', 'long_input_id', 'map_area_id', 'input_address', 'map_area_width', 'map_area_height', 'marker_radius'))->render();

        return $res;
    }
}
if (!function_exists('LHS_BuildMenuTree'))
{
    /**
     * @param $flat_array
     * @param $pidKey
     * @param $openNodes
     * @param $selectedNodes
     * @param $item
     * @param int $parent
     * @param string $idKey
     * @param string $children_key
     * @return mixed
     */
    function LHS_BuildMenuTree($flat_array, $pidKey, $item = false, $openNodes = false, $selectedNodes = [], $parent = 0, $idKey = 'id', $children_key = 'children')
    {
        $grouped = [];
        foreach ($flat_array as $sub)
        {
            $sub['text'] = $sub['title'];
            if ($sub['href'] != '' && $sub['href_type'] == 0)
            {
                $sub['a_attr']['href'] = $sub['href'];
            }
            else
            {
                if ($sub['href'] != '' && $sub['href_type'] == 1 && Auth::check())
                {
                    $sub['href'] = str_replace('[username]', Auth::user()->Uname, $sub['href']);
                    if ($item)
                    {
                        $sub['href'] = str_replace('[subject_id]', $item, $sub['href']);
                        $sub['href'] = str_replace('[page_id]', $item, $sub['href']);
                    }
                    $sub['a_attr']['href'] = url($sub['href']);
                }
                else
                {
                    $route_var = json_decode($sub['route_variable']);
                    /* @FixedMe change username var to current login user */
                    if ($route_var != null)
                    {
                        $result_route_var = [];
                        foreach ($route_var as $rk => $rv)
                        {
                            //$rv =json_decode($rv);
                            if (isset($rv->username) || empty($rv->username))
                            {
                                $result_route_var[ $rk ] = Auth::user()->Uname;
                            }
                        }

                        $sub['a_attr']['href'] = route($sub['route_name'], $result_route_var);
                    }
                    else
                    {
                        $sub['a_attr']['href'] = $sub['href'];
                    }
                }
            }

            if ($openNodes)
            {
                $sub['state']['opened'] = true;
            }

            if (in_array($sub['a_attr']['href'], $selectedNodes))
            {
                $sub['state']['selected'] = true;
            }
            $grouped[ $sub[ $pidKey ] ][] = $sub;
        }
        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey, $children_key) {
            $siblings = sort_arr($siblings);
            foreach ($siblings as $k => $sibling)
            {
                $id = $sibling[ $idKey ];
                if (isset($grouped[ $id ]))
                {
                    $sibling[ $children_key ] = $fnBuilder($grouped[ $id ]);
                }
                $siblings[ $k ] = $sibling;
            }

            return $siblings;
        };
        if (isset($grouped[ $parent ]))
        {
            $tree = $fnBuilder($grouped[ $parent ]);
        }
        else
        {
            $tree = [];
        }

        return $tree;
    }

}
if (!function_exists('LHS_EncodeId'))
{
    function LHS_Afreplace($word)
    {
        $word = str_replace('ی', 'ي', $word);
        $word = str_replace('ک', 'ك', $word);

        return ($word);
    }
}

if (!function_exists('LHS_EncodeId'))
{
    function LFM_EncodeId($id)
    {
        if ($id < 0)
        {
            return $id;
        }
        else
        {
            $hashids = new \Hashids\Hashids(md5('sadeghi'));
            return $hashids->encode($id);
        }
    }
}

if (!function_exists('LHS_DecodeId'))
{
    function LHS_DecodeId($id, $route = false,$site_route)
    {
        if ((int)$id < 0)
        {
            return (int)$id;
        }
        else
        {
            $hashids = new \Hashids\Hashids(md5('sadeghi'));
            if ($route)
            {

                if (in_array($route->getName(), $site_route))
                {
                    if ($hashids->decode($id) != [])
                    {
                        return $hashids->decode($id)[0];
                    }
                    else
                    {
                        return $id;
                    }
                }
                else
                {
                    return $id;
                }
            }
            else
            {
                if (isset($hashids->decode($id)[0]))
                {
                    return $hashids->decode($id)[0];
                }
                else
                {
                    return $id ;
                }
            }
        }
    }
}
