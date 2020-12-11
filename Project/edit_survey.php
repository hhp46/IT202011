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
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$title = $_POST["title"];
	$descrip = $_POST["description"];
	$categ = $_POST["category"];
	$visibil = $_POST["visibility"];
	$user = get_user_id();
	$db = getDB();
	if(isset($id)){
		$stmt = $db->prepare("UPDATE Survey set title=:title, description=:descrip, category=:categ, visibility=:visibil where id=:id");
		
		$r = $stmt->execute([
			":title"=>$title,
			":descrip"=>$descrip,
			":categ"=>$categ,
			":visibil"=>$visibil,
			":id"=>$id
		]);
		if($r){
			flash("Updated successfully with id: " . $id);
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error updating: " . var_export($e, true));
		}
	}
	else{
		flash("ID isn't set, we need an ID in order to update");
	}
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
	$id = $_GET["id"];
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM Survey where id = :id");
	$r = $stmt->execute([":id"=>$id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<br>
<form method="POST">
	<label><b>Title</b></label>
	<input name="title" placeholder="Title" value="<?php echo $result["title"];?>" />
	<label><b>Description</b></label>
	<input type="text"  name="description" value="<?php echo $result["description"];?>" />
	<label><b>Category</b></label>
	<select name="category" value="<?php echo $result["category"];?>">
		<option value="Address Survey" <?php echo ($result["category"] == "0"?'selected="selected"':'');?>>Address Survey</option>
                <option value="Colllege Survey" <?php echo ($result["category"] == "1"?'selected="selected"':'');?>>Colllege Survey</option>
                <option value="Professor Survey" <?php echo ($result["category"] == "2"?'selected="selected"':'');?>>Professor Survey</option>
                 <option value="Favorites Survey" <?php echo ($result["category"] == "3"?'selected="selected"':'');?>>Favorites Survey</option>
                  <option value="Family Survey" <?php echo ($result["category"] == "4"?'selected="selected"':'');?>>Family Survey</option>
                   <option value="Sports Survey" <?php echo ($result["category"] == "5"?'selected="selected"':'');?>>Sports Survey</option>
                    <option value="Other Survey" <?php echo ($result["category"] == "6"?'selected="selected"':'');?>>Other Survey</option>
                
	</select>
	<label><b>Visibility</b></label>
	<select name="visibility" value="<?php echo $result["visibility"];?>">
		<option value="0" <?php echo ($result["visibility"] == "0"?'selected="selected"':'');?>>Draft</option>
                <option value="1" <?php echo ($result["visibility"] == "1"?'selected="selected"':'');?>>Private</option>
                <option value="2" <?php echo ($result["visibility"] == "2"?'selected="selected"':'');?>>Public</option>
                
	</select>
	
	<input type="submit" name="save" value="Update"/>
</form>


<?php require(__DIR__ . "/partials/flash.php");