<script type="text/javascript">
	function Ver(id){
		$("#base"+id).fadeIn('slow');
	}

	function Fechar(id){
		document.getElementById('base'+id).style.display="none";
		$("#base"+id).fadeOut('slow');
	}
</script>

<script language="JavaScript">
function ImpressaoFat(URL) {
 
  var width = 900;
  var height = 550;
 
  var left = 200;
  var top = 20;
 
  window.open(URL,'janela', 'width='+width+', height='+height+', top='+top+', left='+left+', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');
}
</script>


<?php
	// INSTANCIA A CLASSE
	require_once("class/financeiro.class.php");
	$class = new Financeiro;



	// CASO NÃO TENHA OCORRIDO ALGUMA BUSCA, MOSTRA A CAIXA DE PESQUISA
	if (!isset($_POST['de-rpa'])){
?>

<div style='height:200px' class="pesq-rpa">
	<form class="form-inline" method="post">
		<label style="margin:23px 0px 0px 52.5px" class="nome-campos">De:</label>
			<input name="de-rpa" required type="date" class="input-medium"> <br>

		<label style="margin:23px 0px 0px 50.5px" class="nome-campos">Até:</label>
			<input name="ate-rpa" required type="date" class="input-medium"> <br>

		<label style="margin:23px 0px 0px 50.5px" class="nome-campos">Lote:</label>
			<input style='text-transform:uppercase' name="lote-rpa" required type="text" class="input-medium"> <br>

		<button style="margin:10px 0px 0px 106px;" class="btn btn-primary">
			<u class="icon-search icon-white"></u> 
			Buscar
		</button>
	</form>
</div>

<?php
	// APÓS A BUSCA, LISTA OS RELATÓRIOS
	}else{
		$class->FaturamentoPorPrestador($_POST['de-rpa'], $_POST['ate-rpa'], $_POST['lote-rpa']);
?>

<div class="tabela-rpa">
	<?php
		if (isset($_POST['de-rpa'])){
			echo "<h4 class='periodo-rpa'>Periodo: De ".$class->FormataData($_POST['de-rpa'])."  ~ Até ".$class->FormataData($_POST['ate-rpa'])."</h4>";
		}
	?>
	<table class="table table-striped">
		<tr>
			<td><b>ID</b></td>
			<td><b>Prestador</b></td>
			<td><b>Total</b></td>
		</tr>
	
	<?php
		while($row=mysqli_fetch_assoc($class->FaturamentoPorPrestador)){
	?>
		<tr>
			<td><?= $row['id_prestador'] ?></td>
			<td style='text-transform:uppercase'><?= $row['nome_prest'] ?></td>
			<td>
				<?= "R$ ".number_format($row['total'],2) ?>
			</td>
		</tr>
	<?php
		}
	?>

		<!-- <tr>
			<td colspan="4">
				<div id="base<?= $row['id_prest'] ?>" class="fat-prest-total">
					<form class="form-inline">
						<div class="cx-dados-prest-fat">
							<label class="txt-fat-prest">Nome:</label>
								<small><?= $row['nome_prest'] ?></small> <br>

							<label class="txt-fat-prest">Endereço:</label>
								<small>
									<?= $row['logradouro_prest'].", ".$row['numero_prest']."<br>
										<label class='txt-fat-prest'><b>Bairro:</b></label> ".
										$row['bairro_prest']." - ".$row['uf_prest'];
									?>
								</small> <br>

							<label class="txt-fat-prest">CPF:</label>
								<small><?= $row['cpf_prest'] ?></small>

							<label style="margin-left:60px" class="txt-fat-prest">RG:</label>
								<small><?= $row['rg_prest'] ?></small> <br>

							<label class="txt-fat-prest">INSS:</label>
								<small><?= $row['inss'] ?></small> 
						</div>


						<div class="cx-rela-fat-prest">
							<label class="txt-fat-prest">Periodo:</label> <br>
							<small>De: <?= $class->FormataData($_POST['de-rpa'])." ~ ".$class->FormataData($_POST['ate-rpa']) ?></small> <br>

							<label style="margin-top:10px" class="txt-fat-prest">Total:</label> <br>

							<h4 style="margin:0"><?= "R$ ".number_format($row['total'],2) ?></h4> <br>

							<u class="icon-print icon-white"></u>
							<input value="Imprimir" type="button" onClick="ImpressaoFat('http://localhost/lorde/admin/financeiro/FaturamentoPrestador.php?de=<?= $_POST['de-rpa'] ?>&ate=<?= $_POST['ate-rpa'] ?>&prest=<?= $row['id_prest'] ?>')" style="margin:0" class="btn btn-primary">
								
						</div>
					</form>
				</div>
			</td>	
		</tr> -->
	

	</table>

	<div class="form-actions">
		<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn btn-warning">Voltar</button>	  
		<button id="imprimir" style="float:right;margin-right:20px" class="btn btn-primary">
			<u class="icon-print icon-white"></u> Imprimir
		</button>
	</div>
</div>
<?php
	}

?>