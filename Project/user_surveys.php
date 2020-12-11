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
$stmt = $db->prepare("SELECT count(Suvey.id) as total from Survey s where s.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;


$stmt = $db->prepare("SELECT s.id ,title, s.user_id from Survey s WHERE s.user_id = :id LIMIT :offset, :count");
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
<h3>My Surveys Created</h3>


<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                
                    <div>
                        <div><b>Title:</b> <?php safer_echo($r["title"]); ?></div>
                    </div>
                  
                    <div>
                        <div><b>Owner Id:</b> <?php safer_echo($r["user_id"]); ?></div>
                      
                    </div>
                         <a type="button" href="create_quest.php?id=<?php safer_echo($r['id']); ?>">Add Question</a> 
                </div>
                <br>
                <br>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
     <nav aria-label="My Surveys">
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
    
</div>
<?php require(__DIR__ . "/partials/flash.php"); ?>