<script type="text/javascript">
	function NovoDepartamento(){
	$("#formulario-novo-setor").fadeIn('slow');
	document.getElementById('cont-tabela-departamento').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";		
}
</script>

<?php
	require_once('class/setores.class.php');
	$class = new Setores;

	// FAZ O CADASTRO DA FUNCAO
	if (isset($_POST['setor'])){
		$set = $_POST['setor'];
		$class->NovoSetor($set);
	}
?>

<?php
	// FAZ A CONFIRMAÇÃO DE REMOVER O REGISTRO
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=listaSetores&deleteSet="+id;
		     }
		}
    </script>

 <?php
 	// DELETA O DEPARTAMENTO
 	if (isset($_GET['deleteSet'])){
 		$class->DeletarSetor($_GET['deleteSet']);
 	}
 ?>

<?php
	if (isset($_GET['idSet'])){
		require_once('alt_setores.php');
	}else{
?>

<div class="cx-lista-setor">


	<div id="cx-funcoes" class="cx-funcoes"></a>
		<img style="cursor:pointer;margin-left:34px" onClick="NovoDepartamento()" src="../img/bt_novo1.png" alt="">
	</div>

	<div id="cont-tabela-departamento" class="cont-tabela">
		<table class="table table-striped">
			<tr style="font-weight:bold">
				<td width="5%">#ID</td>
				<td width="67%">Setor</td>
				<td width="32%">Ações</td>
			</tr>
<?php
	// LISTA TODOS AS FUNCOES
	$class->ListaSetores();
	while($row=mysqli_fetch_assoc($class->ListaSetores)){
?>
			<tr>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idSet=<?= $row['id_setor'] ?>'"><?= $row['id_setor'] ?></td>
				<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idSet=<?= $row['id_setor'] ?>'"><?= $row['setor'] ?></td>
				<td>

					<button onClick="confirmacao(<?= $row['id_setor'] ?>)" class="btn btn-danger">Excluir</button>
				</td>
			</tr>
<?php
	}
?>

		</table>
	</div>



	<div id="formulario-novo-setor" class="formulario-novo-setor">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Novo Setor</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">Setor:</label>
					<input name="setor" class="input-medium" type="text">

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