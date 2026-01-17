<?php
class Setores
{

	function NovoSetor($set)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM setores WHERE setor=?");
		$stmt->bind_param("s", $set);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		if ($qtd > 0) {
			?>
			<script type="text/javascript">
				alert('Este Setor já está cadastrado!');
			</script>
			<?php
		} else {
			$stmtInsert = $mysqli->prepare("INSERT INTO setores (setor) VALUES (?)");
			$stmtInsert->bind_param("s", $set);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Setor cadastrado!');
					window.location.href = "?funcao=listaSetores";
				</script>
				<?php
			}
			$stmtInsert->close();
		}
	}


	public $ListaSetores;
	function ListaSetores()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaSetores = $mysqli->query("SELECT * FROM setores");
	}



	public $ListaSetoresID;
	function ListaSetoresID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM setores WHERE id_setor=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->ListaSetoresID = $stmt->get_result();
		$stmt->close();
	}


	function AlterarSetor($set, $id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE setores SET setor=? WHERE id_setor=?");
		$stmt->bind_param("si", $set, $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Setor Atualizado!');
			window.location.href = "?funcao=listaSetores";
		</script>
		<?php
	}


	function DeletarSetor($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM setores WHERE id_setor=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Registro Deletado!');
			window.location.href = "?funcao=listaSetores";
		</script>
		<?php
	}



	function NomeSetor($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT setor FROM setores WHERE id_setor=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$setor = $vt['setor'];
		$stmt->close();
		return $setor;
	}



}