<?php 
	require_once 'includes/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if( $mysqli->connect_errno ) {
			echo "<p>$mysqli->connect_error<p>";
			die( "Couldn't connect to database");
		}
	$name = $_POST['name'];
	$lim = 1;

	$stmt = $mysqli->prepare("SELECT * FROM queue WHERE person = ?;");
	$stmt->bind_param('s', $name);
	$stmt->execute();
	$result = $stmt->get_result();

	//Only add if person is not in the queue
	if($result->num_rows < 1) {
		$stmt0 = $mysqli->prepare("SELECT * FROM queue ORDER BY ind DESC LIMIT ?;");
		$stmt0->bind_param('i', $lim);
		$stmt0->execute();
		$result = $stmt0->get_result();
		$row = $result->fetch_assoc();
		$ind = $row['ind']+1;

		$stmt1 = $mysqli->prepare("INSERT INTO queue (person, ind) VALUES (?, ?);");
		$stmt1->bind_param('si', $name, $ind) ;

		$stmt1->execute();
	}				
	
	$mysqli->close();


?>
