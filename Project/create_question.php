<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
	   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
error_reporting(0);
//fetching
$result = [];
if (isset($id)) {
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Questions where id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
//get survey for dropdown
$db = getDB();
$stmt = $db->prepare("SELECT id,title, user_id from Survey LIMIT 10");
$r = $stmt->execute();
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h3>Create Question</h3>
    <form method="POST">
        <label>Question</label>
        <input name="question" placeholder="Question"/>
          <label for="question_0_answer_0">Answer</label>
                                <input class="form-control" type="text" id="question_0_answer_0"
                                       name="question_0_answer_0"
                                       required/>
                                       <label for="question_0_answer_1">Answer 2</label>
                                <input class="form-control" type="text" id="question_0_answer_1"
                                       name="question_0_answer_1"
                                       required/>
         <label>Survey</label>
        <select name="survey_id" value="<?php echo $result["survey_id"];?>" >
            <option value="-1">None</option>
            <?php foreach ($surveys as $survey): ?>
                <?php if($survey["user_id"] == get_user_id()):?>
                 <option value="<?php safer_echo($survey["id"]); ?>" <?php echo ($result["survey_id"] == $survey["id"] ? 'selected="selected"' : ''); ?>
                ><?php safer_echo($survey["title"]); ?></option>
                  <?php endif; ?>
                         
            <?php endforeach; ?>
        </select>
       
	
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $quest = $_POST["question"];
    $survey= $_POST["survey_id"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Questions (question, survey_id) VALUES(:quest, :survey)");
    $r = $stmt->execute([
        ":quest" => $quest,
        ":survey" => $survey,
        
        
    ]);
     if ($r) {//insert questions
        $survey_id = $db->lastInsertId();
        //we could bulk insert questions, but it'll be a bit complex to get the ids back out
        //for use in the Answers insert, so instead I'll do a less efficient route and insert a question and its
        //answers one at a time.
        //loop over each question, insert the question and respective answers
       
            if ($r) {//insert answers
                $question_id = $db->lastInsertId();
                $query = "INSERT INTO Answers(answer, question_id) VALUES";
                $params = [];
                foreach ($q["answers"] as $answerIndex => $a) {
                    if ($answerIndex > 0) {
                        $query .= ",";
                    }
                    $query .= "(:a$answerIndex, :qid)";
                    $params[":a$answerIndex"] = $a["answer"];
                }
                //only need to map this once since it's the same for this batch of answers
                $params[":qid"] = $question_id;
                //echo "<br>Answer<br>";
                //echo $query;
                //echo var_export($params, true);
                $stmt = $db->prepare($query);
                $r = $stmt->execute($params);
                if (!$r) {
                    $hadError = true;
                    flash("Error creating answers: " . var_export($stmt->errorInfo(), true));
                }
            }
            else {
                $hadError = true;
                flash("Error creating questions: " . var_export($stmt->errorInfo(), true));
            }
        }
    
    if ($r) {
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else {
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");