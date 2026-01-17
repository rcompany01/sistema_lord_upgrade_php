<?php	
	// BUSCA A FUNCAO DE ACORDO COM O ID SETADO
	if (isset($_GET['idFunc'])){
		$class->ListaFuncoesID($_GET['idFunc']);
		$row=mysqli_fetch_assoc($class->ListaFuncoesID);
	}


	// FAZ A ALTERACAO DOS DADOS
	if (isset($_POST['att_funcao'])){
		$func=$_POST['att_funcao'];
		$horas = $_POST['att_horas'];
		$id = $_GET['idFunc'];
		$class->AlterarFuncao($func, $horas, $id);
	}
?>


<div id="formulario-att-funcao" class="formulario-att-funcao">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Nova Função</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">Função:</label>
					<input value="<?= $row['funcao'] ?>" name="att_funcao" class="input-xlarge" type="text"> <br>

				<label style="margin:20px 0px 0px 7px" class="control-label">Horas da Função:</label>
					<input value="<?= $row['horas_func'] ?>" name="att_horas" class="input-mini" type="text">

				<div class="form-actions">
					<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
				</div>
			</fieldset>
		</form>
	</div>