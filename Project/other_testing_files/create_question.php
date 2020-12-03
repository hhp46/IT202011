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