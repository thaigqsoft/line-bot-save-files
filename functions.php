<?php
function getuser_profile($a)
{
    $b = print_r($a, true);
    $text_ex = explode('=>', $b);
    $text_ex[2] = str_replace('}', null, $text_ex[2]);
    $text_ex[2] = str_replace('{', null, $text_ex[2]);
    $user_profle = explode(',', $text_ex[2]);
    $a = explode(':', $user_profle[0]);
    $a[1] = str_replace('"', null, $a[1]);
    $user_profle_data['displayName'] = $a[1];
    $a = explode(':', $user_profle[1]);
    $a[1] = str_replace('"', null, $a[1]);
    $user_profle_data['userId'] = $a[1];
    $a = explode(':', $user_profle[2]);
    $a[2] = str_replace('"', null, $a[2]);
    $a[2] = str_replace('//', null, $a[2]);
    $user_profle_data['pictureUrl'] = 'http://' . $a[2];
    $a = explode(':', $user_profle[3]);
    $user_profle_data['statusMessage'] = $a[1];
    return $user_profle_data;
}

function replace_text($text)
{

    for ($x = 0; $x <= 10; $x++) {
        $text = str_replace(' ', null, $text);
    }
    return $text;
}

function debug_system($txt)
{
    $myfile = fopen("debug.txt", "w") or die("Unable to open file!");

    fwrite($myfile, $txt . "\r\n");
    fclose($myfile);
}
