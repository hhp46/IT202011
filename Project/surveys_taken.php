<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
//get latest 10 surveys we haven't take
$db = getDB();
$stmt = $db->prepare("SELECT DISTINCT title, Responses.survey_id from Responses Join Survey ON Responses.survey_id=Survey.id where Responses.user_id=:id LIMIT 10");
$r = $stmt->execute([":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys taken: " . var_export($stmt->errorInfo(), true));
}

?>

<h3>Survey's Taken</h3>

               
          <div class="results">
            <?php foreach ($results as $r): ?>
                
                    <div>
                        <div><?php safer_echo($r["title"]); ?></div> <div> <?php safer_echo($r["survey_id"]); ?></div>
                    
                    </div>
             <?php endforeach; ?>
      
    <?php else: ?>
        <p>No results</p>
        
</div>
<?php require(__DIR__ . "/partials/flash.php"); ?>