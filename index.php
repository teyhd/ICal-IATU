<?php
function get_stud_raspis($group,$dates,$graph,$user_id){
    $array = array();
    $ansr = array();
    $alls = array();
     $mysqlis = new mysqli(HOST_DB, LOGIN_DB, PASS_DB, "raspisanie");
    if (!$mysqlis->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
} 
    
  if ($stmt = $mysqlis->prepare("SELECT timeStart, timeStop, discipline, type,teacher, cabinet, subgroup FROM `timetable` WHERE `class`='{$group}' AND `date`='{$dates}'")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7);
    $num = 1;
    while ($stmt->fetch()) { 
        $col1 = normal($col1);
        $col2 = normal($col2);
        $array['subject'] = "$col3";
        switch ($col4) {
            case 'Лекция':
                $array['type'] =  "Лек";
                break;
           case 'Практика':
                $array['type'] =  "Пр";
                break;
           case 'Лабораторная работа':
                $array['type'] =  "Лаб";
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
        $temp ="$temp* [$num] [$col1-$col2] \n$col3 \nАудитория: [$col6]; \nПодгруппа: [$col7]; \nПреподаватель: [$col5] \n[{$col4}]";
        $num++;
    } 
    $part_d = explode("-", $dates);
    $alls['date'] = "$part_d[2].$part_d[1].$part_d[0]"; //120
    $alls['lessons'] = $ansr;

    $stmt->close(); 
    }   
    if ($temp==null){
        $temp = "В этот день нет пар!";
    } 
$mysqlis->close(); 
if ($graph==2) {
    $fin_json = json_encode($alls);
    g_create($fin_json,$user_id);
    return null;
}
return $temp;

} //Вывод для студентов