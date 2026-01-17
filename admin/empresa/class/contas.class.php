<?php
class Contas
{

	function NovaConta(
		$id_banco,
		$num_banco,
		$agencia,
		$ag_digito,
		$conta,
		$cc_dig,
		$carteira,
		$convenio,
		$prestador
	) {
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM contas WHERE conta=? AND cc_digito=?");
		$stmt->bind_param("ss", $conta, $cc_dig);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		if ($qtd > 0) {
			?>
			<script type="text/javascript">
				alert('Esta Conta já está cadastrada!');
			</script>
			<?php
		} else {
			$stmtInsert = $mysqli->prepare("INSERT INTO contas (id_banco, num_banco, agencia, ag_digito,
                                                            conta, cc_digito, carteira, convenio, id_prestador) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmtInsert->bind_param(
				"isssssssi",
				$id_banco,
				$num_banco,
				$agencia,
				$ag_digito,
				$conta,
				$cc_dig,
				$carteira,
				$convenio,
				$prestador
			);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Conta cadastrada!');
					window.location.href = "?funcao=contas";
				</script>
				<?php
			}
			$stmtInsert->close();
		}
	}


	public $ListaContas;
	function ListaContas()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaContas = $mysqli->query("SELECT * FROM contas");
	}



	public $ListaContasID;
	function ListaContasID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM contas WHERE id_conta=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->ListaContasID = $stmt->get_result();
		$stmt->close();
	}


	function AlterarConta(
		$id_banco,
		$num_banco,
		$agencia,
		$ag_digito,
		$conta,
		$cc_dig,
		$carteira,
		$convenio,
		$prestador,
		$id
	) {
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE contas SET id_banco=?, num_banco=?, agencia=?, ag_digito=?,
                                                    conta=?, cc_digito=?, carteira=?, convenio=?, id_prestador=?
                                                    WHERE id_conta=?");
		$stmt->bind_param(
			"isssssssii",
			$id_banco,
			$num_banco,
			$agencia,
			$ag_digito,
			$conta,
			$cc_dig,
			$carteira,
			$convenio,
			$prestador,
			$id
		);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Conta Atualizada!');
			window.location.href = "?funcao=contas";
		</script>
		<?php
	}


	function DeletarConta($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM contas WHERE id_conta=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();
		?>
		<script type="text/javascript">
			alert('Registro Deletado!');
			window.location.href = "?funcao=contas";
		</script>
		<?php
	}



	function NomePrestador($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT nome_prest FROM prestadores WHERE id_prest=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$nome = $vt['nome_prest'];
		$stmt->close();
		return $nome;
	}


	function NovoCheque($idPrest, $numeracao)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("INSERT INTO cheques (id_prestador, num_cheque) VALUES (?, ?)");
		$stmt->bind_param("is", $idPrest, $numeracao); // Assumed numeracao is string
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert("Forma de pagamento inserida!");
			window.location.href = "?funcao=cheques";
		</script>
		<?php
	}


	public $ListaCheques;
	function ListaCheques()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaCheques = $mysqli->query("SELECT * FROM cheques");
	}


	function DeletarCheque($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM cheques WHERE id_cheque=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Registro Deletado!');
			window.location.href = "?funcao=cheques";
		</script>
		<?php
	}

}