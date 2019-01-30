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

//if statement to check for submit button to see if has been clicked
if($post['submit']){
	//we are getting the input of title and body
	$title = $post['title'];
	$body = $post['body'];
	
	//echo $title; //echo to test to determine if we get the values

	//since we know we are getting data now we build the query to pass that data
	//for our query we will be binding data so we will call unto our bind function
	$database->query('INSERT INTO myblog.posts (title, body) VALUES(:title, :body)');
	$database->bind(':title', $title);
	$database->bind(':body', $body);
	$database->execute();
	//If statement to determine if data was added to database
	if($database->lastInsertId()){
		echo '<p>Post Added!</p>';
	}
	
}

//Right now we are querying after we submit
//if we want to specify the data we can add a where clause
$database->query('SELECT * FROM myblog.posts');
//$database->bind(':id', 3);
$rows = $database->resultset();
//If we want the new additions to show up after 


?>

<!-- ----------------------------------------------------- -->
<!-- Tutorial number 3 start: We are going to insert data via form -->
<h1>Add Post</h1>
<!-- We want to catch our submission so we will do this in the form tag -->
<form method = "post" action = "<?php $_SERVER['PHP_SELF']; ?>">
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
	</div>
<?php endforeach; ?>
</div>
