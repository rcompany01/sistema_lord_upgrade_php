<!- TABELA DE ENTRADA/SAIDA E ASSINATURA COM OS CAMPOS EM BRANCO ->


<table style="font-size:12px" id="secondTable" class="table table-striped">
			<tr>
				<td>Escala</td>
				<td>ID</td>
				<td>Prestador</td>
				<td>Função</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>Entrada</td>
				<td>&nbsp;</td>
				<td>Saída</td>
				<td>&nbsp;</td>
				<td>Assinatura</td>
			</tr>
<?php
	// LISTA OS PRESTADORES ADICIONADOS
	$fat=0;
	$rep=0;
	$class->ListaPrestadorEscala($_GET['idEsc']);
	while ($list=mysqli_fetch_assoc($class->ListaPrestadorEscala)){

		// HORA EXTRA
			$vtEx = explode(":", $list['extra']);
			$extra = $vtEx[0].'.'.$vtEx[1];

		// CALCULO DA HORA EXTRA (FATURAMENTO)
			$ValorHoraExtra = ($list['vl_faturamento'] / $list['horas_func']) / 2;
			$finalExtra = $ValorHoraExtra * $extra;

		// CALCULO DA HORA EXTRA (REPASSE)
			$ValorHoraExtraRep = ($list['vl_repasse'] / $list['horas_func']) / 2;
			$finalExtraRep = $ValorHoraExtraRep * $extra;

		// CALCULA AS HORAS TRABALHADAS E MOSTRA O VALOR PROPORCIONAL (FATURAMENTO)
		$horasTrabalhadas = $list['saida'] - $list['entrada'];
		$valorHora = $list['vl_faturamento'] / $list['horas_func']; 
		$valorFinal = ($valorHora * $horasTrabalhadas) + $finalExtra;

		// CALCULA AS HORAS TRABALHADAS E MOSTRA O VALOR PROPORCIONAL (REPASSE)
		$horasTrabalhadasRep = $list['saida'] - $list['entrada'];
		$valorHoraRep = $list['vl_repasse'] / $list['horas_func'];
		$valorFinalRep = ($valorHoraRep * $horasTrabalhadasRep) + $finalExtraRep;

		// FATURAMENTO / REPASSE
		$fat += $valorFinal;
		$rep += $valorFinalRep;
		
?>
			<tr style="height:32.5px">
				<td style="line-height:32.5px;font-size:17px"><?= $_GET['idEsc'] ?></td>
				<td style="line-height:32.5px;font-size:17px"><?= $list['id_prestador'] ?></td>
				<td style="line-height:32.5px;font-size:17px"><?= strtoupper($class->NomePrestadorID($list['id_prestador'])).' <br> '.$list['entrada']; ?></td>
				<td style="line-height:32.5px;font-size:17px"><?= $class->NomeFuncaoID($list['id_funcao']) ?></td>
				<td style="line-height:32.5px;font-size:17px">&nbsp;</td>
				<td style="line-height:32.5px;font-size:17px">&nbsp;</td>
				<td style="line-height:32.5px;font-size:17px" class="empty-td"></td>
				<td style="line-height:32.5px;font-size:17px">&nbsp;</td>
				<td style="line-height:32.5px;font-size:17px" class="empty-td"></td>
				<td style="line-height:32.5px;font-size:17px">&nbsp;</td>
				<td style="line-height:32.5px;font-size:17px" class="empty-td-ass"></td>
			</tr>

			
<?php
	}
?>
			<!-- <tr>
				<td colspan="1">&nbsp;</td>
				<td id="col-val" colspan="1"><b>Total Faturamento:</b> <?= "R$ ".number_format($fat,2) ?></td>
				<td style="display:none" id="col-valB" colspan="3">&nbsp;</td>
				<td id="col-val2" colspan="5"><b>Total Repasse:</b> <?= "R$ ".number_format($rep,2) ?></td>
				<td style="display:none" id="col-val2B" colspan="5">&nbsp;</td>
				<td colspan="3">&nbsp;</td>
			</tr> -->

		</table>

<!- FIM TABELA ENTRADA/SAIDA ASSINATURA ->