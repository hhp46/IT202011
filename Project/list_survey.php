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
    $stmt = $db->prepare("SELECT id,title,description, category, visibility, user_id from Survey WHERE (visibility = 2 OR (visibility <2 and user_id = :id)) and title like :q order by created desc LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%", ":id" => get_user_id()]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>
<h3>List Surveys</h3>
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
                        <div>Title: <?php safer_echo($r["title"]); ?></div>
                    </div>
                    <div>
                        <div>Description: <?php safer_echo($r["description"]); ?></div>
                    </div>
                     <div>
                        <div>Catgory: <?php safer_echo($r["category"]); ?></div>
                    </div>
                    <div>
                        <div>Visibility: <?php getVisibility($r["visibility"]); ?></div>
                    </div>
                    <div>
                        <div>Owner Id: <?php safer_echo($r["user_id"]); ?></div>
                    </div>
                    <div>
                    <?php if($r["user_id"] == get_user_id()):?>


                        <a type="button" href="edit_survey.php?id=<?php safer_echo($r['id']); ?>">Edit</a> 
                        <a type="button" href="create_quest.php?id=<?php safer_echo($r['id']); ?>">Add Question</a> 
                        
                        <?php endif; ?>

                        <a type="button" href="view_survey.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>