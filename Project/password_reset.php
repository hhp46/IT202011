<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//Note: we have this up here, so our update happens before our get/fetch
//that way we'll fetch the updated data and have it correctly reflect on the form below
//As an exercise swap these two and see how things change

$db = getDB();
//save data if we submitted the form
if (isset($_POST["saved"])) {
    $isValid = true;
    //check if our email changed
    $newEmail = get_email();
    if 

 	 
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
        
 

    
    }
   



?>
<br>

<br>
   
    <form method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" />
      
        <!-- DO NOT PRELOAD PASSWORD-->
        <label for="pw">Password</label>
        <input type="password" name="password"/>
        <label for="npw">New Password</label>
        <input type="password" name="newPassword"/>
        <label for="cpw">Confirm Password</label>
        <input type="password" name="confirm"/>
        <input type="submit" name="saved" value="Update New Password"/>
    </form>
<?php require(__DIR__ . "/partials/flash.php");