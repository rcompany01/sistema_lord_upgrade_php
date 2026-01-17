	<script type="text/javascript">
	function NovoDepartamento(){
	$("#cx-form-departamento").fadeIn('slow');
	document.getElementById('cont-tabela-departamento').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";		
}
</script>

<?php
	require_once('class/banco.class.php');
	$class = new Bancos;

	// FAZ O CADASTRO DO BANCO
	if (isset($_POST['banco'])){
		$banco = $_POST['banco'];
		$class->NovoBanco($banco);
	}
?>


<?php
	// FAZ A CONFIRMAÇÃO DE REMOVER O REGISTRO
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=bancos&deleteBanco="+id;
		     }
		}
    </script>

 <?php
 	// DELETA O DEPARTAMENTO
 	if (isset($_GET['deleteBanco'])){
 		$class->DeletarBanco($_GET['deleteBanco']);
 	}
 ?>

<?php
	if (isset($_GET['idBanco'])){
		require_once('alt_banco.php');
	}else{
?>

<div class="cx-empresa-departamento">


	<div id="cx-funcoes" class="cx-funcoes"></a>
		<img style="cursor:pointer;margin-left:34px" onClick="NovoDepartamento()" src="../img/bt_novo1.png" alt="">
	</div>

	<div id="cont-tabela-departamento" class="cont-tabela">
		<table class="table table-striped">
			<tr style="font-weight:bold">
				<td width="5%">#ID</td>
				<td width="67%">Banco</td>
				<td width="32%">Ações</td>
			</tr>
<?php
	// LISTA TODOS OS BANCOS
	$class->ListaBanco();
	while($row=mysqli_fetch_assoc($class->ListaBanco)){
?>
			<tr>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idBanco=<?= $row['banco_id'] ?>'"><?= $row['banco_id'] ?></td>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idBanco=<?= $row['banco_id'] ?>'"><?= $row['banco'] ?></td>
				<td>
					<button onClick="confirmacao(<?= $row['banco_id'] ?>)" class="btn btn-danger">Excluir</button>
				</td>
			</tr>
<?php
	}
?>

		</table>
	</div>


	<div id="cx-form-departamento" class="cx-form-departamento">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">Banco:</label>
					<input name="banco" class="input-medium" type="text">

				<div class="form-actions">
					<button onClick="window.location.href='?funcao=bancos'" style='float:right' type="button" class="btn">Cancelar</button>
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
				</div>
			</fieldset>
		</form>
	</div>

<?php
	}
?>
</div>