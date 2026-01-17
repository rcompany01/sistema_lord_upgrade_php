<script type="text/javascript">
	function NovoDepartamento(){
	$("#cx-form-departamento").fadeIn('slow');
	document.getElementById('cont-tabela-departamento').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";		
}
</script>

<?php
	require_once('class/departamento.class.php');
	$class = new Departamentos;

	// FAZ O CADASTRO DO DEPARTAMENTO
	if (isset($_POST['departamento'])){
		$dep = $_POST['departamento'];
		$class->NovoDepartamento($dep);
	}
?>

<?php
	// FAZ A CONFIRMAÇÃO DE REMOVER O REGISTRO
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=departamento&deleteDep="+id;
		     }
		}
    </script>

 <?php
 	// DELETA O DEPARTAMENTO
 	if (isset($_GET['deleteDep'])){
 		$class->DeletarDepartamento($_GET['deleteDep']);
 	}
 ?>

<?php
	if (isset($_GET['idDep'])){
		require_once('alt_departamento.php');
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
				<td width="67%">Departamento</td>
				<td width="32%">Ações</td>
			</tr>
<?php
	// LISTA TODOS OS DEPARTAMENTOS
	$class->ListaDepartamento();
	while($row=mysqli_fetch_assoc($class->ListaDepartamento)){
?>
			<tr>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idDep=<?= $row['dep_id'] ?>'"><?= $row['dep_id'] ?></td>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idDep=<?= $row['dep_id'] ?>'"><?= $row['departamento'] ?></td>
				<td>
					<button onClick="confirmacao(<?= $row['dep_id'] ?>)" class="btn btn-danger">Excluir</button>
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
				<legend>Dados Departamento</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">Departamento:</label>
					<input name="departamento" class="input-medium" type="text">

				<div class="form-actions">
					<button onClick="window.location.href='?funcao=departamento'" style='float:right' type="button" class="btn">Cancelar</button>
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
				</div>
			</fieldset>
		</form>
	</div>

</div>
<?php
	}
?>