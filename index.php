
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
    	border: 2px solid #0c5460;
   		border-radius: 5px;
   		background: #e9ecef;
   		width: 600px;
    }

    #top {
	    padding: 20px; 
	    //height: 20%; 
	}

    #bottom {
	    padding: 20px; 
	    height: 68%; 
	    background: #e9ecef;
		border-radius: 30px;
    	border: 3px double #0c5460;
    	overflow: auto;
    	background-color: white;
	}

	.queue
	{
		border-radius: 5px;
		//background: white;
	}

	.mytext
	{
		height: 40px;
		font-family: sans-serif;
		//display: inline-block;
  		//vertical-align: middle;
	}

	.queue li {
		background-color: rgb(233, 236, 239, .3);;

	}
	.queue li:first-child{
      background-color: rgb(0, 255, 0, .5);
    }

   	.queue li:first-child button{
   		display:none;
   	}

    .queue li:nth-child(2){
      background-color: rgb(255, 255, 102, .3);
    }

    .queue li {
	  opacity: 0;
	  transform: rotateX(-90deg);
	  transition: all 0.5s cubic-bezier(.36,-0.64,.34,1.76);
	}

	.queue li.show {
	  opacity: 1;
	  transform: none;
	  transition: all 0.5s cubic-bezier(.36,-0.64,.34,1.76);
	}

	.queue {
 		perspective: 100px;
	}

	h3 {
		color: #0c5460;
	}

	#top .btn-group {
		background-color: white;
	}

	#logo {
		width:50%;
		float:right;
	}

	#tag {
		font-size: 50px;
	}

	.left {
		margin:auto;
	}

	#usermode {
		color: green;
	}

</style>
<body>

	<div class="container">
		
		<div id="top">
			<div class = "logorow row">
				<div class="left col">
					<div id = "tag">
						<i class="fas fa-address-card"></i>
						<span class='mytext'><?php echo $username; ?></span>
					</div>
				</div>
				<div class="right col"><img id="logo" src="node_modules/logo.png"></div>
			</div>
			
			<hr>

			<div class = "row">
				<div class="col">
					<form action="update.php" method="post" id="form1">
						<center>
							<div  class="btn-group" role="group">
						  		<button type="button" name="name" class="btn btn-outline-success" onclick="return send_add();"><i class="fas fa-angle-up"></i> Add to Queue </button>
						  		<button type="button" name="name" class="btn btn-outline-success" onclick="return request_jump();"><i class="fas fa-angle-double-up"></i>  Request Jump </button>
								<button type="button" id="next" class="btn btn-outline-primary"><i class="fas fa-angle-down"></i> Pass Turn </button>
							</div>
						</center>
					</form>
				</div>
			</div>
		

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

	<div class="modal fade" id="nojump" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Request Failed</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
		     <p>The request to jump has not reach an unanimous decision.</p>  
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="nojumpsecond" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Cannot Jump</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
		     <p>You are already speaking next.</p>  
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="nojumpfirst" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Cannot Jump</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
		     <p>You are already speaking.</p>  
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h4 class="modal-title" id="myModalLabel">Jump Request</h4>
	      </div>
	      <div class="modal-body">
		     <p><span id="usermode"></span> has requested to speak next. Do you agree to let him/her jump queue?</p>  
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" id="modal-btn-si">Yes</button>
	        <button type="button" class="btn btn-primary" id="modal-btn-no">No</button>
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
    			$("#repeat").modal("show");
    		}else{
				
				var newLI;
				if(user == username)
				{
					newLI = $("<li id ='"+data.queue_index+"' class='list-group-item justify-content-between' name ='"+user+"'><span class = 'mytext'>"+user+"</span><span class='badge badge-default badge-pill '><button name ='"+user+"' value='Remove' class='btn btn-outline-danger remove'><i class='fas fa-ban'></i></button></span></span></li>");

				 	$(".queue").append(newLI);

				 	console.log("trying to animate", newLI.attr('class'));

	  				$.ajax({
				        type: "POST",
				        url: "updateAdd.php",
				        data: {"name": user},
				        success:function(){console.log("successful add")}
			        });

	    			console.log("add " + user);

				}else{

					newLI = $("<li id ='"+data.queue_index+"' class='list-group-item justify-content-between' name ='"+user+"'><span class = 'mytext'>"+user+"</span></li>");
					
					$(".queue").append(newLI);
				}

				setTimeout(function() {
					newLI.addClass( "show" );
  				}, 10);
	    	}

		};

		//remove
		socket.on('minus', function (data) {
			console.log(data);
			$("#"+data).remove();
			//$("#"+data).one("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function(){ $("#"+data).remove() });
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

		//jump requests
		socket.on('request_jump', function (data) {
			receive_request(data);
		});

		function request_jump(){

			var user = username;

			var myuser = $( ".queue li" ).first().attr('name');
			var my2nduser = $( ".queue li:nth-child(2)" ).attr('name');

    		if(user == myuser)
    		{
    			$("#nojumpfirst").modal("show");
    		}else if(user == my2nduser){
    			$("#nojumpsecond").modal("show");
    		}else{
				socket.emit('request_jump', { username: username });
			}
		}

		/*socket.on('respond_jump', function (data) {
			receive_request(data);
		});*/

		var datapackage;

		function receive_request(data)
		{
			//var answer = confirm(data.username + " has requested to speak next.");
			$("#usermode").html(data.username);

			datapackage = data;

			$('#mi-modal').modal({
			    backdrop: 'static',
			    keyboard: false
			})


		}

		function send_response(data)
		{
			socket.emit('respond_jump', data);
		}

		socket.on('response', function (data) {
			receive_response(data);
		});

		var counter = 0;
		var yescounter = 0;
		function receive_response(data)
		{
			//test
			/*counter++;
			if(counter == 2)
			{
				counter = 0;
				send_jump(data);
				//socket.emit('jump', data);
			}*/

			counter++;

			if(data.response)
			{
				yescounter++;
			}

			if(counter == 2)
			{
			
				if(yescounter == 2){
					socket.emit('jump', data);
				}
				else{
					$("#nojump").modal("show");
				}

				counter = 0;
				yescounter = 0;
			}

		
		}

		//jump!
		socket.on('jump', function (data) {
			receive_jump(data);
		});

		function send_jump(data){

			var user = username;

			var myuser = $( ".queue li" ).first().attr('name');
			var my2nduser = $( ".queue li:nth-child(2)" ).attr('name');

    		if(user == myuser)
    		{
    			$("#nojumpfirst").modal("show");
    		}else if(user == my2nduser){
    			$("#nojumpsecond").modal("show");
    		}
    		else{
				socket.emit('jump', data);
			}

		}

		function receive_jump(data){
    		var user = data.username;    		
			console.log(user);

			var newLI;
			if(user == username)
			{
				newLI = $("<li id ='"+data.queue_index+"' class='list-group-item justify-content-between' name ='"+user+"'><span class = 'mytext'>"+user+"</span><span class='badge badge-default badge-pill '><button name ='"+user+"' value='Remove' class='btn btn-outline-danger remove'><i class='fas fa-ban'></i></button></span></span></li>");
			}else{

				newLI = $("<li id ='"+data.queue_index+"' class='list-group-item justify-content-between' name ='"+user+"'><span class = 'mytext'>"+user+"</span></li>");
			}

			$(".queue li:nth-child(1)").after(newLI);


			setTimeout(function() {
				newLI.addClass( "show" );
			}, 10);

			/*$.ajax({
		        type: "POST",
		        url: "updateJump.php",
		        data: {"name": user},
		        success:function(){console.log("successful jump")}
	        });

	    		console.log("add " + user);
	    	}*/

		};

		var modalConfirm = function(callback){

		  $("#modal-btn-si").on("click", function(){
		    callback(true);
		    $("#mi-modal").modal('hide');
		  });
		  
		  $("#modal-btn-no").on("click", function(){
		    callback(false);
		    $("#mi-modal").modal('hide');
		  });

		}

		modalConfirm(function(confirm){
			datapackage.response = confirm;
			send_response(datapackage);
			//console.log("hello, this is doing thing");
		});
		


	</script>


</body>

</html>
