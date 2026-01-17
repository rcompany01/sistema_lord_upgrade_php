<?php
class Escalas
{



	function NovaEscala($sol, $idCl, $setor, $dtSol, $dtEvent)
	{
		// FAZ A CONEXÃO AO BANCO
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT cl_nome_fantasia FROM clientes WHERE id_cl=?");
		$stmt->bind_param("i", $idCl);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$cliente = $vt['cl_nome_fantasia'];
		$stmt->close();

		$stmtInsert = $mysqli->prepare("INSERT INTO escalas (solicitante, id_cliente, cliente, setor, data_solic, data_evento, status_baixa)
										VALUES(?, ?, ?, ?, ?, ?, '0')");
		$stmtInsert->bind_param("sissss", $sol, $idCl, $cliente, $setor, $dtSol, $dtEvent);

		if ($stmtInsert->execute()) {
			$lastID = $stmtInsert->insert_id;
			echo "<script type='text/javascript'>
						alert('Escala Registrada!');
						window.location.href='?funcao=escalas&idEsc=$lastID#add';
					</script>";
		}
		$stmtInsert->close();
	}


	public $ListaEscalas;
	function ListaEscalas()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$where = "WHERE 1=1";
		$params = array();
		$types = "";

		if (!empty($_GET['id_escala'])) {
			$where .= " AND id_esc=?";
			$types .= "i";
			$params[] = $_GET['id_escala'];
		} elseif (!empty($_GET['id_setor'])) {
			$where .= " AND setor=?";
			$types .= "s";
			$params[] = $_GET['id_setor'];
		} elseif (!empty($_GET['id_cliente'])) {
			$where .= " AND id_cliente=?";
			$types .= "i";
			$params[] = $_GET['id_cliente'];
		} elseif (!empty($_GET['de']) && (!empty($_GET['ate']))) {
			$where .= " AND data_evento BETWEEN ? AND ?";
			$types .= "ss";
			$params[] = $_GET['de'];
			$params[] = $_GET['ate'];
		}


		$inicio = 0;
		$max = 25;


		if (!empty($_GET['pagina'])) {
			$inicio = (int) $_GET['pagina'];
		}

		$order = "ORDER BY id_esc DESC";
		if (!empty($_GET['ordem'])) {
			if ($_GET['ordem'] == 'cod') {
				$order = "ORDER BY id_esc ASC";
			} elseif ($_GET['ordem'] == 'alfa') {
				$order = "ORDER BY cliente ASC";
			} elseif ($_GET['ordem'] == 'codalfa') {
				$order = "ORDER BY id_esc, cliente ASC";
			}
		}

		// Prepare statement with dynamic limit and order is tricky in pure prepared statements for order by without whitelisting, 
		// but here order by options are whitelisted above. Limit works with ?

		$query = "SELECT * FROM escalas $where $order LIMIT ?, ?";
		$stmt = $mysqli->prepare($query);

		// Append Limit params
		$types .= "ii";
		$params[] = $inicio;
		$params[] = $max;

		if ($types) {
			$stmt->bind_param($types, ...$params);
		}

		$stmt->execute();
		$this->ListaEscalas = $stmt->get_result();
		$stmt->close();

	}

	public $ListaEscalasImpressao;
	function ListaEscalasImpressao()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$where = "WHERE 1=1";
		$params = array();
		$types = "";

		if (!empty($_GET['id_escala'])) {
			$where .= " AND id_esc=?";
			$types .= "i";
			$params[] = $_GET['id_escala'];
		} elseif (!empty($_GET['id_setor'])) {
			$where .= " AND setor=?";
			$types .= "s";
			$params[] = $_GET['id_setor'];
		} elseif (!empty($_GET['id_cliente'])) {
			$where .= " AND id_cliente=?";
			$types .= "i";
			$params[] = $_GET['id_cliente'];
		} elseif (!empty($_GET['de']) && (!empty($_GET['ate']))) {
			$where .= " AND data_evento BETWEEN ? AND ?";
			$types .= "ss";
			$params[] = $_GET['de'];
			$params[] = $_GET['ate'];
		}

		$order = "ORDER BY id_esc DESC";
		if (!empty($_GET['ordem'])) {
			if ($_GET['ordem'] == 'cod') {
				$order = "ORDER BY id_esc ASC";
			} elseif ($_GET['ordem'] == 'alfa') {
				$order = "ORDER BY cliente ASC";
			} elseif ($_GET['ordem'] == 'codalfa') {
				$order = "ORDER BY id_esc, cliente ASC";
			}
		}

		$query = "SELECT * FROM escalas $where $order";
		$stmt = $mysqli->prepare($query);

		if ($types) {
			$stmt->bind_param($types, ...$params);
		}

		$stmt->execute();
		$this->ListaEscalasImpressao = $stmt->get_result();
		$stmt->close();
	}


	function FormataData($data)
	{
		$vt = explode('-', $data);
		$dia = $vt[2] . "/" . $vt[1] . "/" . $vt[0];
		return $dia;
	}


	function BuscaSetor($id)
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


	function BuscaNomePrestador($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT nome_prest, status FROM prestadores WHERE id_prest=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$stmt->close();

		// $status can be null if no result found
		$status = isset($vt['status']) ? $vt['status'] : null;
		$qtd = $result->num_rows;
		// Although num_rows on result object, not mysqli connection with store_result or direct query

		if ($qtd == 0 || $status == "0") {
			?>
			<script type="text/javascript">
				alert("Prestador não encontrado ou inativo!");
				var esc = document.getElementById('idEsc').value;
				var func = document.getElementById('FuncID').value;
				window.location.href = "?funcao=escalas&idEsc=" + esc + "&Value=" + func + "#add";
			</script>
			<?php
		} else {
			return $vt['nome_prest'];
		}
	}


	public $BuscaEscala;
	function BuscaEscala($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT esc.*, cl.* FROM escalas AS esc
													INNER JOIN clientes AS cl
													ON esc.id_cliente = cl.id_cl
													WHERE esc.id_esc=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->BuscaEscala = $stmt->get_result();
		$stmt->close();
	}


	public $ListaPrestadorAtivo;
	function ListaPrestadorAtivo()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaPrestadorAtivo = $mysqli->query("SELECT * FROM prestadores WHERE status='1' ORDER BY nome_prest ASC");
	}

	function NomeEscala($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT cliente FROM escalas WHERE id_esc = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$escala = $vt['cliente'];
		$stmt->close();

		return $escala;
	}

	function calcularIntervaloHoras($horaInicio, $horaFim)
	{
		$intervalo = $horaInicio->diff($horaFim);

		if ($intervalo->invert == 1) {
			$horaFim->add(new DateInterval('P1D'));
			$intervalo = $horaInicio->diff($horaFim);
		}

		return $intervalo;

	}


	function AddPrestadoEscala($idEscala, $idPrest, $dataEvento, $entrada, $saida, $extra, $idFuncao, $idCl)
	{
		date_default_timezone_set('UTC');
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		// VERIFICA SE O PRESTADOR ESTÁ EM ALGUMA ESCALA NO MESMO HORARIO
		$stmtV = $mysqli->prepare("SELECT * FROM escala_prestadores 
							WHERE data_evento=?
							AND entrada=?
							AND saida=?
							AND id_prestador=?");
		$stmtV->bind_param("sssi", $dataEvento, $entrada, $saida, $idPrest);
		$stmtV->execute();
		$queryV = $stmtV->get_result();

		$qtdV = $queryV->num_rows;
		$vtV = $queryV->fetch_assoc();
		$stmtV->close();

		if ($qtdV > 0) {
			$escala = $this->NomeEscala($vtV['id_escala']);
			$dataDiaEv = $this->FormataData($vtV['data_evento']);
			echo "	<script>
								alert('Este prestador já consta na escala - " . '[' . $vtV['id_escala'] . '] - ' . $escala . " do dia " . $dataDiaEv . " - " . $entrada . " às " . $saida . "');
							</script>";
		} else {
			// ==============================================================

			$stmtF = $mysqli->prepare("SELECT vl_faturamento, vl_repasse FROM funcoes_clientes WHERE funcao = ? AND id_cliente=?");
			$stmtF->bind_param("ii", $idFuncao, $idCl);
			$stmtF->execute();
			$resultF = $stmtF->get_result();
			$list = $resultF->fetch_assoc();
			$stmtF->close();


			// HORA EXTRA
			$vtEx = explode(":", $extra);
			if ($vtEx[0] > 0) {
				$vtEx[0] = $vtEx[0] * 60;
			}
			$extraFormat = $vtEx[0] + $vtEx[1];		// 0		 

			// CALCULO DA HORA EXTRA (FATURAMENTO)
			$horaComum = ($list['vl_faturamento'] * $extraFormat) / 60;
			$horaExtra = $horaComum + ($horaComum * 0.5);
			$finalExtra = $horaExtra;            // 0

			// CALCULO DA HORA EXTRA (REPASSE)
			$horaComumRep = ($list['vl_repasse'] * $extraFormat) / 60;
			$horaExtraRep = $horaComumRep + ($horaComumRep * 0.5);
			$finalExtraRep = $horaExtraRep;     // 0

			// CALCULA AS HORAS TRABALHADAS E MOSTRA O VALOR PROPORCIONAL (FATURAMENTO)
			$datetime1 = new DateTime($entrada);
			$datetime2 = new DateTime($saida);

			$intervalo = $this->calcularIntervaloHoras($datetime1, $datetime2);

			$hour = $intervalo->h;
			$min = $intervalo->i;

			$horasTrabalhadasFat = ($hour * $list['vl_faturamento']) + (($min * $list['vl_faturamento']) / 60);

			$valorFinal = number_format(abs($horasTrabalhadasFat), 2);


			// CALCULA AS HORAS TRABALHADAS E MOSTRA O VALOR PROPORCIONAL (REPASSE)
			$datetime1Rep = new DateTime($entrada);
			$datetime2Rep = new DateTime($saida);

			$intervaloRep = $this->calcularIntervaloHoras($datetime1Rep, $datetime2Rep);

			$hourRep = $intervaloRep->h;
			$minRep = $intervaloRep->i;

			$horasTrabalhadasRep = ($hourRep * $list['vl_repasse']) + (($minRep * $list['vl_repasse']) / 60);

			$valorFinalRep = number_format(abs($horasTrabalhadasRep), 2);


			$stmtInsert = $mysqli->prepare("INSERT INTO escala_prestadores (id_escala, id_prestador, data_evento, entrada, saida, extra, 
																	id_funcao, status_pag, valor_fat, valor_rep, valor_extra_fat, valor_extra_rep)
																	VALUES(?, ?, ?, ?, ?, ?, ?, '0', ?, ?, ?, ?)");
			$stmtInsert->bind_param(
				"iissssissdd",
				$idEscala,
				$idPrest,
				$dataEvento,
				$entrada,
				$saida,
				$extra,
				$idFuncao,
				$valorFinal,
				$valorFinalRep,
				$finalExtra,
				$finalExtraRep
			);
			$stmtInsert->execute();
			$stmtInsert->close();

		}
	}




	public $ListaPrestadorEscala;
	function ListaPrestadorEscala($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT 
                    ep.*,
                    fc.vl_faturamento,
                    fc.vl_repasse,
                    func.horas_func,
                    pr.nome_prest
                    FROM escala_prestadores AS ep
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    INNER JOIN funcoes_clientes AS fc
                    ON esc.id_cliente = fc.id_cliente AND ep.id_funcao = fc.funcao
                    INNER JOIN funcoes AS func
                    ON fc.funcao = func.id_func
                    INNER JOIN prestadores AS pr
                    ON ep.id_prestador = pr.id_prest
                    WHERE ep.id_escala=?
                    ORDER BY pr.nome_prest ASC";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->ListaPrestadorEscala = $stmt->get_result();
		$stmt->close();
	}



	function NomePrestadorID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT nome_prest FROM prestadores WHERE id_prest=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$prest = $vt['nome_prest'];
		$stmt->close();
		return $prest;
	}


	function NomeFuncaoID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT funcao FROM funcoes WHERE id_func=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$func = $vt['funcao'];
		$stmt->close();
		return $func;
	}


	function AtualizarEscala($sol, $idCl, $setor, $dataSol, $dataEvent, $id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmtC = $mysqli->prepare("SELECT cl_nome_fantasia FROM clientes WHERE id_cl=?");
		$stmtC->bind_param("i", $idCl);
		$stmtC->execute();
		$resultC = $stmtC->get_result();
		$vt = $resultC->fetch_assoc();
		$cliente = $vt['cl_nome_fantasia'];
		$stmtC->close();


		$stmtUp = $mysqli->prepare("UPDATE escalas SET solicitante=?, id_cliente=?, cliente=?, setor=?, data_solic=?, data_evento=?
														WHERE id_esc=?");
		$stmtUp->bind_param("sissssi", $sol, $idCl, $cliente, $setor, $dataSol, $dataEvent, $id);
		$stmtUp->execute();
		$stmtUp->close();
	}


	public $ListaPrestadorEscalaAtt;
	function ListaPrestadorEscalaAtt($id, $id_reg)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$query = "SELECT ep.*, esc.id_cliente, fc.horas_func, fcl.vl_faturamento, fcl.vl_repasse
                    FROM escala_prestadores AS ep
                    INNER JOIN escalas AS esc
                    ON ep.id_escala = esc.id_esc
                    INNER JOIN funcoes AS fc
                    ON ep.id_funcao = fc.id_func
                    INNER JOIN funcoes_clientes AS fcl
                    ON ep.id_funcao = fcl.funcao AND esc.id_cliente = fcl.id_cliente
                    WHERE ep.id_escala=? 
                    AND ep.id=?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("ii", $id, $id_reg);
		$stmt->execute();
		$this->ListaPrestadorEscalaAtt = $stmt->get_result();
		$stmt->close();
	}


	function AtualizaPrestadorEscala($idPrest, $dataEvent, $entrada, $saida, $extra, $funcao, $id, $fat, $rep, $extraFat, $extraRep)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		// ATUALIZA OS VALORES DO PRESTADOR
		$stmtUp = $mysqli->prepare("UPDATE escala_prestadores SET id_prestador=?, data_evento=?, entrada=?, saida=?, extra=?,
                                                                id_funcao=?, status_pag='0', valor_fat=?, valor_rep=?,
                                                                valor_extra_fat=?, valor_extra_rep=?
                                                                WHERE id=?");
		$stmtUp->bind_param(
			"issssidddd i",
			$idPrest,
			$dataEvent,
			$entrada,
			$saida,
			$extra,
			$funcao,
			$fat,
			$rep,
			$extraFat,
			$extraRep,
			$id
		);
		$stmtUp->execute();
		$stmtUp->close();


		// DELETA O PAGAMENTO FEITO ANTERIORMENTE COM OS VALORES ANTIGOS
		$stmtDel = $mysqli->prepare("DELETE FROM valores_pagos_prestadores WHERE id_escala_prest=?");
		$stmtDel->bind_param("i", $id);
		$stmtDel->execute();
		$stmtDel->close();
	}




	function ExcluirPrestadorEscala($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM escala_prestadores WHERE id=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();

		?>
		<script type="text/javascript">
			var esc = document.getElementById('idEsc').value;
			window.location.href = "?funcao=escalas&idEsc=" + esc + "#add";
		</script>
		<?php
	}


	function ExcluirEscala($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt1 = $mysqli->prepare("DELETE FROM escalas WHERE id_esc=?");
		$stmt1->bind_param("i", $id);
		$stmt1->execute();
		$stmt1->close();

		$stmt2 = $mysqli->prepare("DELETE FROM escala_prestadores WHERE id_escala=?");
		$stmt2->bind_param("i", $id);
		$stmt2->execute();
		$stmt2->close();

		?>
		<script type="text/javascript">
			window.location.href = "?funcao=escalas";
		</script>
		<?php
	}


	public $BuscaValoresFuncao;
	function BuscaValoresFuncao($id, $idCl)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT fc.horas_func, fcl.* FROM funcoes_clientes AS fcl
                                    INNER JOIN funcoes AS fc
                                    ON fcl.funcao = fc.id_func
                                    WHERE fcl.funcao=? AND fcl.id_cliente=?");
		$stmt->bind_param("ii", $id, $idCl);
		$stmt->execute();
		$this->BuscaValoresFuncao = $stmt->get_result();
		$qtd = $this->BuscaValoresFuncao->num_rows;
		$stmt->close();

		if ($qtd == '0') {
			?>
			<script type="text/javascript">
				alert("Função não encontrada!");
				var esc = document.getElementById('idEsc').value;
				window.location.href = "?funcao=escalas&idEsc=" + esc + "#add";
			</script>
			<?php
		}

	}



	function RelacaoEscalaPrestTotal($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT id FROM escala_prestadores WHERE id_escala=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();
		return $qtd;
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
                            ep.valor_fat AS total
                            FROM escalas AS esc
                            INNER JOIN funcoes_clientes AS fc
                            ON esc.id_cliente = fc.id_cliente
                            INNER JOIN escala_prestadores AS ep
                            ON fc.funcao = ep.id_funcao AND esc.id_esc = ep.id_escala
                            INNER JOIN funcoes AS func
                            ON ep.id_funcao = func.id_func
                            WHERE esc.id_esc=?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->RelacaoEscalaPrest = $stmt->get_result();
		$stmt->close();
	}

	function TotalEscalas()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$this->ListaPrestadorAtivo = $mysqli->query("SELECT * FROM escalas");
		return $this->ListaPrestadorAtivo->num_rows;
	}

	public $PegaIDPorNomeCl;
	function PegaIDPorNomeCl($nome)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT id_cl FROM clientes WHERE cl_nome_fantasia = ?");
		$stmt->bind_param("s", $nome);
		$stmt->execute();
		$this->PegaIDPorNomeCl = $stmt->get_result();
		$stmt->close();
	}

	public $BuscaCliente;
	function BuscaCliente($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT cl_nome_fantasia FROM clientes WHERE id_cl = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->BuscaCliente = $stmt->get_result();
		$stmt->close();
	}


	function DadosFuncao($idCliente, $funcao)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT fc.*, func.horas_func FROM funcoes_clientes AS fc
					INNER JOIN funcoes AS func
					ON fc.funcao = func.id_func
					WHERE fc.id_cliente = ?
					AND fc.funcao = ?");

		$stmt->bind_param("ii", $idCliente, $funcao);
		$stmt->execute();
		$query = $stmt->get_result();
		$array = $query->fetch_assoc();
		$stmt->close();

		$return = $array['vl_faturamento'] . '/' . $array['vl_repasse'] . '/' . $array['horas_func'];

		echo $return;
	}

}
