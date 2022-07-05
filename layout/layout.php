<?php
$pages = [
    'home' => 'главная',
    'plans' => 'планы',
    'money' => 'деньги',
    'schedule' => 'распорядок дня',
];
$root = $_SERVER['DOCUMENT_ROOT'];


function layout_start ($p_id) {
    global $pages;
?>
<!DOCTYPE html>
<html lang="ru">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../layout/style.css">
<?php
echo "
    <link rel='stylesheet' href='../$p_id/style.css'>
    ";
?>
    <title> <?= $pages[$p_id] ?> </title>
</head>
<body>
    <header>
        <nav>
            <ul>
<?php
$first_half = ceil(count($pages) / 2);
$i = 1;
foreach ($pages as $temp_id => $temp_title) {
    if ($i <= $first_half) {
        if ($temp_id == 'home') {
            echo "<li> <a href='../'> <img src='../layout/src/$temp_id.svg' alt='$temp_title'> </a> </li>";
        }
        else {
            echo "<li> <a href='../$temp_id/'> <img src='../layout/src/$temp_id.svg' alt='$temp_title'> </a> </li>";
        }
    }
    else {
        break;
    }
    $i++;
}
echo "
            </ul>
            <h1> $pages[$p_id] </h1>
            <ul>
";
$i = 0;
foreach ($pages as $temp_id => $temp_title) {
    $i++;
    if ($i > $first_half) {
        if ($temp_id == 'home') {
            echo "<li> <a href='../'> <img src='../layout/src/$temp_id.svg' alt='$temp_title'> </a> </li>";
        }
        else {
            echo "<li> <a href='../$temp_id/'> <img src='../layout/src/$temp_id.svg' alt='$temp_title'> </a> <li>";
        }
    }
    else {
        continue;
    }
}
echo "</ul>";
if (bcmod(count($pages), 2) != 0) {
    echo "<div class='empty'> </div>";
}
?>
        </nav>
    </header>
    <section>
<?php
}

function layout_end ($p_id) {
    global $root;
?>
    </section>
    <footer>

    </footer>
<?php
if ($p_id == 'home') {
    $js_list = glob("$root/*.js");
}
else {
    $js_list = glob("$root/$p_id/*.js");
}
if (isset($js_list)) {
    foreach($js_list as $js) {
        $js = basename($js);
        if ($p_id == 'home') {
            echo "<script type='text/javascript' src='../$js'></script>";
        }
        else {
            echo "<script type='text/javascript' src='../$p_id/$js'></script>";
        }
    }
}
?>
</body>
</html>
<?php
}
?>