<html>
<head>

<style type="text/css">
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #2F4F4F;
}

li {
  float: left;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover:not(.active) {
  background-color: #111;
}

.active {
  background-color: #5F9EA0;
}
</style>
</head>

<body>



<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<br>

<ul>
    <li><a class="active" href="home.php"><b>Home</b></a></li>
    <?php if (!is_logged_in()): ?>
        <li><a  href="login.php"><b>Login</b></a></li>
        <li><a href="register.php"><b>Register</b></a></li>
    <?php endif; ?>
    <?php if (is_logged_in()): ?>
        <li><a href="profile.php"><b>Profile</b></a></li>
        <li><a href="logout.php"><b>Logout</b></a></li>
    <?php endif; ?>
</ul>

</body>
</html>