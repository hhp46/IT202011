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
    $visibil = $_POST["visibility"];
    
   	
   
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
		   $visibil = $_POST["visibility"];
		$stmt = $db->prepare("UPDATE Users set email = :email, visibility=:visibil ,username= :username where id = :id");
		$r = $stmt->execute([":email" => $newEmail, ":visibil"=>$visibil, ":username" => $newUsername, ":id" => get_user_id()]);
		if ($r) {
		flash("Updated Email/user/Visibility");
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
$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $s){

    }
}
$db = getDB();
$stmt = $db->prepare("SELECT count(Suvey.id) as total from Survey s where s.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;


$stmt = $db->prepare("SELECT s.id ,title, s.user_id from Survey s WHERE s.user_id = :id LIMIT :offset, :count");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id());
$stmt->execute();
$s = $stmt->errorInfo();
if($s[0] != "00000"){
    flash(var_export($s, true), "alert");
}


    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<?php

$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $s){

    }
}
$db = getDB();
$stmt = $db->prepare("SELECT count(Responses.title) as total from Responses s where s.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;



$db = getDB();
$stmt = $db->prepare("SELECT title, Count(Responses.survey_id) as TOTAL from Responses JOIN Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title LIMIT :offset, :count");
//$stmt = $db->prepare("SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses LEFT JOIN Survey ON Responses.survey_id=Survey.id UNION (SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses) Right Join Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id());
$stmt->execute();
$s = $stmt->errorInfo();
if($s[0] != "00000"){
    flash(var_export($s, true), "alert");
}


    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);



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
	
		
	<select name="visibility" value="<?php echo $result["visibility"];?>">
		<option value="0" <?php echo ($result["visibility"] == "0"?'selected="selected"':'');?>>Private</option>
                <option value="1" <?php echo ($result["visibility"] == "1"?'selected="selected"':'');?>>Public</option>
               
                
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


<?php if (count($result) > 0): ?>
               
          <div class="results">
            <?php foreach ($result as $re): ?>
                
                    <div>
                     <div>Title: <?php safer_echo($re["title"]); ?></div>
                       
                    </div>
                  
           
      
            </div>
             <?php endforeach; ?>
           
               
    <?php else: ?>
        <p>No Surveys Taken</p>
           <?php endif; ?>
             <br>
            <nav aria-label="Taken Surveys">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page+1) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
      </nav>
<?php require(__DIR__ . "/partials/flash.php");