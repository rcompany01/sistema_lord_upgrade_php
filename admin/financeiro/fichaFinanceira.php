<script type="text/javascript">
	function Ver(id){
		$("#base"+id).fadeIn('slow');
	}

	function Fechar(id){
		document.getElementById('base'+id).style.display="none";
		$("#base"+id).fadeOut('slow');
	}

	function previa(URL){
		var width = 850;
  		var height = 600;
 		
  		var left = 100;
  		var top = 20;
 	
  		window.open(URL,'janela', 'width='+width+', height='+height+', top='+top+', left='+left+',scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');

	}
</script>

<?php
	// FINANCEIRO
	require_once("class/financeiro.class.php");
	$class = new Financeiro;

	// FUNCOES
	require_once("clientes/class/funcoes.class.php");
	$func = new Funcoes;

	// PRESTADORES
	require_once("prestadores/class/prestadores.class.php");
	$prest = new Prestadores;

	// SETORES
	require_once("clientes/class/setores.class.php");
	$set = new Setores;

	$nomePrestador="";
	if (isset($_POST['cod_prest'])){
		$nomePrestador = $prest->NomePrestador($_POST['cod_prest']);
	}
?>

<h4 class="txt-cod">Código do Cooperado</h4>
<form method="post">
	<input name="cod_prest" style="float:left;margin:10px 0px 0px 0px" class="input-mini" type="text">
	<button style="margin:10px 0px 0px 10px;float:left" class="btn btn-primary">Ok</button>

	<h4 style="margin:15px 0px 0px 100px;float:left;color:#2A7BBC;text-decoration:underline"><?= $nomePrestador ?></h4>
</form>

<div class="tabela-financeira">
	<table style="margin:0px 0px 0px 0px;float:left" class="table table-striped">
		<tr>
			<td style="text-align:center;font-weight:bold;color:#2A7BBC">Cliente</td>
			<td style="text-align:center;font-weight:bold;color:#2A7BBC">Lote</td>
			<td style="text-align:center;font-weight:bold;color:#2A7BBC">Data do Evento</td>
			<td style="text-align:center;font-weight:bold;color:#2A7BBC">Local</td>
			<td style="text-align:center;font-weight:bold;color:#2A7BBC">Ver</td>
		</tr>

<?php
	// BUSCA A FICHA FINANCEIRA DE ACORDO COM O ID DO PRESTADOR
	if (isset($_POST['cod_prest'])){
		$class->FichaFinanceira($_POST['cod_prest']);
		while($row=mysqli_fetch_assoc($class->FichaFinanceira)){
?>
		<tr>
			<td style="text-align:center"><?= $class->BuscaNomeCliente($row['cliente']) ?></td>
			<td style="text-align:center"><?= strtoupper($row['lote']) ?></td>
			<td style="text-align:center"><?= strtoupper($class->FormataData($row['data_evento'])) ?></td>
			<td style="text-align:center"><?= strtoupper($set->NomeSetor($row['setor'])) ?></td>
			<td style="text-align:center">				
				<button onClick="previa('financeiro/demonstrativo.php?Prestador=<?= $row['prestador'] ?>&Lote=<?= $row['lote'] ?>')" class="btn btn-primary">
					<u class="icon icon-search icon-white"></u>
				</button>
				<button onClick="Ver(<?= $row['escala'] ?>)" class="btn btn-success">+</button>
				<button onClick="Fechar(<?= $row['escala'] ?>)" class="btn btn-danger">-</button>
			</td>
		</tr>


<?php
	// BUSCA OS VALORES DO PRESTADOR, DE CADA EVENTO
	$class->BuscaLotePrestador($row['lote'], $_POST['cod_prest']);
	$vl = mysqli_fetch_assoc($class->BuscaLotePrestador);

	// BUSCA O VALOR TOTAL DE DESCONTOS PENDENTES AO PRESTADOR
	$class->TotalDescontado($row['data_evento'], $row['prestador'], $row['lote']);
	$desc = mysqli_fetch_assoc($class->TotalDescontado);

	// CALCULO DE HORA EXTRA
		$ValorDaHora = $row['total']/($row['saida']-$row['entrada']);
		$extra = $ValorDaHora * $row['extra'];
		$totalExtra = $extra + ($extra/2);
		$repasse = $row['total'];
		$total = ($extra+$repasse);

	// VALOR TOTAL $liquido
		$liquido = ($row['total']*0.11)+$row['total'];

		// TOTAL DA FOLHA	
		$pagas = "R$ ".number_format($row['total'],2);
		$inss = number_format($row['total']/100*11, 2);

		// VALOR DO INSS SEM O SIMBOLO DE R$
		$vl_inss = ($row['total']/100*11);

		// CALCULO DE IMPOSTO DE RENDA DE ACORDO COM O VALOR
			$ir ="00";
			if($liquido <= "1903.98"){
				$ir = "00";
			}else{
			if ($liquido >= "1903.99"){
				$ir = ($liquido*0.075)+142.80;

			} elseif($liquido <= "2826.65"){
				$ir = ($liquido*0.075)+142.80;

			} elseif($liquido >= "2826.66"){
				$ir = ($liquido*0.15)+354.80;

			} elseif($liquido <= "3751.05"){
				$ir = ($liquido*0.15)+354.80;

			}  elseif($liquido >= "3751.06"){
				$ir = ($liquido*0.225)+636.13;

			} elseif($liquido <= "4664.68"){
				$ir = ($liquido*0.225)+636.13;

			} elseif($liquido >= "4664.68"){
				$ir = ($liquido*0.275)+869.36;

			} 
		}

		// VALOR DO DESCONTO
		$desconto = $desc['total'];
		$formatDesconto = "R$ ".number_format($desconto,2);

		// VALOR TOTAL APÓS OS DESCONTOS
		$vl_total = ($total - $vl_inss - $ir - $desconto);

		switch ($row['mes_ref']) {
			case '1':
				$mes="Janeiro";
				break;

			case '2':
				$mes="Fevereiro";
				break;

			case '3':
				$mes="Março";
				break;

			case '4':
				$mes="Abril";
				break;

			case '5':
				$mes="Maio";
				break;

			case '6':
				$mes="Junho";
				break;

			case '7':
				$mes="Julho";
				break;

			case '8':
				$mes="Agosto";
				break;

			case '9':
				$mes="Setembro";
				break;

			case '10':
				$mes="Outubro";
				break;

			case '11':
				$mes="Novembro";
				break;

			case '12':
				$mes="Dezembro";
				break;
		
		}

?>
		<tr>
			<td colspan="8">
				<div id="base<?= $row['escala'] ?>" class="base-calculo">

					<h4 class="txt-base">Base de Cálculo do Recibo</h4>

					<h5 class="padrao-camp">Total das Pagas</h5>

					<h5 style="margin-left:60px" class="padrao-camp">INSS</h5>

					<h5 style="margin-left:110px" class="padrao-camp">IR</h5>

					<h5 style="margin-left:120px" class="padrao-camp">Descontos</h5>

					<h5 style="margin-left:76px" class="padrao-camp">Total</h5>

					<h5 style="margin-left:128px" class="padrao-camp">Recebimento</h5>

					<h5 style="margin-left:45px" class="padrao-camp">Mês Referente</h5>
				

					<div style="margin:0px 0px 0px 10px;float:left" class="input-prepend input-append">
						<span class="add-on">R$</span>
						 <input data-thousands="" data-decimal="." disabled value="<?= number_format($row['total'],2) ?>" required name="valor" id="valor" style='border-radius:0px 5px 5px 0px;' class="input-small" type="text">
					</div>

					<h4 style="float:left;margin:5px 0px 0px 15px">-</h4>

					<div style="margin:0px 0px 0px 18px;float:left" class="input-prepend input-append">
						<span class="add-on">R$</span>
						 <input disabled value="<?= $inss ?>" required name="valor" id="valor" style='border-radius:0px 5px 5px 0px;' class="input-mini" type="text">
					</div>

					<h4 style="float:left;margin:5px 0px 0px 15px">-</h4>

					<div style="margin:0px 0px 0px 18px;float:left" class="input-prepend input-append">
						<span class="add-on">R$</span>
						 <input disabled value="<?= number_format($ir,2) ?>" required name="valor" id="valor" style='border-radius:0px 5px 5px 0px;' class="input-mini" type="text">
					</div>

					<h4 style="float:left;margin:5px 0px 0px 15px">-</h4>

					<div style="margin:0px 0px 0px 13px;float:left" class="input-prepend input-append">
						<span class="add-on">R$</span>
						 <input disabled value="<?= $desconto ?>" required name="valor" id="valor" style='border-radius:0px 5px 5px 0px;' class="input-mini" type="text">
					</div>

					<h4 style="float:left;margin:5px 0px 0px 15px">=</h4>

					<div style="margin:0px 0px 0px 23px;float:left" class="input-prepend input-append">
						<span class="add-on">R$</span>
						 <input disabled value="<?= number_format($vl_total,2) ?>" required name="valor" id="valor" style='border-radius:0px 5px 5px 0px;' class="input-small" type="text">
					</div>

					<input value="<?= $row['recebimento'] ?>" disabled class="input-small" style="margin:0px 0px 0px 26px;float:left" type="text">

					<input value="<?= $mes." / ".$row['ano_ref'] ?>" disabled class="input-medium" style="margin:0px 0px 0px 30px;float:left" type="text">

						
				</div>
			</td>
		</tr>
<?php
	}
}
?>



		
	</table>
</div>
<?php
	// BUSCA TODOS OS DESCONTOS APLICADOS AO PRESTADOR
if (isset($_POST['cod_prest'])){
	$class->BuscaDescontosTotais($_POST['cod_prest']);
?>
	<div class="cx-totais-desc">
		<h4 class="txt-desc-total">Descontos</h4>

		<table class="table table-striped">
			<tr>
				<td style="text-align:center;color:#2A7BBC;font-weight:bold">Descrição</td>
				<td style="text-align:center;color:#2A7BBC;font-weight:bold">Valor</td>
				<td style="text-align:center;color:#2A7BBC;font-weight:bold">Dt. Solicitação</td>
				<td style="text-align:center;color:#2A7BBC;font-weight:bold">Descontado</td>
				<td style="text-align:center;color:#2A7BBC;font-weight:bold">Dt. Desconto</td>
				<td style="text-align:center;color:#2A7BBC;font-weight:bold">Lote</td>
			</tr>
		
		<?php
			while($descontos=mysqli_fetch_assoc($class->BuscaDescontosTotais)){
				$descricao="";
				if ($descontos['descricao_desconto']==""){
					$descricao = "Não Especificado";
				}else{
					$descricao = $descontos['descricao_desconto'];
				}
		?>
			<tr>
				<td style="text-align:center"><?= $descricao ?></td>
				<td style="text-align:center"><?= "R$ ".$descontos['desconto'] ?></td>
				<td style="text-align:center"><?= $class->FormataData($descontos['data_sol_desc']) ?></td>
				<td style="text-align:center"><?= "Sim" ?></td>
				<td style="text-align:center"><?= $class->FormataData($descontos['dia_desc']) ?></td>
				<td style="text-align:center"><?= strtoupper($descontos['lote']) ?></td>
			</tr>
		<?php
			}
		?>

		</table>
	</div>



	<div class="cx-totais-inss">
		<h4 class="txt-desc-total">INSS</h4>

		<table class="table table-striped">
			<tr>
				<td style="text-align:center;color:#2A7BBC;font-weight:bold">Lote</td>
				<td style="text-align:center;color:#2A7BBC;font-weight:bold">Valor</td>
			</tr>
		
		<?php
			$class->RepassePorLote($_POST['cod_prest']);
			while($totalInss=mysqli_fetch_assoc($class->RepassePorLote)){
				// CALCULO DE HORA EXTRA
				$ValorDaHora = $totalInss['total']/($totalInss['saida']-$totalInss['entrada']);
				$extra = $ValorDaHora * $totalInss['extra'];
				$totalExtra = $extra + ($extra/2);
				$repasse = $totalInss['total'];
				$total = ($extra+$repasse) - $totalInss['desconto'];
				$InssTotal = $total*0.11;
		?>
			<tr>
				<td style="text-align:center"><?= strtoupper($totalInss['lote']) ?></td>
				<td style="text-align:center"><?= "R$ ".number_format($InssTotal,2) ?></td>
			</tr>
		<?php
			}
		?>

		</table>
	</div>
<?php
	}
?>