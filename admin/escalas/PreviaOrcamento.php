<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../../css/styles.css">
	<link rel="stylesheet" href="../../css/bootstrap.css">
	<link rel="stylesheet" href="../../css/print.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/main.js"></script>
	<title>Prévia Orçamento</title>
</head>
<body>

<div id="imprimir" class="bt-print-previa">
	<u style="position:absolute;top:3px;right:80px;cursor:pointer" class="icon-print"></u> 
	<b style="position:absolute;top:0;right:20px;cursor:pointer">Imprimir</b>
</div>

<?php
	// CLASS ORCAMENTO
	require_once("class/escalas.class.php");
	$class = new Escalas;

	// CLASSE DAS FUNCOES
	require_once('../clientes/class/funcoes.class.php');
	$func= new Funcoes;
	$func->ListaFuncoes();

	// CLASSE QUE PEGA OS DADOS DO ORÇAMENTO
	$class->BuscaEscala($_GET['escala']);
	$row = mysqli_fetch_assoc($class->BuscaEscala);

	// TOTAL DE PRESTADORES
	$qtdPrest = $class->RelacaoEscalaPrestTotal($_GET['escala']);

	// CLASSE QUE BUSCA A RELAÇÃO DE ESCALA DOS PRESTADORES
	$class->RelacaoEscalaPrest($_GET['escala']);

	// PRESTADORES
	require_once("../prestadores/class/prestadores.class.php");
	$prest = new Prestadores;
	

?>

<div class="cx-previa-orc">
	<div class="logo_dm">
		<img src="../../img/logo.png" height="77" width="120" alt="">
	</div>

	<h4 style="margin-left:300px" class="titulo-dm">Orçamento - Prévia do Evento</h4>


	<div class="cx-dados-previa">
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


	<table style="margin-top:30px;float:left" class="table table-striped">
		<tr>
			<td><b>ID</b></td>
			<td><b>Profissional</b></td>
			<td><b>Função</b></td>
			<td><b>Horas</b></td>
			<td><b>Entrada</b></td>
			<td><b>Saída</b></td>
			<td><b>Valor Total</b></td>
		</tr>
<?php
	$horas=0;
	$totalValor=0;
	while($esc = mysqli_fetch_assoc($class->RelacaoEscalaPrest)){
		$horas = $esc['saida']-$esc['entrada'];
		$totalValor += $esc['total'];
?>
		<tr>
			<td><?= $esc['id_prestador'] ?></td>
			<td><?= strtoupper($prest->NomePrestador($esc['id_prestador'])) ?></td>
			<td><?= $func->NomeFuncao($esc['id_funcao']) ?></td>
			<td><?= $horas." HRS" ?></td>
			<td><?= $esc['entrada'] ?></td>
			<td><?= $esc['saida'] ?></td>
			<td><?= "R$ ".number_format($esc['total'],2) ?></td>
		</tr>
<?php
	}
?>
		<tr>
			<td colspan="5">&nbsp;</td>
			<td><b>Valor Total do Evento</b></td>
			<td><?= "<b>R$ ".number_format($totalValor,2)."</b>" ?></td>
		</tr>

		<tr>
			<td colspan="7">
				<b>Obs:</b>
				<small>
					<b>Valores sujeitos a alterações devido as horas adicionais</b>
				</small>
			</td>
		</tr>
	</table>

</div>


</body>
</html>