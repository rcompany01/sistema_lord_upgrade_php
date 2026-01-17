<script language="JavaScript">
function imprimir(URL) {
 
  var width = 900;
  var height = 550;
 
  var left = 200;
  var top = 20;
 
  window.open(URL,'janela', 'width='+width+', height='+height+', top='+top+', left='+left+', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');

}
</script>


<?php
	// FAZ A CONFIRMAÇÃO PARA PAGAR O RECIBO
	error_reporting(E_ALL);
	ini_set("display_errors", 0);
?>
    <script type="text/javascript">
    	function pagar(id,esc) {
		     var resposta = confirm("Pagar este recibo?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=ImprimirRecibos&PagarRecibo="+id+"&Escala="+esc;
		     }
		}
    </script>


<?php
	// INSTANCIA A CLASSE
	require_once("class/financeiro.class.php");
	$class = new Financeiro;


	// BUSCA OS PRESTADORES POR LOTE, E LISTA OS VALORES A RECEBER
	if (isset($_POST['lote'])){
		$class->BuscaLoteImpressao($_POST['lote']);
	}

	// PEGA O NOME DO PRESTADOR
	require_once("prestadores/class/prestadores.class.php");
	$prest = new Prestadores;


	// PEGA O NOME DA FUNÇÃO
	require_once("clientes/class/funcoes.class.php");
	$func = new Funcoes;


	// PÁGINA DE IMPRESSÃO
		if (isset($_GET['Prestador']) && isset($_GET['Lote'])){
			require_once("demonstrativo.php");
		}else{

	
?>


<div class="cx-baixa-escala">
	<div id='formulario-baixa-escala' class="formulario-baixa-escala">
		<form class="form-inline" method="post">
			<fieldset style="margin-top:30px">
				<legend>Imprimir Recibos</legend>

				<label style="margin:0px 0px 0px 33px" class="control-label">Lote:</label>
					<input class="input-medium" style="margin:0px 0px 0px 0px;text-transform:uppercase" required name="lote" type="text"> <br>

				</fieldset>



				<div class="form-actions">
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Buscar</button>			  
				</div>

		</form>

<?php
	// CASO O CAMPO SEJA ENVIADO, A TABELA É MOSTRADA
	if (!empty($_POST['lote'])){
?>	
	
	<form method="post">
		<input type="hidden" name="lote" value="<?= $_POST['lote'] ?>">
		<input type="hidden" name="print" value="all">
		<button onClick="imprimir('financeiro/PrintAll.php?Lote=<?= $_POST['lote'] ?>')" class="btn btn-primary"><u class="icon-print icon-white"></u> 
			Imprimir Todos
		</button>
	</form>
		
		<table style="margin-top:20px" class="table table-striped">
			<tr>
				<td>Status Pagamento</td>
				<td style="text-align:center">ID Prestador</td>
				<td style="text-align:center">Prestador</td>
				<td style="text-align:center">ID Função</td>
				<td style="text-align:center">Função</td>
				<td style="text-align:center">Lote</td>
				<td style="text-align:center">Imprimir</td>				
			</tr>
			
			<?php
				// BUSCA O LOTE
				while($row=mysqli_fetch_assoc($class->BuscaLoteImpressao)){
					// PEGA O ID DO DESCONTO
					$class->TotalDesconto($row['data_evento'], $row['id_prestador'], $row['lote']);
					$pgt = mysqli_fetch_assoc($class->TotalDesconto);

					// BUSCA OS VALORES DE CADA FUNCAO
					$class->BuscaValoresFuncao($row['id_funcao'], $row['id_cliente']);
					$vl = mysqli_fetch_assoc($class->BuscaValoresFuncao);


					// BUSCA A FORMA DE PAGAMENTO (CONTA) DO PRESTADOR
					
					$class->TipoPagamento($row['id_prestador']);
					$pagamento = mysqli_fetch_assoc($class->TipoPagamento);

					$forma="";
					if (empty($pagamento['banco'])){
						$forma="Cheque";
					}else{
						$forma="Conta";
					}



					// BUSCA MES E ANO DE REFERENCIA DO LOTE
						$class->DataRef($row['lote']);
						$ref = mysqli_fetch_assoc($class->DataRef);

					// BUSCA O VALOR DESCONTADO (CASO TENHA)
						$valorDesc="0";
						$class->TotalDescontado($row['data_evento'], $row['id_prestador'], $row['lote']);
						$desc = mysqli_fetch_assoc($class->TotalDescontado);

						if (!empty($desc['total'])){
							$valorDesc = $desc['total'];
						}

					// FAZ O CALCULO PROPORCIONAL DO PAGAMENTO DO PRESTADOR

					// HORA EXTRA
						$vtEx = explode(":", $row['extra']);
						$extra = $vtEx[0].'.'.$vtEx[1];

					// CALCULO DA HORA EXTRA (FATURAMENTO)
						$ValorHoraExtra = ($row['vl_faturamento'] / $row['horas_func']) / 2;
						$finalExtra = $ValorHoraExtra * $extra;

					// CALCULO DA HORA EXTRA (REPASSE)
						$ValorHoraExtraRep = ($row['vl_repasse'] / $row['horas_func']) / 2;
						$finalExtraRep = $ValorHoraExtraRep * $extra;

					// CALCULA AS HORAS TRABALHADAS E MOSTRA O VALOR PROPORCIONAL (FATURAMENTO)
						$horasTrabalhadas = $row['saida'] - $row['entrada'];
						$valorHora = $row['vl_faturamento'] / $row['horas_func']; 
						$valorFinal = ($valorHora * $horasTrabalhadas) + $finalExtra;

					// CALCULA AS HORAS TRABALHADAS E MOSTRA O VALOR PROPORCIONAL (REPASSE)
						$horasTrabalhadasRep = $row['saida'] - $row['entrada'];
						$valorHoraRep = $row['vl_repasse'] / $row['horas_func'];
						$valorFinalRep = ($valorHoraRep * $horasTrabalhadasRep) + $finalExtraRep;


					$status_pgto="";
					if ($row['status_pag']=='0'){
						$status_pgto="<img src='../img/b1.png'>";
					}else{
						$status_pgto="<img src='../img/b2.png'>";
					}




			?>
				<tr>

					<td style="text-align:center">
						<?= $status_pgto ?>
					</td>
					<td style="text-align:center"><?= $row['id_prestador'] ?></td>
					<td style="text-align:center"><?= strtoupper($prest->NomePrestador($row['id_prestador'])) ?></td>
					<td style="text-align:center"><?= $row['id_funcao'] ?></td>
					<td style="text-align:center"><?= $func->NomeFuncao($row['id_funcao']) ?></td>
					<td style="text-align:center"><?= strtoupper($row['lote']) ?></td>
					<td style="text-align:center">
						<b onClick="imprimir('financeiro/demonstrativo.php?Prestador=<?= $row['id_prestador'] ?>&Lote=<?= $row['lote'] ?>&ValFunc=<?= $vl['id'] ?>');" style="cursor:pointer" class="icon-print"></b>
					</td>
					
<!-- 					<td>

						<button onClick="window.location.href='?funcao=ImprimirRecibos&PagarRecibo=<?= $row['id_prestador'] ?>&Escala=<?= $row['id_escala'] ?>&idCliente=<?= $row['id_cliente'] ?>&DataEvento=<?= $row['data_evento'] ?>&Entrada=<?= $row['entrada'] ?>&Saida=<?= $row['saida'] ?>&Extra=<?= $row['extra'] ?>&Funcao=<?= $row['id_funcao'] ?>&Setor=<?= $row['setor'] ?>&LotePrest=<?= $row['lote'] ?>&Faturamento=<?= $valorFinal ?>&Repasse=<?= $valorFinalRep ?>&Forma=<?= $forma ?>&Mes=<?= $ref['mes_ref'] ?>&Ano=<?= $ref['ano_ref'] ?>&Desc=<?= $valorDesc ?>&DescricaoDesc=<?= $desc['descricao_desc'] ?>'" class="btn btn-success">Pagar</button>

					</td> -->
				</tr>
			<?php
				}
			?>
		</table>
<?php
	} 
?>
	</div>
</div>

<?php
	}
?>
