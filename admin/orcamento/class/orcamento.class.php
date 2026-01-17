<?php
class Orcamento
{


	function NovoOrcamento(
		$solicitante,
		$cep,
		$rua,
		$num,
		$compl,
		$bairro,
		$cidade,
		$uf,
		$evento,
		$data_sol,
		$data_event,
		$publico,
		$responsavel,
		$voucher,
		$id_escala
	) {
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		// CADASTRA OS DADOS PRIMARIOS DO ORÇAMENTO
		$stmtInsert = $mysqli->prepare("INSERT INTO orcamento (solicitante_orc, cep_orc, rua_orc, num_orc, compl_orc, 
													bairro_orc, cidade_orc, uf_orc, evento_orc, data_sol,
													data_evento, publico, responsavel, voucher, id_escala)
													VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmtInsert->bind_param(
			"ssssssssssssssi",
			$solicitante,
			$cep,
			$rua,
			$num,
			$compl,
			$bairro,
			$cidade,
			$uf,
			$evento,
			$data_sol,
			$data_event,
			$publico,
			$responsavel,
			$voucher,
			$id_escala
		);

		if ($stmtInsert->execute()) {
			// BUSCA O ULTIMO REGISTRO DO BANCO, E SELECIONA O ID
			$id = $stmtInsert->insert_id;

			// REDIRECIONA PARA A PAGINA QUE INSERE OS VALORES DO ORCAMENTO
			?>
			<input type="hidden" id="id_orc" value="<?= $id ?>">
			<script type="text/javascript">
				alert('Dados do Orçamento Registrado!');
				var id = document.getElementById('id_orc').value;
				window.location.href = "?funcao=orcamento";
			</script>
			<?php
		}
		$stmtInsert->close();
	}



	public $BuscaOrcamento;
	function BuscaOrcamento()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->BuscaOrcamento = $mysqli->query("SELECT * FROM orcamento ORDER BY id_orc ASC");
	}

	public $BuscaOrcamentoID;
	function BuscaOrcamentoID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM orcamento WHERE id_orc=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->BuscaOrcamentoID = $stmt->get_result();
		$stmt->close();
	}


	function FormataData($data)
	{
		$vt = explode('-', $data);
		$dia = $vt[2] . "/" . $vt[1] . "/" . $vt[0];
		return $dia;
	}



	function AdicionaValores($qtd, $funcao_val, $valor)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$id_orc = $_GET['AdicionarValor'];

		$stmtCheck = $mysqli->prepare("SELECT funcao_val, qtd_val, valor FROM valores_orcamento WHERE funcao_val=? AND id_orcamento=?");
		$stmtCheck->bind_param("ii", $funcao_val, $id_orc);
		$stmtCheck->execute();
		$resultCheck = $stmtCheck->get_result();
		$qtd_reg = $resultCheck->num_rows;

		// CASO NAO TENHA NENHUM REGISTRO, FAZ UM NOVO INSERT
		if ($qtd_reg == 0) {
			$stmtCheck->close();
			$stmtInsert = $mysqli->prepare("INSERT INTO valores_orcamento (qtd_val, funcao_val, valor, id_orcamento) VALUES (?, ?, ?, ?)");
			$stmtInsert->bind_param("didi", $qtd, $funcao_val, $valor, $id_orc);
			$stmtInsert->execute();
			$stmtInsert->close();

			// CASO TENHA REGISTRO, SOMAMOS O VALOR DA TABELA COM O QUE ESTA SENDO ENVIADO
		} else {
			$vt = $resultCheck->fetch_assoc();
			$stmtCheck->close();

			// VALORES DA TABELA
			$quantidade = $vt['qtd_val'];
			$val = $vt['valor'];
			// ATUALIZA OS VALORES
			$novaQtd = $quantidade + $qtd;
			$novoValor = $val + $valor;

			$stmtUp = $mysqli->prepare("UPDATE valores_orcamento SET qtd_val=?, valor=? WHERE funcao_val=? AND id_orcamento=?");
			$stmtUp->bind_param("ddii", $novaQtd, $novoValor, $funcao_val, $id_orc);
			$stmtUp->execute();
			$stmtUp->close();
		}

	}


	public $BuscaValoresOrcamento;
	function BuscaValoresOrcamento($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM valores_orcamento WHERE id_orcamento=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->BuscaValoresOrcamento = $stmt->get_result();
		$stmt->close();
	}

	public $ValorTotalOrcamento;
	function ValorTotalOrcamento()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ValorTotalOrcamento = $mysqli->query("SELECT SUM(valor) AS total FROM valores_orcamento");
	}


	public $ValorTotalOrcamentoID;
	function ValorTotalOrcamentoID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT SUM(valor) AS total FROM valores_orcamento WHERE id_orcamento=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->ValorTotalOrcamentoID = $stmt->get_result();
		$stmt->close();
	}



	public $TotalPrestadoresEvento;
	function TotalPrestadoresEvento($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT SUM(qtd_val) AS qtd_prest FROM valores_orcamento WHERE id_orcamento=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->TotalPrestadoresEvento = $stmt->get_result();
		$stmt->close();
	}



	function ExcluirValor($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM valores_orcamento WHERE id_val=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();
	}


	function AtualizaOrcamento(
		$solicitante,
		$cep,
		$rua,
		$num,
		$compl,
		$bairro,
		$cidade,
		$uf,
		$evento,
		$data_sol,
		$data_event,
		$publico,
		$responsavel,
		$voucher,
		$id_escala,
		$id
	) {
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE orcamento SET solicitante_orc=?, cep_orc=?, rua_orc=?, num_orc=?, compl_orc=?,
                                                        bairro_orc=?, cidade_orc=?, uf_orc=?, evento_orc=?, data_sol=?,
                                                        data_evento=?, publico=?, responsavel=?, voucher=?, id_escala=?
                                                        WHERE id_orc=?");
		$stmt->bind_param(
			"ssssssssssssssii",
			$solicitante,
			$cep,
			$rua,
			$num,
			$compl,
			$bairro,
			$cidade,
			$uf,
			$evento,
			$data_sol,
			$data_event,
			$publico,
			$responsavel,
			$voucher,
			$id_escala,
			$id
		);
		$stmt->execute();
		$stmt->close();

	}


	function ExcluirOrcamento($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt1 = $mysqli->prepare("DELETE FROM orcamento WHERE id_orc=?");
		$stmt1->bind_param("i", $id);
		$stmt1->execute();
		$stmt1->close();

		$stmt2 = $mysqli->prepare("DELETE FROM valores_orcamento WHERE id_orcamento=?");
		$stmt2->bind_param("i", $id);
		$stmt2->execute();
		$stmt2->close();

		?>
		<script type="text/javascript">
			window.location.href = "?funcao=orcamento";
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

	public $RelacaoEscalaPrest;
	function RelacaoEscalaPrest($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT ep.id_prestador, 
                            ep.entrada, 
                            ep.saida,
                            ep.id_funcao,
                            fc.vl_faturamento
                            FROM escalas AS esc
                            INNER JOIN funcoes_clientes AS fc
                            ON esc.id_cliente = fc.id_cliente
                            INNER JOIN escala_prestadores AS ep
                            ON fc.funcao = ep.id_funcao AND esc.id_esc = ep.id_escala
                            INNER JOIN orcamento AS orc
                            ON ep.id_escala = orc.id_escala
                            WHERE orc.id_escala=?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->RelacaoEscalaPrest = $stmt->get_result();
		$stmt->close();
	}


	public $RelacaoEscalaPrestTotal;
	function RelacaoEscalaPrestTotal($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT ep.id_prestador, 
                            ep.entrada, 
                            ep.saida,
                            ep.id_funcao,
                            fc.vl_faturamento
                            FROM escalas AS esc
                            INNER JOIN funcoes_clientes AS fc
                            ON esc.id_cliente = fc.id_cliente
                            INNER JOIN escala_prestadores AS ep
                            ON fc.funcao = ep.id_funcao AND esc.id_esc = ep.id_escala
                            INNER JOIN orcamento AS orc
                            ON ep.id_escala = orc.id_escala
                            WHERE orc.id_escala=?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->RelacaoEscalaPrestTotal = $stmt->get_result();

		// For queries that just select, we can use num_rows on the result object
		$qtd = $this->RelacaoEscalaPrestTotal->num_rows;
		$stmt->close();
		return $qtd;
	}

}