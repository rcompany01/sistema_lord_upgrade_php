<?php	
	// BUSCA O DEPARTAMENTO DE ACORDO COM O ID SETADO
	if (isset($_GET['idDep'])){
		$class->ListaDepartamentoID($_GET['idDep']);
		$row=mysqli_fetch_assoc($class->ListaDepartamentoID);
	}


	// FAZ A ALTERACAO DOS DADOS
	if (isset($_POST['alt_departamento'])){
		$dep=$_POST['alt_departamento'];
		$id = $_GET['idDep'];
		$class->AlterarDepartamento($dep, $id);
	}
?>



<div id="cx-alt-departamento" class="cx-alt-departamento">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados Departamento</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">Departamento:</label>
					<input value="<?= $row['departamento'] ?>" name="alt_departamento" class="input-medium" type="text">

				<div class="form-actions">
					<button onClick="window.location.href='?funcao=departamento'" style='float:right' type="button" class="btn">Cancelar</button>
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Alterar</button>			  
				</div>
			</fieldset>
		</form>
	</div>