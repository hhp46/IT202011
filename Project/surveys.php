<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//get latest 10 surveys we haven't take
$db = getDB();
$stmt = $db->prepare("SELECT id, title, visibility, user_id FROM Survey WHERE (visibility = 2 OR (visibility <2 and user_id = :id)) and (SELECT count(1) from Responses where user_id = :id and survey_id = Survey.id) = 0 order by created desc LIMIT 10");
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
<?php
if (isset($_POST["results"])) {
 die(header("Location: " . getURL("results.php")));}
?>
<div class="container-fluid">
    <h3>Available Surveys (<?php echo $count; ?>)</h3>
    <?php if (isset($results) && $count > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $s): ?>
                <div >
                    <div class="row">
                        <div ><?php safer_echo($s["title"]); ?></div>
                         
                        <div>
                            <a type="button" href="<?php echo getURL("survey.php?id=" . $s["id"]); ?>">   Take Survey      </a>
                        </div>
                    </div>
                </div>
                <br>
                <br>
            <?php endforeach; ?>
        </div>
        <form method="POST">
        <input type="submit" name="results" value="Results Page"/>
         
    </form>
    <?php else: ?>
        <p>No surveys available</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php"); ?>