<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 0);
// INSTANCIA A CLASSE
	require_once("class/financeiro.class.php");
	$class = new Financeiro;

	// SETORES
	require_once("../clientes/class/setores.class.php");
	$set = new Setores;

	// PRESTADORES
	require_once('../prestadores/class/prestadores.class.php');
	$pr = new Prestadores;

	// FUNCAO
	require_once('../clientes/class/funcoes.class.php');
	$fc = new Funcoes;

	// CLIENTES
	require_once('../clientes/class/clientes.class.php');
	$cl = new Clientes;

/*
* Criando e exportando planilhas do Excel
* /
*/
// Definimos o nome do arquivo que será exportado
$arquivo = 'repasse.xls';
// Criamos uma tabela HTML com o formato da planilha
// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel; charset=utf-8");
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
header ("Content-Description: PHP Generated Data" );

$html = '';
$html .= '<table>';

$html .= "<tr>";
$html .= "<td>ID</td>";
$html .= "<td>Prestador</td>";
$html .= "<td>Funcao</td>";
$html .= "<td>Dt. Evento</td>";
$html .= "<td>Entrada</td>";
$html .= "<td>Saida</td>";
$html .= "<td>Extra</td>";
$html .= "<td>Valor</td>";
$html .= "<td>Vl. Extra</td>";
$html .= "<td>Solic.</td>";
$html .= "<td>Setor</td>";
$html .= "</tr>";




	$class->DadosFaturamentoTodos($_GET['de'], $_GET['ate']);
	$faturamento=0;
	$cont=0;
	while ($row_fat=mysqli_fetch_assoc($class->DadosFaturamentoTodos)){
	$cont++;

	// HORA EXTRA
		$vtEx = explode(":", $row_fat['extra']);
		$extra = $vtEx[0].'.'.$vtEx[1];

	// CALCULO DA HORA EXTRA (FATURAMENTO)
		$ValorHoraExtra = ($row_fat['vl_repasse'] / $row_fat['horas_func']) / 2;
		$finalExtra = $row_fat['extra_rep'];


	// CALCULO DE VALOR TOTAL DE FATURAMENTO
		$horasTrabalhadas = $row_fat['saida']-$row_fat['entrada'];
		$faturamento += $row_fat['repasse'] + $finalExtra;

		$html .= "<tr>";
		$html .= "<td> ".$row_fat['prestador']."</td>";
		$html .= "<td> ".strtoupper($pr->NomePrestador($row_fat['prestador']))."</td>";
		$html .= "<td> ".strtoupper($fc->NomeFuncao($row_fat['funcao']))."</td>";
		$html .= "<td> ".$class->FormataData($row_fat['data_evento'])."</td>";
		$html .= "<td> ".$row_fat['entrada']."</td>";
		$html .= "<td> ".$row_fat['saida']."</td>";
		$html .= "<td> ".$row_fat['extra']."</td>";
		$html .= "<td> R$ ".number_format(round($row_fat['repasse']),2)."</td>";
		$html .= "<td> R$ ".number_format($finalExtra,2)."</td>";
		$html .= "<td> ".$row_fat['escala']."</td>";
		$html .= "<td> ".strtoupper($set->NomeSetor($row_fat['setor']))."</td>";
		$html .= "</tr>";

}


$html .= "<tr>";
$html .= "<td>Dt. Impressão: ".date('d/m/Y')."</td>";
$html .= "<td>Total Serviços: ".$cont."</td>";
$html .= "<td>Repasse : R$ ".number_format($faturamento,2)."</td>";
$html .= "</tr>";


$html .= '</table>';

// Envia o conteúdo do arquivo
echo $html;
