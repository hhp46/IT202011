<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
	   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>



<?php
if (isset($_GET["id"])) {
    $sid = $_GET["id"];
$db = getDB();
$stmt = $db->prepare("SELECT Survey.id, title, description, user_id FROM Survey WHERE Survey.id = :survey_id");
$r = $stmt->execute([":survey_id" => $sid]);
if ($r) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching survey detail: " . var_export($stmt->errorInfo(), true));
}
}
?>

<?php
if (isset($_GET["id"])) {
    $sid = $_GET["id"];
$db = getDB();
$stmt = $db->prepare("SELECT Responses.user_id, Responses.survey_id, Responses.question_id, Responses.answer_id, answer, question FROM Responses JOIN Answers on Answers.id = Responses.answer_id JOIN Questions on Responses.question_id = Questions.id WHERE Responses.survey_id = :survey and Responses.user_id = :user");

$r = $stmt->execute([":id" => get_user_id(),":survey_id" => $sid]);
if ($r) {
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching responses: " . var_export($stmt->errorInfo(), true));
}
}

?>


<h3>Your Survey Responses</h3>

        
 <?php if (isset($result) && !empty($result)): ?>
      <div class="results">   
        <div class="card-body">
            <div>
                
                <div>Survey Title: <?php safer_echo($result["title"]); ?></div>
                <div>Description:: <?php safer_echo($result["description"]); ?></div>
      
      
       <?php if (count($results) > 0): ?>
        
            <?php foreach ($results as $r): ?>
                
                    <div>
                        <div>Question: <?php safer_echo($r["question"]); ?></div>
                    </div>
                    <div>
                        <div>Answer: <?php safer_echo($r["answer"]); ?></div>
                    </div>
                    </div>
             <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
       

            
     
    
    
    
<?php require(__DIR__ . "/partials/flash.php");