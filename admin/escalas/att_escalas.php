<?php
	error_reporting(0);
	ini_set("display_errors", 0);
?>

<input type="hidden" id="idEsc" value="<?= $_GET['idEsc'] ?>">
<?php
	require_once("controller.php");
?>
<form id="form-edit-esc" class="form-inline" method="post">
	<fieldset>
		<legend>Editar Escala</legend>

		<label style="margin-left:0px" class="control-label">Dt. da Solicitação:</label>
			<input class="input-medium" type="date" name="att_data_sol" value="<?= $row['data_solic'] ?>">

		<br>

		<label style="margin:15px 0px 0px 46px" class="control-label">Solicitante:</label>
			<input value="<?= $row['solicitante'] ?>" name="att_solicitante" class="input-medium" type="text">

		<br>


		<label style="margin:15px 0px 0px 67px" class="control-label">Cliente:</label>
		<input type="hidden" name="att_id_cliente" value="<?= $row['id_cliente'] ?>">
			<select disabled required name="att_id_cliente" id="">
				<option value="">Selecione o Cliente..</option>
				<?php
					$clientes->ListaClientes();
					while($rowCl=mysqli_fetch_assoc($clientes->ListaClientes)){
						$sel="";
						if ($rowCl['id_cl']==$row['id_cliente']){
							$sel="selected";
						}else{
							$sel="";
						}
				?>
					<option <?= $sel ?> value="<?= $rowCl['id_cl'] ?>">
						<?= $rowCl['cl_nome_fantasia'] ?>
					</option>
				<?php
					}
				?>
			</select>

		<br>

		<label style="margin:15px 0px 0px 77px" class="control-label">Setor:</label>
			<select required name="att_setor" id="">
				<option value="">Selecione o Setor..</option>
				<?php
					$setores->ListaSetores();
					while($rowSt=mysqli_fetch_assoc($setores->ListaSetores)){
						$sel="";
						if ($rowSt['id_setor']==$row['setor']){
							$sel="selected";
						}else{
							$sel="";
						}
				?>
					<option <?= $sel ?> value="<?= $rowSt['id_setor'] ?>">
						<?= $rowSt['setor'] ?>
					</option>
				<?php
					}
				?>
			</select>
		<br>
		<label style="margin:15px 0px 0px 15px" class="control-label">Data do Evento:</label>
			<input value="<?= $row['data_evento'] ?>" required name="att_data_event" class="input-medium" type="date">

	</fieldset>



	<div class="form-actions">
		<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
		<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
	</div>
</form>

<?php
	/* CASO SEJA CLICADO NO BOTAO EDITAR (PRESTADORES CADASTRADOS), 
		FAZ UM INCLUDE DE UM OUTRO FORMULARIO PARA EXBICAO E EDIÇÃO
		BUSCA OS DADOS DOS PRESTADORES PARA REALIZAR AS ALTERACOES
	*/

	if (isset($_GET['EditEsc'])){
		include('att_prestador.php');

	// SE NÃO, EXIBE O FORMULARIO NORMALMENTE
	}else{
?>
		
		<script type="text/javascript">
			function BuscaIDFun(){
			var id = document.getElementById('idCl').value;
			<?php
				if (isset($_GET['idSet'])){
					$st = $_GET['idSet'];
			?>
				window.location.href="?funcao=escalas&escala=novo&idSet=<?= $st ?>&idCl="+id;
			<?php
				}else{
			?>
				window.location.href="?funcao=escalas&escala=novo&idCl="+id;
			<?php
				}
			?>
		}
		</script>



		<form id="form-add-prest" method="post" class="form-inline">
			<fieldset>
				<legend id="add">Adicionar Prestadores</legend>



				<label style="margin:15px 0px 0px 10px" class="control-label">Cod. Função:</label>
					<?php
						$sel ="";
						if (!empty($_GET['Value'])){
							$sel = $_GET['Value'];
						}
					?>
					<input value="<?= $sel ?>" id="cod_func" type="text" name="add_funcao" class="input-mini" onchange="mostravalor(this.value)">
				

				<label style="margin:15px 0px 0px 60px" class="control-label">Função:</label>
					<select onchange="mostravalor(this.value)" required class="input-large" name="add_funcao" id="funcao">
						<option value="">Selecione a Função..</option>
						<?php
							$funcao->ListaFuncoes();
							while($func=mysqli_fetch_assoc($funcao->ListaFuncoes)){
								$sel = "";
								if ($func['id_func']==$_GET['Value']){
									$sel = "selected";
								}else{
									$sel="";
								}
						?>
							<option <?= $sel ?> value="<?= $func['id_func'] ?>"><?= $func['id_func']." - ".$func['funcao'] ?></option>
						<?php
							}
						?>
					</select>
				
				<?php
				if (!empty($_GET['Value'])){
					$funcao->ListaFuncoesID($_GET['Value']);
					$Infofuncao = mysqli_fetch_assoc($funcao->ListaFuncoesID);
				}
				?>
				<?php (isset($_GET['Value'])) ? $funcaoID = $Infofuncao['funcao'] :  $funcaoID="" ?>
				<input value="<?= $funcaoID ?>" style="width:300px" required type="text" id="country_id_set_fun" onkeyup="autocompletFun()">
			    <ul style="margin-left:508px;width:312px" id="country_list_id_set_fun"></ul>



				<br>


<?php
	if (isset($_GET['Value'])){
?>
	
<?php // PEGA O VALOR DE UMA FUNÇÃO REAL DA URL ?>
	<input type="hidden" id="FuncID" name="Value" value="<?= $_GET['Value'] ?>">
<?php // ====================== ?>


<?php
	// BUSCA O NOME DO PRESTADOR PELO CÓDIGO APENAS PARA EXIBIÇÃO
	
	if (isset($_GET['PrestID'])){
		$class->BuscaNomePrestador($_GET['PrestID']);
	}
?>



				<label style="margin:15px 0px 0px 25px" class="control-label">Cod. Prest:</label>
					<?php 
						$codPrest=""; 
						(!empty($_GET['PrestID'])) ? $codPrest=$_GET['PrestID'] : $codPrest=""
					?>
					<input value="<?= $codPrest; ?>" onblur="BuscaNomePrest(this.value)" type="text" name="add_prest" class="input-mini">


				<label style="margin:15px 0px 0px 47px" class="control-label">Prestador:</label>
					<select onchange="BuscaNomePrest(this.value)" required class="input-large" name="add_prest" id="prestador">
						<option value="">Selecione o Prestador..</option>
						<?php
							$esc->ListaPrestadorAtivo();
							while($prest=mysqli_fetch_assoc($esc->ListaPrestadorAtivo)){
								$sel = "";
								if ($prest['id_prest']==$_GET['PrestID']){
									$sel = "selected";
								}else{
									$sel="";
								}
						?>
							<option <?= $sel ?> value="<?= $prest['id_prest'] ?>"><?= $prest['id_prest']." - ".$prest['nome_prest'] ?></option>
						<?php
							}
						?>
					</select>


				<label style="margin:15px 0px 0px 47px" class="control-label">Nome Prestador:</label>
				<?php
					$nomePrest=""; 
					(!empty($_GET['PrestID'])) ? $nomePrest=$class->BuscaNomePrestador($_GET['PrestID']) : $nomePrest=""
				?>
					<input style="width:390px" value="<?= $nomePrest ?>" required name="prest" type="text" id="country_id" onkeyup="autocomplet()">
			        <ul onClick="FocusNome()" style="margin-left:674px;width:380px" id="country_list_id"></ul>

			        <input type="hidden" id="idPrestAuto">				
<br>

				<label style="margin:15px 0px 0px 20px" class="control-label">Hr. Entrada:</label>
					<input required id="HrEntrada" type="time" name="entrada" class="input-small">

				<label style="margin:15px 0px 0px 20px" class="control-label">Hr. Saida:</label>
					<input onBlur="CalculaFat()" required id="HrSaida" type="time" name="saida" class="input-small">

				<label style="margin:15px 0px 0px 20px" class="control-label">Hr. Extra:</label>
					<input onBlur="CalculaExtra()" required type="time" name="extra" id="HrExtra" class="input-small">
<?php
	}
?>
				
			</fieldset>

<?php
// BUSCA O VALOR DA FUNCAO
if (isset($_GET['Value'])){
	$class->BuscaValoresFuncao($_GET['Value'], $row['id_cliente']);
	$vl=mysqli_fetch_assoc($class->BuscaValoresFuncao);
?>



			<fieldset style="margin-top:20px">
				<legend>Valores</legend>

				<label style="margin:0px 0px 0px 18px" class="control-label">Vl. Repasse:</label>
						<div class="input-prepend input-append">
							<span class="add-on">R$</span>
							<!-- valores dinamicos -->
						  <input value="<?= $vl['vl_repasse'] ?>" disabled id="valorRep" style='border-radius:0px 5px 5px 0px' class="input-small" id="appendedPrependedInput" type="text">
						  
							<!-- HORAS DA FUNCAO + REPASSE E FATURAMENTO FIXOS -->
						  <input type="hidden" value="<?= $vl['horas_func'] ?>" id="horasFunc">
						  <input type="hidden" value="<?= $vl['vl_repasse'] ?>" id="repFix">
						  <input type="hidden" value="<?= $vl['vl_faturamento'] ?>" id="fatFix">

						  <!-- ALTERAM -->
						  <input type="hidden" value="" id="repTotal">
						  <input type="hidden" value="" id="fatTotal">
						</div>

					<label style="margin:15px 0px 0px 20px" class="control-label">Vl. Faturamento:</label>
						<div class="input-prepend input-append">
							<span class="add-on">R$</span>
							<input value="<?= $vl['vl_faturamento'] ?>" disabled id="valorFat" style='border-radius:0px 5px 5px 0px' class="input-small" id="appendedPrependedInput" type="text">
						</div>

					<label style="margin:15px 0px 0px 20px" class="control-label">Vl. Extra Rep:</label>
						<div class="input-prepend input-append">
							<span class="add-on">R$</span>
							<input disabled id="valor" style='border-radius:0px 5px 5px 0px' class="input-small" id="appendedPrependedInput" type="text">
						</div>

					<br>

					<label style="margin:15px 0px 0px 12px" class="control-label">Vl. Extra Fat.:</label>
						<div class="input-prepend input-append">
							<span class="add-on">R$</span>
						  <input disabled id="valor" style='border-radius:0px 5px 5px 0px' class="input-small" id="appendedPrependedInput" type="text">
						</div>

					<label style="margin:15px 0px 0px 20px" class="control-label">Vl. Rep. Dia:</label>
						<div class="input-prepend input-append">
							<span class="add-on">R$</span>
						  <input disabled id="valor" style='border-radius:0px 5px 5px 0px' class="input-small" id="appendedPrependedInput" type="text">
						</div>

					<label style="margin:15px 0px 0px 20px" class="control-label">Vl. Fat. Dia:</label>
						<div class="input-prepend input-append">
							<span class="add-on">R$</span>
						  <input disabled id="valor" style='border-radius:0px 5px 5px 0px' class="input-small" id="appendedPrependedInput" type="text">
						</div>
			</fieldset>
<?php
	}
?>

			<div class="form-actions">
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-success">Adicionar Prestador</button>			  
			</div>
		</form>

<?php
	}
?>
		
		<?php
			// TOTAL DE PRESTADORES
			$qtdPrest = $class->RelacaoEscalaPrestTotal($_GET['idEsc']);
		?>

		<div id="cabecalho-esc-print">
			<div class="logo_dm">
				<img src="../img/logo.png" height="77" width="120" alt="">
			</div>

			<h4 style="margin-left:300px" class="titulo-dm"><?= "Cliente - ".$row['cliente'] ?></h4>


			<div style="margin-bottom:20px" class="cx-dados-previa">
				<div class="caixas-dados-previa">
					<h4 class="nome-campos-previa"><b>Contratada: LORD EVENTOS</b></h4>
					<h4 class="nome-campos-previa">RUA PASCOAL DE MIRANDA 40</h4>
					<h4 class="nome-campos-previa">08120-020 - SÃO PAULO</h4>
					<h4 class="nome-campos-previa">Telefone: 3427-7077</h4>
				</div>

				<div class="caixas-dados-previa">
					<h4 class="nome-campos-previa"><b>Solicitante: </b><?= $row['solicitante'] ?></h4>
					<h4 class="nome-campos-previa"><b>Endereço: </b><?= $row['cl_rua'] ?></h4>
					<h4 class="nome-campos-previa"><b>Evento: </b><?= $class->NomeSetor($row['setor']) ?></h4>
					<h4 class="nome-campos-previa"><b>Responsável: </b></h4>
				</div>

				<div class="caixas-dados-previa">
					<h4 class="nome-campos-previa"><b>Total de Prestadores: </b><?= $qtdPrest ?></h4>
					<h4 class="nome-campos-previa"><b>Data de Solicitação: </b><?= $class->FormataData($row['data_solic']) ?></h4>
					<h4 class="nome-campos-previa"><b>Data do Evento: </b><?= $class->FormataData($row['data_evento']) ?></h4>
				</div>
			</div>
		</div>

		<table style="font-size:12px" id="firstTable" class="table table-striped">
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
				<td id="col-acoes">Valor</td>
				<td id="col-acoes">Ações</td>
			</tr>
<?php
	// LISTA OS PRESTADORES ADICIONADOS
	$fat=0;
	$rep=0;
	$class->ListaPrestadorEscala($_GET['idEsc']);
	while ($list=mysqli_fetch_assoc($class->ListaPrestadorEscala)){
		include("calculos-escalas.php");		
?>
			<tr>
				<td><?= $list['id_escala'] ?></td>
				<td><?= $list['id_prestador'] ?></td>
				<td style='text-transform:uppercase'><?= $class->NomePrestadorID($list['id_prestador']) ?></td>
				<td><?= $class->FormataData($list['data_evento']) ?></td>
				<td><?= $list['entrada'] ?></td>
				<td><?= $horarioSaida ?></td>
				<td><?= $list['extra'] ?></td>
				<td><?= $list['id_funcao'] ?></td>
				<td><?= $class->NomeFuncaoID($list['id_funcao']) ?></td>
				<td id="col-acoes"><?= "R$ ".(number_format($valorFinal,2)) ?></td>
				<td id="col-acoes">
					<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $_GET['idEsc'] ?>&EditEsc=<?= $list['id'] ?>'" class="btn btn-warning">
						<u class="icon icon-edit icon-white"></u>
					</button>
					<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $_GET['idEsc'] ?>&DelEsc=<?= $list['id'] ?>';" class="btn btn-danger">
						<u class="icon icon-remove icon-white"></u>
					</button>
				</td>
			</tr>

			
<?php
	}
?>
			<tr>
				<td colspan="1">&nbsp;</td>
				<td id="col-val" colspan="3"><b>Total Faturamento:</b> <?= "R$ ".number_format($fat,2) ?></td>
				<td style="display:none" id="col-valB" colspan="3">&nbsp;</td>
				<td id="col-val2" colspan="3"><b>Total Repasse:</b> <?= "R$ ".number_format($rep,2) ?></td>
				<td style="display:none" id="col-val2B" colspan="3">&nbsp;</td>
				<td colspan="4">&nbsp;</td>
			</tr>

		</table>


		<!-	 TABELA QUE LISTA A ENTRADA E SAIDA EM BRANCO + ASSINATURA ->
		<?php
			include("entrada-saida-ass.php");
		?>

		<!-	 TABELA QUE LISTA SOMENTE A ASSINATURA ->
		<?php
			include("assinatura.php");
		?>


		<div id="bar-print-escala" class="form-actions">
			<button id="imprimir" style='float:left;margin-right:10px' class="btn btn-primary">
				<u class="icon-print icon-white"></u>
				Imprimir
			</button>

			<label style="float: left;">
				<input onchange='handleChange(this);' style="margin:0px 0px 0px 20px" type="checkbox" name="valores-fat-rep">
					Ocultar Faturamento
			</label>

			<label style="float: left;">
				<input onchange='handleChangeB(this);' style="margin:0px 0px 0px 20px" type="checkbox" name="valores-fat-rep">
					Ocultar Repasse
			</label>

			<label style="float: left;">
				<input onchange='TableEmpty(this);' style="margin:0px 0px 0px 20px" type="checkbox" name="valores-fat-rep">
					Entrada/Saída + Assinatura
			</label>


			<label style="float: left;">
				<input onchange='TableAss(this);' style="margin:0px 0px 0px 20px" type="checkbox" name="valores-fat-rep">
					Assinatura
			</label>


			<a href="escalas/excell.php?idEsc=<?= $_GET['idEsc'] ?>">
				<button id="imprimir" style='float:right;margin-right:20px;margin-top:0px' class="btn btn-success">
					<u class="icon-align-center icon-white"></u>
					Exportar Excell
				</button>
			</a>

			<a href="escalas/pdf.php?idEsc=<?= $_GET['idEsc'] ?>">
				<button id="imprimir" style='float:right;margin-right:20px;margin-top:0px' class="btn btn-danger">
					<u class="icon-align-center icon-white"></u>
					PDF
				</button>
			</a>
		</div>



<script>
	function mostravalor(id){
		var url = document.getElementById("idEsc").value;
		var cod = document.getElementById("cod_func");
		var func = document.getElementById("funcao");
		window.location.href="?funcao=escalas&idEsc="+url+"&Value="+id+"#add";

		if (cod.value!=""){
			func.disabled=true;
		}else if (func.value!=""){
			cod.disabled=true;
		}
	}



	function BuscaNomePrest(id){
		var esc = document.getElementById('idEsc').value;
		var func = document.getElementById('FuncID').value;
		window.location.href="?funcao=escalas&idEsc="+esc+"&Value="+func+"&PrestID="+id+"#add";
	}


	function handleChange(cb) {
	  if(cb.checked == true){
	  	document.getElementById('col-val').style.display="none";
	  	document.getElementById('col-valB').style.display="table-cell";
	  }else{
	   document.getElementById('col-val').style.display="table-cell";
	   document.getElementById('col-valB').style.display="none";
	  }
	}

	function handleChangeB(cb) {
	  if(cb.checked == true){
	  	document.getElementById('col-val2').style.display="none";
	  	document.getElementById('col-val2B').style.display="table-cell";
	  }else{
	   document.getElementById('col-val2').style.display="table-cell";
	   document.getElementById('col-val2B').style.display="none";
	  }
	}

	function TableEmpty(cb){
		if(cb.checked == true){
		  	document.getElementById('firstTable').style.display="none";
		  	document.getElementById('secondTable').style.display="table";
		  }else{
		   document.getElementById('firstTable').style.display="table";
		   document.getElementById('secondTable').style.display="none";
		  }
	}


	function TableAss(cb){
		if(cb.checked == true){
		  	document.getElementById('firstTable').style.display="none";
		  	document.getElementById('thirdTable').style.display="table";
		  }else{
		   document.getElementById('firstTable').style.display="table";
		   document.getElementById('thirdTable').style.display="none";
		  }
	}





	function CalculaFat(){
		var start = document.getElementById('HrEntrada').value;
	    var end = document.getElementById('HrSaida').value;

	   	start = start.split(":");
	    end = end.split(":");
	    var startDate = new Date(0, 0, 0, start[0], start[1], 0);
	    var endDate = new Date(0, 0, 0, end[0], end[1], 0);
	    var diff = endDate.getTime() - startDate.getTime();
	    var hours = Math.floor(diff / 1000 / 60 / 60);
	    diff -= hours * 1000 * 60 * 60;
	    var minutes = Math.floor(diff / 1000 / 60);

	    // If using time pickers with 24 hours format, add the below line get exact hours
	    if (hours < 0)
	       hours = hours + 24;

	    hour = (hours <= 9 ? "0" : "") + hours;
	    min= (minutes <= 9 ? "0" : "") + minutes;

	    // VALOR DO REPASSE
	    var rep = document.getElementById('valorRep');
	    // VALÓR DO FATURAMENTO
	    var fat = document.getElementById('valorFat');


	    var repFx = document.getElementById('repFix');
	    var fatFx = document.getElementById('fatFix');
	    var horas = document.getElementById('horasFunc');

	    var repTotal = document.getElementById('repTotal');
		var fatTotal = document.getElementById('fatTotal');		

	    rep.value = Math.abs(((((repFx.value) * hour)) + (((repFx.value) * min) / 60))).toFixed(2);
	    fat.value = Math.abs(((((fatFx.value) * hour)) + (((fatFx.value) * min) / 60))).toFixed(2);

		repTotal.value = rep.value;
		fatTotal.value = fat.value;

		

	}

	function CalculaExtra(){
		var start = document.getElementById('HrEntrada').value;
	    var end = document.getElementById('HrSaida').value;

	    s = start.split(':');
	    e = end.split(':');

	    min = e[1]-s[1];
	    hour_carry = 0;
	    if(min < 0){
	        min += 60;
	        hour_carry += 1;
	    }
	    hour = e[0]-s[0]-hour_carry;
	    diff = hour + "." + min;


		var extra = document.getElementById('HrExtra').value;

		var rep = document.getElementById('valorRep');
	    var fat = document.getElementById('valorFat');

	    var repFixo = document.getElementById('repFix');
		var fatfixo = document.getElementById('fatFix');	

		ex = extra.split(":");
		hourEx =ex[0];
		if (hourEx > 0){
			hourEx = hourEx*60;
		}
		minEx = ex[1];
		difEx = ex[0]+"."+ex[1];

		// Total de Minutos
		totalMin = parseFloat(hourEx) + parseFloat(minEx);

		// CONTINUAR DAQUI

		var totalEx = (((repFix.value) * totalMin) / 60);

		var totalFatEx = (((fatFix.value) * totalMin) / 60);

		rep.value = ((totalEx += (totalEx*0.5)) + (parseFloat(rep.value))).toFixed(2);		
		fat.value = (totalFatEx += (totalFatEx*0.5) + (parseFloat(fat.value))).toFixed(2);

		// ====

	}


	

</script>
