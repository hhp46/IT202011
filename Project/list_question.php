<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT ques.id,ques.question,ques.survey_id, Survey.user_id from Questions as ques JOIN Survey on ques.survey_id = Survey.id WHERE ques.question like :q order by created desc LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
}
?>
<h3>List Questions</h3>
<form method="POST">
    <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                 <div>
                        <div>ID: <?php safer_echo($r["id"]); ?></div>
                    </div>
                    <div>
                        <div>Question: <?php safer_echo($r["question"]); ?></div>
                    </div>
                    <div>
                        <div>Survey: <?php safer_echo($r["survey_id"]); ?></div>
                    </div>
                    
                    <div>
                        <?php if($r["user_id"] == get_user_id()):?>

                        <a type="button" href="edit_question.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        
                               
                        <?php endif; ?>
                        <a type="button" href="view_question.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>