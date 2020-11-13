<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//saving
if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $quest = $_POST["question"];
    $survey = $_POST["survey_id"];
      if ($survey <= 0) {
        $survey = null;
    }
    
    $db = getDB();
    
    if (isset($id)) {
        $stmt = $db->prepare("UPDATE Questions set question=:quest, survey_id=:survey where id=:id");
        $r = $stmt->execute([
            ":quest" => $quest,
            ":survey" => $survey,
            ":id" => $id
        ]);
        if ($r) {
            flash("Updated successfully with id: " . $id);
        }
        else {
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
    }
    else {
        flash("ID isn't set, we need an ID in order to update");
    }
}
?>
<?php
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
$stmt = $db->prepare("SELECT id,title from Survey LIMIT 10");
$r = $stmt->execute();
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h3>Edit Question</h3>
    <form method="POST">
        <label>Question</label>
        <input name="question" placeholder="Question" value="<?php echo $result["question"]; ?>"/>
        <label>Survey</label>
        <select name="survey_id" value="<?php echo $result["survey_id"];?>" >
            <option value="-1">None</option>
            <?php foreach ($surveys as $survey): ?>
                <option value="<?php safer_echo($survey["id"]); ?>" <?php echo ($result["survey_id"] == $survey["id"] ? 'selected="selected"' : ''); ?>
                ><?php safer_echo($survey["title"]); ?></option>
            <?php endforeach; ?>
        </select>
      
        
        <input type="submit" name="save" value="Update"/>
    </form>


<?php require(__DIR__ . "/partials/flash.php");