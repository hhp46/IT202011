<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
	   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
if (isset($_POST["submit"])) {
   
    die(header("Location: " . getURL("surveys.php")));
      //  flash("Answers have been recorded", "success");
    }
    else {
        flash("There was an error going to surveys page: " . var_export($stmt->errorInfo(), true));
    }
    

?>

<?php
if (isset($_GET["id"])) {
    $sid = $_GET["id"];
$db = getDB();
$stmt = $db->prepare("SELECT title, description, user_id FROM Survey and (SELECT id, survey_id, question_id answer_id FROM Responses on Questions.id=Responses.question_id)) WHERE Responses.survey_id = :survey and Responses.user_id = :user");
$r = $stmt->execute([":id" => get_user_id(),":survey_id" => $sid]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching responses: " . var_export($stmt->errorInfo(), true));
}
$count = 0;
if (isset($results)) {
    $count = count($results);
}
?>

<?php
if (isset($_GET["id"])) {
    $sid = $_GET["id"];
$db = getDB();
$stmt = $db->prepare("SELECT user_id, survey_id, question_id, answer_id FROM Responses JOIN Answers on Answers.id = Responses.answer_id JOIN Questions on Responses.question_id = Questions.id WHERE Questions.survey_id = :id  ");
$r = $stmt->execute([":id" => get_user_id(),":survey_id" => $sid]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching responses: " . var_export($stmt->errorInfo(), true));
}
$count = 0;
if (isset($results)) {
    $count = count($results);
}
}
}
?>


<h3>Your Survey Responses</h3>

<div class="container-fluid">
        <div class="list-group">
            <?php foreach ($results as $s): ?>
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-8"><?php safer_echo($s["title"]); ?></div>
                          <div class="col-8"><?php safer_echo($s["description"]); ?></div>
                        <div class="col-8"><?php safer_echo($s["question"]); ?></div>  <div class="col-8"><?php safer_echo($s["answer_id"]); ?></div>
                        <div class="col">
                           <input type="submit" name="submit" class="btn btn-success btn-block" value="Available Surveys"/>
                           
                        </div>
                    </div>
                </div>
                
            <?php endforeach; ?>
        </div>
        
   
    <?php else: ?>
        
    <?php endif; ?>
</div>



<?php require(__DIR__ . "/partials/flash.php");