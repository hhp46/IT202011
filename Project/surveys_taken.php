<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
//surveys taken by user
if (isset($_GET["id"])) {
    $sid = $_GET["id"];
$db = getDB();
$stmt = $db->prepare("SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses Join Survey ON Responses.survey_id=Survey.id WHERE Responses.survey_id = :survey and Responses.user_id=:id order by title ASC LIMIT 10");
$r = $stmt->execute([":survey" =>$sid ,":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys taken and count: " . var_export($stmt->errorInfo(), true));
}
}

?>



<h3>Survey's Taken</h3>
<br>

<h4>Title -- ID</h4>


<?php if (count($results) > 0): ?>
               
          <div class="results">
            <?php foreach ($results as $r): ?>
                
                    <div>
                        <div><?php safer_echo($r["title"]); ?> - <?php safer_echo($r["TOTAL"]); ?></div>
                    
                    </div>
           
      
            </div>
             <?php endforeach; ?>
           
               
            
       
    <?php else: ?>
        <p>No results</p>
           <?php endif; ?>
           
      
           
<?php require(__DIR__ . "/partials/flash.php"); ?>