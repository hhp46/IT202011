<title> Home Page </title>
<h1> Home Page</h1>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>
<br>

<p2>Welcome, <?php echo $email;  ?></p2>

<?php require(__DIR__ . "/partials/flash.php");