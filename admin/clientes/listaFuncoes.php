<script type="text/javascript">
	function NovoDepartamento(){
	$("#formulario-nova-funcao").fadeIn('slow');
	document.getElementById('cont-tabela-departamento').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";		
}
</script>

<?php
	require_once('class/funcoes.class.php');
	$class = new Funcoes;

	// FAZ O CADASTRO DA FUNCAO
	if (isset($_POST['funcao'])){
		$func = $_POST['funcao'];
		$hora = $_POST['horas'];
		$class->NovaFuncao($func, $hora);
	}
?>

<?php
	// FAZ A CONFIRMAÇÃO DE REMOVER O REGISTRO
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=listaFuncoes&deleteFunc="+id;
		     }
		}
    </script>

 <?php
 	// DELETA O DEPARTAMENTO
 	if (isset($_GET['deleteFunc'])){
 		$class->DeletarFuncao($_GET['deleteFunc']);
 	}
 ?>

<?php
	if (isset($_GET['idFunc'])){
		require_once('alt_funcoes.php');
	}else{
?>


<div class="cx-lista-funcoes">


	<div id="cx-funcoes" class="cx-funcoes"></a>
		<img style="cursor:pointer;margin-left:34px" onClick="NovoDepartamento()" src="../img/bt_novo1.png" alt="">
	</div>

	<div id="cont-tabela-departamento" class="cont-tabela">
		<table class="table table-striped">
			<tr style="font-weight:bold">
				<td width="5%">#ID</td>
				<td width="48%">Função</td>
				<td width="15%">Horas</td>
				<td width="32%">Ações</td>
			</tr>
<?php
	// LISTA TODOS AS FUNCOES
	$class->ListaFuncoes();
	while($row=mysqli_fetch_assoc($class->ListaFuncoes)){
?>
			<tr>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idFunc=<?= $row['id_func'] ?>'"><?= $row['id_func'] ?></td>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idFunc=<?= $row['id_func'] ?>'"><?= $row['funcao'] ?></td>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idFunc=<?= $row['id_func'] ?>'"><?= $row['horas_func']." Hr(s)" ?></td>
				<td>

					<button onClick="confirmacao(<?= $row['id_func'] ?>)" class="btn btn-danger">Excluir</button>
				</td>
			</tr>
<?php
	}
?>

		</table>
	</div>



	<div id="formulario-nova-funcao" class="formulario-nova-funcao">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Nova Função</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">Função:</label>
					<input name="funcao" class="input-xlarge" type="text"> <br>


				<label style="margin:20px 0px 0px 7px" class="control-label">Horas da Função:</label>
					<input name="horas" class="input-mini" type="text">

				<div class="form-actions">
					<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
				</div>
			</fieldset>
		</form>
	</div>
</div>

<?php
	}
?>