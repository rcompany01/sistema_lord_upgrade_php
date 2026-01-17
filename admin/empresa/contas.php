<script type="text/javascript">
	function NovaEmpresa(){
	$("#formulario-nova-conta").fadeIn('slow');
	document.getElementById('tabela-contas').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";	
}
</script>

<?php
	require_once('class/banco.class.php');
	require_once('class/contas.class.php');
	require_once('prestadores/class/prestadores.class.php');
	$prest = new Prestadores;
	$banco = new Bancos;
	$class = new Contas;


	// CADASTRA UMA NOVA CONTA
	if (isset($_POST['id_banco'])){
		$class-> NovaConta( addslashes($_POST['id_banco']),
							addslashes($_POST['num_banco']),
							addslashes($_POST['agencia']),
							addslashes($_POST['ag_dig']),
							addslashes($_POST['cc']),
							addslashes($_POST['dig_cc']),
							addslashes($_POST['carteira']),
							addslashes($_POST['convenio']),
							addslashes($_POST['id_prest'])
							);
	}
?>

<?php
	// FAZ A CONFIRMAÇÃO DE REMOVER O REGISTRO
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=contas&deleteAccount="+id;
		     }
		}
    </script>

 <?php
 	// DELETA O DEPARTAMENTO
 	if (isset($_GET['deleteAccount'])){
 		$class->DeletarConta($_GET['deleteAccount']);
 	}
 ?>

<?php
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
			<td>Prestador</td>
			<td>Banco</td>
			<td>Conta</td>
			<td>Num. Banco</td>
			<td>Agência</td>
			<td>Digito</td>
			<td>Conta Corrente</td>
			<td>Digito CC</td>
			<td>Ações</td>
		</tr>
<?php
	// LISTA TODAS AS CONTAS CADASTRADAS
	$class->ListaContas();
	while ($rowCC=mysqli_fetch_assoc($class->ListaContas)) {

	// BUSCA O NOME REAL DO BANCO PELO ID
	$banco->ListaBancoID($rowCC['id_banco']);
	$nome_banco = mysqli_fetch_assoc($banco->ListaBancoID);
?>
		<tr>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idAccount=<?= $rowCC['id_conta'] ?>'"><?= $class->NomePrestador($rowCC['id_prestador']) ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idAccount=<?= $rowCC['id_conta'] ?>'"><?= $nome_banco['banco'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idAccount=<?= $rowCC['id_conta'] ?>'"><?= $rowCC['conta'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idAccount=<?= $rowCC['id_conta'] ?>'"><?= $rowCC['num_banco'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idAccount=<?= $rowCC['id_conta'] ?>'"><?= $rowCC['agencia'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idAccount=<?= $rowCC['id_conta'] ?>'"><?= $rowCC['ag_digito'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idAccount=<?= $rowCC['id_conta'] ?>'"><?= $rowCC['conta'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idAccount=<?= $rowCC['id_conta'] ?>'"><?= $rowCC['cc_digito'] ?></td>
			<td>
				<button onClick="confirmacao(<?= $rowCC['id_conta'] ?>)" class="btn btn-danger">Excluir</button>
			</td>
		</tr>
<?php
	}
?>
	</table>

	<div id='formulario-nova-conta' class="formulario-nova-conta">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados da Conta</legend>


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
				<label style="margin:15px 0px 0px 78px" class="control-label">Banco:</label>
					<select required class="span2" name="id_banco" id="">
						<option value="">Selecione..</option>
						<?php
							// LISTA TODOS OS BANCOS EM UM SELECT
							$banco->ListaBanco();
							while($row_banco=mysqli_fetch_assoc($banco->ListaBanco)){
						?>
						<option value="<?= $row_banco['banco_id'] ?>"><?= $row_banco['banco'] ?></option>
						<?php
							}
						?>
					</select>

				<label style="margin-left:20px" class="control-label">Num. Banco:</label>
					<input name="num_banco" class="input-small" type="text">

<br>
				<label style="margin:15px 0px 0px 67px" class="control-label">Agência:</label>
					<input required name="agencia" class="input-small" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Digito:</label>
					<input name="ag_dig" class="input-mini" type="text">
<br>
				<label style="margin:15px 0px 0px 20px" class="control-label">Conta Corrente:</label>
					<input required name="cc" class="input-small" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Digito CC:</label>
					<input name="dig_cc" class="input-mini" type="text">
<br>
				<label style="margin:15px 0px 0px 67px" class="control-label">Carteira:</label>
					<input name="carteira" class="input-small" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Convênio:</label>
					<input name="convenio" class="input-small" type="text">

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