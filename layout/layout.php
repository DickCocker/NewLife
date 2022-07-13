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
    <script src="../layout/script.js"></script>
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

function emoji ($inpt_emoji = FALSE) {
    global $root;
    $emoji_groups = [
        'animals_and_nature' => 'животные и растения',
        'flags' => 'флаги',
        'food_and_drink' => 'еда и напитки',
        'objects' => 'предметы',
        'people' => 'люди',
        'smileys' => 'эмоции',
        'symbols' => 'символы',
        'travel_and_places' => 'путешествия',
    ];
    echo "<div id='emoji'>";
    if ($inpt_emoji) {
        echo "<img src='../src_global/emoji/$inpt_emoji' id='emoji_button' data-icon='$inpt_emoji'>";
    }
    else {
        echo "<img src='../src_global/emoji.svg' id='emoji_button' data-icon='$inpt_emoji'>";
    }
    echo '<div class="hidden">';
    echo "<ul>";
    foreach (scandir("$root/src_global/emoji/") as $emoji_dir) {
        if ($emoji_dir != '.' and $emoji_dir != '..') {
            if (isset($emoji_groups[$emoji_dir])) {
                $group_title = $emoji_groups[$emoji_dir];
            }
            else {
                $group_title = $emoji_dir;
            }
            echo "
            <li onclick='emoji_select_group(this, \"$emoji_dir\")'>
                <p> $group_title </p>
            </li>
            ";
        }
    }
    echo "
            </ul>
            <div id='emoji_list'></div>
        </div>
    </div>
    ";
}
?>