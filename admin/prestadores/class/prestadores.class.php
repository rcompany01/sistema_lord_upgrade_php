<?php
class Prestadores
{

	function NovoPrestador(
		$nome,
		$rg,
		$orgao,
		$expedicao,
		$cpf,
		$nacionalidade,
		$nascimento,
		$sexo,
		$estadoCiv,
		$escolaridade,
		$mae,
		$pai,
		$cep,
		$logradouro,
		$numero,
		$compl,
		$bairro,
		$cidade,
		$uf,
		$tel,
		$cel,
		$email,
		$foto,
		$status,
		$inss,
		$pis,
		$ccm,
		$titulo,
		$zona,
		$indicado
	) {

		// Path seems hardcoded for localhost/dev environment, might want to check this eventually if deployment environment changes
		// Correcao Path Upload
		$caminho = dirname(__FILE__) . '/../fotos/' . $_FILES['foto']['name'];
		$arquivo_tmp = $_FILES['foto']['tmp_name'];
		move_uploaded_file($arquivo_tmp, $caminho);

		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT rg_prest, cpf_prest FROM prestadores WHERE rg_prest=? AND cpf_prest=?");
		$stmt->bind_param("ss", $rg, $cpf);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		// If qtd is 0, it means no duplicate found (logic in original code was: if ($qtd==$qtd) which is always true? 
		// Wait, the original code had `if ($qtd==$qtd)`. That is a bug in the legacy code, it should have been checking if $qtd == 0.
		// However, looking at the else block `else{ alert('Este Prestador já está cadastrado!'); }`, it implies the IF block is for success (no duplicate).
		// So `if ($qtd==0)` is likely the intended logic, or maybe the dev made a mistake and it always inserted.
		// Given the `else` block exists, I will assume the intention is to check for duplicates.
		// Re-reading original: `if ($qtd==$qtd)` is TAUTOLOGY (always true). This means the check was ineffective.
		// Correction: I should probably fix this logic to actually check for duplicates to make it secure/correct.
		// `if ($qtd == 0)`: insert. `else`: duplicate found.

		if ($qtd == 0) {
			$stmtInsert = $mysqli->prepare("INSERT INTO prestadores (
										nome_prest, rg_prest, orgao_prest, expedicao_prest, cpf_prest, 
										nacionalidade_prest, nascimento_prest, sexo_prest, est_civil, escolaridade,
										mae, pai, cep_prest, logradouro_prest, numero_prest,
										compl_prest, bairro_prest, cidade_prest, uf_prest, tel_prest,
										cel_prest, email_prest, foto_prest, status, inss,
										pis, ccm, titulo, zona, indicado
										)
										VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

			$stmtInsert->bind_param(
				"ssssssssssssssssssssssssssssss",
				$nome,
				$rg,
				$orgao,
				$expedicao,
				$cpf,
				$nacionalidade,
				$nascimento,
				$sexo,
				$estadoCiv,
				$escolaridade,
				$mae,
				$pai,
				$cep,
				$logradouro,
				$numero,
				$compl,
				$bairro,
				$cidade,
				$uf,
				$tel,
				$cel,
				$email,
				$foto,
				$status,
				$inss,
				$pis,
				$ccm,
				$titulo,
				$zona,
				$indicado
			);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Prestador Cadastrado!');
					window.location.href = "?funcao=listaPrestadores";
				</script>
				<?php
			}
			$stmtInsert->close();

		} else {
			?>
			<script type="text/javascript">
				alert('Este Prestador já está cadastrado!');
				// Add redirect or stay to correct UI behavior
			</script>
			<?php
		}
	}


	public $ListaPrestadores;
	function ListaPrestadores()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$inicio = 0;
		$max = 25;

		if (!empty($_GET['pagina'])) {
			$inicio = (int) $_GET['pagina'];
		}
		// $limit=$inicio.",".$max; // Pure string limit

		$order = "";
		if (!empty($_GET['ordem'])) {
			$order = "ORDER BY nome_prest ASC";
		} else {
			$order = "";
		} // Implicit DESC or natural order? Original was empty.

		$query = "SELECT * FROM prestadores $order LIMIT ?, ?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("ii", $inicio, $max);
		$stmt->execute();
		$this->ListaPrestadores = $stmt->get_result();
		$stmt->close();
	}


	public $ListaPrestadoresTotal;
	function ListaPrestadoresTotal()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$order = "";
		if (!empty($_GET['ordem'])) {
			$order = "ORDER BY nome_prest ASC";
		} else {
			$order = "";
		}
		$this->ListaPrestadoresTotal = $mysqli->query("SELECT * FROM prestadores $order");
	}


	function TotalPrestadores()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$result = $mysqli->query("SELECT COUNT(*) as total FROM prestadores");
		$row = $result->fetch_assoc();
		return $row['total'];
	}


	function UltimaEscala($prest)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT MAX(data_evento) AS ultima FROM valores_pagos_prestadores WHERE prestador=?");
		$stmt->bind_param("i", $prest); // Assuming prestador ID is int
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$last = $vt['ultima'];
		$stmt->close();

		$empty = "00/00/0000";

		if ($last != "") {
			$dt = explode('-', $last);
			$vetor_data = $dt[2] . "/" . $dt[1] . "/" . $dt[0];
			return $vetor_data;
		} else {
			return $empty;
		}
	}

	public $ListaPrestadoresAlf;
	function ListaPrestadoresAlf()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaPrestadoresAlf = $mysqli->query("SELECT * FROM prestadores ORDER BY nome_prest ASC");
	}



	public $ListaPrestadoresID;
	function ListaPrestadoresID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM prestadores WHERE id_prest=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->ListaPrestadoresID = $stmt->get_result();
		$stmt->close();
	}

	public $ListaPrestadoresNome;
	function ListaPrestadoresNome($nome)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$searchTerm = "%" . $nome . "%";
		$stmt = $mysqli->prepare("SELECT * FROM prestadores WHERE nome_prest LIKE ?");
		$stmt->bind_param("s", $searchTerm);
		$stmt->execute();
		$this->ListaPrestadoresNome = $stmt->get_result();
		$stmt->close();
	}

	public $ListaPrestadoresStatus;
	function ListaPrestadoresStatus($st)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$order = "";
		if (!empty($_GET['ordem'])) {
			$order = "ORDER BY nome_prest ASC";
		} else {
			$order = "";
		}

		// Status is likely char or string ('1', 'n')
		$query = "SELECT * FROM prestadores WHERE status=? $order";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $st);
		$stmt->execute();
		$this->ListaPrestadoresStatus = $stmt->get_result();
		$stmt->close();
	}


	function AttPrestador(
		$id,
		$nome,
		$rg,
		$orgao,
		$expedicao,
		$cpf,
		$nacionalidade,
		$nascimento,
		$sexo,
		$estadoCiv,
		$escolaridade,
		$mae,
		$pai,
		$cep,
		$logradouro,
		$numero,
		$compl,
		$bairro,
		$cidade,
		$uf,
		$tel,
		$cel,
		$email,
		$foto,
		$inss,
		$pis,
		$ccm,
		$titulo,
		$zona,
		$indicado
	) {

		// $caminho = 'C:/wamp/www/lorde/admin/prestadores/fotos/'.$_FILES['att_foto']['name'];
		// Warning: path specific to environment. Keeping it as is but note it might break if folder structure changed.
		// Safe practice: verify destination dir exists or use relative path if possible, but keeping original logic.
		if (isset($_FILES['att_foto']['name']) && $_FILES['att_foto']['name'] != "") {
			// Correção de Path para funcionar em qualquer servidor (Linux/Windows)
			$caminho = dirname(__FILE__) . '/../fotos/' . $_FILES['att_foto']['name'];
			$arquivo_tmp = $_FILES['att_foto']['tmp_name'];
			move_uploaded_file($arquivo_tmp, $caminho);
		}
		// Logic note: if no new photo upload, $foto parameter should contain old photo name preferably (usually handled in form hidden input).

		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE prestadores SET
                                    nome_prest=?, rg_prest=?, orgao_prest=?, expedicao_prest=?, cpf_prest=?, 
                                    nacionalidade_prest=?, sexo_prest=?, est_civil=?, escolaridade=?, mae=?,
                                    pai=?, cep_prest=?, logradouro_prest=?, numero_prest=?, compl_prest=?,
                                    bairro_prest=?, cidade_prest=?, uf_prest=?, tel_prest=?, cel_prest=?,
                                    email_prest=?, foto_prest=?, inss=?, pis=?, ccm=?,
                                    titulo=?, zona=?, indicado=?
                                    WHERE id_prest=?");

		$stmt->bind_param(
			"ssssssssssssssssssssssssssssi",
			$nome,
			$rg,
			$orgao,
			$expedicao,
			$cpf,
			$nacionalidade,
			$sexo,
			$estadoCiv,
			$escolaridade,
			$mae,
			$pai,
			$cep,
			$logradouro,
			$numero,
			$compl,
			$bairro,
			$cidade,
			$uf,
			$tel,
			$cel,
			$email,
			$foto,
			$inss,
			$pis,
			$ccm,
			$titulo,
			$zona,
			$indicado,
			$id
		);

		if ($stmt->execute()) {
			?>
			<script type="text/javascript">
				alert('Prestador Atualizado!');
				window.location.href = "?funcao=listaPrestadores";
			</script>
			<?php
		}
		$stmt->close();

	}


	function DesativarPrestador($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE prestadores SET status='n' WHERE id_prest=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Prestador Desativado!');
			window.location.href = "?funcao=listaPrestadores";
		</script>
		<?php
	}



	function AtivarPrestador($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE prestadores SET status='1' WHERE id_prest=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			alert('Prestador Ativo!');
			window.location.href = "?funcao=listaPrestadores";
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

	function FormataData($data)
	{
		$vt = explode('-', $data);
		$dia = $vt[2] . "/" . $vt[1] . "/" . $vt[0];
		return $dia;
	}

	function Ativos()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$result = $mysqli->query("SELECT COUNT(*) as total FROM prestadores WHERE status='1'");
		$row = $result->fetch_assoc();
		return $row['total'];
	}

	function Inativos()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$result = $mysqli->query("SELECT COUNT(*) as total FROM prestadores WHERE status='n'");
		$row = $result->fetch_assoc();
		return $row['total'];
	}

}