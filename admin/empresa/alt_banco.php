<?php	
	// BUSCA O DEPARTAMENTO DE ACORDO COM O ID SETADO
	if (isset($_GET['idBanco'])){
		$class->ListaBancoID($_GET['idBanco']);
		$row=mysqli_fetch_assoc($class->ListaBancoID);
	}


	// FAZ A ALTERACAO DOS DADOS
	if (isset($_POST['alt_banco'])){
		$banco=$_POST['alt_banco'];
		$id = $_GET['idBanco'];
		$class->AlterarBanco($banco, $id);
	}
?>



<div id="cx-alt-banco" class="cx-alt-banco">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">Banco:</label>
					<input value="<?= $row['banco'] ?>" name="alt_banco" class="input-medium" type="text">

				<div class="form-actions">
					<button onClick="window.location.href='?funcao=bancos'" style='float:right' type="button" class="btn">Cancelar</button>
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
				</div>
			</fieldset>
		</form>
	</div>