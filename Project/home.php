<html>
<title> Home </title>
<head>

<h1>Home Page</h1>
</head>

<body>
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

<style>
p2 {
text-align:center;
font-weight: bold;
}

body {
  background-color: #F0F8FF;
}
h1 {
text-align: center;
color: #860d0d;
font-weight: bold;
}
</style>

</body>
</html>