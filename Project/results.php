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
$stmt = $db->prepare("SELECT Responses.id as id, Responses.user_id, Responses.survey_id, Responses.question_id, answer, question, Count(Responses.answer_id) as TOTAL FROM Responses JOIN Answers on Answers.id = Responses.answer_id JOIN Questions on Responses.question_id = Questions.id WHERE Responses.survey_id = :survey and Responses.user_id = :user GROUP BY id" );

//SELECT count(1) FROM Responses where Responses.answer_id = answer_id



$r = $stmt->execute([":survey" => $sid,":user" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching responses: " . var_export($stmt->errorInfo(), true));
}
}

?>


<h3><u>Your Survey Responses</u></h3>

        
 <?php if (isset($result) && !empty($result)): ?>
              <div class="results">   
<div class="card-body">
            <div>
                
                <div><b>Survey Title:</b> <?php safer_echo($result["title"]); ?></div>
                <div><b>Description:</b> <?php safer_echo($result["description"]); ?></div>
      <br>
      <br>
      
      
        
            <?php foreach ($results as $r): ?>
                
                    <div>
                        <div><b>Question:</b> <?php safer_echo($r["question"]); ?></div>
                    </div>
                    <div>
                        <div><b>Answer:</b> <?php safer_echo($r["answer"]); ?> - -  <?php safer_echo($r["TOTAL"]); ?> time(s) picked</div>
                    </div>
                    </div>
                    <br>
                    
             <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results - Insert Id of Survey you took in Url to display results</p>
        
 </div>  
  <?php endif; ?>


       
   
                        
     
    
    
    
<?php require(__DIR__ . "/partials/flash.php");