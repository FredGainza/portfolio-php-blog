<?php
session_start();

$code_avatar = "";
$caract = "abcdefghijklmnopqrstuvwxyz0123456789";
$len = strlen($caract);
for ($i = 0; $i < 20; $i++) {
    $position_caract = rand(0, $len - 1);
    $code_avatar .= $caract[$position_caract];
}
if (isset($_GET['type_avatar']) && !empty($_GET['type_avatar'])) {
    $_SESSION['type_avatar'] = $_GET['type_avatar'];
}
// $lien_avatar = "https://avatars.dicebear.com/v2/".$_POST['avatar_type']."/" .$code_avatar. ".svg";
$lien_avatar = "https://avatars.dicebear.com/v2/" . $_SESSION['type_avatar'] . "/" . $code_avatar . ".svg";
$_SESSION['lien_avatar'] = $lien_avatar;
// $_SESSION['avatar_type'] = $_POST['avatar_type'];




header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
