<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>DOGODEK <?php echo $dogodek->ime;?></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> <!-- nucamo jquery da lahko postamo ($.ajax({...) -->

</head>
<body>
	<div id="container">
	
		<h3>INFORMACIJE DOGODKA</h3>
		
		
		<?php 
		
		if($dogodek->slika != "")//če ima dogodek sliko jo prikažemo, damo tudi link okoli nje da lahko odpremo celo sliko
		{
		?>
			<a href="<?php echo $this->config->base_url(); ?>slike/<?php echo $dogodek->slika; ?>"><img src="<?php echo $this->config->base_url(); ?>slike/<?php echo $dogodek->slika; ?>" alt="<?php echo $dogodek->slika; ?>" height="150"></a>
			<br/>
		<?php 
		}
		?>	
		
		<p>Id dogodka: <?php echo $dogodek->id;?></p>
	
		<p>Ime dogodka: <?php echo $dogodek->ime;?></p>
	
		<p>Lokacija dogodka: <?php echo $dogodek->lokacija;?></p>
		
		<p>Začetek dogodka: <?php echo date('d/m/Y H:i', $dogodek->zacetek);?></p>
		
		<p>Trajanje dogodka: <?php echo date('d/m/Y H:i', $dogodek->trajanje);?></p>
		
		<p>Termin prijave/odjave: <?php echo date('d/m/Y H:i', $dogodek->termin); ?></p>
		
		<p>Min. udeležencev:  <?php echo $dogodek->min_udelezencev; ?></p>
		
		<p>Max. udeležencev:  <?php echo $dogodek->max_udelezencev; ?></p>
		
		<p>Trenutno udeležencev: <?php echo count($prijavljeniNaDogodek); //count vrne število elementov v array ?></p>
		
		<p>Opis dogodka: <?php echo $dogodek->opis; ?></p>
		
		
		<?php 
		$trenutniCasTimestamp = time() + 7200;
		
		if($dogodek->termin < $trenutniCasTimestamp) //če je timestamp trenutnega časa večji je dogodek potekel in lahko prikažemo oceno
		{
		?>
		
		<p>Ocena dogodka: <?php echo $ocena; ?></p>
		
		<?php 
		if($dogodek->prisotnost == "Y") // če je bil uporabnik prisoten lahko tudi dogodek oceni
		{
		?>
		
		Oceni dogodek
		<select id="oceni">
		  <option value="1">1</option>
		  <option value="2">2</option>
		  <option value="3">3</option>
		  <option value="4">4</option>
		  <option value="5" selected>5</option>
		</select>
  		<button onclick="oceniDogodek(<?php echo $dogodek->id; ?>)">Oceni</button>
		
		<?php 
		}
		?>
		
		
		
		<?php
		}
		?>
		
		
		
		
		<?php 
		
		$trenutniCasTimestamp = time() + 7200;
		
		if($dogodek->termin > $trenutniCasTimestamp) //če je timestamp trenutnega časa manjši od timestampa termina prijave/odjave pomeni da se še vedno lahko prijavimo/odjavimo
		{
			if(isset($dogodek->id_uporabnika))  //če ima id_uporabnika pomeni da je ta uporabnik že prijavlen na ta dogodek, zato mu ponudimo odjavo
			{
			?>
			<button onclick="odjavaIzDogodka(<?php echo $dogodek->id;?>)">ODJAVA</button>
			<?php 
			}
			else 
			{
			?>
			<button onclick="prijavaNaDogodek(<?php echo $dogodek->id;?>)">PRIJAVA</button>
			<?php 
			}
		}
		?>
		
		
		<?php 
		if($tipUporabnika == 1) //prikažemo seznam prijavljenih uporabnikov samo organizatorju
		{
		?>
			<h3>PRIJAVLJENI NA DOGODEK</h3>
			<?php 
			foreach ($prijavljeniNaDogodek as $uporabnik) 
			{
				echo $uporabnik->ime.", ".$uporabnik->priimek;
			?>
			
				<?php 
				
				$trenutniCasTimestamp = time() + 7200;
				
				if($dogodek->trajanje  < $trenutniCasTimestamp) //gumbe za prisotnost pokažemo le če je dogodek že pretekel
				{
					if($uporabnik->prisotnost == "N") //če 
					{
					?>
						<button onclick="PotrdiPrisotnost(<?php echo $uporabnik->id; ?>, <?php echo $dogodek->id;?>)">POTRDI PRISOTNOST</button>
						<br>
					<?php
					}
					else if($uporabnik->prisotnost == "Y")
					{
					?>
						<button onclick="OdpotrdiPrisotnost(<?php echo $uporabnik->id; ?>, <?php echo $dogodek->id;?>)">ODPOTRDI PRISOTNOST</button>
						<br>
					<?php 
					}
				}
				?>
				
			<?php 
			}
			?>
		<?php
		}
		?>
		
		<br/>
		<br/>
	
		<a href="<?php echo $this->config->base_url(); ?>CtrMain">NAZAJ</a>
		
	</div>
	
	
	<script>

	function oceniDogodek(idDogodka) 
	{
		var selectOceni = document.getElementById("oceni");
		var ocenaDogodka = selectOceni.options[selectOceni.selectedIndex].value; //dobimo izbrano oceno dogodka (https://stackoverflow.com/questions/1085801/get-selected-value-in-dropdown-list-using-javascript)

		$.ajax({
	        url : "http://localhost/Dogodki_praktikum/CtrMain/oceni_dogodek",
	        type: "POST",
	        data: {'idDogodka': idDogodka, 'ocenaDogodka': ocenaDogodka},
	        success: function (data) 
	        {
	        	if(data == 1) {
		        	alert("Vaša ocena je zabeležena");
					location.reload();
			    } else {
					alert("Napaka");
				}
	        },
	        error: function (jXHR, textStatus, errorThrown) {
		        alert(errorThrown);
	        }
	    });
	}
	 
	function PotrdiPrisotnost(idUporabnika, idDogodka)
	{
		//alert(idDogodka);
		$.ajax({
	        url : "http://localhost/Dogodki_praktikum/CtrMain/potrdi_prisotnost",
	        type: "POST",
	        data: {'idUporabnika': idUporabnika, 'idDogodka': idDogodka},
	        success: function (data) 
	        {
	        	if(data == 1) {
					location.reload();
			    } else {
					alert("Napaka");
				}
	        },
	        error: function (jXHR, textStatus, errorThrown) {
		        alert(errorThrown);
	        }
	    });
	}
	
	function OdpotrdiPrisotnost(idUporabnika, idDogodka)
	{
		$.ajax({
	        url : "http://localhost/Dogodki_praktikum/CtrMain/odpotrdi_prisotnost",
	        type: "POST",
	        data: {'idUporabnika': idUporabnika, 'idDogodka': idDogodka},
	        success: function (data) 
	        {
	        	if(data == 1) {
					location.reload();
			    } else {
					alert("Napaka");
				}
	        },
	        error: function (jXHR, textStatus, errorThrown) {
		        alert(errorThrown);
	        }
	    });
	}
	
	function prijavaNaDogodek(idDogodka) //javaskript fukncija, ki te preko posta prijavi na dogodek 
	{
		//alert(idDogodka);
		$.ajax({
	        url : "http://localhost/Dogodki_praktikum/CtrMain/prijava_na_dogodek",
	        type: "POST",
	        data: {'idDogodka': idDogodka},
	        success: function (data) 
	        {
	        	if(data == 1) {
					alert("Uspešno prijavlen na dogodek");
					location.reload();
			    } else {
					alert("Napaka");
					location.reload();
				}
	        },
	        error: function (jXHR, textStatus, errorThrown) {
		        alert(errorThrown);
	        }
	    });
	}
	
	function odjavaIzDogodka(idDogodka)
	{
		$.ajax({
	        url : "http://localhost/Dogodki_praktikum/CtrMain/odjava_iz_dogodka",
	        type: "POST",
	        data: {'idDogodka': idDogodka},
	        success: function (data) 
	        {
	        	if(data == 1) {
					alert("Uspešno odjavljeni iz dogodka");
					location.reload(); //osveži stran
			    } else {
					alert("Napaka");
					location.reload();
				}
	        },
	        error: function (jXHR, textStatus, errorThrown) {
		        alert(errorThrown);
	        }
	    });
	}
	
	</script> 
</body>
</html>
