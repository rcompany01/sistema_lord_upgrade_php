<script type="text/javascript">
	function NovaEmpresa(){
	$("#formulario-novo-cheque").fadeIn('slow');
	document.getElementById('tabela-contas').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";	
}
</script>

<?php
	require_once('prestadores/class/prestadores.class.php');
	$prest = new Prestadores;

	require_once('class/contas.class.php');
	$class = new Contas;

	// FAZ A CONFIRMAÇÃO DE REMOVER O REGISTRO
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=cheques&deleteCheque="+id;
		     }
		}
    </script>

 <?php

 	// INSERE UM NOVO REGISTRO
 	if (isset($_POST['id_prest'])){
 		$class->NovoCheque($_POST['id_prest'], $_POST['num_cheque']);
 	}


 	// DELETA O REGISTRO
 	if (isset($_GET['deleteCheque'])){
 		$class->DeletarCheque($_GET['deleteCheque']);
 	}
 ?>

<?php
	// PAGINA DE EDITAR O REGISTRO
	if (isset($_GET['idAccount'])){
		require_once('alt_contas.php');
	}else{
?>

<div class="cx-contas-dados">
	
	<div id="cx-funcoes" class="cx-funcoes"></a>
		<img style="cursor:pointer" onClick="NovaEmpresa()" src="../img/bt_novo1.png" alt="">
		<img style="cursor:pointer" id='imprimir' src="../img/bt_imprimir1.png" alt="">
	</div>

	<table id='tabela-contas' class="table table-striped">
		<tr style="font-weight:bold">
			<td style="text-align:center">ID Prestador</td>
			<td style="text-align:center">Prestador</td>
			<td style="text-align:center">Nº Cheque</td>
			<td style="text-align:center">Ações</td>
		</tr>
<?php
	// LISTA TODAS AS CONTAS CADASTRADAS
	$class->ListaCheques();
	while ($row=mysqli_fetch_assoc($class->ListaCheques)) {
?>
		<tr>
			<td style="cursor:pointer;text-align:center"><?= $row['id_prestador'] ?></td>
			<td style="cursor:pointer;text-align:center"><?= $prest->NomePrestador($row['id_prestador']) ?></td>
			<td style="cursor:pointer;text-align:center"><?= $row['num_cheque'] ?></td>
			<td style="text-align:center">
				<button onClick="confirmacao(<?= $row['id_cheque'] ?>)" class="btn btn-danger">Excluir</button>
			</td>
		</tr>
<?php
	}
?>
	</table>

	<div id='formulario-novo-cheque' class="formulario-novo-cheque">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados do Cheque</legend>


				<label style="margin:15px 0px 0px 56px" class="control-label">Prestador:</label>
					<select required class="span2 input-large" name="id_prest" id="">
						<option value="">Selecione..</option>
						<?php
							// LISTA TODOS OS PRESTADORES EM UM SELECT
							$prest->ListaPrestadoresAlf();
							while($row_prest=mysqli_fetch_assoc($prest->ListaPrestadoresAlf)){
						?>
						<option value="<?= $row_prest['id_prest'] ?>"><?= $row_prest['nome_prest'] ?></option>
						<?php
							}
						?>
					</select>

					<br>
				

				<label style="margin:15px 0px 0px 31px" class="control-label">Num. Cheque:</label>
					<input required name="num_cheque" class="input-medium" type="text">

			</fieldset>


			<div class="form-actions">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
			</div>
		</form>
	</div>
</div>

<?php
	}
?>