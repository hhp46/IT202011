<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin"or "Admin2")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
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
    $stmt = $db->prepare("SELECT id,title,description,visibility, user_id from Survey WHERE title like :q order by created desc LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
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
                        <div><b>Title:</b> <?php safer_echo($r["title"]); ?></div>
                    </div>
                    <div>
                        <div><b>Description:</b> <?php safer_echo($r["description"]); ?></div>
                    </div>
                    <div>
                        <div><b>Visibility:</b> <?php getVisibility($r["visibility"]); ?></div>
                    </div>
                    <div>
                        <div><b>Owner Id: </b> <?php safer_echo($r["user_id"]); ?></div>
                    </div>
                     
                    <div>
                        <a type="button" href="admineditsurvey.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                         <a type="button" href="admincreatequest.php?id=<?php safer_echo($r['id']); ?>">Add Question</a> 
                        <a type="button" href="adminviewsurvey.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
                
                <br>
                <br>
                
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>