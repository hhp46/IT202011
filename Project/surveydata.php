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
$stmt = $db->prepare("SELECT Survey.id, title, user_id FROM Survey JOIN Questions ON Responses.question_id = Questions.id JOIN Answers ON Answers.id = Responses.answer_id  LEFT JOIN Responses ON Responses.user_id, Responses.survey_id, Responses.question_id, Responses.answer_id, answer, question WHERE Survey.id = :survey_id AND Responses.survey_id = :survey AND Responses.user_id = :user");

//SELECT r.survey_id, r.question_id, r.answer_id, answer, question FROM Questions q JOIN Answers a ON a.question_id=q.id LEFT JOIN Responses r ON r.survey_id=q.survey_id WHERE r.survey_id = 1

//SELECT a.id, COUNT (DISTINCT r.user_id) FROM Questions q JOIN Answers a ON a.question_id=q.id LEFT JOIN Responses r ON r.survey_id=q.survey_id WHERE  r.survey_id = 1 GROUP BY a.id


$r = $stmt->execute([":survey_id" => $sid, ":survey" => $sid, ":user" => get_user_id()]);
if ($r) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching survey data: " . var_export($stmt->errorInfo(), true));
}
}
?>

<h3>Survey DATA</h3>

        
 <?php if (isset($result) && !empty($result)): ?>
              <div class="results">   
<div class="card-body">
            <div>
                
                <div>Survey Title: <?php safer_echo($result["title"]); ?></div>
                
      
      
        
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
        
 </div>  
  <?php endif; ?>


       



















   
<?php require(__DIR__ . "/partials/flash.php");