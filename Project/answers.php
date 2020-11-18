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


?>
    <h3>Create Answers</h3>
    <form method="POST">
        <label>Answer</label>
        <input name="answer" placeholder="Answer"/>
          
	
        <input type="submit" name="save" value="Create"/>
    </form>

<?php
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $a = $_POST["answer"];
    $qid = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Answers (answer, question_id) VALUES(:a, :qid)");
    $r = $stmt->execute([
        ":a" => $a,
        ":qid" => $qid,
        
        
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