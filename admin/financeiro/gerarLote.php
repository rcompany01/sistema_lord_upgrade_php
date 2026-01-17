<?php
	
	// PEGA O NOME DO PRESTADOR
	require_once("prestadores/class/prestadores.class.php");
	$name = new Prestadores;



	// PEGA O NOME DA FUNCAO
	require_once("clientes/class/funcoes.class.php");
	$func = new Funcoes;


	require_once("escalas/class/escalas.class.php");
	$esc = new Escalas;
	// TRAZ OS DADOS DA ESCALA
	if (isset($_GET['GerarLote'])){
		$esc->BuscaEscala($_GET['GerarLote']);
		$rowEsc = mysqli_fetch_assoc($esc->BuscaEscala);
	}


	
?>

<form name="BuscaEscala" method="post" action="?funcao=baixarEscalas">
	<input type="hidden" name="de" value="<?= $_GET['de'] ?>">
	<input type="hidden" name="ate" value="<?= $_GET['ate'] ?>">
</form>

<?php
	// GERA UM LOTE
		if (isset($_POST['lote'])){
			$class->NovoLote($_POST['lote'],$_GET['GerarLote']);
		}
?>

<form method="post" class="form-inline">
	<fieldset style="margin-top:30px">
		<legend>Gerar Lote</legend>

		<label style="margin:0px 0px 0px 30px" class="control-label"><b>Solicitação:</b></label>
			<small><?= $rowEsc['id_esc'] ?></small> <br>

		<label style="margin:5px 0px 0px 30px"><b>ID:</b></label>
			<small><?= $rowEsc['id_cliente'] ?></small> <br>			

		<label style="margin:5px 0px 0px 30px"><b>Cliente:</b></label>
			<small><?= $class->BuscaNomeCliente($rowEsc['id_cliente']) ?></small> <br>

		<label style="margin:5px 0px 0px 30px" class="control-label"><b>ID Setor:</b></label>
			 <small><?= $rowEsc['setor'] ?></small><br>

		<label style="margin:5px 0px 0px 30px" class="control-label"><b>Dt. Evento:</b></label>
			<small><?= $class->FormataData($rowEsc['data_evento']) ?></small> <br>


		<label style="margin:25px 0px 0px 30px" class="control-label"><b>Lote:</b></label>
			<input style="text-transform: uppercase;" name="lote" required type="text" class="input-small">


		



		<div class="form-actions">
			<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
			<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Gerar</button>			  
		</div>



		<table style="margin-top:30px" class="table table-striped">
			<tr>
				<td>Prestadores</td>
				<td>ID Função</td>
				<td>Função</td>
				<td>Hora Entrada</td>
				<td>Hora Saída</td>
				<td>Hora Extra</td>
			</tr>
<?php
	// LISTA TODOS OS PRESTADORES PARA ESTE EVENTO
		
		if (isset($_GET['GerarLote'])){
			$class->BuscaPrestadoresEvento($_GET['GerarLote']);
			while ($prest = mysqli_fetch_assoc($class->BuscaPrestadoresEvento)){
?>
			<tr>
				<td><?= $name->NomePrestador($prest['id_prestador']) ?></td>
				<td><?= $prest['id_funcao'] ?></td>
				<td><?= $func->NomeFuncao($prest['id_funcao']) ?></td>
				<td><?= $prest['entrada'] ?></td>
				<td><?= $prest['saida'] ?></td>
				<td><?= $prest['extra'] ?></td>
			</tr>
<?php
	}
}
?>
		</table>

	</fieldset>
</form>
