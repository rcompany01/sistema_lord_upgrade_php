<?php
class Funcoes
{

	function NovaFuncao($func, $hora)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM funcoes WHERE funcao=?");
		$stmt->bind_param("s", $func);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		if ($qtd > 0) {
			?>
			<script type="text/javascript">
				alert('Esta Função já está cadastrada!');
			</script>
			<?php
		} else {
			$stmtInsert = $mysqli->prepare("INSERT INTO funcoes (funcao, horas_func) VALUES (?, ?)");
			$stmtInsert->bind_param("ss", $func, $hora);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Função cadastrada!');
					window.location.href = "?funcao=listaFuncoes";
				</script>
				<?php
			}
			$stmtInsert->close();
		}
	}


	public $ListaFuncoes;
	function ListaFuncoes()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaFuncoes = $mysqli->query("SELECT * FROM funcoes ORDER BY funcao ASC");
	}



	public $ListaFuncoesID;
	function ListaFuncoesID($func)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM funcoes WHERE id_func=?");
		$stmt->bind_param("i", $func);
		$stmt->execute();
		$this->ListaFuncoesID = $stmt->get_result();
		$stmt->close();
	}


	function AlterarFuncao($func, $horas, $id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE funcoes SET funcao=?, horas_func=? WHERE id_func=?");
		$stmt->bind_param("ssi", $func, $horas, $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Função Atualizada!');
			window.location.href = "?funcao=listaFuncoes";
		</script>
		<?php
	}


	function DeletarFuncao($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM funcoes WHERE id_func=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Registro Deletado!');
			window.location.href = "?funcao=listaFuncoes";
		</script>
		<?php
	}

	function NomeFuncao($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT funcao FROM funcoes WHERE id_func=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$funcao = $vt['funcao'];
		$stmt->close();
		return $funcao;
	}



}