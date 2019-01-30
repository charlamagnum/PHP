<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>26_MYSQL_passdata_to_select</title>
	<link rel="stylesheet" type="text/css" href="css/basic.css" />
</head>

<body>

<h3>26_MYSQL_passdata_to_select </h3>

<form id="myform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">


<h2 style="background-color: #F5DEB3;">Pass data to a Select Statement </h2>

<p>
	Select a Continent to display its countries:
	<select name="continent">
		<option value="">All</option>
		<option value="Africa">Africa</option>
		<option value="Antarctica">Antarctica</option>
		<option value="Asia">Asia</option>
		<option value="Australia">Australia</option>
		<option value="Central America">Central America</option>
		<option value="">Europe</option>
		<option value="North America">North America</option>
		<option value="Oceania">Oceania</option>
		<option value="South America">South America</option>
	</select>
</p>

<?php

	//****************************************
	// Get the continent code
	//****************************************
	
	//Check to see if user has made a selection
	if(isset($_POST['continent']))
	{
		$continent = $_POST['continent'];
	
		//if selection has been ALL then its empty, set it to all
		if(empty($continent))
		{
			$continent = 'ALL';
		}
	//When starting up no selection has been made so set to all
	} else {
		$continent = 'ALL';
	}
	
	//****************************************
	// Connect to MySQL and Database
	//****************************************
	
	$db = mysqli_connect('localhost','root','soluna'); //returns a pointer -- database connection

	//Check to see if we were able to connect
	if(!$db)
	{
		print "<h1>Unable to Connect to MYSQL </h1>";
	}

	//set database name we want to work in
	$dbname = "geography";

	$btest = mysqli_select_db($db, $dbname);

	//check to see if we were able to connect to database
	if(!$btest)
	{
		print "<h1>Unable to Select the Database</h1>";
	}

	//****************************************
	// SELECT from table and display Results
	//****************************************
	
	/*Sample Statement we will use:
		SELECT country_code, country_en, continent, government_form, currency, currency_code, population, birthrate, deathrate, life_expectancy, url
		FROM
		countries
		WHERE continent = 'Asia'
		ORDER BY country_code
	*/

	//Begin to setup our SQL statement 
	$sql_statement = "SELECT country_code, country_en, continent, government_form, currency, currency_code, population, birthrate, deathrate, life_expectancy, url ";
	$sql_statement .= "FROM countries ";
	//check to see if continent has been set so we add to query
	if($continent != 'ALL'){
		$sql_statement .= "WHERE continent = '" . $continent . "' ";
	}
	$sql_statement .= ' ORDER BY country_code';
	
	//if it works result will be special gridlike result
	$result = mysqli_query($db, $sql_statement);

	$outputDisplay = "";
	$myrowcount = 0;

	//if it didnt go right then we will write out errors
	if(!$result){
		$outputDisplay .= "<br /><font color=red>MySQL No: ".mysqli_errno($db);
		$outputDisplay .= "<br />MySQL Error: ".mysqli_error($db);
		$outputDisplay .= "<br />SQL Statement: ".$sql_statement;
		$outputDisplay .= "<br />MySQL Affected Rows: ".mysqli_affected_rows($db)."</font><br/>";
	} else {

		//Set heading to reflect selected continent or all
		if($continent == 'ALL')
		{
			$outputDisplay = "<h3>Basic Country Stats - All Continents</h3>";
		}
		else {
			$outputDisplay = "<h3>Basic Country Stats - " . $continent . "</h3>";
		}
		//Set the table we'll place the data in
		$outputDisplay .= '<table border=1 style="color:black;">';
		//Set the headings
		$outputDisplay .= '<tr><th>Country Code</th><th>Country</th><th>Continent</th><th>Form of Gov</th><th>Currency</th><th>Currency Code</th><th>Population</th><th>Birthrate</th><th>Deathrate</th><th>Life Expectancy</th><th>URL</th></tr>';
	
		//Getting the number of rows
		$numresults = mysqli_num_rows($result);

		//Traverse the results
		for($i = 0; $i < $numresults; $i++)
		{
			//If statement used to change the background colors for table in alternate rows
			if(!($i%2) == 0)
			{
				$outputDisplay .= "<tr style=\"background-color: #F5DEB3;\">";	
			} else{
				$outputDisplay .= "<tr style=\"background-color: white;\">";	
			}
			//Get our actual values
			$row = mysqli_fetch_array($result);	//Takes one row of a result and sticks
			$country_code = $row['country_code'];
			$country_en = $row['country_en'];
			$continent = $row['continent'];
			$government_form = $row['government_form'];
			$currency = $row['currency'];
			$currency_code = $row['currency_code'];
			$population = $row['population'];
			$birthrate = $row['birthrate'];
			$deathrate = $row['deathrate'];
			$life_expectancy = $row['life_expectancy'];
			//We build URL since the default URL is in German :( SAD!
			$url = "https://www.worlddata.info/".$continent."/".str_replace(" ", "-", $country_en)."/index.php";
			//URL
			//$url = $row['url'];
			//URL needs change
			//$url = str_replace("laenderdaten", "worlddata", $url);

			//Place values on our outputDisplay variable
			$outputDisplay .= "<td>".$country_code."</td>";
			$outputDisplay .= "<td>".$country_en."</td>";
			$outputDisplay .= "<td>".$continent."</td>";
			$outputDisplay .= "<td>".$government_form."</td>";
			$outputDisplay .= "<td>".$currency."</td>";
			$outputDisplay .= "<td>".$currency_code."</td>";
			$outputDisplay .= "<td>".number_format($population)."</td>"; //formats to display commas. 1000 - 1,000
			$outputDisplay .= "<td>".$birthrate."</td>";
			$outputDisplay .= "<td>".$deathrate."</td>";
			$outputDisplay .= "<td>".$life_expectancy."</td>";
			$outputDisplay .= "<td><a href=\"".$url."\">More Info</a></td>";

			$outputDisplay .= "</tr>\n";
		}
		
		$outputDisplay .= "</table>";
	}
	
?>

<!--Submit button-->

<br /> <br/> <input type="submit" value="RUN SQL with Selection" />

<hr size="4" style="background-color:#F5DEB3; color: #F5DEB3;">
<?php
	$outputDisplay .= "<br /><br /><b>Number of Rows in Results: $numresults </b><br/><br/>";
	print $outputDisplay;
?>

</body>
</html>