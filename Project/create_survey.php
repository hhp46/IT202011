<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<br>

<br>


<form method="POST">
	<label><b>Survey Title</b> </label>
		<br>
	<input name="title" placeholder="Title"/>
		<br>
		<br>
	<label><b>Description</b> </label>
		<br>
	<input type="text" min="1" name="description"/>
	<br>
		<br>
	<label><b>Category</b> </label>
		<br>
	<select name="category">
		<option value="Address Survey">Address Survey</option>
		<option value="College Survey">College Survey</option>
		<option value="Professor Survey">Professor Survey</option>
		<option value="Favorites Survey">Favorites Survey</option>
		<option value="Family Survey">Family Survey</option>
		<option value="Sports Survey">Sports Survey</option>
		<option value="Other Survey">Other Survey</option>
	</select>
<br>
		<br>
	<label><b>Visibility</b> </label>
	<select name="visibility">
		<option value="0">Draft</option>
		<option value="1">Private</option>
		<option value="2">Public</option>
	</select>
<br>
		<br>
	<input type="submit" name="save" value="Create"/>
</form>
<br>
<?php
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$title = $_POST["title"];
	$descrip = $_POST["description"];
	$categ = $_POST["category"];
	$visibil = $_POST["visibility"];
	$user = get_user_id();
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO Survey (title, description, category, visibility, user_id) VALUES(:title, :descrip, :categ, :visibil, :user)");
	$r = $stmt->execute([
		":title"=>$title,
		":descrip"=>$descrip,
		":categ"=>$categ,
		":visibil"=>$visibil,
		":user"=>$user
	]);
	if($r){
		flash("Created successfully with id: " . $db->lastInsertId());
	}
	else{
		$e = $stmt->errorInfo();
		flash("Error creating: " . var_export($e, true));
	}
}
?>
<?php require(__DIR__ . "/partials/flash.php");