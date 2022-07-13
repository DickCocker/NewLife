<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = 'root';
$db_title = 'newlife';
$sql = mysqli_connect($db_host, $db_username, $db_password, $db_title);

if ($sql) {
    switch ($_POST['operation']) {
        case 'add':
            switch ($_POST['then']) {
                case 'today':
                    $date = date('Y-m-d');
                    break;
                case 'tomorrow':
                    $year = date('Y');
                    $month = date('m');
                    $day = intval(date('d')) + 1;
                    if ($day > cal_days_in_month(CAL_GREGORIAN, $month, $year)) {
                        $day = 01;
                        $month = intval($month)+1;
                        if ($month > 12) {
                            $month = 01;
                            $year = intval($year) + 1;
                        }
                    }
                    $date = "$year-$month-$day";
                    break;
            }
            if ($_POST['then'] == 'non-period') {
                $sql_request = "INSERT INTO `plans` (`id`, `text`, `icon`, `date`, `checked`, `deadline`) VALUES (NULL, '$_POST[text]', NULL, '$date', '0', '0');";
            }
            else {
                $sql_request = "INSERT INTO `plans` (`id`, `text`, `icon`, `date`, `checked`, `deadline`) VALUES (NULL, '$_POST[text]', NULl, '$date', '0', '0');";
            }
            break;
        case 'del':
            $sql_request = "DELETE FROM plans WHERE `plans`.`id` = $_POST[id];";
            break;
        case 'check':
            $status = ($_POST['checked'] == 'true') ? 1 : 0;
            $sql_request = "UPDATE `plans` SET `checked` = '$status' WHERE `plans`.`id` = $_POST[id];";
            break;
        case 'calendar_del':
            $id = $_POST['id'];
            $sql_request = "DELETE FROM plans WHERE `plans`.`id` = $id";
            break;
        case 'calendar_add':
            $text = $_POST['text'];
            $check = $_POST['check'];
            $deadline = $_POST['deadline'];
            $date = $_POST['date'];
            $icon = $_POST['icon'];

            if ($icon) {
                $sql_request = "INSERT INTO `plans` (`id`, `text`, `icon`, `date`, `checked`, `deadline`) VALUES (NULL, '$text', '$icon', '$date', '$check', '$deadline');";
            }
            else {
                $sql_request = "INSERT INTO `plans` (`id`, `text`, `icon`, `date`, `checked`, `deadline`) VALUES (NULL, '$text', NULL, '$date', '$check', '$deadline');";
            }
            break;
        case 'calendar_change':
            $id = $_POST['id'];
            $text = $_POST['text'];
            $check = $_POST['check'];
            $deadline = $_POST['deadline'];
            $date = $_POST['date'];
            $icon = $_POST['icon'];
            
            $sql_request = "UPDATE `plans` SET `text` = '$text', `date` = '$date', `checked` = '$check', `deadline` = '$deadline', `icon` = '$icon' WHERE `plans`.`id` = $id;";
            break;

    }
    if (!$sql -> query($sql_request)) {
        echo 'Не удалось отправить запрос в базу данных';
    }
}
else {
    echo 'Не можем связаться с базой данных :(';
}
?>