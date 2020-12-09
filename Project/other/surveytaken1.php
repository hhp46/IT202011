<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}






$db = getDB();
$stmt = $db->prepare("SELECT title, Count(Responses.survey_id) as total from Responses JOIN Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title");
//$stmt = $db->prepare("SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses LEFT JOIN Survey ON Responses.survey_id=Survey.id UNION (SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses) Right Join Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title");
$r = $stmt->execute([":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys taken and count: " . var_export($stmt->errorInfo(), true));
}


?>

<?php
if (isset($_POST["results"])) {
 die(header("Location: " . getURL("results.php")));}
?>


<h3>Survey's Taken</h3>
<br>

<h4>Title - Times taken</h4>


<?php if (count($results) > 0): ?>
               
          <div class="results">
            <?php foreach ($results as $r): ?>
                
                    <div>
                        <div> <?php safer_echo($r["title"]); ?> - <?php safer_echo($r["total"]); ?></div>
                    
                    </div>
           
      
            </div>
             <?php endforeach; ?>
           
               
            
        <form method="POST">
        <input type="submit" name="results" value="Results Page"/>
         
    </form>
    
    <?php else: ?>
        <p>No results</p>
           <?php endif; ?>
           
    <nav aria-label="Taken surveys">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page+1) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>   
           
<?php require(__DIR__ . "/partials/flash.php"); ?>