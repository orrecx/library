<?php
session_start();

$colors = array("Pink" => "f0d0d0", "Violet" => "cda8ef", "Blue" => "a8c1ef","Green" => "a8efab","Yellow" => "efee7b");
$color_pref_name="";
$color_pref_value="#b3d9ff";
$color_pref ="";

if (isset($_SESSION['color_preferences']))
{
    $color_pref_name = $_SESSION['color_preferences'];
    unset($_SESSION['color_preferences']);
}
elseif (isset($_COOKIE['color_preferences']))
{
    $color_pref_name = $_COOKIE['color_preferences'];
}

if($color_pref_name != "")
{
    $color_pref = '<a href="colourchooser.php"> Color preference: '. $color_pref_name . '</a> , ';
    $color_pref_value = "#". $colors[$color_pref_name];
    setcookie("color_preferences", $color_pref_name, time() + 24*3600);
}
else
{
    $color_pref = '<a href="colourchooser.php">To color preferences</a>, ';
}

?>