<?php
class Funcionarios
{

	function NovoFuncionario($user, $pass, $cargo)
	{
		// Note: Password seems to be stored in plain text or handled elsewhere. Ensure hashing is implemented if this is a real auth system.
		// Following existing pattern but enhancing security with prepared statements.
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		// VERIFICA SE TEM ALGUM USUARIO REPETIDO ANTES DE INSERIR
		$stmt = $mysqli->prepare("SELECT usuario FROM usuarios WHERE usuario=?");
		$stmt->bind_param("s", $user);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		if ($qtd == 0) {
			$stmtInsert = $mysqli->prepare("INSERT INTO usuarios (usuario, senha, cargo, status) VALUES(?, ?, ?)");
			// Assuming status logic or adding default if needed, original query was INSERT INTO usuarios (usuario, senha, cargo, status) but missing status value in VALUES list? 
			// Original: VALUES('$user', '$pass', '$cargo')") . This implies status was missing or auto-defaulted or incorrect SQL.
			// Wait, original SQL: INSERT INTO usuarios (usuario, senha, cargo, status) VALUES('$user', '$pass', '$cargo') -> This is 4 columns and 3 values. This would fail in strict mode.
			// However, I see another method `NovoUsuario` below that sets status='1'.
			// I will assume status should be '1' here too or it's a bug. Let's look at `NovoUsuario`.
			// `NovoUsuario` inserts 6 columns: nome, sobrenome, usuario, senha, cargo, status.
			// `NovoFuncionario` inserts 4 columns.
			// I will add a default status of '1' to match `NovoUsuario` pattern or just fix usage.

			// Correction: The original code for NovoFuncionario:
			// INSERT INTO usuarios (usuario, senha, cargo, status) VALUES('$user', '$pass', '$cargo')
			// It is indeed mismatch. I will fix it by adding '1' as status, assuming active.

			$status = '1';
			$stmtInsert = $mysqli->prepare("INSERT INTO usuarios (usuario, senha, cargo, status) VALUES(?, ?, ?, ?)");
			$stmtInsert->bind_param("ssss", $user, $pass, $cargo, $status);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Usuário Inserido!');
					window.location.href = "?funcao=funcionarios";
				</script>
				<?php
			}
			$stmtInsert->close();
		} else {
			?>
			<script type="text/javascript">
				alert('Este usuário já está cadastrado!');
				window.location.href = "?funcao=NovoUsuario";
			</script>
			<?php
		}
	}



	public $BuscaUsuario;
	function BuscaUsuario()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->BuscaUsuario = $mysqli->query("SELECT nome, sobrenome, cargo FROM usuarios");
	}



	function NovoUsuario($nome, $sobre, $user, $senha, $cargo)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario=?");
		$stmt->bind_param("s", $user);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		if ($qtd == 0) {
			// Suggestion: Use password_hash() for $senha here. Keeping as is for now to minimize logic change impact unless requested.
			$stmtInsert = $mysqli->prepare("INSERT INTO usuarios (nome, sobrenome, usuario, senha, cargo, status) VALUES(?, ?, ?, ?, ?, '1')");
			$stmtInsert->bind_param("sssss", $nome, $sobre, $user, $senha, $cargo);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert("Usuário Cadastrado!");
					window.location.href = "?funcao=NovoUsuario";
				</script>
				<?php
			}
			$stmtInsert->close();
		} else {
			?>
			<script type="text/javascript">
				alert("Usuário já está cadastrado!");
				window.location.href = "?funcao=NovoUsuario";
			</script>
			<?php
		}
	}

}