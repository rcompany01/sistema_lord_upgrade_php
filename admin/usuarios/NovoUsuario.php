<?php
	require_once("public_html/sistema_lord/empresa/class/departamento.class.php");
	$class = new Departamentos;

	// BUSCA OS DEPARTAMENTOS
		$class->ListaDepartamento();

	// INSERE OS DADOS OD USUÁRIO
		require_once("empresa/class/funcionarios.class.php");
		$us = new Funcionarios;

		if (isset($_POST['nome'])){

			if ($_POST['pass']==$_POST['re-pass']){

				$nome=addslashes($_POST['nome']);
				$sobre=addslashes($_POST['sobre']);
				$user=addslashes($_POST['usuario']);
				$funcao=addslashes($_POST['pass']);
				$pass=addslashes($_POST['cargo']);
				$repass=addslashes($_POST['re-pass']);
				$us->NovoUsuario($nome,$sobre,$user,$funcao,$pass,$repass);
			}else{
				?>
					<script type="text/javascript">
						alert('As Senhas Não Combinam!');
					</script>
				<?php
			}
		}
?>
<div class="cx-novo-usuario">
	<form method="post" class="form-inline">
		<fieldset>
			<legend>Novo Usuário</legend>

			<label style="margin-left:31px" class="nome-campos">Nome:</label>
				<input required name="nome" type="text" class="input-medium"> 

			<label class="nome-campos">Sobrenome:</label>
				<input required name="sobre" type="text" class="input-medium"> <br>

			<label class="nome-campos">Usuário:</label>
				<input required name="usuario" type="text" class="input-medium"> <br>

			<label style="margin-left:33px" class="nome-campos">Cargo:</label>
				<select required name="cargo">
					<option value="">Selecione o Cargo.</option>
					<?php
						while($row=mysqli_fetch_assoc($class->ListaDepartamento)){
					?>
						<option value="<?= $row['departamento'] ?>"><?= $row['departamento'] ?></option>
					<?php
						}
					?>
				</select>

				<br>

			<label style="margin-left:31px" class="nome-campos">Senha:</label>
				<input required name="pass" type="password" class="input-medium">

			<label class="nome-campos">Repita a Senha:</label>
				<input required name="re-pass" type="password" class="input-medium">

			<div class="form-actions">
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Cadastrar</button>			  
			</div>
		</fieldset>
	</form>
</div>