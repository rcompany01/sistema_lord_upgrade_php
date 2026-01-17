<script type="text/javascript">
	function NovaEmpresa(){
	$("#formulario-novo-orcamento").fadeIn('slow');
	document.getElementById('tabela-empresas').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";	
}
</script>

<?php 
	require_once("class/financeiro.class.php");
	$class = new Financeiro;


	// BUSCA O NOME DO SETOR
	require_once("clientes/class/setores.class.php");
	$set = new Setores;
?>

<div class="cx-baixa-escala">
	<div id='formulario-baixa-escala' class="formulario-baixa-escala">
		<?php
			if (isset($_GET['GerarLote'])){
				require_once("gerarLote.php");
			}else{
		?>
		<form class="form-inline" method="post">
			<fieldset style="margin-top:30px">
				<legend>Baixar Escalas</legend>
				<label style="margin:0px 0px 0px 33px" class="control-label">Periodo:</label>
				<br>
					<label style="margin:15px 0px 0px 30px">De:</label> 
						<input class="input-medium" style="margin:0px 0px 0px 0px" required name="de" type="date"> <br>

					<label style="margin:15px 0px 0px 30px">Até:</label> 
						<input class="input-medium" style="margin:0px 0px 0px 0px" required name="ate" type="date">

				<br>

				

			</fieldset>



			<div class="form-actions">
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Buscar</button>			  
			</div>

		</form>


<?php
	}if (isset($_POST['de'])){
		$de = $_POST['de'];
		$ate = $_POST['ate'];
		$class->BuscaEscalas($de, $ate);
?>

<fieldset style="margin-top:30px">
	<legend>Escalas (De: <?= $class->FormataData($_POST['de']) ?> ~  Até: <?= $class->FormataData($_POST['ate']) ?>)</legend>

		<table class="table table-striped">
			<tr>
				<td>Status</td>
				<td>Solicitação</td>
				<td>ID Cliente</td>
				<td>Cliente</td>
				<td>ID Setor</td>
				<td>Setor</td>
				<td>Dt. Evento</td>
				<td>Ações</td>
			</tr>
<?php
		while ($row = mysqli_fetch_assoc($class->BuscaEscalas)){
			$baixa = "";
			if ($row['status_baixa']=='0'){
				$baixa = "<img src='../img/b1.png'>";
			}else{
				$baixa = "<img src='../img/b2.png'>";
			}

?>
			<tr>
				<td><?= $baixa ?></td>
				<td><?= $row['id_esc'] ?></td>
				<td><?= $row['id_cliente'] ?></td>
				<td><?= $class->BuscaNomeCliente($row['id_cliente']) ?></td>
				<td><?= $row['setor'] ?></td>
				<td><?= $set->NomeSetor($row['setor']) ?></td>
				<td><?= $class->FormataData($row['data_evento']) ?></td>
				<td>
					<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&GerarLote=<?= $row['id_esc'] ?>&de=<?= $_POST['de'] ?>&ate=<?= $_POST['ate'] ?>'" class="btn btn-success">Gerar Lote</button>
				</td>
			</tr>
<?php
	} // FIM DO WHILE
?>
		</table>
</fieldset>
<?php
	} // FIM DO IF
?>

	</div>
</div>
