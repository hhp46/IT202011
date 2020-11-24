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
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys taken: " . var_export($stmt->errorInfo(), true));
}

?>

<?php
//get how many times survey was taken 
if (isset($_GET["id"])) {
    $sid = $_GET["id"];
$db = getDB();
////SELECT title, COUNT(Responses.survey_id) from Responses Join Survey ON Responses.survey_id=Survey.id WHERE Responses.survey_id = 1 and Responses.user_id= 4 GROUP BY title
$stmt = $db->prepare("SELECT title, COUNT(Responses.survey_id) from Responses Join Survey ON Responses.survey_id=Survey.id WHERE Responses.survey_id = :survey and Responses.user_id= :id GROUP BY title");
$r = $stmt->execute([":survey" =>$sid ,":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching survey count: " . var_export($stmt->errorInfo(), true));
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
                        <div><?php safer_echo($r["title"]); ?> - <?php safer_echo($r["survey_id"]); ?></div>
                    
                    </div>
           
      
            </div>
             <?php endforeach; ?>
           
               
            
       
    <?php else: ?>
        <p>No results</p>
           <?php endif; ?>
           
           <?php if (count($results) > 0): ?>
           
              <div class="counts">
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