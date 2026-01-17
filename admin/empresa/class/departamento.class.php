<?php
class Departamentos
{

	function NovoDepartamento($dep)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM departamentos WHERE departamento=?");
		$stmt->bind_param("s", $dep);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		if ($qtd > 0) {
			?>
			<script type="text/javascript">
				alert('Este departamento já está cadastrado!');
			</script>
			<?php
		} else {
			$stmtInsert = $mysqli->prepare("INSERT INTO departamentos (departamento) VALUES (?)");
			$stmtInsert->bind_param("s", $dep);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Departamento cadastrado!');
					window.location.href = "?funcao=departamento";
				</script>
				<?php
			}
			$stmtInsert->close();
		}
	}


	public $ListaDepartamento;
	function ListaDepartamento()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaDepartamento = $mysqli->query("SELECT * FROM departamentos ORDER BY departamento ASC");
	}



	public $ListaDepartamentoID;
	function ListaDepartamentoID($dep)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM departamentos WHERE dep_id=?");
		$stmt->bind_param("i", $dep);
		$stmt->execute();
		$this->ListaDepartamentoID = $stmt->get_result();
		$stmt->close();
	}


	function AlterarDepartamento($dep, $id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE departamentos SET departamento=? WHERE dep_id=?");
		$stmt->bind_param("si", $dep, $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Departamento Atualizado!');
			window.location.href = "?funcao=departamento";
		</script>
		<?php
	}


	function DeletarDepartamento($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM departamentos WHERE dep_id=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Registro Deletado!');
			window.location.href = "?funcao=departamento";
		</script>
		<?php
	}

}