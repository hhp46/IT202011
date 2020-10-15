<title> Home Page </title>
<h1> Home Page</h1>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the username to display

$username = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["username"])) {
    $username = $_SESSION["user"]["username"];
}


?>
<br>

<p2>Welcome, <?php echo  $username;  ?></p2>

<?php require(__DIR__ . "/partials/flash.php");