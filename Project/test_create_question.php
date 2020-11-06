<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
    <h3>Create Question</h3>
    <form method="POST">
        <label>Question</label>
        <input name="question" placeholder="Question"/>
        <label>Survey</label>
	<select name="survey_id">
		<option value="0"> Survey 1</option>
		<option value="1"> Survey 2</option>
		<option value="2"> Survey 3</option>
		<option value="3"> Survey 4</option>
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