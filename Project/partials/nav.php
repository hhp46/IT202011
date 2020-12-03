
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
       <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Admin
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
     
           
            <a class="nav-link"  href="<?php echo getURL("test/test_list_survey.php"); ?>">Search All Surveys</a>
             
           
            
            </div>
  </li>
        <?php endif; ?>
    <?php if (is_logged_in()): ?>
    	<li><a href="<?php echo getURL("create_survey.php"); ?>">Create Survey</a></li>
    	<li><a href="<?php echo getURL("list_survey.php"); ?>">View Surveys</a></li>
    	<li><a href="<?php echo getURL("create_quest.php"); ?>">Create Question</a></li>
    	<li><a href="<?php echo getURL("list_quest.php"); ?>">View Questions</a></li>
    	<li><a href="<?php echo getURL("list_category.php"); ?>">View Category</a></li>
    	<li><a href="<?php echo getURL("surveys.php"); ?>">Take Surveys</a></li>
    	<li><a href="<?php echo getURL("surveys_taken.php"); ?>">Taken Surveys</a></li>
        <li style="float:right"><a href="<?php echo getURL("logout.php"); ?>">Logout</a></li>
         <li style="float:right"><a href="<?php echo getURL("profile.php"); ?>">Profile</a></li>
         <li style="float:right"><a href="<?php echo getURL("user_surveys.php"); ?>">My Surveys</a></li>
    <?php endif; ?>
</ul>
</nav>