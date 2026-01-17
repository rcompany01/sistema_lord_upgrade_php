<?php
session_start();
// CASO A PAGINA NÃO TENHA RECEBIDO A SESSÃO, FAZ O REDIRECT PARA O FORMULARIO DE LOGIN
if (!isset($_SESSION['autenticado'])) {
	header("location: ../index.php");
}
// Libera o arquivo de sessão para evitar travamento com requests AJAX/LongPolling simultâneos
session_write_close();

// LOGOUT DA PAGINA
if (isset($_GET['logout'])) {
	session_destroy();
	header("location: ../index.php");
}

// error_reporting(0);
// ini_set(“display_errors”, 0);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<title>Lord Eventos - Admin</title>
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/styles.css">
	<link rel="stylesheet" href="../css/print.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<script src="js/masked.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

	<script language="javascript">
		function showTimer() {
			var time = new Date();
			var hour = time.getHours();
			var minute = time.getMinutes();
			var second = time.getSeconds();
			if (hour < 10) hour = "0" + hour;
			if (minute < 10) minute = "0" + minute;
			if (second < 10) second = "0" + second;
			var st = hour + ":" + minute + ":" + second;
			document.getElementById("timer").innerHTML = st;
		} function initTimer() { // O metodo nativo setInterval executa uma determinada funcao em um determinado tempo 
			setInterval(showTimer, 1000);
		}
	</script>





</head>

<body onload="initTimer();">
	<div class="container">
		<header class="cabecalho">
			<div onClick="window.location.href='../admin'" class="logo"></div>
			<div class="menu">

				<ul style='float:left' class="nav nav-pills">
					<li class="dropdown">
						<a style='color:#000242' class="dropdown-toggle" data-toggle="dropdown" href="#">
							Cadastros
							<b class="caret"></b>
						</a>

						<ul class="dropdown-menu">

							<li class="dropdown-submenu">
								<a tabindex="-1" href="#">Empresa</a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="?funcao=dados">Dados</a></li>
									<li><a tabindex="-1" href="?funcao=departamento">Departamentos</a></li>
									<li><a tabindex="-1" href="?funcao=funcionarios">Funcionários</a></li>
									<li><a tabindex="-1" href="?funcao=bancos">Bancos</a></li>
									<li><a tabindex="-1" href="?funcao=contas">Contas Bancárias</a></li>
									<li><a tabindex="-1" href="?funcao=cheques">Cheques</a></li>
								</ul>
							</li>

							<li class="dropdown-submenu">
								<a tabindex="-1" href="#">Clientes</a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="?funcao=listaClientes">Lista</a></li>
									<li><a tabindex="-1" href="?funcao=listaFuncoes">Funções</a></li>
									<li><a tabindex="-1" href="?funcao=listaSetores">Setores</a></li>
								</ul>
							</li>

							<li><a tabindex="-1" href="?funcao=listaPrestadores">Prestadores</a></li>
						</ul>

					</li>
				</ul>

				<ul style='float:left' class="nav nav-pills">
					<li class="dropdown">
						<a style='color:#000242' class="dropdown-toggle" data-toggle="dropdown" href="#">
							Eventos
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a tabindex="-1" href="?funcao=escalas">Escalas</a></li>
							<!-- <li><a tabindex="-1" href="?funcao=orcamento">Orçamento</a></li> -->
						</ul>
					</li>
				</ul>

				<ul style='float:left' class="nav nav-pills">
					<li class="dropdown">
						<a style='color:#000242' class="dropdown-toggle" data-toggle="dropdown" href="#">
							Financeiro
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a tabindex="-1" href="?funcao=baixarEscalas">Baixar Escalas</a></li>
							<li><a tabindex="-1" href="financeiro/descontos.php">Descontos</a></li>
							<li><a tabindex="-1" href="?funcao=CalcularLote">Calcular Lote</a></li>

							<li class="dropdown-submenu">
								<a tabindex="-1" href="#">Imprimir</a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="?funcao=ImprimirRecibos">Pagar Prestadores</a></li>
									<li><a tabindex="-1" href="?funcao=ImprimirRecibos2">Imprimir Recibos</a></li>
									<li><a tabindex="-1" href="?funcao=FaturamentoRepasse">Faturamento/Repasse</a></li>
									<li><a tabindex="-1" href="?funcao=RPA">RPA</a></li>
									<li><a tabindex="-1" href="?funcao=RelPagto">Relatório de Pagamentos</a></li>
								</ul>
							</li>

							<li><a tabindex="-1" href="?funcao=fichaFinanceira">Ficha Financeira</a></li>
							<li><a tabindex="-1" href="?funcao=RecibosPagos">Recibos Pagos</a></li>

						</ul>
					</li>
				</ul>


				<ul style='float:left' class="nav nav-pills">
					<li class="dropdown">
						<a style='color:#000242' class="dropdown-toggle" data-toggle="dropdown" href="#">
							Configurações
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li class="dropdown-submenu">
								<a tabindex="-1" href="#">Usuários</a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="?funcao=NovoUsuario">Novo Usuário</a></li>
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
				<img src="../img/user_icon.png" height="63" width="66" alt="">
				<h4 style='margin:10px 0px 0px 0px' class="txt-info"><?= ucfirst($_SESSION['nome']) ?></h4>
				<h4 class="txt-info"><i><?= ucfirst($_SESSION['cargo']) ?></i></h4>
			</div>
		</header>

	</div>

	<hr style='margin:0;border:1px solid #ccc'>



	<?php
	// CONTEUDO DAS TELAS
	?>
	<div class="container">
		<?php
		if (isset($_GET['funcao'])) {
			switch ($_GET['funcao']) {

				// MODULO DE CADASTRO
				case 'dados':
					require_once('empresa/dados.php');
					break;

				case 'departamento':
					require_once('empresa/departamento.php');
					break;

				case 'funcionarios':
					require_once('empresa/funcionarios.php');
					break;

				case 'bancos':
					require_once('empresa/bancos.php');
					break;

				case 'contas':
					require_once('empresa/contas.php');
					break;

				case 'cheques':
					require_once('empresa/cheques.php');
					break;

				case 'listaClientes':
					require_once('clientes/listaClientes.php');
					break;

				case 'listaFuncoes':
					require_once('clientes/listaFuncoes.php');
					break;

				case 'listaSetores':
					require_once('clientes/listaSetores.php');
					break;

				case 'listaPrestadores':
					require_once('prestadores/index.php');
					break;

				// MODULO DE EVENTOS
				case 'escalas':
					require_once('escalas/index.php');
					break;


				case 'orcamento':
					require_once('orcamento/index.php');
					break;

				// MODULO FINANCEIRO
				case 'baixarEscalas':
					require_once('financeiro/baixarEscalas.php');
					break;

				case 'CalcularLote':
					require_once('financeiro/CalcularLote.php');
					break;

				case 'fichaFinanceira':
					require_once('financeiro/fichaFinanceira.php');
					break;

				case 'RecibosPagos':
					require_once('financeiro/RecibosPagos.php');
					break;

				case 'AlterarRecibo':
					require_once('financeiro/AlterarRecibo.php');
					break;

				case 'ImprimirRecibos':
					require_once('financeiro/ImprimirRecibos.php');
					break;

				case 'ImprimirRecibos2':
					require_once('financeiro/ImprimirRecibos2.php');
					break;

				case 'FaturamentoRepasse':
					require_once('financeiro/FaturamentoRepasse.php');
					break;

				case 'RelPagto':
					require_once('financeiro/RelPagto.php');
					break;

				case 'RPA':
					require_once('financeiro/RPA.php');
					break;

				case 'NovoUsuario':
					require_once('usuarios/NovoUsuario.php');
					break;

			}
		} else {
			?>
			<div class="img-clock">
				<div class="hora" id=timer align="center"></div>
			</div>
			<?php
		}
		?>
	</div>


	<footer class="rodape">
		<h4 class="txt-rodape">Desenvolvido por TR Consultoria - 2015</h4>
	</footer>

	<!-- <script src="../js/jquery.js"></script> -->
	<script src="../js/bootstrap.min.js"></script>

</body>

<script>
	$(function () {
		$('#valor').maskMoney();
	})

	$(function () {
		$('#valor1').maskMoney({ decimal: ",", thousands: "." });
	})

	$(function () {
		$('#valor2').maskMoney({ decimal: ",", thousands: "." });
	})

	$(function () {
		$('#valor1fat').maskMoney({ decimal: ".", thousands: "." });
	})

	$(function () {
		$('#valor2rep').maskMoney({ decimal: ".", thousands: "." });
	})

	$(function () {
		$('#repasse').maskMoney({ decimal: ",", thousands: "." });
	})

	$(function () {
		$('#extra').maskMoney({ decimal: ",", thousands: "." });
	})

	$(function () {
		$('#extra2').maskMoney({ decimal: ",", thousands: "." });
	})
</script>

</html>