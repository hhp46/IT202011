<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
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
    $stmt = $db->prepare("SELECT Survey.id,title,description,Survey.visibility, user_id, Users.username FROM Survey  JOIN Users on Survey.user_id = Users.id where Survey.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>

<?php if (isset($result) && !empty($result)): ?>
<br>
<br>
    <div class="card">
        <div class="card-title">
            <h3><b><u>Title:</u></b> <?php safer_echo($result["title"]); ?></h3>
        </div>
        <div class="card-body">
            <div>
                <p><u>Survey Info...</u></p>
                <div><b>Description: </b> <?php safer_echo($result["description"]); ?></div>
                <div><b>Current Visibility:</b> <?php getVisibility($result["visibility"]); ?></div>
                <div><b>Owned by:</b> <?php safer_echo($result["username"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");