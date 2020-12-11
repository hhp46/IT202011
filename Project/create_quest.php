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


?>
    <h3>Create Question</h3>
    <form method="POST">
        <label><b>Question</b></label>
        <br>
        <input name="question" placeholder="Question"/>
           
       
	
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $quest = $_POST["question"];
    $survey=  $_GET["id"];
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