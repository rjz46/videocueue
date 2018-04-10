
<?php $username = $_GET['name']; ?>

<!DOCTYPE html>
<html>
<head>
	
	<title>Queue Demo</title>

	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
      
	<script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>


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

    h3 {
    	padding-top: 10px;
    	text-align: center;
    }

    .container {
    	height:100%;
    	border: 2px solid #73AD21;
   		border-radius: 5px;
    }

    #top {
	    padding: 20px; 
	    height: 20%; 
	}

    #bottom {
	    padding: 20px; 
	    height: 80%; 
	    background: white;
		border-radius: 30px;
	}

	.queue
	{
		border-radius: 5px;
		background: white;
	}

	.mytext
	{
		height: 50px;
		display: inline-block;
  		vertical-align: middle;
	}
</style>
<body>

	<div class="container">
		
		<div id="top">

			<h3>Video Conference Queue Cue Support</h3>

			<hr>

			<div class = "row">
				<div class="col">
					<div>
						<i class="fas fa-address-card" style="font-size:45px"></i>
						<span class='mytext'><?php echo $username; ?></span>
					</div>
				</div>
				<div class="col">
					<form action="update.php" method="post" id="form1">
						<div style="float:right;" class="btn-group" role="group">
					  		<button type="button" name="name" class="btn btn-outline-success" onclick="return send_add();"><i class="fas fa-angle-up"></i></button>
					  		<button type="button" name="name" class="btn btn-outline-success" onclick="return send_add();"><i class="fas fa-angle-double-up"></i></button>
							<button type="button" id="next" class="btn btn-outline-primary"><i class="fas fa-angle-down"></i></button>
						</div>
					</form>
				</div>
			</div>
		
			<hr>

		</div>
		<div id="bottom">
			<ul class="list-group queue">
			 	<!--li class="list-group-item justify-content-between"> 
			 	hello
			 	<span class="badge badge-default badge-pill"><input type="button" name ="hello" value="Remove" class="btn btn-outline-danger remove" /></span>
			 	</li-->
			</ul>
		</div>
	</div>


	<div class="modal fade" id="repeat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Error</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
		     <p>You cannot add yourself twice in a row. Please wait for another user.</p>  
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="pass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Error</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
		     <p>You cannot pass another user's turn.</p>  
	      </div>
	    </div>
	  </div>
	</div>

	<script>

		//add
		socket.on('plus', function (data) {
			receive_add(data);
		
		});

		function send_add(){
			var user = username;
			var myuser = $( ".queue li" ).last().attr('name');

    		if(user == myuser)
    		{
    			$("#repeat").modal("show");
    		}else{
				socket.emit('add', { username: username });
			}
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
					var newLI = "<li id ='"+data.queue_index+"' class='list-group-item justify-content-between' name ='"+user+"'><span class = 'mytext'>"+user+"</span><span class='badge badge-default badge-pill '><button name ='"+user+"' value='Remove' class='btn btn-outline-danger remove'><i class='fas fa-ban'></i></button></span></span></li>";

				 	$(".queue").append(newLI);

  					setTimeout(function() {
   						newLI.className = newLI.className + " show";
  					}, 10);

				}else{

					$(".queue").append("<li id ='"+data.queue_index+"' class='list-group-item justify-content-between' name ='"+user+"'><span class = 'mytext'>"+user+"</span></li>");
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
  			//$( ".queue li" ).first().remove();
  			first_to_his_name = $( ".queue li" ).first();
  			var user = first_to_his_name.attr('name');

  			if(username != user)
    		{
    			$("#pass").modal("show");
    		}else{
					
	  			queue_index = first_to_his_name.attr('id');
	  			socket.emit('remove', queue_index);

	  			next();
	  		}
		});


		function send_pass(val){
			socket.emit('pass');
		}

		function send_jump(val){
			socket.emit('jump', { username: username });
		}


	</script>


</body>

</html>
