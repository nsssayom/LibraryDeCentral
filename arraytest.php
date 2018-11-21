<?php
$array = array();
$array['title'] = "Musics of Big Bang";

$newArr = array();
$newArr[0] = "S. Hawking";
$newArr[1] = "Leonard";

$array['author'] = $newArr;

/*foreach ($array as $key => $value) {
    if ($key == "author") {
        foreach ($array['author'] as $ikey => $ivalue) {
            echo $ikey . "-" . $ivalue;
        }
    }
}
*/

echo $array[0][0];
?>