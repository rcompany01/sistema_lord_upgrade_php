<?php

class Autenticacao
{


	function autentica($user, $senha)
	{
		// FAZ A CONEXAO COM O BANCO USANDO A CLASSE CENTRALIZADA
		require_once(dirname(__FILE__) . '/../DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		// PREPARED STATEMENT PARA EVITAR SQL INJECTION
		$stmt = $mysqli->prepare("SELECT nome, cargo FROM usuarios WHERE usuario = ? AND senha = ? AND status = '1'");

		if ($stmt) {
			$stmt->bind_param("ss", $user, $senha);
			$stmt->execute();
			$result = $stmt->get_result();
			$qtd = $result->num_rows;

			// VERIFICA SE FOI ENCONTRADO ALGUM REGISTRO
			if ($qtd === 1) {
				$vt = $result->fetch_assoc();

				$_SESSION['autenticado'] = 'ok';
				$_SESSION['nome'] = $vt['nome'];
				$_SESSION['cargo'] = $vt['cargo'];

				// Redirecionamento seguro
				header("Location: ../../admin/");
				exit;
			} else {
				// CASO NAO ENCONTRE O USUÁRIO OU SENHA NA BASE
				?>
				<script type="text/javascript">
					alert('Usuário/Senha Inválidos');
					window.location.href = "index.php";
				</script>
				<?php
			}
			$stmt->close();
		} else {
			// Erro na preparação da query
			die("Erro interno no sistema: falha ao preparar consulta de login.");
		}
	}

}
