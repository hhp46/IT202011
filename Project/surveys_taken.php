<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$page = 1;
$per_page = 4;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $s){

    }
}

$db = getDB();
$stmt = $db->prepare("SELECT count(Responses.title) as total from Responses s where s.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;



$db = getDB();
$stmt = $db->prepare("SELECT title, Count(Responses.survey_id) as TOTAL from Responses JOIN Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title LIMIT :offset, :count");
//$stmt = $db->prepare("SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses LEFT JOIN Survey ON Responses.survey_id=Survey.id UNION (SELECT title, COUNT(Responses.survey_id) as TOTAL from Responses) Right Join Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title");
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":id", get_user_id());
$stmt->execute();
$s = $stmt->errorInfo();
if($s[0] != "00000"){
    flash(var_export($s, true), "alert");
}


    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                        <div> <?php safer_echo($r["title"]); ?>  <?php safer_echo($r["TOTAL"]); ?></div>
                    
                    </div>
           
      
            </div>
             <?php endforeach; ?>
           
               
            
    
    
    <?php else: ?>
        <p>No results</p>
           <?php endif; ?>
           
      
         <nav aria-label="My Taken Surveys">
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
        
          <form method="POST">
        <input type="submit" name="results" value="Results Page"/>
         
    </form>  
<?php require(__DIR__ . "/partials/flash.php"); ?>