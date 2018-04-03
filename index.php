
<?php $username = $_GET['name']; ?>

<!DOCTYPE html>
<html>
<head>
	
	<title>Queue Demo</title>

	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
      

	<script>
		var username = "<?php echo $username; ?>";
		var socket = io('http://ec2-54-183-146-210.us-west-1.compute.amazonaws.com:3000');
	</script>

</head>

<style>
	button .remove{
    	text-align: right;
	}

	body, html
	{
		background-repeat: no-repeat;
    	height: 100%;
    }

    .badge {
    	float: right;
    }

</style>
<body>
	
	<div class="container">
		<hr>

		<div class = "row">
			<form action="update.php" method="post" id="form1">
				<fieldset class="col-sm" id="Jim">
					<div class="btn-group" role="group">
				  		<input type="button" name="name" class="btn btn-outline-success" value="Add" onclick="return send_add();"/>

					</div>

						<button type="button" id="next" class="btn btn-outline-primary">Pass</button>

				</fieldset>
			</form>
		
		</div>
		<span class="glyphicon glyphicon-asterisk"></span>
		<hr>
		<div class="container">
			<ul class="list-group queue">
			 	<!--li class="list-group-item justify-content-between"> 
			 	hello
			 	<span class="badge badge-default badge-pill"><input type="button" name ="hello" value="Remove" class="btn btn-outline-danger remove" /></span>
			 	</li-->
			</ul>
		</div>

		<hr>
	</div>


	<script>

		//add
		socket.on('plus', function (data) {
			receive_add(data);
		
		});

		function send_add(){
			socket.emit('add', { username: username });
		}

		function receive_add(data){
    		var user = data.username;    		
    		var myuser = $( ".queue li" ).last().attr('name');

    		if(user == myuser)
    		{
    			alert("cannot add again, please wait");
    		}else{
				
				if(user == username)
				{
					var newLI = "<li id ='"+data.queue_index+"' class='list-group-item justify-content-between' name ='"+user+"'><span>"+user+"</span><span class='badge badge-default badge-pill '><input type='button' name ='"+user+"' value='Remove' class='btn btn-outline-danger remove'/></span></span></li>";

				 	$(".queue").append(newLI);

  					setTimeout(function() {
   						newLI.className = newLI.className + " show";
  					}, 10);

				}else{

					$(".queue").append("<li id ='"+data.queue_index+"' class='list-group-item justify-content-between' name ='"+user+"'><span>"+user+"</span></li>");
				}

				$.ajax({
			        type: "POST",
			        url: "updateAdd.php",
			        data: {"name": user},
			        success:function(){console.log("successful add")}
		        });

	    		console.log("add " + user);
	    	}

		};

		//remove
		socket.on('minus', function (data) {
			console.log(data);
			$("#"+data).remove();
		});

		function send_remove(val){
			socket.emit('remove', { queueindex: queueindex });
		}

		function receive_remove(val){
			var user = val.name;
			console.log("remove " + user);

			$.ajax({
		        type: "POST",
		        url: "updateRemove.php",
		        data: {"name": user},
		        success:function(){console.log("successful remove")}
	        });
		}

		$(document).on( "click", '.remove', function() {
  			//$(this).parent().parent().detach();
  			//remove(this);

  			queue_index = $(this).parent().parent().attr('id');
  			socket.emit('remove', queue_index);

		});


		//next
		function next(){
			$.ajax({
		        type: "POST",
		        url: "updateNext.php",
		        success:function(){console.log("successful next")}
	        });
		}

		//remove first
		$( "#next" ).click(function() {
  			$( ".queue li" ).first().remove();
  			next();
		});


		function send_pass(val){
			socket.emit('pass', { username: username });
		}

		function send_jump(val){
			socket.emit('jump', { username: username });
		}


	</script>


</body>

</html>
