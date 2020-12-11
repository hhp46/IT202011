<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php
    $survey=  $_GET["id"];
    $db = getDB();
  $stmt = $db->prepare("SELECT ques.id,ques.question,ques.survey_id from Questions as ques WHERE ques.survey_id = :id");
    $r = $stmt->execute([":id" => $survey]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }

?>
<h3>List of Questions</h3>

<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                
                    <div>
                        <div><b>Question:</b> <?php safer_echo($r["question"]); ?></div>
                    </div>
                   
                    <div>
                      
                        <a type="button" href="answers.php?id=<?php safer_echo($r['id']); ?>">Create Answers</a>
                        
                        
                    </div>
                </div>
                <br>
                <br>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php"); ?>