<script type="text/javascript">
	function NovaEmpresa(){
	// $("#formulario-nova-escala").fadeIn('slow');
	document.getElementById('tabela-escalas').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";
	document.getElementById('busca-esc').style.display="none";
	document.getElementById('busca-setor').style.display="none";
	document.getElementById('busca-cliente').style.display="none";
	document.getElementById('busca-periodo').style.display="none";
	window.location.href="?funcao=escalas&escala=novo";
}

	function BuscaCl(){
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

	function BuscaSetor(){
		var id = document.getElementById('idSet').value;
		<?php
			if (isset($_GET['idCl'])){
				$cl = $_GET['idCl'];
		?>
			window.location.href="?funcao=escalas&escala=novo&idCl=<?= $cl ?>&idSet="+id;
		<?php
			}else{
		?>
			window.location.href="?funcao=escalas&escala=novo&idSet="+id;
		<?php
			}
		?>
	}


	function BuscaIDCL(id){
		document.getElementById('idCl').value = id;
	}

	function BuscaIDSet(id){
		document.getElementById('idSet').value = id;
	}
</script>

<script type="text/javascript">
	function previa(URL){
		var width = 1150;
  		var height = 550;
 		
  		var left = 100;
  		var top = 20;
 	
  		window.open(URL,'janela', 'width='+width+', height='+height+', top='+top+', left='+left+',scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');

	}
</script>
<?php

	// ============================================== 
	require_once('class/escalas.class.php');
	$class = new Escalas;

	// REGISTRA UMA NOVA ESCALA
	if (isset($_POST['id_cliente'])){

		$idCliente = $_POST['id_cliente'];
		$class->NovaEscala(	$_POST['solicitante'],
							$idCliente,
							$_POST['setor'],
							$_POST['data_sol'],
							$_POST['data_event']);
	}
	// ================================================


	// ================================================
		require_once('clientes/class/clientes.class.php');
		require_once('clientes/class/setores.class.php');
		require_once('clientes/class/funcoes.class.php');
		$clientes = new Clientes;
		$setores = new Setores;
		$funcao = new Funcoes;
		$esc = new Escalas;
	// ================================================

?>

<?php
	// FAZ A CONFIRMAÇÃO PARA DESATIVAR O PRESTADOR
	
	if (isset($_GET['ExcluirEscala'])){
		$class->ExcluirEscala($_GET['ExcluirEscala']);
	}

?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=escalas&ExcluirEscala="+id;
		     }
		}
    </script>

<?php
	// FAZ O INCLUDE DA PAGINA DE ALTERACAO DOS DADOS
	if (isset($_GET['idEsc'])){
		// ARQUIVO DE ALTERACAO DOS DADOS
		require_once('att_escalas.php');
	}else{
?>


<div class="cx-empresa-dados">

	<?php
	// NOVA ESCALA
	if (!empty($_GET['escala'])){

		// Busca o nome do Cliente pelo ID
		if (!empty($_GET['idCl'])){
			$class->BuscaCliente($_GET['idCl']);
			$infoCl = mysqli_fetch_assoc($class->BuscaCliente);
		}


		// Busca o nome do SETOR pelo ID
		if (!empty($_GET['idSet'])){
			$class->BuscaSetor($_GET['idSet']);
			$infoSet = $class->BuscaSetor($_GET['idSet']);
		}
?>


	<div id='formulario-nova-escala' class="formulario-nova-escala">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Nova Escala</legend>

				<?php (!empty($_GET['idCl'])) ? $clienteID = $_GET['idCl'] : $clienteID="" ?>
				<label style="margin:15px 0px 0px 49px" class="control-label">ID Cliente:</label>
					<input name="id_cliente" value="<?= $clienteID ?>" id="idCl" style="width:40px;" required type="text" onBlur="BuscaCl()">
				

				<?php (!empty($_GET['idCl'])) ? $nomeCl = $infoCl['cl_nome_fantasia'] : $nomeCl = "" ?>
				<label style="margin:15px 0px 0px 17px" class="control-label">Nome Cliente:</label>
					<input value="<?= $nomeCl ?>" style="width:270px" required type="text" id="country_id" onkeyup="autocompletCl()">
			        <ul style="margin-left:289px;width:281px" id="country_list_id"></ul>

				<br>
				
				<?php (!empty($_GET['idSet'])) ? $SetorID = $_GET['idSet'] : $SetorID="" ?>
				<label style="margin:15px 0px 0px 60px" class="control-label">ID Setor:</label>
					<input name="setor" value="<?= $SetorID ?>" id="idSet" style="width:40px;" required type="text" onBlur="BuscaSetor()">
				

				<?php (!empty($_GET['idSet'])) ? $nomeSet = $infoSet : $nomeSet = "" ?>
				<label style="margin:15px 0px 0px 17px" class="control-label">Nome Setor:</label>
					<input value="<?= $nomeSet ?>" style="width:270px" required type="text" id="country_id_set" onkeyup="autocompletSet()">
			        <ul style="margin-left:280px;width:281px" id="country_list_id_set"></ul> <br>

				<label style="margin-top:20px" class="control-label">Dt. da Solicitação:</label>
					<input style="text-align:center" value="<?= date('d/m/Y') ?>" name="data_sol" class="input-medium" type="date">

				<br>

				<label style="margin:15px 0px 0px 46px" class="control-label">Solicitante:</label>
					<input name="solicitante" class="input-medium" type="text">
				<br>
				<label style="margin:15px 0px 0px 15px" class="control-label">Data do Evento:</label>
					<input required name="data_event" class="input-medium" type="date">

			</fieldset>



			<div class="form-actions">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
			</div>
		</form>
	</div>
	
	<?php
		}else{
	?>



	<div id="busca-esc" style="float:left">
		<form method="get">
			<input type="hidden" name="funcao" value="escalas">
			<h4 style="float:left;margin:20px 0px 0px 0px;font-size:15px">ID Escala:</h4>
				<input required style="float:left;margin:15px 0px 0px 10px" class="input-mini" type="text" name="id_escala">
					<button style="float:left;margin:15px 0px 0px 10px" class="btn btn-primary">
						<u class="icon-search icon-white"></u>
					</button>

					<input value="Ver Todos" type="button" style="float:left;margin:15px 0px 0px 10px" onClick="window.location.href='?funcao=escalas'" class="btn btn-warning">
		</form>
	</div>


	<div id="busca-setor" style="float:left">
		<form style="margin:0" method="get">
			<input type="hidden" name="funcao" value="escalas">
			<h4 style="float:left;margin:20px 0px 0px 50px;font-size:15px">Setor:</h4>
				<input required style="float:left;margin:15px 0px 0px 10px" class="input-mini" type="text" name="id_setor">
					<button style="float:left;margin:15px 0px 0px 10px" class="btn btn-primary">
						<u class="icon-search icon-white"></u>
					</button>
		</form>
	</div>

	<div id="busca-cliente" style="float:left">
		<form style="margin:0" method="get">
			<input type="hidden" name="funcao" value="escalas">
			<h4 style="float:left;margin:20px 0px 0px 50px;font-size:15px">Cliente:</h4>
				<input required style="float:left;margin:15px 0px 0px 10px" class="input-mini" type="text" name="id_cliente">
					<button style="float:left;margin:15px 0px 0px 10px" class="btn btn-primary">
						<u class="icon-search icon-white"></u>
					</button>
		</form>
	</div>

	<div style="width:560px" id="busca-periodo" style="float:left">
		<form style="margin:0" method="get">
			<input type="hidden" name="funcao" value="escalas">
			<h4 style="float:left;margin:60px 0px 0px 10px;font-size:15px">Período:</h4>
				<input required style="float:left;margin:55px 0px 0px 10px" class="input-medium" type="date" name="de">
				<input required style="float:left;margin:55px 0px 0px 10px" class="input-medium" type="date" name="ate">
					<button style="float:left;margin:55px 0px 0px 10px" class="btn btn-primary">
						<u class="icon-search icon-white"></u>
					</button>
		</form>
	</div>

	<?php
		// VERIFICAÇÃO PARA DEIXAR O INPUT SELECIONADO

		$sel="";
		if (!empty($_GET['ordem'])){
			switch ($_GET['ordem']) {
				case 'cod':
					$sel = "selected";
					break;

				case 'alfa':
					$sel2 = "selected";
					break;

				case 'codalfa':
					$sel3 = "selected";
					break;
			}
		}else{
			$sel="";
		}
	?>

	<div style="width:360px" id="busca-periodo" style="float:left">
		<form style="margin:0" method="get">
			<input type="hidden" name="funcao" value="escalas">
			<h4 style="float:left;margin:50px 0px 0px 0px">Ordem:</h4>
			<select style="float:left;margin:45px 0px 0px 10px" name="ordem" class="input-medium">
				<option value="">Selecione</option>
				<option <?= $sel ?> value="cod">Código</option>
				<option <?= $sel2 ?> value="alfa">Afabética</option>
				<option <?= $sel3 ?> value="codalfa">Código + Afabética</option>
			</select>
			<button style="float:left;margin:45px 0px 0px 10px" class="btn btn-primary">
				<u class="icon-search icon-white"></u>
			</button>
		</form>
	</div>



	
	<div style="margin-top:44px;margin-left:460px" id="cx-funcoes" class="cx-funcoes"></a>
		<img style="cursor:pointer" onClick="NovaEmpresa()" src="../img/bt_novo1.png" alt="">
		<img style="cursor:pointer" id='imprimir' src="../img/bt_imprimir1.png" alt="">
	</div>

	<style type="text/css">
		#tabela-escalas-impressao{
			display: none;
		}

		@media print{
			#tabela-escalas-impressao{
				display: block;
			}
			#tabela-escalas{
				display: none;
			}
		}
	</style>

	<table style="font-size:12px" id='tabela-escalas' class="table table-striped">
		<tr style="font-weight:bold">
			<td style="text-align:center">Status da Baixa</td>
			<td style="text-align:center">ID Solicitação</td>
			<td style="text-align:center">ID</td>
			<td style="text-align:center">Cliente</td>
			<td style="text-align:center">Setor</td>
			<td style="text-align:center">Dt. Solicitação</td>
			<td style="text-align:center">Dt. Evento</td>
			<td style="text-align:center">Lote</td>
			<td style="text-align:center">Ações</td>
		</tr>
<?php
	$class->ListaEscalas();
	while($row=mysqli_fetch_assoc($class->ListaEscalas)){
		$img ="";
		if ($row['status_baixa']=='0'){
			$img = "<img src='../img/b1.png'>";
		}else{
			$img = "<img src='../img/b2.png'>";
		}
?>
		<tr>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $img ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $row['id_esc'] ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $row['id_cliente'] ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $row['cliente'] ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $class->BuscaSetor($row['setor']) ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $class->FormataData($row['data_solic']) ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $class->FormataData($row['data_evento']) ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= strtoupper($row['lote']) ?></td>
			<td style="text-align:center;font-weight:bold">
				<button onClick="previa('escalas/PreviaOrcamento.php?escala=<?= $row['id_esc'] ?>')" class="btn btn-primary"><u class="icon-search icon-white"></u> Prévia</button>
				<button onClick="confirmacao(<?= $row['id_esc'] ?>)" class="btn btn-danger">Excluir</button>
			</td>
		</tr>
<?php
	}
?>
	</table>

	<table style="font-size:12px" id='tabela-escalas-impressao' class="table table-striped">
		<tr style="font-weight:bold">
			<td style="text-align:center">Status da Baixa</td>
			<td style="text-align:center">ID Solicitação</td>
			<td style="text-align:center">ID</td>
			<td style="text-align:center">Cliente</td>
			<td style="text-align:center">Setor</td>
			<td style="text-align:center">Dt. Solicitação</td>
			<td style="text-align:center">Dt. Evento</td>
			<td style="text-align:center">Lote</td>
		</tr>
<?php
	$class->ListaEscalasImpressao();
	while($row=mysqli_fetch_assoc($class->ListaEscalasImpressao)){
		$img ="";
		if ($row['status_baixa']=='0'){
			$img = "<img src='../img/b1.png'>";
		}else{
			$img = "<img src='../img/b2.png'>";
		}
?>
		<tr>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $img ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $row['id_esc'] ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $row['id_cliente'] ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $row['cliente'] ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $class->BuscaSetor($row['setor']) ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $class->FormataData($row['data_solic']) ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= $class->FormataData($row['data_evento']) ?></td>
			<td style="cursor:pointer;text-align:center;font-weight:bold" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idEsc=<?= $row['id_esc'] ?>'"><?= strtoupper($row['lote']) ?></td>
		</tr>
<?php
	}
?>
	</table>

<?php

	if (isset($_GET['pagina'])){
		if ($_GET['pagina']=="0"){
			?>
				<script type="text/javascript">
					window.location.href="?funcao=escalas";
				</script>
			<?php
		}
	}


	$pag = "0";
	$pagAv = "25";
	if (!isset($_GET['pagina'])){
		$pag = "0";
	}else{
		$pag = $_GET['pagina'] - "25";
		$pagAv = $_GET['pagina'] + "25";
	}



	// SE CASO TENHA UM FILTRO POR STATUS, A BARRA DE PAGINAÇÃO SOME
	if (!isset($_GET['status_prest'])){
?>

<div id="paginacao-prest" class="form-actions">
	<ul class="pager">

		<?php
			// BOTAO ANTERIOR
			$disabled="disabled";
			$url="#";
			if (isset($_GET['pagina'])){
				if ($_GET['pagina']=='0'){
					$disabled="disabled";
					$url="#";
				}else{
					$disabled="active";
					$url="?funcao=escalas&pagina=$pag";
				}
			}
		?>
	  <li class="<?= $disabled ?>"><a href="<?= $url ?>">Anterior</a></li>





	  <?php
	  	// BOTAO PROXIMO
	  	$qtd = $class->TotalEscalas();
	  	if ($qtd>1){
	  ?>
	  	<li><a href="?funcao=escalas&pagina=<?= $pagAv ?>">Próxima</a></li>
	  <?php
	  	}else{
	  ?>
		<li><a href="#">Próxima</a></li>
	  <?php
		}
	  ?>
	</ul>		  
</div>

<?php
	}
}
?>

</div>

<?php
	}
?>