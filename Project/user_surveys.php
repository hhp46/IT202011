<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
	   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php

$db = getDB();
$stmt = $db->prepare("SELECT id,title, user_id from Survey WHERE user_id = :id LIMIT 10");
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
<h3>List Surveys</h3>


<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                
                    <div>
                        <div>Title: <?php safer_echo($r["title"]); ?></div>
                    </div>
                  
                    <div>
                        <div>Owner Id: <?php safer_echo($r["user_id"]); ?></div>
                      
                    </div>
                         <a type="button" href="create_quest.php?id=<?php safer_echo($r['id']); ?>">Add Question</a> 
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php"); ?>