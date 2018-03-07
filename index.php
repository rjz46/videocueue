
<!DOCTYPE html>
<html>


<head>
	
	<title>Queue Demo</title>

	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</head>

<style>
	button .remove{
    	text-align: right;
	}
</style>
<body>

	<?php
		require_once 'includes/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if( $mysqli->connect_errno ) {
				echo "<p>$mysqli->connect_error<p>";
				die( "Couldn't connect to database");
			}
	?>
	
	<div class="container">
		<hr>

		<div class = "row">
			<form action="index.php" method="post">
				<fieldset class="col-sm" id="Jim">
					<div class="btn-group" role="group">
				  		<input type="button" name="name" class="btn btn-outline-success add" value="Jim" onclick="add(this)"/>
					</div>
					<div class="btn-group" role="group">
				  		<input type="button" name="name" class="btn btn-outline-success add" value="Ru" onclick="add(this)"/>
					</div>
					<div class="btn-group" role="group">
						<input type="button" name="name" class="btn btn-outline-success add" value="Luping" onclick="add(this)"/>
					</div>
				</fieldset>
			</form>
		
		</div>

		<hr>
		<div class="container">
			<ul class="list-group queue">
			 	<li class="list-group-item justify-content-between"> 
			 	hello
			 	<span class="badge badge-default badge-pill"><button type="button" class="btn btn-outline-danger remove">Remove</button></span>
			 	</li>
			</ul>
		</div>

		<hr>
		<button type="button" id="next" class="btn btn-outline-primary">Next Person</button>
	</div>


	<script>
		function add(val){
    		var user = val.value;
    		console.log(user);
    		alert($( ".queue li" ).last().html());
				$(".queue").append("<li class='list-group-item justify-content-between'>"+user+"<span class='badge badge-default badge-pill'><button type='button' class='btn btn-outline-danger remove'>Remove</button></span></li>");		
	
			<?php


				$name = $_POST['name'];
				$lim = 1;

				$stmt = $mysqli->prepare("SELECT * FROM queue ORDER BY ind DESC LIMIT ?;");
				$stmt->bind_param('i', $lim);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				$ind = $row['ind']+1;

				$stmt1 = $mysqli->prepare("INSERT INTO queue (person, ind) VALUES (?, ?);");
				$stmt1->bind_param('si', $name, $ind) ;

				$stmt1->execute();
				
				$mysqli->close();

				$_POST['name']="";
			?>	
		};


		$(document).on( "click", '.remove', function() {
  			$(this).parent().parent().detach();
		});

		//remove first
		$( "#next" ).click(function() {
  			$( ".queue li" ).first().remove();
		});

		//add contraint, person cannot add himself/herself twice


	</script>


</body>

</html>
