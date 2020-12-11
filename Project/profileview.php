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


?>
<?php
if (isset($_GET["id"])){
    $id = $_GET["id"];
}

?>


<?php
$result = [];
if (isset($id)){
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Users where id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt ->fetch(PDO::FETCH_ASSOC);
    if (!$result){
        $e = $stmt->errorInfo();
          flash("Error");  
    }
}
?>

<?php if ($result["visibility"] == 2): ?>
    
        <?php if (isset($result) && !empty($result)): ?>
            <div class="card">
                <div class="card-title">
                    <h3>Creator's Profile Information <?php $result["username"]; ?></h3>
                    
                </div>
                <br>
                <br>
                <div class="card-body">
                    <div>
                       
                        <?php if ($result["visibility"] == 1): ?>
                            
                            
                            
                        <?php endif; ?>
                       <div><b>Username:</b> <?php safer_echo($result["username"]); ?></div>
                    <br>
                     <div><b>Profile Visibility:</b> <?php getVisibility($result["visibility"]); ?></div>
                     <br>
                     <div><b>Email: </b><?php safer_echo($result["email"]); ?></div>
                         
                    
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>Error looking up id...</p>
        <?php endif; ?>
        
        
        
<?php elseif ($result["visibility"] == 1 && get_user_id() == $id): ?>
    <h2><?php echo $result["username"] . "'s Profile" ?></h2>
    <?php if (isset($result) && !empty($result)): ?>
        <div class="card">
            <div class="card-title">
                <h3>About <?php $result["username"]; ?></h3>
            </div>
            <div class="card-body">
                <div>
                    
                    <?php if ($result["visibility"] == 1): ?>
                        
                        
                    <?php endif; ?>
                    <div><b>Username:</b> <?php safer_echo($result["username"]); ?></div>
                    <br>
                     <div><b>Profile Visibility:</b> <?php getVisibility($result["visibility"]); ?></div>
                     <br>
                     <div><b>Email: </b><?php safer_echo($result["email"]); ?></div>
                    
                    
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>Error looking up id...</p>
    <?php endif; ?>
<?php else: ?>
    <h2><?php echo $result["username"] . "has a Private Profile. Can't access. Please go back!!" ?></h2>
<?php endif; ?>

<?php require_once(__DIR__ . "/partials/flash.php");
