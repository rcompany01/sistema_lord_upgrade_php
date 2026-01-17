<!- TABELA DE ASSINATURA COM O CAMPO EM BRANCO ->


<table style="font-size:11px" id="thirdTable" class="table table-striped">
			<tr>
				<td>ID Solicitação</td>
				<td>ID Prest.</td>
				<td>Prestador</td>
				<td>Dt Evento</td>
				<td>Hr. Entrada</td>
				<td>Hr. Saida</td>
				<td>Hr. Extra</td>
				<td>ID Função</td>
				<td>Função</td>
				<td width="25%">Assinatura</td>
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
			<tr style="height:40px">
				<td style="line-height:40px;font-size:15px"><?= $list['id_escala'] ?></td>
				<td style="line-height:40px;font-size:15px"><?= $list['id_prestador'] ?></td>
				<td style="line-height:40px;font-size:15px"><?= strtoupper($class->NomePrestadorID($list['id_prestador'])) ?></td>
				<td style="line-height:40px;font-size:15px"><?= $class->FormataData($list['data_evento']) ?></td>
				<td style="line-height:40px;font-size:15px"><?= $list['entrada'] ?></td>
				<td style="line-height:40px;font-size:15px"><?= $list['saida'] ?></td>
				<td style="line-height:40px;font-size:15px"><?= $list['extra'] ?></td>
				<td style="line-height:40px;font-size:15px"><?= $list['id_funcao'] ?></td>
				<td style="line-height:40px;font-size:15px"><?= $class->NomeFuncaoID($list['id_funcao']) ?></td>
				<td>
					<div class="linha-ass"></div>
				</td>
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

<!- FIM TABELA ASSINATURA ->