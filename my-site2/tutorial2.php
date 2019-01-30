<?php

require 'classes/Database.php';

$database = new Database();


//tutorial2 fetching data
//$database->query('SELECT * FROM posts');

//we will query after we submit
//if we want to specify the data we can add a where clause
//$database->query('SELECT * FROM myblog.posts');
//$database->bind(':id', 3);
//$rows = $database->resultset();

//print_r($rows);

//now we can print each individually
//But lets print it in the HTML layout

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


if($post['delete']){
	$delete_id = $post['delete_id'];
	
	$database->query('DELETE FROM myblog.posts WHERE id = :id');
	$database->bind(':id', $delete_id);
	$database->execute();
}



//if statement to check for submit button to see if has been clicked
if($post['submit']){
	//we are getting the input of title and body
	$id = $post['id'];
	$title = $post['title'];
	$body = $post['body'];
	
	//echo $title; //echo to test to determine if we get the values

	//since we know we are getting data now we build the query to pass that data
	//for our query we will be binding data so we will call unto our bind function
	$database->query('UPDATE myblog.posts SET title = :title, body = :body WHERE id = :id');
	$database->bind(':title', $title);
	$database->bind(':body', $body);
	$database->bind(':id', $id);
	$database->execute();
	//If statement to determine if data was added to database
	//if($database->lastInsertId()){
	//	echo '<p>Post Added!</p>';
	//}
	
} //End If Submit

//Right now we are querying after we submit
//if we want to specify the data we can add a where clause
$database->query('SELECT * FROM myblog.posts');
//$database->bind(':id', 3);
$rows = $database->resultset();
//If we want the new additions to show up after 


?>



<!-- ----------------------------------------------------- -->
<!-- Tutorial number 4 start: We are going to Update/Delete data via form -->
<h1>Update Post</h1>
<!-- We want to catch our submission so we will do this in the form tag -->
<form method = "post" action = "<?php $_SERVER['PHP_SELF']; ?>">
	<label> Post Title</label><br />
	<input type="text" name="id" placeholder="Specify ID" /><br /><br />
	<label> Post Title</label><br />
	<input type="text" name="title" placeholder="Add a Title..." /><br /><br />
	<label> Post Body</label><br />
	<textarea name="body"></textarea><br /><br />
	<input type="submit" name="submit" value="Submit"/>

</form>





<!-- Part of tutorial number 2 -->
<h1>Posts </h1>

<div>

<?php	foreach($rows as $row) : ?>
	<div>
		<h3>	
			<?php echo $row['title'];?>	 
		</h3>
		<p>
			<?php echo $row['body'] ?> 
		</p>
		<!-- Part of tutorial number 4 adding a DELETE BUTTON. We will create a form just with delete button -->
		<br />
		<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="delete_id" value="<?php echo $row['id'];?>	">
			<input type="submit" name="delete" value="Delete" />
		</form>
	</div>
<?php endforeach; ?>
</div>
