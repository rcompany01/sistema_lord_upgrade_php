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
		$class->BuscaLote($_POST['lote']);
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
				<legend>Pagamento de Prestadores</legend>

				<label style="margin:0px 0px 0px 33px" class="control-label">Lote:</label>
					<input class="input-medium" style="margin:0px 0px 0px 0px;text-transform:uppercase" required name="lote" type="text"> <br>

				</fieldset>



				<div class="form-actions">
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Buscar</button>			  
				</div>

		</form>

<?php
	// FAZ O PAGAMENTO DO PRESTADOR
	if (isset($_GET['PagarRecibo'])){
		$class->PagarRecibo(	$_GET['PagarRecibo'], 
								$_GET['Escala'], 
								$_GET['idCliente'], 
								$_GET['DataEvento'], 
								$_GET['Entrada'], 
								$_GET['Saida'], 
								$_GET['Extra'], 
								$_GET['Funcao'],
								$_GET['Setor'],
								$_GET['LotePrest'], 
								$_GET['Faturamento'],
								$_GET['Repasse'], 
								$_GET['Forma'], 
								$_GET['Mes'], 
								$_GET['Ano'], 
								$_GET['Desc'],
								$_GET['DescricaoDesc'],
								$_GET['ExtraFat'],
								$_GET['ExtraRep'],
								$_GET['EscalaPrest']);


		}


	// CASO O CAMPO SEJA ENVIADO, A TABELA É MOSTRADA
	if (!empty($_POST['lote'])){
?>	
	


	<?php
		// PAGA TODOS OS PRESTADORES DAQUELE LOTE
		if (!empty($_POST['pgto'])){
			// BUSCA O LOTE
			while($row=mysqli_fetch_assoc($class->BuscaLote)){
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

				

					$faturamentoPgto = $row['valor_fat'];
					$repassePgto = $row['valor_rep'];

					$class->PagarReciboTotal(	$row['id_prestador'], 
												$row['id_escala'], 
												$row['id_cliente'], 
												$row['data_evento'], 
												$row['entrada'], 
												$row['saida'], 
												$row['extra'], 
												$row['id_funcao'],
												$row['setor'],
												$row['lote'], 
												$faturamentoPgto,
												$repassePgto, 
												$forma, 
												$ref['mes_ref'], 
												$ref['ano_ref'], 
												$valorDesc,
												$desc['descricao_desc'],
												$row['valor_extra_fat'],
												$row['valor_extra_rep'],
												$row['id']);
			}

			echo "<script>
					alert('Prestadores Pagos');
					window.location.href='?funcao=ImprimirRecibos';
				</script>";
		}
	?>

	<form method="post">
		<input type="hidden" name="lote" value="<?= $_POST['lote'] ?>">
		<input type="hidden" name="pgto" value="pay">
		<button class="btn btn-success">
			Pagar Todos
		</button>
	</form>

		<table style="margin-top:20px" class="table table-striped">
			<tr>
				<td style="text-align:center">ID Prestador</td>
				<td style="text-align:center">Prestador</td>
				<td style="text-align:center">ID Função</td>
				<td style="text-align:center">Função</td>
				<td style="text-align:center">Lote</td>
				<!-- <td style="text-align:center">Imprimir</td> -->
				<td>Ações</td>
			</tr>
			
			<?php
				// BUSCA O LOTE
				while($row=mysqli_fetch_assoc($class->BuscaLote)){
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

						// EXTRA DO FATURAMENTO
					 	$extraFat = $row['valor_extra_fat'];

					 	// EXTRA DO FATURAMENTO
					 	$extraRep = $row['valor_extra_rep'];

						// VALOR DO FATURAMENTO
					 	$valorFinal = $row['valor_fat'] + $extraFat;

					 	// VALOR DO REPASSE
					 	$valorFinalRep = $row['valor_rep'] + $extraRep;




			?>
				<tr>
					<td style="text-align:center"><?= $row['id_prestador'] ?></td>
					<td style="text-align:center"><?= $prest->NomePrestador($row['id_prestador']) ?></td>
					<td style="text-align:center"><?= $row['id_funcao'] ?></td>
					<td style="text-align:center"><?= $func->NomeFuncao($row['id_funcao']) ?></td>
					<td style="text-align:center"><?= strtoupper($row['lote']) ?></td>
					<td>

						<button onClick="window.location.href='?funcao=ImprimirRecibos&PagarRecibo=<?= $row['id_prestador'] ?>&Escala=<?= $row['id_escala'] ?>&idCliente=<?= $row['id_cliente'] ?>&DataEvento=<?= $row['data_evento'] ?>&Entrada=<?= $row['entrada'] ?>&Saida=<?= $row['saida'] ?>&Extra=<?= $row['extra'] ?>&Funcao=<?= $row['id_funcao'] ?>&Setor=<?= $row['setor'] ?>&LotePrest=<?= $row['lote'] ?>&Faturamento=<?= $valorFinal ?>&Repasse=<?= $valorFinalRep ?>&Forma=<?= $forma ?>&Mes=<?= $ref['mes_ref'] ?>&Ano=<?= $ref['ano_ref'] ?>&Desc=<?= $valorDesc ?>&DescricaoDesc=<?= $desc['descricao_desc'] ?>&ExtraFat=<?= $row['valor_extra_fat'] ?>&ExtraRep=<?= $row['valor_extra_rep'] ?>&EscalaPrest=<?= $row['id'] ?>'" class="btn btn-success">Pagar</button>

					</td>
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
