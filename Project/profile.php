<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//Note: we have this up here, so our update happens before our get/fetch
//that way we'll fetch the updated data and have it correctly reflect on the form below
//As an exercise swap these two and see how things change
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}





$db = getDB();
//save data if we submitted the form
if (isset($_POST["saved"])) {

    $isValid = true;
    
    
   	
   
    //check if our email changed
    $newEmail = get_email();
    if (get_email() != $_POST["email"]) {
        //TODO we'll need to check if the email is available
        $email = $_POST["email"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where email = :email");
        $stmt->execute([":email" => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }
            catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            flash("Email already in use");
            //for now we can just stop the rest of the update
            $isValid = false;
        }
        else {
            $newEmail = $email;
        }
    }
    $newUsername = get_username();
    if (get_username() != $_POST["username"]) {
        $username = $_POST["username"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where username = :username");
        $stmt->execute([":username" => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }
            catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            flash("Username already exists");
            //for now we can just stop the rest of the update
            $isValid = false;
        }
        else {
            $newUsername = $username;
        }
    }
    
   
 	  if ($isValid) {
		$userID = null;
		$currentPass = null;
		
		$stmt = $db->prepare("UPDATE Users set email = :email, username= :usernamewhere id = :id");
		$r = $stmt->execute([":email" => $newEmail, ":username" => $newUsername, ":id" => get_user_id()]);
		if ($r) {
		flash("Updated Email/user");
			 }
		else {
		flash("Error updating profile");
		    }
        //password is optional, so check if it's even set
        //if so, then check if it's a valid reset request
        if (!empty($_POST["newPassword"]) && !empty($_POST["confirm"]) && !empty($_POST["password"])) {
            $currentPass = $_POST["password"];
            $stmt = $db->prepare("SELECT password from Users WHERE id = :id");
            $params = array(":id" => get_user_id());
            $r = $stmt->execute($params);
           $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                flash("Something went wrong, please try again");  }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && isset($result["password"])) {
                
                $DBPassHash = $result["password"];
            if (password_verify($currentPass, $DBPassHash)) {
            flash("Old Password Correct!");
                if ($_POST["newPassword"] == $_POST["confirm"]) {
            flash("Password is Updated!");
                    $newPassword = $_POST["newPassword"];
                    $newPassHash = password_hash($newPassword, PASSWORD_BCRYPT);                        
                    //this one we'll do separate
                    $stmt = $db->prepare("UPDATE Users set password = :password where id = :id");
                    $r = $stmt->execute([":id" => get_user_id(), ":password" => $newPassHash]);
                 
             
                  
            } 
            else {
             flash("Passwords dont match!"); }
            }   
            else {
             flash("Error: Old password incorrect!");}
        }
       
        
        }
        
 
//fetch/select fresh data in case anything changed
        $stmt = $db->prepare("SELECT email, username from Users WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => get_user_id()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $email = $result["email"];
            $username = $result["username"];
            //let's update our session too
            $_SESSION["user"]["email"] = $email;
            $_SESSION["user"]["username"] = $username;
        }
        
        
    }
    }
    
   



?>
<br>

<?php

$db = getDB();
$stmt = $db->prepare("SELECT id,title, user_id from Survey WHERE user_id = :id LIMIT 10");
$r = $stmt->execute([":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys: " . var_export($stmt->errorInfo(), true));
}
$count = 0;
if (isset($results)) {
    $count = count($results);
}

?>
<?php
//surveys taken by user
//if (isset($_GET["id"])) {
  //  $sid = $_GET["id"];
  //Responses.survey_id = :survey and 
  //":survey"=>$sid,
$db = getDB();
$stmt = $db->prepare("SELECT title, Responses.survey_id from Responses JOIN Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title");
//$stmt = $db->prepare("SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses LEFT JOIN Survey ON Responses.survey_id=Survey.id UNION (SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses) Right Join Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title");
$re = $stmt->execute([":id" => get_user_id()]);
if ($re) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys taken and count: " . var_export($stmt->errorInfo(), true));
}


?>



<br>
   <h3>Update Profile Below</h3>
    <form method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php safer_echo(get_email()); ?>"/>
        <label for="username">Username</label>
        <input type="text" maxlength="60" name="username" value="<?php safer_echo(get_username()); ?>"/>
        <!-- DO NOT PRELOAD PASSWORD-->
        <label for="pw">Password</label>
        <input type="password" name="password"/>
        <label for="npw">New Password</label>
        <input type="password" name="newPassword"/>
        <label for="cpw">Confirm Password</label>
        <input type="password" name="confirm"/>
        
        <label>Visibility</label>
	<select name="visibility">
		
		<option value="0">Private</option>
		<option value="1">Public</option>
	</select>
	
	
        <input type="submit" name="saved" value="Save Profile"/>
    </form>
    
    
    
    
<h3>Surveys Created:</h3>


<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                
                    <div>
                        <div>Title: <?php safer_echo($r["title"]); ?></div>
                    </div>
                  
                   
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No Surveys Created</p>
    <?php endif; ?>
</div>

<h3>Survey's Taken</h3>
<br>



<?php if (count($result) > 0): ?>
               
          <div class="results">
            <?php foreach ($result as $re): ?>
                
                    <div>
                     <div>Title: <?php safer_echo($re["title"]); ?></div>
                       
                    </div>
           
      
            </div>
             <?php endforeach; ?>
           
               
    <?php else: ?>
        <p>No results</p>
           <?php endif; ?>
           
<?php require(__DIR__ . "/partials/flash.php");