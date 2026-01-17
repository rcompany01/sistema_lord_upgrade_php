<script type="text/javascript">
	//window.print();
</script>
<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 0);

	// PEGA O NOME DO PRESTADOR
	require_once("../prestadores/class/prestadores.class.php");
	$prest = new Prestadores;


	// BUSCA OS VALORES E DADOS DA ESCALA DO PRESTADOR
	require_once("class/financeiro.class.php");
	$class = new Financeiro;


	$date = date('m');
	$mes="";
	switch ($date) {
		case '01':
			$mes = "JANEIRO";
			break;
		
		case '02':
			$mes = "FEVEREIRO";
			break;

		case '03':
			$mes = "MARÇO";
			break;

		case '04':
			$mes = "ABRIL";
			break;

		case '05':
			$mes = "MAIO";
			break;

		case '06':
			$mes = "JUNHO";
			break;

		case '07':
			$mes = "JULHO";
			break;

		case '08':
			$mes = "AGOSTO";
			break;

		case '09':
			$mes = "SETEMBRO";
			break;

		case '10':
			$mes = "OUTUBRO";
			break;

		case '11':
			$mes = "NOVEMBRO";
			break;

		case '12':
			$mes = "DEZEMBRO";
			break;
	}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../../css/styles.css">
	<link rel="stylesheet" href="../../css/bootstrap.css">
	<title>Demonstrativo</title>
</head>

<style type="text/css">
	body{
		padding: 0;
		margin: 0;
	}

	@media print{
		.break{
			page-break-inside:avoid;
		}

		.breakbe{
			page-break-before: always;
		}
	}
</style>

<body>
	


<?php
	// BUSCA OS DADOS DO LOTE DE ACORDO COM O ID DO PRESTADOR
	$pagamentoTotal =0;
	$class->DemonstrativosPrestadores($_GET['Lote']);
	while($row = mysqli_fetch_assoc($class->DemonstrativosPrestadores)){
?>

	<table class="table table-striped break">
		<tr>
			<td style='border-top:none' colspan='2'><img src="../../img/logo.png" height="77" width="120" alt=""></td>
			<td style='border-top:none;padding-top:35px' colspan='3'><b>Demonstrativo de Pagamento</b></td>
			<td style='border-top:none;padding-top:35px' colspan='3'><b>Lote:</b> <?= strtoupper($_GET['Lote']) ?></td>
		</tr>

		<tr>
			<td style='text-align:left!important' colspan='4'><b>Prestador:</b> <?= $row['id_prestador']." - ".$prest->NomePrestador($row['id_prestador']) ?></td>
			<td colspan='3'>&nbsp;</td>
		</tr>

		<tr>
			<td style="text-align:center">Dt. Evento</td>
			<td style="text-align:center">Hr. Entrada</td>
			<td style="text-align:center">Hr. Saída</td>
			<td style="text-align:center">Função</td>
			<td style="text-align:center">Hr. Extra</td>
			<td style="text-align:center">Vlr. Hr. Extra</td>
			<td style="text-align:center">Vlr. Paga</td>
			<td style="text-align:center">Total</td>
		</tr>

		<?php
			// BUSCA OS DADOS DO LOTE DE ACORDO COM O ID DO PRESTADOR
			$pagamentoTotal =0;
			$cont = 0;
			$class->BuscaLotePrestador($_GET['Lote'], $row['id_prestador']);
			while($demons = mysqli_fetch_assoc($class->BuscaLotePrestador)){
				$cont++;

			/* 
			=====================================================
			VALOR TOTAL A RECEBER 
			(HORAS DE TRABALHO * VALOR DA HORA)
			*/

			// CUSTO DA HORA DE TRABALHO
			$ValorDaHora = $demons['vl_repasse']/$demons['horas_func'];
			$horasTrabalhadas = $demons['saida'] - $demons['entrada'];

			// CALCULO DO EXTRA
			// $extra = $ValorDaHora / 2;
			// $totalExtra = ($extra * $demons['extra']);

			$extra = $demons['valor_extra_rep'];


			//$repasse = $ValorDaHora*$horasTrabalhadas;
			$total = $demons['valor_rep'] + $extra;
			$pagamentoTotal += $total;


			// ===================================================



			// BUSCA O VALOR TOTAL DE DESCONTOS PENDENTES AO PRESTADOR
			$class->TotalDesconto($demons['data_evento'], $demons['id_prestador'], $_GET['Lote']);
			$desc = mysqli_fetch_assoc($class->TotalDesconto);


			// BUSCA A FORMA DE PAGAMENTO (CONTA) DO PRESTA
			$class->TipoPagamento($demons['id_prestador']);
			$pagamento = mysqli_fetch_assoc($class->TipoPagamento);



			// BUSCA OS CHEQUES COMO FORMA DE PAGAMENTO

			if ($pagamento['banco']==""){
				$class->ListaChequesID($demons['id_prestador']);
				$cheque = mysqli_fetch_assoc($class->ListaChequesID);
			}

			if ($cont == 13){
				echo "</table>";
				
				echo "<table style='float:left;margin-top:20px;page-break-inside:avoid ' width='100%;height:100%' class='table table-striped'>
						<tr>
							<td style='text-align:center'>Dt. Evento</td>
							<td style='text-align:center'>Hr. Entrada</td>
							<td style='text-align:center'>Hr. Saída</td>
							<td style='text-align:center'>Função</td>
							<td style='text-align:center'>Hr. Extra</td>
							<td style='text-align:center'>Vlr. Hr. Extra</td>
							<td style='text-align:center'>Vlr. Paga</td>
							<td style='text-align:center'>Total</td>
						</tr>";

					$cont = 0;
					echo "<hr>";
					
			}
		?>

				<tr>
					<td style="text-align:center;font-size:14px"><?= $class->FormataData($demons['data_evento']) ?></td>
					<td style="text-align:center;font-size:14px"><?= $demons['entrada'] ?></td>
					<td style="text-align:center;font-size:14px"><?= $demons['saida'] ?></td>
					<td style="text-align:center;font-size:14px"><?= $class->NomeFuncao($demons['id_funcao']) ?></td>
					<td style="text-align:center;font-size:14px"><?= $demons['extra'] ?></td>
					<td style="text-align:center;font-size:14px"><?= "R$ ".$demons['valor_extra_rep']; ?></td>
					<td style="text-align:center;font-size:14px"><?= number_format($demons['valor_rep'],2) ?></td>
					<td style="text-align:center;font-size:14px"><?= number_format($total,2) ?></td>
				</tr>

			<?php
				}
				// VERIFICACAO DO DIGITO DA AGENCIA
				$dig="";
				if ($pagamento['ag_digito']==""){
					$dig="";
				}else{
					$dig="-".$pagamento['ag_digito'];
				}


				// VERIFICACAO DO DIGITO DA CONTA
				$digCC="";
				if ($pagamento['cc_digito']==""){
					$digCC="";
				}else{
					$digCC="-".$pagamento['cc_digito'];
				}

				// CASO NAO TENHA FORMA DE PAGAMENTO CADASTRADA
				if ($pagamento['banco']=='' && $cheque['num_cheque']==''){
					$formaRec = "Nenhum";
					// CASO TENHA ALGUMA FORMA DE PAGAMENTO, VERIFICA QUAL É.
				}else{
					// VERIFICA QUAL A FORMA DE PAGAMENTO DO PRESTADOR
					if ($pagamento['banco']==""){
						$formaRec = "Cheque: ".$cheque['num_cheque'];
					}else{
						$formaRec = $pagamento['banco']." ".$pagamento['agencia'].$dig." / ".$pagamento['conta'].$digCC;
					}
				}

				// TOTAL DA FOLHA
				
				$pagas = "R$ ".number_format($pagamentoTotal,2);
				$inss = "R$ ".number_format($pagamentoTotal/100*11, 2);

				// VALOR DO INSS SEM O SIMBOLO DE R$
				$vl_inss = ($pagamentoTotal/100*11);




				// CALCULO DE IMPOSTO DE RENDA DE ACORDO COM O VALOR
				$ir ="00";
				if ($pagamentoTotal >= "1903.99"){
					$ir = ($pagamentoTotal/100)*7.5;
				} if($pagamentoTotal <= "2826.65"){
					$ir = ($pagamentoTotal/100)*7.5;
				} if($pagamentoTotal >= "2826.66"){
					$ir = ($pagamentoTotal/100)*15;
				} if($pagamentoTotal <= "3751.05"){
					$ir = ($pagamentoTotal/100)*15;
				}  if($pagamentoTotal >= "3751.06"){
					$ir = ($pagamentoTotal/100)*22.5;
				} if($pagamentoTotal <= "4664.68"){
					$ir = ($pagamentoTotal/100)*22.5;
				} if($pagamentoTotal >= "4664.68"){
					$ir = ($pagamentoTotal/100)*27.5;
				} if($pagamentoTotal <= "1903.98"){
					$ir = "0";
				}

				// VALOR DO DESCONTO
					$desconto = $desc['total'];
					$formatDesconto = "R$ ".number_format($desconto,2);

				// VALOR TOTAL APÓS OS DESCONTOS
					$vl_total = ($pagamentoTotal - $vl_inss - $ir - $desconto)	;
					
			?>


			<tr>
				<td style='border:none' colspan='8'>&nbsp;</td>
			</tr>

			<tr>
				<td style='border:none;border-bottom:2px solid #000' colspan='8'>&nbsp;</td>
			</tr>

			<tr>
				<td colspan='2'><b>Forma de Recebimento : <?= $formaRec ?></b></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style='text-align: left!important' colspan='2'><b>Total de Pagas:</b> </td>
				<td colspan='2' style='text-align:right!important'><?= $pagas ?></td>
			</tr>

			<tr>
				<td colspan='2'>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style='text-align:left!important' colspan='2'><b>INSS:</b></td>
				<td colspan='2' style='text-align:right!important'><?= $inss ?></td>
			</tr>

			<tr>
				<td colspan='2'><?= "SÃO PAULO, ".date('d')." ".$mes." "."DE"." ".date('Y') ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style='text-align:left!important' colspan='2'><b>IR:</b></td>
				<td colspan='2' style='text-align:right!important'><?= "R$ ".number_format($ir,2) ?></td>
			</tr>

			<tr>
				<td style='border-bottom:2px solid #000' colspan='2'>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style='text-align:left!important' colspan='2'><b>Desconto:</b> </td>
				<td colspan='2' style='text-align:right!important'><?= $formatDesconto ?></td>
			</tr>

			<tr>
				<td colspan='2'><?= strtoupper($prest->NomePrestador($row['id_prestador'])) ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style='text-align:left!important' colspan='2'><b>Total:</b></td>
				<td colspan='2' style='text-align:right!important'><?= number_format($vl_total,2) ?></td>
			</tr>
	</table>

<?php
	}
?>


	<script src="../js/bootstrap.min.js"></script>
</body>
</html>


