<?php

	require_once 'BSG.php';  //require class


	$apikey = '';  //my api key
	$api = new BSG($apikey, 'originator', 'aplhaname', 3, 'Bitrix-24');
	$client = $api->getSmsClient(); //get one of the clients
	$balance = $client->getBalance();
		

	if(isset($_POST['phone']) && sizeof($_POST['phone']) && isset($_POST['message']) && !empty($_POST['message'])){
		$message = trim($_POST['message']);
		header('content-type: text/plain');
		print_r($_POST);
		foreach($_POST['phone'] as $phone){
			list($numero, $nombre) = explode(':', $phone);
			$result = $client->sendSms(
				$numero, 
				$nombre.",\r\n".$message, //modificar si no se desea salto de linea 
				'successSend' . (string)time()
			);
			echo "\r\n";
			echo $nombre.",\r\n".$message;
			//print_r($result);
			echo "\r\n\r\n";
		}
		
		die;
	}
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>SMS</title>
	<meta charset="utf-8">
      <link type="text/css" rel="stylesheet" href="iconos.css" rel="stylesheet">
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" media="screen">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<style>
input[type='file'] { //hide text file tex
  color: transparent;
}

::placeholder { //change placeholder color
  color: red;
  opacity: 1; 
}

</style>
<body>


<?php

	echo "<br><br><br><br>";
  //envio a un solo numero 
  /*
	$result = $client->sendSms(
		'57', //envio a numero telefonico formato "570000000000"
		'101669', 
		'successSend' . (string)time()
	);
	print_r($result);
	*/
  
	?>
	
	
	

	  
	  
	  
	<main>
    <center>
	  <div class="container">
    <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
    <div class='row'>
    <div class='col s12'>
    <?php
		
		echo '<b> Su saldo es '.$balance['currency'].': '. $balance['amount'].'</b>'; //muestra saldo del usuario

		?>  
			  </div>
            </div>

 <center>
 <h3>formato de numeros a enviar +57**********</h3>    
				 <div class='row'>
				 <div class="col s6">
				 <div class="file-field input-field">
            <div class="btn">
              <span>
			          <i class="fa fa-file" aria-hidden="true"></i>
			        </span>
              <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
			        <input type="file" name="archivo" id="inputFile" accept=".txt">
            </div>
			
            <div class="file-path-wrapper">
              <input class="file-path validate" placeholder="click para subir archivo" type="text">
            </div>     
          </div>
				</div>
				<div class="col s4 input-field">
			    <input class="" id="newPhone" type="text" placeholder="ingrese el numero 57**********">
				</div>
				<div class="col s1 input-field">
				<input class="btn" id="btnAddPhone" type="button" value="+" >
				</div>
				</div>
				<button  onclick="document.getElementById('link').click()"> DESCARGA EL FORMATO!</button>
          <a id="link" CLASS="RED" href="numero.txt" download hidden></a>
        <br><br><br>			
				
				

<div id="templatePhone" style="display:none;">
		<input class="valuePhone" name="phone[]" type="hidden" value="">
		<span class="textPhone"></span><span class="valuePhone"></span>
		<input class="deletePhone" type="button" value="X">
	</div>
	
	<form id="form" method="post">
		<textarea name="message" placeholder="ingresar texto de mensaje"  maxlength="200"></textarea>
		<input class="btn" type="submit" value="enviar">
		<button type="reset" class='col s5 btn waves-effect red lighten-2'>Borrar Mensaje</button>
		
	</form>

</center>


		
		
		
		
		
		
	
	<script>
		window.addEventListener("load", function() {
			newPhone.addEventListener("keypress", soloNumeros, false);
		});
			function soloNumeros(e){
			  var key = window.event ? e.which : e.keyCode;
			  if (key < 48 || key > 57) {
				e.preventDefault();
			  }
			}


		function addPhone(){
			var nombre = newPhone.value.toString().trim().replace(/([\d])+/ig, "").trim();
			var numero = newPhone.value.toString().replace(/\D/ig,"").trim();
			var value = numero;
			if(value == '' || value == null){
				return;
			}
			var div = templatePhone.cloneNode(true);
			console.log(div);
			div.removeAttribute('id');
			div.removeAttribute('style');
			div.querySelector('.valuePhone').value = numero+':'+nombre;
			//div.querySelector('.textPhone').innerText = value.split(/([\d]{3})([\d]{3})([\d]{4})/, ).map(function(s, i){
				div.querySelector('.textPhone').innerText = nombre+" "+value.split().map(function(s, i){
				if(i == 1){
					s = '('+s+') ';
				}else
				if(i == 2){
					s = ''+s+'-';
				}
				return s;
			}).join("");
			div.querySelector('.deletePhone').onclick = function(){
				this.parentNode.parentNode.removeChild(this.parentNode);
			};
			console.log(div);
			form.appendChild(div);
			newPhone.value = "";
		}
		newPhone.oninput = function(){
			//this.value = this.value.replace(/\D/ig,"");
		}
		newPhone.onkeyup = function(e){
			if(e.keyCode == 13){
				addPhone();
			}
		}
		btnAddPhone.onclick = function(e){
			addPhone();
		}
		inputFile.onchange = function(){
			var file = this.files[0];
			var reader = new FileReader(); 
			reader.onload = function(e) {
				var text = this.result;
				var lines = text.split(',');
				lines = lines.map(function(phone){
					phone = phone.trim();
					newPhone.value = phone;
					addPhone();
					return phone;
				});
				console.log(lines);
			}
			reader.readAsText(file );
		}
		
		var grupos = [];
		function send(){
			if(grupos.length > 0){
				grupo = grupos.shift();
				console.log('send');
				var data = form['message'].name+"="+encodeURIComponent(form['message'].value);
				
				grupo.map(function(temp){
					data += "&"+temp.name+"="+encodeURIComponent(temp.value);
					return temp;
				});
				var http = new XMLHttpRequest();
				http.open('POST', "", true);
				http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				http.onreadystatechange = function() {//Call a function when the state changes.
					if(http.readyState == 4) {
						console.log(http.responseText);
						send();
					}
				}
				http.send(data);
			}
		}
		form.onsubmit = function(e){
			e.preventDefault();
			console.log(this);
			var list = [].slice.call(this['phone[]']);
			while(list.length){ 
				grupos.push(list.splice(0, 10));
			}
			console.log("grupos:", grupos);
			send();
			return false;
		}
		
	</script>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
	</div>
	</div>
	  </center>
	 </main>
</body>
</html>
