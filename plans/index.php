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
                echo "<li class='add'> <input type='text' placeholder='новая задача'> <button class='add' onclick='add(\"$div[id]\")'> <img src='../$p_id/src/add.svg' alt='add'> </button> </li>";
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
            $query_result = mysqli_query($sql, "SELECT * FROM plans WHERE date LIKE '$date'");
            if ($query_result) {
                for ($data = []; $row = mysqli_fetch_assoc($query_result); $data[] = $row);
                unset($query_result);
                $count_rows = 10;
                $count_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                echo '<table>';
                foreach (range(1, $count_rows) as $row) {
                    echo "<tr style='height: calc(100% / $count_rows)'>";
                    foreach (range(1, $count_days) as $day) {
                        $day = str_pad($day, 2, '0', STR_PAD_LEFT);
                        $date = "$year-$month-$day";
                        foreach ($data as $key => $value) {
                            if ($value['date'] == $date) {
                                $target = $value;
                                unset($data[$key]);
                                break;
                            }
                        }
                        if ($target) {
                            echo "<td id='$target[id]' onclick='calendar_change_set(\"$target[id]\", \"$target[text]\", $target[checked], $target[deadline], \"$target[date]\", \"$target[icon]\")' style='width: calc(100% / $count_days)'>";
                            if ($target['icon']) {
                                echo "<img src='../src_global/emoji/$target[icon]'>";
                            }
                            else {
                                echo "<img src='./src/point.svg'>";
                            }
                        }                  
                        else {
                            echo "<td class='empty' onclick='calendar_add_set(\"$target[date]\")' style='width: calc(100% / $count_days)'>";
                        }
                        echo '</td>';
                        unset($target);
                    }
                    echo '</tr>';
                }
                echo '</table>';
?>
<div class="body_shadow hidden">
    <div id="calendar_window">
        <div class="head">
            <div class="empty"></div>
            <h2></h2>
            <button id='close' onclick="calendar_window_close()"></button>
        </div>
        <textarea placeholder="Текст задачи"></textarea>
        <div id="options">
            <div id="left">
                <div id="top">
                    <div id="check">
                        <p> выполнено? </p>
                        <input type="checkbox">
                    </div>
                    <div id="deadline">
                        <p> дэдлайн? </p>
                        <input type="checkbox">
                    </div>
                </div>
                <div id="date">
                    <p> дата: </p>
                    <input type="date">
                </div>
            </div>
            <div id="right">
                <?= emoji($target['icon']) ?>
                <div id="save-cancel">
                    <button id="del" onclick="calendar_window_del()"> удалить </button>
                    <button id="save" onclick="calendar_window_save()"> сохранить </button>
                    <button onclick="calendar_window_close()"> отменить </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
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

layout_end($p_id);
?>