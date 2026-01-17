<?php
	session_start();
	// CASO A PAGINA NÃO TENHA RECEBIDO A SESSÃO, FAZ O REDIRECT PARA O FORMULARIO DE LOGIN
	if (!isset($_SESSION['autenticado'])){
		header("location: ../index.php");
	}

	// LOGOUT DA PAGINA
	if (isset($_GET['logout'])){
		session_destroy();
		header("location: ../index.php");
	}


	// FINANCEIRO
	require_once("class/financeiro.class.php");
	$class = new Financeiro;

	// PRESTADORES
	require_once("../prestadores/class/prestadores.class.php");
	$prest = new Prestadores;


	// EXCLUI O REGISTRO DE DESCONTO

		if (isset($_GET['DeleteDesc'])){
			$class->DeleteDesc($_GET['DeleteDesc']);
		}
?>





<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Lord Eventos - Admin</title>
	<link rel="stylesheet" href="../../css/bootstrap.css">
	<link rel="stylesheet" href="../../css/styles.css">
	<link rel="stylesheet" href="../../css/print.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/script.js"></script>
	<script src="../js/masked.js"></script>
	<script type="text/javascript" src="../js/main.js"></script>
	
	<script type="text/javascript">
		function NovoDesconto(){
			$("#formulario-novo-desconto").fadeIn('slow');
			document.getElementById('tabela-descontos').style.display="none";
			document.getElementById('cx-funcoes').style.display="none";	
		}
	</script>


<?php
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

	
</head>
<body onload="initTimer();">
	<div class="container">
		<header class="cabecalho">
			<div onClick="window.location.href='../index.php'" class="logo"></div>
			<div class="menu">

				<ul style='float:left' class="nav nav-pills">
				  <li class="dropdown">
				    <a style='color:#000242' class="dropdown-toggle"
				       data-toggle="dropdown"
				       href="#">
				        Cadastros
				        <b class="caret"></b>
				      </a>

				    <ul class="dropdown-menu">

				      <li class="dropdown-submenu">
					    <a tabindex="-1" href="#">Empresa</a>
					    <ul class="dropdown-menu">
					       <li><a tabindex="-1" href="/admin/index.php?funcao=dados">Dados</a></li>
					        <li><a tabindex="-1" href="/admin/index.php?funcao=departamento">Departamentos</a></li>
					         <li><a tabindex="-1" href="/admin/index.php?funcao=funcionarios">Funcionários</a></li>
					          <li><a tabindex="-1" href="/admin/index.php?funcao=bancos">Bancos</a></li>
					           <li><a tabindex="-1" href="/admin/index.php?funcao=contas">Contas Bancárias</a></li>
					           <li><a tabindex="-1" href="/admin/index.php?funcao=cheques">Cheques</a></li>
					    </ul>
					  </li>

				      <li class="dropdown-submenu">
					    <a tabindex="-1" href="#">Clientes</a>
					    <ul class="dropdown-menu">
					      <li><a tabindex="-1" href="/admin/index.php?funcao=listaClientes">Lista</a></li>
					      <li><a tabindex="-1" href="/admin/index.php?funcao=listaFuncoes">Funções</a></li>
					      <li><a tabindex="-1" href="/admin/index.php?funcao=listaSetores">Setores</a></li>
					    </ul>
					  </li>

				      <li><a tabindex="-1" href="/admin/index.php?funcao=listaPrestadores">Prestadores</a></li>
				    </ul>

				  </li>
				</ul>

				<ul style='float:left' class="nav nav-pills">
				  <li class="dropdown">
				    <a style='color:#000242' class="dropdown-toggle"
				       data-toggle="dropdown"
				       href="#">
				        Eventos
				        <b class="caret"></b>
				      </a>
				    <ul class="dropdown-menu">
				      <li><a tabindex="-1" href="/admin/index.php?funcao=escalas">Escalas</a></li>
				      <!-- <li><a tabindex="-1" href="/admin/index.php?funcao=orcamento">Orçamento</a></li> -->
				    </ul>
				  </li>
				</ul>

				<ul style='float:left' class="nav nav-pills">
				  <li class="dropdown">
				    <a style='color:#000242' class="dropdown-toggle"
				       data-toggle="dropdown"
				       href="#">
				        Financeiro
				        <b class="caret"></b>
				      </a>
				    <ul class="dropdown-menu">
				      <li><a tabindex="-1" href="/admin/index.php?funcao=baixarEscalas">Baixar Escalas</a></li>
				      <li><a tabindex="-1" href="descontos.php">Descontos</a></li>
				      <li><a tabindex="-1" href="/admin/index.php?funcao=CalcularLote">Calcular Lote</a></li>

				      <li class="dropdown-submenu">
					    <a tabindex="-1" href="#">Imprimir</a>
					    <ul class="dropdown-menu">
					       <li><a tabindex="-1" href="/admin/index.php?funcao=ImprimirRecibos">Recibos</a></li>
					       <li><a tabindex="-1" href="/admin/?funcao=FaturamentoRepasse">Faturamento/Repasse</a></li>
					       <li><a tabindex="-1" href="/admin/?funcao=RPA">RPA</a></li>
					       <li><a tabindex="-1" href="#">Relatório de Pagamentos</a></li>
					    </ul>
					  </li>

					  <li><a tabindex="-1" href="/admin/index.php?funcao=fichaFinanceira">Ficha Financeira</a></li>
					  <li><a tabindex="-1" href="/admin/index.php?funcao=RecibosPagos">Recibos Pagos</a></li>

				    </ul>
				  </li>
				</ul>


				<ul style='float:left' class="nav nav-pills">
				  <li class="dropdown">
				    <a style='color:#000242' class="dropdown-toggle"
				       data-toggle="dropdown"
				       href="#">
				        Configurações
				        <b class="caret"></b>
				      </a>
				    <ul class="dropdown-menu">
				      <li class="dropdown-submenu">
					    <a tabindex="-1" href="#">Usuários</a>
					    <ul class="dropdown-menu">
					       <li><a tabindex="-1" href="#">Novo Usuário</a></li>
					    </ul>
					  </li>
				    </ul>
				  </li>
				</ul>
			
			<ul class="nav nav-pills">
				  <li><a style='color:red' href="?logout=ok">Sair</a></li>
				</ul>

			</div>


			<div class="cx-info">
				<img src="../../img/user_icon.png" height="63" width="66" alt="">
				<h4 style='margin:7px 0px 0px 0px' class="txt-info"><?= ucfirst($_SESSION['nome']) ?></h4>
				<h4 class="txt-info"><i><?= ucfirst($_SESSION['cargo']) ?></i></h4>
			</div>
		</header>

	</div>

	<hr style='margin:0;border:1px solid #ccc'>



	<div class="container">
		<div class="cx-desc">
	
	<?php
		// INCLUI O FORMULARIO DE ATERAÇÃO DO DESCONTO
			if (isset($_GET['AlterarDesc'])){
				require_once("AlterarDesconto.php");
			}else{
	?>
			<div id="cx-funcoes" class="cx-funcoes"></a>
				<img style="cursor:pointer" onClick="NovoDesconto()" src="../../img/bt_novo1.png" alt="">
				<img style="cursor:pointer" id='imprimir' src="../../img/bt_imprimir1.png" alt="">
			</div>


		<?php
			// TABELA DE DESCONTOS
			$class->BuscaDesconto();
		?>
			<table id="tabela-descontos" class="table table-striped">
				<tr>
					<td style="text-align:center">ID</td>
					<td style="text-align:center">Prestador</td>
					<td style="text-align:center">Valor</td>
					<td style="text-align:center">Status</td>
					<td style="text-align:center">Ações</td>
				</tr>

			<?php
				while ($list = mysqli_fetch_assoc($class->BuscaDesconto)){
					$status = "";
					if ($list['status_desc']=='1'){
						$status = "<img src='../../img/b2.png'> Em aberto";
					}else{
						$status="<img src='../../img/b1.png'> Finalizado";
					}
			?>
				<tr>
					<td onClick="window.location.href='?AlterarDesc=<?= $list['id_desc'] ?>'" style="text-align:center;cursor:pointer"><?= $list['id_desc'] ?></td>
					<td onClick="window.location.href='?AlterarDesc=<?= $list['id_desc'] ?>'" style="text-align:center;cursor:pointer"><?= $prest->NomePrestador($list['id_prest_desc']) ?></td>
					<td onClick="window.location.href='?AlterarDesc=<?= $list['id_desc'] ?>'" style="text-align:center;cursor:pointer"><?= "R$ ".number_format($list['valor_desc'],2) ?></td>
					<td onClick="window.location.href='?AlterarDesc=<?= $list['id_desc'] ?>'" style="text-align:center;cursor:pointer"><?= $status; ?></td>
					<td style="text-align:center">
						<button onClick="confirmacao(<?= $list['id_desc'] ?>)" class="btn btn-danger">Excluir</button>
					</td>
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
	</div>


	<footer class="rodape">
		<h4 class="txt-rodape">Desenvolvido por TR Consultoria - 2015</h4>
	</footer>

	<!-- <script src="../js/jquery.js"></script> -->
	<script src="../../js/bootstrap.min.js"></script>

	<script>
		$(function() {
		    $('#valor').maskMoney();
		  })
	</script>

</body>

</html>



























