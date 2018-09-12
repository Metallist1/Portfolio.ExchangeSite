<?php
unset($_COOKIE['who']);
setcookie("who", "", time() - 3600, '/');
unset($_COOKIE['user']);
setcookie("user", "", time() - 3600, '/');
unset($_COOKIE['usergame']);
setcookie("usergame", "", time() - 3600, '/');
unset($_COOKIE['botid']);
setcookie("botid", "", time() - 3600, '/');
unset($_COOKIE['botgame']);
setcookie("botgame", "", time() - 3600, '/');
unset($_COOKIE['mod']);
setcookie("mod", "", time() - 3600, '/');
if(isset($_COOKIE['admin'])) {
unset($_COOKIE['admin']);
setcookie("admin", "", time() - 3600, '/');
}
if(isset($_COOKIE['bonus'])) {
unset($_COOKIE['bonus']);
setcookie("bonus", "", time() - 3600, '/');
}
header('Location: http://www.sazone.lt/Newproject/index.php');

?>