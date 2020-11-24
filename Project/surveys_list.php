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
$db = getDB();
$stmt = $db->prepare("SELECT title, Responses.survey_id from Responses Join Survey ON Responses.survey_id=Survey.id where Responses.user_id=:id order by Responses.created ASC LIMIT 10");
$r = $stmt->execute([":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys taken: " . var_export($stmt->errorInfo(), true));
}

?>



<h3>Survey's Taken</h3>
<br>

<h4>Title -- ID</h4>


<?php if (count($results) > 0): ?>
               
          <div class="results">
            <?php foreach ($results as $r): ?>
                
                    <div>
                        <div><?php safer_echo($r["title"]); ?> - <?php safer_echo($r["survey_id"]); ?></div>
                    
                    </div>
           
      
            </div>
             <?php endforeach; ?>
           
                 
    <?php else: ?>
        <p>No results</p>
           <?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php"); ?>