
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<link rel="stylesheet" href="<?php echo getURL("static/css/styles.css"); ?>">
<nav>
<ul class="nav">
    <li><a class="active" href="<?php echo getURL("home.php"); ?>">Home</a></li>
    <?php if (!is_logged_in()): ?>
        <li><a href="<?php echo getURL("login.php"); ?>">Login</a></li>
        <li><a href="<?php echo getURL("register.php"); ?>">Register</a></li>
    <?php endif; ?>
     <?php if (has_role("Admin")): ?>
      <div class="dropdown">
    <button class="dropbtn">Admin
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
     
            <a href="<?php echo getURL("test/test_create_survey.php"); ?>">Create Survey</a>
            <a href="<?php echo getURL("test/test_list_survey.php"); ?>">View Surveys</a>
            <a href="<?php echo getURL("test/test_create_question.php"); ?>">Create Questions</a>
            <a href="<?php echo getURL("test/test_list_question.php"); ?>">View Questions</a>
            </div>
  </div> 
        <?php endif; ?>
    <?php if (is_logged_in()): ?>
    	<li><a href="<?php echo getURL("create_survey.php"); ?>">Create Survey</a></li>
    	<li><a href="<?php echo getURL("list_survey.php"); ?>">View Surveys</a></li>
    	<li><a href="<?php echo getURL("create_question.php"); ?>">Create Question</a></li>
    	<li><a href="<?php echo getURL("list_question.php"); ?>">View Questions</a></li>
    	<li><a href="<?php echo getURL("list_category.php"); ?>">View Category</a></li>
    	<li><a href="<?php echo getURL("surveys.php"); ?>">Take Surveys</a></li>
        <li style="float:right"><a href="<?php echo getURL("logout.php"); ?>">Logout</a></li>
         <li style="float:right"><a href="<?php echo getURL("profile.php"); ?>">Profile</a></li>
    <?php endif; ?>
</ul>
</nav>