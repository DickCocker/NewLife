<?php

$group = $_POST['group'];
$emoji_array = scandir("../src_global/emoji/$group/");
unset($emoji_array[array_search('.', $emoji_array)]);
unset($emoji_array[array_search('..', $emoji_array)]);
$emoji_array['group'] = $group;
$emoji_array = json_encode($emoji_array);
echo $emoji_array;

?>