<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PRIJAVA</title>

</head>
<body>
	<div id="container">
	
		
		<h3>PRIJAVA</h3>
		<form id="formLogin" method="post" role="form" action="<?php echo $this->config->base_url(); ?>CtrMain/login_perform">
			
			<label>E-mail</label>
			<input type="text" name="email" id="email" required>
			
			<br/>
			<br/>
			
			<label>Geslo</label>
			<input type="text" name="password" id="password" required>
			
			<br/>
			<br/>
			<button type="submit">PRIJAVA</button>
				
		</form>
	</div>
</body>
</html>
