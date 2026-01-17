
<?php
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
</style>

<body>
	
	<div class="cx-demonstrativo">
		<div class="logo_dm">
			<img src="../../img/logo.png" height="77" width="120" alt="">
		</div>
			
			<h4 class="titulo-dm">Demonstrativo de Pagamento</h4>

			<h5 class="lote-dm"><b>Lote:</b> <?= strtoupper($_GET['Lote']) ?></h5>

		<div class="dados-dm">
			<h5 class="txt-tit">Prestador:</h5>
				<h5 class="prest"><?= $_GET['Prestador']." - ".$prest->NomePrestador($_GET['Prestador']) ?></h5>

				<table style="float:left;margin-top:20px;" width="100%" class="table table-striped">
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
	if (isset($_GET['Prestador'])){
		$pagamentoTotal =0;
		$class->BuscaLotePrestador($_GET['Lote'], $_GET['Prestador']);
		while($row = mysqli_fetch_assoc($class->BuscaLotePrestador)){


		/* 
		=====================================================
		VALOR TOTAL A RECEBER 
		(HORAS DE TRABALHO * VALOR DA HORA)
		*/

		// CUSTO DA HORA DE TRABALHO
		$ValorDaHora = $row['vl_repasse']/$row['horas_func'];
		$horasTrabalhadas = $row['saida'] - $row['entrada'];

		// CALCULO DO EXTRA
		// $extra = $ValorDaHora / 2;
		// $totalExtra = ($extra * $row['extra']);

		$extra = $row['valor_extra_rep'];


		//$repasse = $ValorDaHora*$horasTrabalhadas;
		$total = $row['valor_rep'] + $extra;
		$pagamentoTotal += $total;


		// ===================================================



		// BUSCA O VALOR TOTAL DE DESCONTOS PENDENTES AO PRESTADOR
		$class->TotalDesconto($row['data_evento'], $_GET['Prestador'], $_GET['Lote']);
		$desc = mysqli_fetch_assoc($class->TotalDesconto);


		// BUSCA A FORMA DE PAGAMENTO (CONTA) DO PRESTADOR
		$class->TipoPagamento($_GET['Prestador']);
		$pagamento = mysqli_fetch_assoc($class->TipoPagamento);



		// BUSCA OS CHEQUES COMO FORMA DE PAGAMENTO

		if ($pagamento['banco']==""){
			$class->ListaChequesID($_GET['Prestador']);
			$cheque = mysqli_fetch_assoc($class->ListaChequesID);
		}


		?>

					<tr>
						<td style="text-align:center"><?= $class->FormataData($row['data_evento']) ?></td>
						<td style="text-align:center"><?= $row['entrada'] ?></td>
						<td style="text-align:center"><?= $row['saida'] ?></td>
						<td style="text-align:center"><?= $row['id_funcao'] ?></td>
						<td style="text-align:center"><?= $row['extra'] ?></td>
						<td style="text-align:center"><?= "R$ ".$row['valor_extra_rep'] ?></td>
						<td style="text-align:center"><?= "R$ ".number_format($row['valor_rep'],2) ?></td>
						<td style="text-align:center"><?= "R$ ".number_format($total,2) ?></td>
					</tr>
		<?php

	}
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
?>
					

				</table>


				<hr style="float:left;border:1px solid #000;width:799px;margin:80px 0px 0px 0px">

				<div class="cx-final">
					<?php
					// CASO NAO TENHA FORMA DE PAGAMENTO CADASTRADA
					if ($pagamento['banco']=='' && $cheque['num_cheque']==''){
						?>
							<script type="text/javascript">
								alert('Este Prestador não tem nenhuma forma de pagamento!');
							</script>
							<h5 class="txt-recebimento"><b>Forma de Recebimento: </b>Nenhum</h5>
						<?php
						// CASO TENHA ALGUMA FORMA DE PAGAMENTO, VERIFICA QUAL É.
					}else{

						// VERIFICA QUAL A FORMA DE PAGAMENTO DO PRESTADOR
						if ($pagamento['banco']==""){
					?>
						<h5 class="txt-recebimento"><b>Forma de Recebimento: </b>Cheque</h5>
						<h5 class="txt-recebimento2"><?= "Numeração: ".$cheque['num_cheque'] ?></h5>
					<?php
						}else{
					?>
						<h5 class="txt-recebimento"><b>Forma de Recebimento: </b></h5>
						<h5 class="txt-recebimento2"><?= $pagamento['banco']." ".$pagamento['agencia'].$dig." / ".$pagamento['conta'].$digCC ?></h5>
					<?php
						}
					}
					?>
					<h4 class="txt-data"><?= "SÃO PAULO, ".date('d')." ".$mes." "."DE"." ".date('Y') ?></h4>

					<hr style="width:300px;margin:40px 0px 0px 20px;border:1px solid #000">
					
					<h4 class="txt-nome-ass"><?= $prest->NomePrestador($_GET['Prestador']) ?></h4>

				</div>

<?php
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
				<div class="totais">
					<div class="divisao">
						<h4 class="txt-totais">Total de Pagas:</h4>

						<h4 class="txt-val"><?= $pagas ?></h4>
					</div>

					<div class="divisao">
						<h4 class="txt-totais">INSS:</h4>

						<h4 class="txt-val"><?= $inss ?></h4>
					</div>

					<div class="divisao">
						<h4 class="txt-totais">IR:</h4>

						<h4 class="txt-val"><?= "R$ ".number_format($ir,2) ?></h4>
					</div>

					<div class="divisao">
						<h4 class="txt-totais">Desconto:</h4>

						<h4 class="txt-val"><?= $formatDesconto ?></h4>
					</div>

					<div style="border:none" class="divisao">
						<h4 class="txt-totais">Total:</h4>

						<h4 class="txt-val"><?= number_format($vl_total,2) ?></h4>
					</div>
				</div>
		</div>
	</div>


	<script src="../js/bootstrap.min.js"></script>
</body>
</html>


