<?php
	// SELECIONA QUAIS SETORES A BUSCA VAI SELECIONAR		 	
			$valores="";
			if (isset($_POST['setor'])){
				$vt = $_POST['setor'];
				foreach ($vt as $in => $valor) {
					$valores .= " - ".$set->NomeSetor($valor);
				}
			}
?>

<div class="vis-fat">
	<h4 style="margin:20px 0px 0px 0px;font-weight:normal;font-family:tahoma"><b>Cliente:</b> <?= $cl->BuscaCliente($_GET['Cod']) ?></h4>

	<h4 style="margin:20px 0px 0px 0px;font-weight:normal;font-family:tahoma">
		<b>Período:</b> 
		<?= "De: ".$class->FormataData($_POST['de'])." ~ "."Até: ".$class->FormataData($_POST['ate']) ?>
	</h4>

	<h4 style="margin:20px 0px 0px 0px;font-weight:normal;font-family:tahoma">
		<b>Setor:</b> <?php print($valores); ?>
	</h4>
</div>


<div class="imp-fat">
			<div class="logo_dm">
				<img src="../img/logo.png" height="77" width="120" alt="">
			</div>

			<h4 class="rel-fat-txt">Relatório de Faturamento</h4>

			<br>

			<h4 style="margin:30px 0px 0px 30px;font-weight:normal;font-family:tahoma"><b>Cliente:</b> <?= $cl->BuscaCliente($_GET['Cod']) ?></h4>

			<h4 style="margin:20px 0px 0px 30px;font-weight:normal;font-family:tahoma">
				<b>Período:</b> 
				<?= "De: ".$class->FormataData($_POST['de'])." ~ "."Até: ".$class->FormataData($_POST['ate']) ?>
			</h4>

			<h4 style="margin:20px 0px 0px 30px;font-weight:normal;font-family:tahoma">
				<b>Setor:</b> <?php print($valores); ?>
			</h4>

		</div>




<table style="margin-top:30px;font-size:12px" class="table table-striped tabela-fat-rep">
	<tr>
		<td style="text-aling:center;font-weight:bold">ID</td>
		<td style="text-aling:center;font-weight:bold">Prestador</td>
		<td style="text-aling:center;font-weight:bold">Função</td>
		<td style="text-aling:center;font-weight:bold">Dt. Evento</td>
		<td style="text-aling:center;font-weight:bold">Entrada</td>
		<td style="text-aling:center;font-weight:bold">Sáida</td>
		<td style="text-aling:center;font-weight:bold">Extra</td>
		<td style="text-aling:center;font-weight:bold">Valor</td>		
		<td style="text-aling:center;font-weight:bold">Vl. Extra</td>
		<td style="text-aling:center;font-weight:bold">Solic.</td>
		<td style="text-aling:center;font-weight:bold">Setor</td>
	</tr>

<?php
	$class->DadosFaturamento($_GET['Cod'], $_POST['de'], $_POST['ate']);
	$faturamento=0;
	$cont=0;
	while ($row_fat=mysqli_fetch_assoc($class->DadosFaturamento)){
	$cont++;

	// // CALCULO DE HORA EXTRA
	// $ValorDaHora = $row_fat['repasse']/($row_fat['saida']-$row_fat['entrada']);
	// $extra = $ValorDaHora * $row_fat['extra'];
	// $totalExtra = $extra + ($extra/2);
	// $repasse = $row_fat['repasse'];
	// $total = ($extra+$repasse);

	// HORA EXTRA
		$vtEx = explode(":", $row_fat['extra']);
		$extra = $vtEx[0].'.'.$vtEx[1];

	// CALCULO DA HORA EXTRA (FATURAMENTO)
		$ValorHoraExtra = ($row_fat['vl_faturamento'] / $row_fat['horas_func']) / 2;
		$finalExtra = $row_fat['extra_fat'];


	// CALCULO DE VALOR TOTAL DE FATURAMENTO
		$horasTrabalhadas = $row_fat['saida']-$row_fat['entrada'];
		$faturamento += $row_fat['faturamento'] + $finalExtra;



?>
	<tr>
		<td><?= $row_fat['prestador'] ?></td>
		<td><?= strtoupper($pr->NomePrestador($row_fat['prestador'])) ?></td>
		<td><?= $fc->NomeFuncao($row_fat['funcao']) ?></td>
		<td><?= $class->FormataData($row_fat['data_evento']) ?></td>
		<td><?= $row_fat['entrada'] ?></td>
		<td><?= $row_fat['saida'] ?></td>
		<td><?= $row_fat['extra'] ?></td>
		<td><?= "R$ ".number_format($row_fat['faturamento'],2) ?></td>
		<td><?= "R$ ".$finalExtra ?></td>
		<td><?= $row_fat['escala'] ?></td>
		<td><?= $set->NomeSetor($row_fat['setor']) ?></td>
	</tr>

	

<?php
	}


	 // MONTA UMA URL PARA REDIRECIONAR PARA A PAGINA DE IMPRESSAO


	 $url="?funcao=FaturamentoRepasse";
	 if (empty($_POST['de'])){
	 	$url="?funcao=FaturamentoRepasse";
	 }else{
	 	$url = 'financeiro/faturamento.php?Cod='.$_GET['Cod'].'&De='.$_POST['de'].'&ate='.$_POST['ate'].'';
	}
?>

	<tr>
		<td colspan="3"><b>Dt. Impressão:</b> <?= date('d/m/Y') ?></td>
		<td colspan="3"><b>Total Serviços:</b> <?= $cont ?></td>
		<td colspan="5"><b>Faturamento :</b> <?= "R$ ".number_format($faturamento,2) ?></td>
	</tr>

</table>

<div class="form-actions">
		<button onClick="window.location.href='?funcao=FaturamentoRepasse'" style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Voltar</button>		
	<?php
		 if ($cont>0){
	?>
		<button onClick="imprimir('<?= $url ?>');" style='float:right;margin-right:10px' type="submit" class="btn btn-success">Imprimir <u class="icon-print icon-white"></u></button>		  
	
	<?php
		}
	?>

	<a href="financeiro/fat-excell.php?funcao=FaturamentoRepasse&Cod=<?= $_GET['Cod'] ?>&de=<?= $_POST['de'] ?>&ate=<?= $_POST['ate'] ?>">
		<button style='float:right;margin-right:20px;margin-top:0px' class="btn btn-success">
			<u class="icon-align-center icon-white"></u>
			Exportar Excell
		</button>
	</a>
</div>