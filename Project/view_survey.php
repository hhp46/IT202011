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
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Survey.id,title,description, category, Survey.visibility, user_id, Users.username FROM Survey  JOIN Users on Survey.user_id = Users.id where Survey.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<h3>View Surveys</h3>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
           <b> Survey Title:  </b><?php safer_echo($result["title"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p><b>Survey Info</b></p>
                <div>Description: <?php safer_echo($result["description"]); ?></div>
                <div>Category: <?php safer_echo($result["category"]); ?></div>
                <div>Current Visibility: <?php getVisibility($result["visibility"]); ?></div>
                <div>Owned by: <?php safer_echo($result["username"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");