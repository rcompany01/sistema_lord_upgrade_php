<?php
	// CASO A PAGINA NÃO TENHA RECEBIDO A SESSÃO, FAZ O REDIRECT PARA O FORMULARIO DE LOGIN
	if (!isset($_SESSION['autenticado'])){
		header("location: ../index.php");
	}

	// LOGOUT DA PAGINA
	if (isset($_POST['logout'])){
		session_destroy();
		header("location: ../index.php");
	}


	// FINANCEIRO
	require_once("financeiro/class/financeiro.class.php");
	$class = new Financeiro;

	// PRESTADORES
	require_once("prestadores/class/prestadores.class.php");
	$prest = new Prestadores;



	// EXCLUI O REGISTRO DE DESCONTO

		if (isset($_GET['DeleteDesc'])){
			$class->DeleteDesc($_GET['DeleteDesc']);
		}

	// FAZ A CONFIRMAÇÃO PARA DESATIVAR O PRESTADOR
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Remover Registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?DeleteDesc="+id;
		     }
		}
    </script>


		<div class="cx-recibos-pagos">
	
	<?php
		// INCLUI O FORMULARIO DE ATERAÇÃO DO DESCONTO
			if (isset($_GET['AlterarDesc'])){
				require_once("AlterarDesconto.php");
			}else{
	?>
			<div id="cx-funcoes" class="cx-funcoes"></a>
				<img style="cursor:pointer;margin-left:39px" id='imprimir' src="../img/bt_imprimir1.png" height="64" width="68" alt="">
			</div>


		<table class="table table-striped">
			<tr>
				<td style="text-align:center">ID Prestador</td>
				<td style="text-align:center">Prestador</td>
				<td style="text-align:center">Dt. Pagamento</td>
				<td style="text-align:center">Vlr. Recibo</td>
				<td style="text-align:center">Forma Receber</td>
				<td style="text-align:center">Nº Cheque</td>
				<td style="text-align:center">Lote</td>
				<td style="text-align:center">Status Pagamento</td>
			</tr>
			
			<?php
				$class->BuscaRecibosPagos();
				// BUSCA OS RECIBOS PAGOS
				while($row=mysqli_fetch_assoc($class->BuscaRecibosPagos)){
					$status_pgto="";
					if ($row['status_pag']=='0'){
						$status_pgto="<img src='../img/b1.png'>";
					}else{
						$status_pgto="<img src='../img/b2.png'>";
					}

				// BUSCA A FORMA DE PAGAMENTO (CONTA) DO PRESTADOR
					$class->TipoPagamento($row['id_prestador']);
					$pagamento = mysqli_fetch_assoc($class->TipoPagamento);

					$nomePag="";
					if ($pagamento['banco']!=""){
						$nomePag="Conta Corrente";
						$numero_cheque="";
					}
				// ===============================================


				// BUSCA OS CHEQUES COMO FORMA DE PAGAMENTO
					if ($pagamento['banco']==""){
						$class->ListaChequesID($row['id_prestador']);
						$cheque = mysqli_fetch_assoc($class->ListaChequesID);
						if ($cheque['num_cheque']!=""){
							$nomePag="Cheque";
							$numero_cheque=$cheque['num_cheque'];
						}else{
							$numero_cheque="";
						}
					}
			?>
				<tr>
					<td style="text-align:center"><?= $row['id_prestador'] ?></td>
					<td style="text-align:center"><?= $prest->NomePrestador($row['id_prestador']) ?></td>
					<td style="text-align:center"><?= $class->FormataData($row['data_pgto']) ?></td>
					<td style="text-align:center"></td>
					<td style="text-align:center"><?= $nomePag ?></td>
					<td style="text-align:center"><?= $numero_cheque ?></td>
					<td style="text-align:center"><?= strtoupper($row['lote']) ?></td>
					<td style="text-align:center"><?= $status_pgto ?></td>
				</tr>
			<?php
				}
			?>
		</table>


		<?php
			// FORMULARIO DE CADASTRO DE DESCONTOS

		if (isset($_POST['prest'])){
			$nome=$_POST['prest'];
			$valor=$_POST['valor'];
			$data_sol=$_POST['data_sol'];
			$dia=$_POST['dia'];
			$descricao=$_POST['descricao'];
			$class->NovoDesconto($nome, $valor, $data_sol, $dia, $descricao);
		}
		?>


			<div id="formulario-novo-desconto" class="formulario-novo-desconto">
				<form class="form-inline" method="post">
					<fieldset>
						<legend>Descontos</legend>

						<label style="margin:0px 0px 0px 32px" class="control-label">Prestador:</label>
			                    <input required name="prest" type="text" id="country_id" onkeyup="autocomplet()">
			                    <ul id="country_list_id"></ul>					
						

						<label style="margin:0px 0px 0px 20px" class="control-label">Valor:</label>		                    

			                    <div class="input-prepend input-append">
									<span class="add-on">R$</span>
								  	<input data-thousands="" data-decimal="." class="input-small" type="text" id="valor" name="valor" style='border-radius:0px 5px 5px 0px'>
								</div>


						<br>

						<label style="margin:55px 0px 0px 0px" class="control-label">Dt. do Evento :</label>
			                    <input name="data_sol" type="date">


			             <label style="margin:55px 0px 0px 20px" class="control-label">Desconto a Partir de :</label>
			                    <input name="dia" type="date">

			              <br>

						<label style="margin:15px 0px 0px 27px" class="control-label">Descrição :</label>
							<textarea class="area-sol" name="descricao" id=""></textarea>
						

						<div class="form-actions">
							<button onClick="window.location.href='descontos.php'" style='float:right' type="button" class="btn">Cancelar</button>
							<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
						</div>
					</fieldset>
				</form>
			</div>
			<?php
				}
			?>
	

		</div>






























