<?php
    $db_link = mysqli_connect('host','user','password');
    mysqli_select_db($db_link, 'db_name');
    mysqli_set_charset($db_link, 'utf8');
    $sql = "SELECT db_name.m_date.date_timestamp, db_name.t_weather.weather, db_name.t_pressure.pressure, db_name.t_temperature.temp, db_name.t_humidity.humidity, db_name.t_wind.wind_speed, db_name.t_sunrise_sundown.sunrise, db_name.t_sunrise_sundown.sundown FROM db_name.m_date, db_name.t_weather, db_name.t_pressure, db_name.t_temperature, db_name.t_humidity, db_name.t_wind, db_name.t_sunrise_sundown WHERE db_name.m_date.date_timestamp = db_name.t_weather.date_timestamp AND db_name.m_date.place_ID = db_name.t_weather.place_ID AND db_name.t_weather.date_timestamp = db_name.t_pressure.date_timestamp AND db_name.t_weather.place_ID = db_name.t_pressure.place_ID AND db_name.t_pressure.date_timestamp = db_name.t_temperature.date_timestamp AND db_name.t_pressure.place_ID = db_name.t_temperature.place_ID AND db_name.t_temperature.date_timestamp = db_name.t_humidity.date_timestamp AND db_name.t_temperature.place_ID = db_name.t_humidity.place_ID AND db_name.t_humidity.date_timestamp = db_name.t_wind.date_timestamp AND db_name.t_humidity.place_ID = db_name.t_wind.place_ID AND db_name.t_wind.date_timestamp = db_name.t_sunrise_sundown.date_timestamp AND db_name.t_wind.place_ID = db_name.t_sunrise_sundown.place_ID order by db_name.m_date.date_timestamp asc;";
    $result = mysqli_query($db_link, $sql);
    print("<thead><tr><td>日付</td><td>天気</td><td>気圧[hpa]</td><td>気温[℃]</td><td>湿度[％]</td><td>風[m/s]</td><td>日の出</td><td>日の入り</td></tr></thead>");
    print("<tbody id='targetTable_tbody'>");
    
    while($row = mysqli_fetch_array($result)) {
        // echo print_r($row);       // Print the entire row data
        $date_timestamp  = $row[0];
        $weather = $row[1];
        $pressure  = $row[2];
        $temp = $row[3];
        $humidity  = $row[4];
        $wind_speed = $row[5];
        $sunrise  = $row[6];
        $sundown = $row[7];
        // $i++;
        print("<tr><td align='right'>$date_timestamp</td><td align='right'>$weather</td><td align='right'>$pressure</td><td align='right'>$temp</td><td align='right'>$humidity</td><td align='right'>$wind_speed</td><td align='right'>$sunrise</td><td align='right'>$sundown</td></tr>");

    }
    print("</tbody>");

    mysqli_close($db_link);
?>