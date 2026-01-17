<?php
class Bancos
{

	function NovoBanco($banco)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM bancos WHERE banco=?");
		$stmt->bind_param("s", $banco);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		if ($qtd > 0) {
			?>
			<script type="text/javascript">
				alert('Este Banco já está cadastrado!');
			</script>
			<?php
		} else {
			$stmtInsert = $mysqli->prepare("INSERT INTO bancos (banco) VALUES (?)");
			$stmtInsert->bind_param("s", $banco);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Banco cadastrado!');
					window.location.href = "?funcao=bancos";
				</script>
				<?php
			}
			$stmtInsert->close();
		}
	}


	public $ListaBanco;
	function ListaBanco()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaBanco = $mysqli->query("SELECT * FROM bancos ORDER BY banco ASC");
	}



	public $ListaBancoID;
	function ListaBancoID($idBanco)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM bancos WHERE banco_id=?");
		$stmt->bind_param("i", $idBanco);
		$stmt->execute();
		$this->ListaBancoID = $stmt->get_result();
		$stmt->close();
	}


	function AlterarBanco($banco, $id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE bancos SET banco=? WHERE banco_id=?");
		$stmt->bind_param("si", $banco, $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Banco Atualizado!');
			window.location.href = "?funcao=bancos";
		</script>
		<?php
	}


	function DeletarBanco($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM bancos WHERE banco_id=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Registro Deletado!');
			window.location.href = "?funcao=bancos";
		</script>
		<?php
	}

}