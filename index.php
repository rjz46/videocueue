
<!DOCTYPE html>
<html>
<head>
	
	<title>Queue Demo</title>

	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
      

	<script>
		var socket = io('http://localhost:80');


  		socket.on('connection', function (data) {
			//socket.on('news', function (data) {
    			console.log("stuff");
  			//socket.emit('my other event', { my: 'data' });
  			//});
  		});

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
				  		<input type="button" name="name" class="btn btn-outline-success add" value="Jim" onclick="return add(this)"/>
					</div>
					<div class="btn-group" role="group">
				  		<input type="button" name="name" class="btn btn-outline-success add" value="Ru" onclick="return add(this)"/>
					</div>
					<div class="btn-group" role="group">
						<input type="button" name="name" class="btn btn-outline-success add" value="Luping" onclick="return add(this)"/>
					</div>
					<div class="btn-group" role="group">
						<input type="button" name="name" class="btn btn-outline-success add" value="Liye" onclick="return add(this)"/>
					</div>
					<div class="btn-group" role="group">
						<input type="button" name="name" class="btn btn-outline-success add" value="Elise" onclick="return add(this)"/>
					</div>
					<div class="btn-group" role="group">
						<input type="button" name="name" class="btn btn-outline-success add" value="Paula" onclick="return add(this)"/>
					</div>
				</fieldset>
			</form>
		
		</div>

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
		<button type="button" id="next" class="btn btn-outline-primary">Next Person</button>
	</div>


	<script>

		function add(val){
    		var user = val.value;
    		console.log("add " + user);
    		var myuser = $( ".queue li" ).last().attr('name');

    		if(user == myuser)
    		{
    			alert("cannot add again, please wait");
    		}else{
				$(".queue").append("<li class='list-group-item justify-content-between' name ='"+user+"'><span>"+user+"</span><span class='badge badge-default badge-pill'><input type='button' name ='"+user+"' value='Remove' class='btn btn-outline-danger remove'/></span></li>");		
			}		


			$.ajax({
		        type: "POST",
		        url: "updateAdd.php",
		        data: {"name": user},
		        success:function(){console.log("successful add")}
	        });

	        socket.emit('add', { username: user });

		};

		function remove(val){
			var user = val.name;
			console.log("remove " + user);

			$.ajax({
		        type: "POST",
		        url: "updateRemove.php",
		        data: {"name": user},
		        success:function(){console.log("successful remove")}
	        });
		}

		function next(){
			$.ajax({
		        type: "POST",
		        url: "updateNext.php",
		        success:function(){console.log("successful next")}
	        });
		}


		$(document).on( "click", '.remove', function() {
  			$(this).parent().parent().detach();
  			remove(this);

  			socket.emit('remove', { username: remove });

		});

		//remove first
		$( "#next" ).click(function() {
  			$( ".queue li" ).first().remove();
  			next();
		});


	</script>


</body>

</html>
