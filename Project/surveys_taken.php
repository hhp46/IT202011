<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$page = 1;
$per_page = 6;
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
$stmt = $db->prepare("Select distinct title, s.id, s.user_id, (select count(distinct user_id) from Responses where Responses.survey_id = s.id) as times from Survey s JOIN Responses r on s.id = r.survey_id where r.user_id = :id LIMIT :offset, :count"); 

//$stmt = $db->prepare("SELECT title, Count(Responses.survey_id) as TOTAL from Responses JOIN Survey ON Responses.survey_id=Survey.id WHERE Responses.user_id=:id GROUP BY title LIMIT :offset, :count");


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




<?php if (count($results) > 0): ?>
               
         <div class="results"> 
            <?php foreach ($results as $r): ?>
                
                    <div>
                        <div> <b>Title:</b> <?php safer_echo($r["title"]); ?> </div>
                           <div> <b>Number of times taken:</b> <?php safer_echo($r["times"]); ?>   <div> 
                           <div> <b>Profile Link:  </b>     <a  type="button" href="profileview.php?id=<?php safer_echo($r["user_id"]); ?>">View Creator's Profile</a> </div>
                       
                       </div>
                    
                   	 </div>  
                    </div>  
          </div>  
  <br>
  <br>
             <?php endforeach; ?>
           
               
    <?php else: ?>
        <p>No results</p>
           <?php endif; ?>
           
      
       <nav1>
            <ul > 
                <li  <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li  <?php echo ($page-1) == $i?"active":"";?>"><a  href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li  <?php echo ($page+1) >= $total_pages?"disabled":"";?>">
                    <a  href="?page=<?php echo $page+1;?>">Next</a>
                </li>
        </ul> 
        </nav1>   
        
          <form method="POST">
        <input type="submit" name="results" value="Results Page"/>
         
    </form>  
<?php require(__DIR__ . "/partials/flash.php"); ?>