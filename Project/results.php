<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
	   flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>



<?php
if (isset($_GET["id"])) {
    $sid = $_GET["id"];
$db = getDB();
$stmt = $db->prepare("SELECT Survey.id, title, description, user_id FROM Survey WHERE Survey.id = :survey_id");
$r = $stmt->execute([":survey_id" => $sid]);
if ($r) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching responses: " . var_export($stmt->errorInfo(), true));
}
}
?>



<h3>Your Survey Responses</h3>

        
 <?php if (isset($result) && !empty($result)): ?>
    <div class="card">
     
        <div class="card-body">
            <div>
                
                <div>Survey Title: <?php safer_echo($result["title"]); ?></div>
                <div>Description:: <?php safer_echo($result["description"]); ?></div>
              
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error ...</p>
<?php endif; ?>

<?php require(__DIR__ . "/partials/flash.php");