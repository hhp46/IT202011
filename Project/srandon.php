<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}


    $db = getDB();

    $stmt = $db->prepare("Select * from Survey s where s.id not in (SELECT distinct survey_id from Responses where user_id = :user_id) ORDER BY RAND() LIMIT 1");
    $r = $stmt->execute([":user_id" => get_user_id()]);
    $random = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
if (isset($_POST["random"])) {

    $stmt = $db->prepare("Select * from Survey s where s.id not in (SELECT distinct survey_id from Responses where user_id = :user_id) ORDER BY RAND() LIMIT 1");
    $r = $stmt->execute([":user_id" => get_user_id()]);
    $random = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$stmt = $db->prepare("SELECT Survey.title, Survey.id, count(Responses.survey_id) as total FROM Survey LEFT JOIN (SELECT distinct user_id, survey_id FROM Responses) as Responses on Survey.id = Responses.survey_id GROUP BY title");
$r = $stmt->execute();


if ($r){
    $taken = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else{
    flash("There was a problem fetching the results");
}

?>

 <form method="POST">
       
 
        <input type="submit" name="random" value="Random Survey" href="survey.php?id=<?php safer_echo($random[0]['id']); ?>">
    </form>
    
    
<?php require(__DIR__ . "/partials/flash.php"); ?>