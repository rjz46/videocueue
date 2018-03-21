<?php 
	require_once 'includes/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if( $mysqli->connect_errno ) {
			echo "<p>$mysqli->connect_error<p>";
			die( "Couldn't connect to database");
		}
	$name = $_POST['name'];

	/**$stmt = $mysqli->prepare("DELETE FROM queue WHERE person = ?;");
	$stmt->bind_param('s', $name);
	$stmt->execute();

	$stmt0 = $mysqli->prepare("SELECT * FROM queue;");
	$stmt0->execute();
	$result = $stmt0->get_result();

	for ($i = 1; $i <= $result->num_rows+1; $i++) {
		$stmt1 = $mysqli->prepare("SELECT * FROM queue WHERE ind = ?;");
		$stmt1->bind_param('i', $i);
		$stmt1->execute();
		$result1 = $stmt1->get_result();
		if(!$result1){
			$indOld = $i + 1;
			$stmt2 = $mysqli->prepare("UPDATE queue SET ind = ? WHERE ind = ?");
			$stmt2->bind_param('ii', $i, $indOld);
			$stmt2->execute();
		}
			
	};**/

	$lim = 1;
	$stmt2 = $mysqli->prepare("SELECT * FROM queue WHERE removed IS NULL AND person = ? ORDER BY ind ASC LIMIT ?;");
	$stmt2->bind_param('si', $name, $lim);
	$stmt2->execute();
	$result2 = $stmt2->get_result();
	$num_rows = $stmt2->fetch();

	if ( $result2 && count($num_rows) == 1 ) {
		$row = $result2->fetch_assoc();

		$ind = $row['ind'];
	}

	$stmt3 = $mysqli->prepare("UPDATE queue SET removed = CURRENT_TIMESTAMP WHERE ind = ?");
	$stmt3->bind_param('i', $ind);
	$stmt3->execute();

	$mysqli->close();


?>
