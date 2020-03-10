<?php
date_default_timezone_set('Europe/Ulyanovsk');
$date = date('Y-m-d');
$date = "2020-03-10";
$date = htmlspecialchars($_POST["date"]);
$group ='АИСТбд-21';
$mysqlis = new mysqli("95.104.192.212", "vlad", "pXYMvrx8xILHDPxd", "raspisanie");
    if (!$mysqlis->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
} 
    
  if ($stmt = $mysqlis->prepare("SELECT timeStart, timeStop, discipline, type,teacher, cabinet, subgroup FROM `timetable` WHERE `class`='{$group}' AND `date`>='{$date}' AND `subgroup`<2")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7);
    $ansr = array();
   
    while ($stmt->fetch()) { 
        $timese_START = explode(":", $col1);
        $timese_END = explode(":", $col2);
        $dateps = explode("-", $date);
        $array['start'] = date('c', mktime($timese_START[0], $timese_START[1], $timese_START[2], $dateps[1], $dateps[2], $dateps[0]));
        $array['end']  = date('c', mktime($timese_END[0], $timese_END[1], $timese_END[2], $dateps[1], $dateps[2], $dateps[0]));
        $array['subject'] = "$col3";
        switch ($col4) {
            case 'Лекция':
                $array['type'] =  "Лк";
                break;
           case 'Практика':
                $array['type'] =  "Пр";
                break;
           case 'Лабораторная работа':
                $array['type'] =  "Лб";
                break;     
            
            default:
                $array['type'] =  "Н";
                break;
        }
        $array['teacher'] = "$col5";
        $array['audience'] = "$col6";
        $array['time_start'] = "$col1";
        $array['time_end'] = "$col2";
        $array['subgroup'] = "$col7";
        array_push($ansr,$array);
        $temp ="$temp* $col1";
        } 

    $stmt->close(); 
    }   
    if ($temp==null){

        array_push($ansr,$array);
    } 
$mysqlis->close(); 
    
$fin_json = json_encode($ansr);
echo $fin_json;
