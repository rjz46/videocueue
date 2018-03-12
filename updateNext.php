<?php 
	require_once 'includes/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if( $mysqli->connect_errno ) {
			echo "<p>$mysqli->connect_error<p>";
			die( "Couldn't connect to database");
		}

	$stmt = $mysqli->prepare("DELETE FROM queue WHERE ind = 1;");
	$stmt->execute();

	$stmt0 = $mysqli->prepare("SELECT * FROM queue;");
	$stmt0->execute();
	$result = $stmt0->get_result();

	for ($i = 1; $i <= $result->num_rows+1; $i++) {
		$indNew = $i - 1;
		$stmt1 = $mysqli->prepare("UPDATE queue SET ind = ? WHERE ind = ?");
		$stmt1->bind_param('ii', $indNew, $i);
		$stmt1->execute();
	};


	$stmt2 = $mysqli->prepare("DELETE FROM queue WHERE ind = 0;");
	$stmt2->execute();

	$mysqli->close();
?>
