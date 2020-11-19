<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
	   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
//get latest 10 surveys we haven't take
$db = getDB();
$stmt = $db->prepare("SELECT id, title, description, user_id FROM Survey and (SELECT id, survey_id, question_id answer_id FROM Responses on Questions.id=Responses.question_id)) WHERE Questions.survey_id = :id order by created desc LIMIT 10");
$r = $stmt->execute([":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys: " . var_export($stmt->errorInfo(), true));
}
$count = 0;
if (isset($results)) {
    $count = count($results);
}
?>











<?php require(__DIR__ . "/partials/flash.php");