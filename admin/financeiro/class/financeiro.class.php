<?php

class Financeiro
{

	public $BuscaEscalas;
	function BuscaEscalas($de, $ate)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM escalas WHERE data_evento BETWEEN ? AND ?");
		$stmt->bind_param("ss", $de, $ate);
		$stmt->execute();
		$this->BuscaEscalas = $stmt->get_result();
		$stmt->close();
	}


	function FormataData($data)
	{
		$vt = explode('-', $data);
		$dia = $vt[2] . "/" . $vt[1] . "/" . $vt[0];
		return $dia;
	}



	function BuscaNomeCliente($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT cl_nome_fantasia FROM clientes WHERE id_cl=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$cliente = $vt['cl_nome_fantasia'];
		$stmt->close();
		return $cliente;
	}


	function NovoLote($lote, $id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		// Transaction for safety
		$mysqli->begin_transaction();
		try {
			$stmt1 = $mysqli->prepare("UPDATE escalas SET lote=? WHERE id_esc=?");
			$stmt1->bind_param("si", $lote, $id);
			$stmt1->execute();
			$stmt1->close();

			$stmt2 = $mysqli->prepare("UPDATE escalas SET status_baixa='1' WHERE id_esc=?");
			$stmt2->bind_param("i", $id);
			$stmt2->execute();
			$stmt2->close();

			$mysqli->commit();

			echo "<script type='text/javascript'>
                alert('Lote Gerado!');
                document.BuscaEscala.submit();
            </script>";
		} catch (Exception $e) {
			$mysqli->rollback();
			die("Erro ao gerar lote: " . $e->getMessage());
		}
	}


	public $BuscaPrestadoresEvento;
	function BuscaPrestadoresEvento($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM escala_prestadores WHERE id_escala=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->BuscaPrestadoresEvento = $stmt->get_result();
		$stmt->close();
	}


	public $BuscaLote;
	function BuscaLote($lote)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT 
                    ep.*,
                    esc.id_esc,
                    esc.setor,
                    esc.id_cliente,
                    esc.lote,
                    ep.status_pag,
                    esc.status_baixa,
                    fc.vl_faturamento,
                    fc.vl_repasse,
                    func.horas_func
                    FROM escala_prestadores AS ep
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    INNER JOIN funcoes_clientes AS fc
                    ON esc.id_cliente = fc.id_cliente AND ep.id_funcao = fc.funcao
                    INNER JOIN funcoes AS func
                    ON fc.funcao = func.id_func
                    WHERE esc.lote = ?
                    AND esc.status_baixa='1'
                    AND ep.status_pag='0'";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $lote);
		$stmt->execute();
		$this->BuscaLote = $stmt->get_result();
		$stmt->close();
	}


	public $BuscaLoteImpressao;
	function BuscaLoteImpressao($lote)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT 
                    ep.id_escala,
                    ep.id_prestador,
                    ep.data_evento,
                    ep.entrada,
                    ep.saida,
                    ep.extra,
                    ep.id_funcao,
                    esc.id_esc,
                    esc.setor,
                    esc.id_cliente,
                    esc.lote,
                    ep.status_pag,
                    esc.status_baixa,
                    fc.vl_faturamento,
                    fc.vl_repasse,
                    func.horas_func
                    FROM escala_prestadores AS ep
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    INNER JOIN funcoes_clientes AS fc
                    ON esc.id_cliente = fc.id_cliente AND ep.id_funcao = fc.funcao
                    INNER JOIN funcoes AS func
                    ON fc.funcao = func.id_func
                    WHERE esc.lote = ?
                    AND esc.status_baixa='1'
                    GROUP BY ep.id_prestador
                    ORDER BY CAST(ep.id_prestador AS decimal) ASC";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $lote);
		$stmt->execute();
		$this->BuscaLoteImpressao = $stmt->get_result();
		$stmt->close();
	}


	public $BuscaValoresFuncao;
	function BuscaValoresFuncao($funcao, $id_cliente)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT id, vl_faturamento, vl_repasse FROM funcoes_clientes
                                WHERE funcao=? AND id_cliente=?");
		$stmt->bind_param("ii", $funcao, $id_cliente);
		$stmt->execute();
		$this->BuscaValoresFuncao = $stmt->get_result();
		$stmt->close();
	}


	public $BuscaLotePrestador;
	function BuscaLotePrestador($lote, $idPrest)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT 
                    ep.*,
                    esc.id_esc,
                    esc.id_cliente,
                    esc.lote,
                    fc.vl_faturamento,
                    fc.vl_repasse,
                    func.horas_func
                    FROM escala_prestadores AS ep
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    INNER JOIN funcoes_clientes AS fc
                    ON esc.id_cliente = fc.id_cliente AND fc.funcao = ep.id_funcao
                    INNER JOIN funcoes AS func
                    ON fc.funcao = func.id_func AND fc.id_cliente = esc.id_cliente
                    WHERE esc.lote = ?
                    AND ep.id_prestador=?
                    ORDER BY ep.data_evento ASC";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("si", $lote, $idPrest);
		$stmt->execute();
		$this->BuscaLotePrestador = $stmt->get_result();
		$stmt->close();
	}


	public $DemonstrativosPrestadores;
	function DemonstrativosPrestadores($lote)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT 
                    ep.id_escala,
                    ep.id_prestador,
                    ep.data_evento,
                    ep.entrada,
                    ep.saida,
                    ep.extra,
                    ep.id_funcao,
                    esc.id_esc,
                    esc.id_cliente,
                    esc.lote,
                    fc.vl_faturamento,
                    fc.vl_repasse,
                    func.horas_func
                    FROM escala_prestadores AS ep
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    INNER JOIN funcoes_clientes AS fc
                    ON esc.id_cliente = fc.id_cliente AND fc.funcao = ep.id_funcao
                    INNER JOIN funcoes AS func
                    ON fc.funcao = func.id_func AND fc.id_cliente = esc.id_cliente
                    WHERE esc.lote = ?
                    GROUP BY ep.id_prestador";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $lote);
		$stmt->execute();
		$this->DemonstrativosPrestadores = $stmt->get_result();
		$stmt->close();
	}


	function NovoDesconto($nome, $valor, $data_sol, $dia, $descricao)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT id_prest,nome_prest FROM prestadores WHERE nome_prest=?");
		$stmt->bind_param("s", $nome);
		$stmt->execute();
		$result = $stmt->get_result();
		$qtd = $result->num_rows;
		$vt = $result->fetch_assoc();
		$stmt->close();

		if ($qtd > 0) {
			$id_prestador = $vt['id_prest'];
			$status_desc = '1';

			$stmtInsert = $mysqli->prepare("INSERT INTO descontos (id_prest_desc, valor_desc, data_sol_desc, dia_desc, descricao_desc, status_desc) VALUES (?, ?, ?, ?, ?, ?)");
			$stmtInsert->bind_param("idssss", $id_prestador, $valor, $data_sol, $dia, $descricao, $status_desc);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Desconto Agendado!');
					window.location.href = "descontos.php";
				</script>
				<?php
			}
			$stmtInsert->close();
		} else {
			?>
			<script type="text/javascript">
				alert('Nome de Prestador Inválido!');
			</script>
			<?php
		}
	}


	public $BuscaDesconto;
	function BuscaDesconto()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$this->BuscaDesconto = $mysqli->query("SELECT * FROM descontos ORDER BY id_desc ASC");
	}


	public $BuscaDescontoID;
	function BuscaDescontoID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM descontos WHERE id_desc=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->BuscaDescontoID = $stmt->get_result();
		$stmt->close();
	}


	function AttDesc($nome, $valor, $data_sol, $dia, $descricao, $id, $status)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT id_prest,nome_prest FROM prestadores WHERE nome_prest=?");
		$stmt->bind_param("s", $nome);
		$stmt->execute();
		$result = $stmt->get_result();
		$qtd = $result->num_rows;
		$vt = $result->fetch_assoc();
		$stmt->close();

		if ($qtd > 0) {
			$id_prestador = $vt['id_prest'];

			$stmtUp = $mysqli->prepare("UPDATE descontos SET id_prest_desc=?, valor_desc=?, data_sol_desc=?, dia_desc=?, descricao_desc=?, status_desc=? WHERE id_desc=?");
			$stmtUp->bind_param("idssssi", $id_prestador, $valor, $data_sol, $dia, $descricao, $status, $id);

			if ($stmtUp->execute()) {
				?>
				<script type="text/javascript">
					alert('Dados Atualizados!');
					window.location.href = "descontos.php";
				</script>
				<?php
			}
			$stmtUp->close();
		} else {
			?>
			<script type="text/javascript">
				alert('Nome de Prestador Inválido!');
			</script>
			<?php
		}
	}


	function DeleteDesc($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM descontos WHERE id_desc=?");
		$stmt->bind_param("i", $id);

		if ($stmt->execute()) {
			?>
			<script type="text/javascript">
				alert('Registro Deletado!');
				window.location.href = "descontos.php";
			</script>
			<?php
		}
		$stmt->close();
	}

	public $TotalDesconto;
	function TotalDesconto($data_evento, $prestador, $lote)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT
                    dc.id_desc,
                    dc.id_prest_desc, 
                    SUM(dc.valor_desc) AS total , 
                    dc.data_sol_desc, 
                    dc.dia_desc, 
                    dc.descricao_desc, 
                    ep.id_prestador, 
                    ep.data_evento,
                    esc.lote,
                    esc.status_baixa
                    FROM descontos AS dc
                    INNER JOIN escala_prestadores AS ep
                    ON dc.id_prest_desc = ep.id_prestador
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    WHERE dc.data_sol_desc=?
                    AND ep.data_evento=?
                    AND dc.status_desc='1'
                    AND dc.dia_desc >= ?
                    AND dc.id_prest_desc=?
                    AND esc.lote=?
                    AND esc.status_baixa='1'";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sssds", $data_evento, $data_evento, $data_evento, $prestador, $lote);
		$stmt->execute();
		$this->TotalDesconto = $stmt->get_result();
		$stmt->close();
	}


	function PagarRecibo($idPrestador, $escala, $cliente, $data_evento, $entrada, $saida, $extra, $funcao, $setor, $lote, $faturamento, $repasse, $recebimento, $mes, $ano, $desconto, $descricao, $extraFat, $extraRep, $escalaPrestador)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT lote FROM valores_lotes WHERE lote=?");
		$stmt->bind_param("s", $lote);
		$stmt->execute();
		$query = $stmt->get_result();
		$qtd = $query->num_rows;
		$stmt->close();

		if ($qtd > 0) {
			$data = date('Y-m-d');

			$stmtUp = $mysqli->prepare("UPDATE escala_prestadores SET status_pag='1', data_pgto=? WHERE id=? AND id_escala=?");
			$stmtUp->bind_param("sii", $data, $escalaPrestador, $escala);
			$stmtUp->execute();
			$stmtUp->close();

			$stmtIns = $mysqli->prepare("INSERT INTO valores_pagos_prestadores
                                (escala, cliente, prestador, data_evento, entrada, saida, extra, funcao, setor, lote, faturamento, repasse, recebimento, mes_ref, ano_ref, desconto, descricao_desconto, extra_fat, extra_rep, id_escala_prest)
                                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

			$stmtIns->bind_param(
				"iiissssissdddsssdssd",
				$escala,
				$cliente,
				$idPrestador,
				$data_evento,
				$entrada,
				$saida,
				$extra,
				$funcao,
				$setor,
				$lote,
				$faturamento,
				$repasse,
				$recebimento,
				$mes,
				$ano,
				$desconto,
				$descricao,
				$extraFat,
				$extraRep,
				$escalaPrestador
			);

			$stmtIns->execute();
			$stmtIns->close();

			?>
			<script type="text/javascript">
				alert('Prestador Pago!');
				window.location.href = "?funcao=ImprimirRecibos";
			</script>
			<?php

		} else {
			?>
			<script type="text/javascript">
				alert("Calcule o Lote Antes de Efetuar o Pagamento!");
				window.location.href = "?funcao=ImprimirRecibos";
			</script>
			<?php
		}
	}


	function PagarReciboTotal($idPrestador, $escala, $cliente, $data_evento, $entrada, $saida, $extra, $funcao, $setor, $lote, $faturamento, $repasse, $recebimento, $mes, $ano, $desconto, $descricao, $extraFat, $extraRep, $escalaPrestador)
	{
		// Reuse the logic from PagarRecibo or implement similar secure logic.
		$this->PagarRecibo($idPrestador, $escala, $cliente, $data_evento, $entrada, $saida, $extra, $funcao, $setor, $lote, $faturamento, $repasse, $recebimento, $mes, $ano, $desconto, $descricao, $extraFat, $extraRep, $escalaPrestador);
	}


	public $TipoPagamento;
	function TipoPagamento($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT bc.banco, ct.agencia, ct.ag_digito, ct.conta, ct.cc_digito, ct.id_prestador
                                FROM contas AS ct
                                INNER JOIN bancos AS bc
                                ON ct.id_banco = bc.banco_id
                                WHERE ct.id_prestador=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->TipoPagamento = $stmt->get_result();
		$stmt->close();
	}


	function CalcularLote($lote, $pgt, $mes, $ano)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$sql = "SELECT esc.lote, ep.id_funcao, SUM(ep.valor_rep) AS total
                FROM escalas AS esc
                INNER JOIN escala_prestadores As ep
                ON esc.id_esc = ep.id_escala
                INNER JOIN funcoes_clientes AS fc
                ON esc.id_cliente = fc.id_cliente AND ep.id_funcao= fc.funcao
                INNER JOIN funcoes AS func
                ON fc.funcao = func.id_func
                WHERE esc.lote=?";

		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("s", $lote);
		$stmt->execute();
		$result = $stmt->get_result();
		$qtd = $result->num_rows;
		$vt = $result->fetch_assoc();
		$total = $vt['total'];
		$stmt->close();

		if ($qtd > 0) {
			$stmtCheck = $mysqli->prepare("SELECT * FROM valores_lotes WHERE lote=?");
			$stmtCheck->bind_param("s", $lote);
			$stmtCheck->execute();
			$resultCheck = $stmtCheck->get_result();
			$qtd_lote = $resultCheck->num_rows;
			$stmtCheck->close();

			if ($qtd_lote == 0) {
				$stmtIns = $mysqli->prepare("INSERT INTO valores_lotes (lote, data_pagamento, mes_ref, ano_ref, total) VALUES (?, ?, ?, ?, ?)");
				$stmtIns->bind_param("ssssd", $lote, $pgt, $mes, $ano, $total);
				$stmtIns->execute();
				$stmtIns->close();

				?>
				<script type="text/javascript">
					alert('Lote Calculado!');
					window.location.href = "?funcao=CalcularLote";
				</script>
				<?php
			} else {
				$stmtUp = $mysqli->prepare("UPDATE valores_lotes SET mes_ref=?, total=? WHERE lote=?");
				$stmtUp->bind_param("sds", $mes, $total, $lote);
				$stmtUp->execute();
				$stmtUp->close();

				?>
				<script type="text/javascript">
					alert("Valores Atualizados!");
					window.location.href = "?funcao=CalcularLote";
				</script>
				<?php
			}
		} else {
			?>
			<script type="text/javascript">
				alert("Lote Inválido!");
				window.location.href = "?funcao=CalcularLote";
			</script>
			<?php
		}
	}


	public $BuscaRecibosPagos;
	function BuscaRecibosPagos()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$this->BuscaRecibosPagos = $mysqli->query("SELECT 
                                ep.id_escala,
                                ep.id_prestador,
                                ep.data_evento,
                                ep.entrada,
                                ep.saida,
                                ep.extra,
                                ep.id_funcao,
                                esc.id_esc,
                                esc.id_cliente,
                                esc.lote,
                                ep.status_pag,
                                ep.data_pgto,
                                esc.status_baixa
                                FROM escala_prestadores AS ep
                                INNER JOIN escalas AS esc
                                ON ep.id_escala = esc.id_esc
                                WHERE esc.status_baixa='1'
                                AND ep.status_pag='1'");
	}


	public $ListaChequesID;
	function ListaChequesID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM cheques WHERE id_prestador=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->ListaChequesID = $stmt->get_result();
		$stmt->close();
	}


	public $FichaFinanceira;
	function FichaFinanceira($idPrest)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT vp.*, SUM(vp.repasse) AS total
                                FROM valores_pagos_prestadores AS vp
                                WHERE vp.prestador = ?
                                GROUP BY vp.cliente,vp.lote
                                ORDER BY vp.data_evento ASC");
		$stmt->bind_param("i", $idPrest);
		$stmt->execute();
		$this->FichaFinanceira = $stmt->get_result();
		$stmt->close();
	}


	public $TotalDescontado;
	function TotalDescontado($data_evento, $prestador, $lote)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT
                    dc.id_desc,
                    dc.id_prest_desc, 
                    SUM(dc.valor_desc) As total , 
                    dc.data_sol_desc, 
                    dc.dia_desc, 
                    dc.descricao_desc, 
                    ep.id_prestador, 
                    ep.data_evento,
                    esc.lote,
                    esc.status_baixa
                    FROM descontos AS dc
                    INNER JOIN escala_prestadores AS ep
                    ON dc.id_prest_desc = ep.id_prestador
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    WHERE dc.data_sol_desc=?
                    AND ep.data_evento=?
                    AND dc.dia_desc >= ?
                    AND dc.id_prest_desc=?
                    AND esc.lote=?
                    AND esc.status_baixa='1'";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sssds", $data_evento, $data_evento, $data_evento, $prestador, $lote);
		$stmt->execute();
		$this->TotalDescontado = $stmt->get_result();
		$stmt->close();
	}

	public $DataRef;
	function DataRef($lote)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM valores_lotes WHERE lote=?");
		$stmt->bind_param("s", $lote);
		$stmt->execute();
		$this->DataRef = $stmt->get_result();
		$stmt->close();
	}

	public $BuscaDescontosTotais;
	function BuscaDescontosTotais($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT dsc.data_sol_desc,
                                dsc.dia_desc,
                                vpp.lote,
                                vpp.desconto,
                                vpp.descricao_desconto,
                                vpp.desconto
                                FROM descontos AS dsc
                                INNER JOIN valores_pagos_prestadores AS vpp
                                ON dsc.id_prest_desc = vpp.prestador AND dsc.data_sol_desc = vpp.data_evento
                                WHERE vpp.prestador=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->BuscaDescontosTotais = $stmt->get_result();
		$stmt->close();
	}

	public $RepassePorLote;
	function RepassePorLote($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT entrada, saida, extra, SUM(repasse) AS total, desconto, lote 
                                FROM valores_pagos_prestadores 
                                WHERE prestador=?
                                GROUP BY lote");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->RepassePorLote = $stmt->get_result();
		$stmt->close();
	}


	public $DadosFaturamento;
	function DadosFaturamento($idCl, $de, $ate)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$valores = "";
		$cond = "";
		$types = "iss";
		$params = array($idCl, $de, $ate);

		if (isset($_POST['setor'])) {
			if (is_array($_POST['setor'])) {
				// Modern secure approach: use IN operator with placeholders
				$setores = $_POST['setor'];
				$placeholders = implode(',', array_fill(0, count($setores), '?'));
				$cond = "AND vpp.setor IN ($placeholders)";

				$types .= str_repeat('s', count($setores));
				foreach ($setores as $s) {
					$params[] = $s;
				}
			} else {
				// Should be array, but fallback if string
				$cond = "AND vpp.setor = ?";
				$types .= 's';
				$params[] = $_POST['setor'];
			}
		}

		$query = "SELECT vpp.*, fc.horas_func, fcl.vl_faturamento, fcl.vl_repasse
                    FROM valores_pagos_prestadores AS vpp
                    INNER JOIN funcoes AS fc
                    ON vpp.funcao = fc.id_func
                    INNER JOIN funcoes_clientes AS fcl
                    ON fc.id_func = fcl.funcao AND vpp.cliente = fcl.id_cliente
                    WHERE vpp.cliente = ?
                    AND vpp.data_evento BETWEEN ? AND ?
                    $cond
                    ORDER BY vpp.data_evento ASC";

		$stmt = $mysqli->prepare($query);
		if ($stmt) {
			$stmt->bind_param($types, ...$params);
			$stmt->execute();
			$this->DadosFaturamento = $stmt->get_result();
			$stmt->close();
		} else {
			die("Erro na consulta de faturamento: " . $mysqli->error);
		}
	}

	public $DadosFaturamentoTodos;
	function DadosFaturamentoTodos($de, $ate)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT vpp.*, fc.horas_func, fcl.vl_faturamento, fcl.vl_repasse
                                FROM valores_pagos_prestadores AS vpp
                                INNER JOIN funcoes AS fc
                                ON vpp.funcao = fc.id_func
                                INNER JOIN funcoes_clientes AS fcl
                                ON fc.id_func = fcl.funcao AND vpp.cliente = fcl.id_cliente
                                WHERE vpp.data_evento BETWEEN ? AND ?
                                ORDER BY vpp.data_evento ASC");

		$stmt->bind_param("ss", $de, $ate);
		$stmt->execute();
		$this->DadosFaturamentoTodos = $stmt->get_result();
		$stmt->close();
	}

	public $SetorFaturamento;
	function SetorFaturamento($idCl)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT DISTINCT setor
                                FROM valores_pagos_prestadores 
                                WHERE cliente = ?
                                ORDER BY data_evento,setor ASC");
		$stmt->bind_param("i", $idCl);
		$stmt->execute();
		$this->SetorFaturamento = $stmt->get_result();

		$qtd = $this->SetorFaturamento->num_rows;
		$stmt->close();

		if ($qtd == 0) {
			?>
			<script type="text/javascript">
				alert('A Busca Não Retornou Resultados!');
				window.location.href = "?funcao=FaturamentoRepasse";
			</script>
			<?php
		}
	}


	public $FaturamentoPorPrestador;
	function FaturamentoPorPrestador($de, $ate, $lote)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT 
                    pt.nome_prest,
                    ep.id_prestador,
                    SUM(ep.valor_rep + ep.valor_extra_rep) AS total,
                    esc.id_esc,
                    esc.id_cliente,
                    esc.lote
                    FROM escala_prestadores AS ep
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    INNER JOIN funcoes_clientes AS fc
                    ON esc.id_cliente = fc.id_cliente AND fc.funcao = ep.id_funcao
                    INNER JOIN prestadores AS pt
                    ON ep.id_prestador = pt.id_prest
                    WHERE ep.data_evento BETWEEN ? AND ?
                    AND esc.lote = ?
                    GROUP BY ep.id_prestador
                    ORDER BY ep.data_evento ASC";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sss", $de, $ate, $lote);
		$stmt->execute();
		$this->FaturamentoPorPrestador = $stmt->get_result();
		$stmt->close();
	}


	public $FaturamentoPorPrestadorID;
	function FaturamentoPorPrestadorID($de, $ate, $id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT pt.*, ep.data_evento, SUM(fc.vl_faturamento) as total
                FROM prestadores AS pt
                INNER JOIN escala_prestadores AS ep
                ON pt.id_prest = ep.id_prestador
                INNER JOIN escalas AS esc
                ON ep.id_escala = esc.id_esc
                INNER JOIN funcoes_clientes AS fc
                ON esc.id_cliente = fc.id_cliente AND ep.id_funcao = fc.funcao
                WHERE ep.data_evento BETWEEN ? AND ?
                AND esc.status_baixa = '1'
                AND pt.id_prest=?
                GROUP BY pt.nome_prest";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("ssi", $de, $ate, $id);
		$stmt->execute();
		$this->FaturamentoPorPrestadorID = $stmt->get_result();
		$stmt->close();
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
		$stmt->close();

		return $vt['funcao'];
	}

}
?>