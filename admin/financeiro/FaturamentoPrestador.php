<script type="text/javascript">
	window.print();
</script>

<?php
	require_once("class/financeiro.class.php");
	$class = new Financeiro;

	$class->FaturamentoPorPrestadorID($_GET['de'], $_GET['ate'], $_GET['prest']);
	$vt=mysqli_fetch_assoc($class->FaturamentoPorPrestadorID);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../../css/styles.css">
	<link rel="stylesheet" href="../../css/bootstrap.css">
	<title>Relátorio de Faturamento</title>
</head>
<body>
	<form class="form-inline">
	<div class="cx-fat-prest">
		<div class="logo_dm">
			<img src="../../img/logo.png" height="77" width="120" alt="">
		</div>
			
		<h4 style="margin-left:180px" class="titulo-dm">Faturamento Total</h4> <br> <br> <br> <br>

	<hr>
		<div class="cx-dados-prest-fat">
			<label style="margin-left:30px" class="txt-fat-prest">Nome:</label>
				<small><?= $vt['nome_prest'] ?></small> <br>

			<label style="margin-left:30px" class="txt-fat-prest">Endereço:</label>
				<small><?= $vt['logradouro_prest'].", ".$vt['numero_prest'] ?></small> <br>

			<label style="margin-left:30px" class="txt-fat-prest">Bairro:</label>
				<small><?= $vt['bairro_prest']." - ".$vt['uf_prest'] ?></small> <br>

			<label style="margin-left:30px" class="txt-fat-prest">CPF:</label>
				<small><?= $vt['cpf_prest'] ?></small> 

			<label style="margin-left:150px" class="txt-fat-prest">RG:</label>
				<small><?= $vt['rg_prest'] ?></small> <br>

			<label style="margin-left:30px" class="txt-fat-prest">INSS:</label>
				<small><?= $vt['inss'] ?></small> <br>
		</div>

		<div style="margin-left:130px" class="cx-rela-fat-prest">
			<label class="txt-fat-prest">Período:</label> <br>
				<small>De: <?= $class->FormataData($_GET['de'])." ~ Até:".$class->FormataData($_GET['ate']) ?></small> <br>
		
			<label style="margin-top:10px" class="txt-fat-prest">Total:</label> <br>
				<h4 style="margin:0"><?= "R$ ".number_format($vt['total'],2) ?></h4>
		</div>
	</div>
</form>
</body>
</html>

