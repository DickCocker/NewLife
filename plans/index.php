<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include("$root/layout/layout.php");

$p_id = 'plans';

layout_start($p_id);


$db_host = 'localhost';
$db_username = 'root';
$db_password = 'root';
$db_title = 'newlife';
$sql = mysqli_connect($db_host, $db_username, $db_password, $db_title);

if ($sql) {
    $divs = [
        ['id' => 'today', 'head' => 'сегодня'],
        ['id' => 'non-period', 'head' => 'когда-нибудь'],
        ['id' => 'calendar', 'head' => 'календарь'],
        ['id' => 'tomorrow', 'head' => 'завтра']
    ];
    foreach($_COOKIE as $key => $value) {
        $operation = mb_substr($key, 0, mb_strpos($key, '_'));
        switch ($operation) {
            case 'add':
                $belonging = mb_substr($key,  mb_strpos($key, '_')+1);
                $belonging = mb_substr($belonging, 0, mb_strpos($belonging, '_'));
                $in_array = FALSE;
                foreach($divs as $div) {
                    if (in_array($belonging, $div)) {
                        $in_array = TRUE;
                        break;
                    }
                }
                if ($in_array) {
                    $sql_request = "INSERT INTO `plans` (`id`, `text`, `checked`, `date`) VALUES (NULL, '$value', '0', '$date');";
                }
                break;
            case 'del':
                $sql_request = "DELETE FROM plans WHERE `plans`.`id` = $value";
                break;
            case 'check':
                $id = mb_substr($key, mb_strpos($key, '_')+1);
                if ($value == 'true') {
                    $status = 1;
                }
                else {
                    $status = 0;
                }
                    $sql_request = "UPDATE `plans` SET `checked` = '$status' WHERE `plans`.`id` = $id;";
                break;
        }
        if ($sql -> query($sql_request)) {
            setcookie($key, '');
        }
    }

    foreach ($divs as $div) {
        echo "
        <div id='$div[id]'>
            <div class='head'>
                <h2> $div[head] </h2>
            </div>
        ";
        if ($div['id'] != 'calendar') {
            switch ($div['id']) {
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
            if ($div['id'] == 'non-period') {
                $query_result = mysqli_query($sql, "SELECT id, text, checked FROM plans WHERE date IS NULL");
            }
            else {
                $query_result = mysqli_query($sql, "SELECT id, text, checked FROM plans WHERE date = '$date'");
            }
            if ($query_result) {
                for ($data = []; $row = mysqli_fetch_assoc($query_result); $data[] = $row);
                unset($query_result);
                echo '<ul>';
                if (empty($data)) {
                    echo '<li class="no_plans"> <p> Пока тут ничего нет </p> </li> <hr class="no_plans">';
                }
                else {
                    foreach ($data as $elem) {
                        if ($elem['checked'] == '0') {
                            echo "<li id='id$elem[id]'> <input type='checkbox' onclick='check(this, \"$elem[id]\")'> <p> $elem[text] </p> <button class='del' onclick='del(\"$elem[id]\", \"$div[id]\")'> <img src='../$p_id/src/delete.svg' alt='delete'> </button> </li> <hr>";
                        }
                        else {
                            echo "<li id='id$elem[id]'> <input type='checkbox' onclick='check(this,\"$elem[id]\")' checked> <p> $elem[text] </p> <button class='del' onclick='del(\"$elem[id]\", \"$div[id]\")'> <img src='../$p_id/src/delete.svg' alt='delete'> </button> </li> <hr>";
                        }
                    }
                }
                echo "<li class='add'> <input type='text' placeholder='новая задача'> <button class='add' onclick='add(\"$div[id]\", \"$p_id\")'> <img src='../$p_id/src/add.svg' alt='add'> </button> </li>";
                echo '</ul>';
            }
            else {
                echo '<p> Не удалось извлечь данные из базы данных, приносим свои извенения! </p>';
            }
        }
        else {
            if ($_GET['year']) {
                $year = $_GET['year'];
            }
            else {
                $year = date('Y');
            }
            if ($_GET['month']) {
                $month = $_GET['month'];
            }
            else {
                $month = date('m');
            }
            $date = "$year-$month-%";
            $query_result = mysqli_query($sql, "SELECT id, text, date, checked FROM plans WHERE date LIKE '$date'");
            if ($query_result) {
                for ($data = []; $row = mysqli_fetch_assoc($query_result); $data[] = $row);
                unset($query_result);
                #var_dump($data);
                echo '<table>';
                foreach (range(1, 10) as $row) {
                    echo '<tr>';
                    foreach (range(1, cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'))) as $day) {
                        echo '<td>';
                        
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            }
            else {
                echo '<p> Не удалось извлечь данные из базы данных, приносим свои извенения! </p>';
            }
        }
        echo "</div>";
    }
}
else {
    echo 'Не можем связаться с базой данных :(';
}
var_dump($_COOKIE);

layout_end($p_id);
?>