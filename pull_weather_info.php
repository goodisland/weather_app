<?php
$url = "http://api.openweathermap.org/data/2.5/weather?id=1850147&units=metric&appid=248461d86de5f4e441779f9d3b216aa7";
$json = file_get_contents($url);
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$arr = json_decode($json,true);

//For Debug
$timezoneID = 'Asia/Tokyo';
date_default_timezone_set($timezoneID);

//値の取得
$float_coord_lon = (float)$arr["coord"]["lon"]; //経度
$float_coord_lat = (float)$arr["coord"]["lat"]; //緯度
$int_weather_id = (int)$arr["weather"][0]["id"]; //気候のID
$str_weather_main = $arr["weather"][0]["main"]; //気候
$str_weather_description = $arr["weather"][0]["description"]; //気候の描写
$str_weather_icon = $arr["weather"][0]["icon"]; //気候のアイコンのID
$str_base = $arr["base"]; //ベース(?)
$float_main_temp = (float)$arr["main"]["temp"]; //平均気温
$float_main_feelsLike = (float)$arr["main"]["feels_like"]; //体感気温
$float_main_tempMin = (float)$arr["main"]["temp_min"]; //最低気温
$float_main_tempMax = (float)$arr["main"]["temp_max"]; //最高気温
$int_main_pressure = (int)$arr["main"]["pressure"]; //気圧
$int_main_humidity = (int)$arr["main"]["humidity"]; //湿度
$int_visibility = (int)$arr["visibility"]; //視界
$float_wind_speed =  (float)$arr["wind"]["speed"]; //風速
$int_wind_deg =  (int)$arr["wind"]["deg"]; //風向
$int_clouds_all = (int)$arr["clouds"]["all"]; //雲量
$int_dt = (int)$arr["dt"]; //時刻
$date_dt = date('Y-m-d H:i' ,$int_dt);
$int_sys_type = (int)$arr["sys"]["type"]; //システムのタイプ(?)
$int_sys_id = (int)$arr["sys"]["id"]; //システムのID(?)
$str_sys_country = $arr["sys"]["country"]; //国
$int_sys_sunrise = (int)$arr["sys"]["sunrise"]; //日の出
$date_sunrise = date('Y-m-d H:i' ,$int_sys_sunrise);
$int_sys_sunset = (int)$arr["sys"]["sunset"]; //日没
$date_sunset = date('Y-m-d H:i' ,$int_sys_sunset);
$int_timezone = (int)$arr["timezone"]; //タイムゾーン
$int_id = (int)$arr["id"]; //ID
$str_name = $arr["name"]; //都市名
$int_cod = (int)$arr["cod"]; //コード(?)

// echo $date_dt;

//Debug
$weather_info_array = array(
        $float_coord_lon => '経度',
        $float_coord_lat => '緯度',
        $int_weather_id => '気候のID',
        $str_weather_main => '気候',
        $str_weather_description => '気候の描写',
        $str_weather_icon => '気候のアイコンのID',
        $str_base => 'ベース',
        $float_main_temp => '平均気温',
        $float_main_feelsLike => '体感気温',
        $float_main_tempMin => '最低気温',
        $float_main_tempMax => '最高気温',
        $int_main_pressure => '気圧',
        $int_main_humidity => '湿度',
        $int_visibility => '視界',
        $float_wind_speed => '風速',
        $int_wind_deg => '風向',
        $int_clouds_all => '雲量',
        $date_dt => '時刻',
        $int_sys_type => 'システムのタイプ',
        $int_sys_id => 'システムのID',
        $str_sys_country => '国',
        $date_sunrise => '日の出',
        $date_sunset => '日没',
        $int_timezone => 'タイムゾーン',
        $int_id => 'ID',
        $str_name => '都市名',
        $int_cod => 'コード',
);

// //エラーを表示しない。
// ini_set('display_errors', 0);

//表示
echo '天気表示webシステム 変数一覧\n';
foreach($weather_info_array as $key => $value){
        echo '・' . $value . '：' . $key . '\n';
}

$db_link = mysqli_connect('host','user','password');
mysqli_select_db($db_link, 'db_name');
mysqli_set_charset($db_link, 'utf8');

// DML配列
$array_query = [
        "INSERT INTO db_name.m_place (place_ID, place_name, country, timezone, latitude, longitude) VALUES($int_id, '$str_name', '$str_sys_country', $int_timezone, $float_coord_lat, $float_coord_lon)",
        "INSERT INTO db_name.m_date (place_ID, date_timestamp) VALUES($int_id, FROM_UNIXTIME($int_dt))",
        "INSERT INTO db_name.t_temperature (place_ID, date_timestamp, temp, temp_min, temp_max, temp_feelslike) VALUES($int_id, FROM_UNIXTIME($int_dt), $float_main_temp, $float_main_tempMin, $float_main_tempMax, $float_main_feelsLike)",
        "INSERT INTO db_name.t_weather (place_ID, date_timestamp, weather, weather_details) VALUES($int_id, FROM_UNIXTIME($int_dt), '$str_weather_main', '$str_weather_description')",
        "INSERT INTO db_name.t_wind (place_ID, date_timestamp, wind_speed, wind_deg) VALUES($int_id, FROM_UNIXTIME($int_dt), $float_wind_speed, $int_wind_deg)",
        "INSERT INTO db_name.t_pressure (place_ID, date_timestamp, pressure) VALUES($int_id, FROM_UNIXTIME($int_dt), $int_main_pressure)",
        "INSERT INTO db_name.t_humidity (place_ID, date_timestamp, humidity) VALUES($int_id, FROM_UNIXTIME($int_dt), $int_main_humidity)",
        "INSERT INTO db_name.t_sunrise_sundown (place_ID, date_timestamp, sunrise, sundown) VALUES($int_id, FROM_UNIXTIME($int_dt), FROM_UNIXTIME($int_sys_sunrise), FROM_UNIXTIME($int_sys_sunset))",
];

//DML(INSERT)実行
foreach($array_query as $sql){
        $result_flag = mysqli_query($db_link, $sql);
        // print($sql);
        
        // エラーメッセージを表示したい場合は下記を有効にする。
        if (!$result_flag) {
                echo mysqli_error($db_link);
        }
}

//切断
$close_flag = mysqli_close($db_link);
if ($close_flag){
    print('切断に成功しました。<br>');
}

?>