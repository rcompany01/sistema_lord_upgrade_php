<?php	
	// BUSCA O SETOR DE ACORDO COM O ID SETADO
	if (isset($_GET['idSet'])){
		$class->ListaSetoresID($_GET['idSet']);
		$row=mysqli_fetch_assoc($class->ListaSetoresID);
	}


	// FAZ A ALTERACAO DOS DADOS
	if (isset($_POST['att_setor'])){
		$set=$_POST['att_setor'];
		$id = $_GET['idSet'];
		$class->AlterarSetor($set, $id);
	}
?>


<div id="formulario-att-setor" class="formulario-att-setor">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Novo Setor</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">Setor:</label>
					<input value="<?= $row['setor'] ?>" name="att_setor" class="input-medium" type="text">

				<div class="form-actions">
					<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Atualizar</button>			  
				</div>
			</fieldset>
		</form>
	</div>