<?php
$arquivo = 'escala.xls';
// Criamos uma tabela HTML com o formato da planilha
// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel; charset=utf-8");
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
header ("Content-Description: PHP Generated Data" );
require_once("class/escalas.class.php");
$class = new Escalas;

/*
* Criando e exportando planilhas do Excel
* /
*/
// Definimos o nome do arquivo que será exportado

$html = '';
$html .= '<table>';

$html .= "<tr>";
$html .= "<td>ID</td>";
$html .= "<td>Prestador</td>";
$html .= "<td>Dt Evento</td>";
$html .= "<td>Hr. Entrada</td>";
$html .= "<td>Hr. Saida</td>";
$html .= "<td>Hr. Extra</td>";
$html .= "<td>ID Funcao</td>";
$html .= "<td>Funcao</td>";
$html .= "<td>Valor</td>";
$html .= "</tr>";


	$fat=0;
	$rep=0;
	$class->ListaPrestadorEscala($_GET['idEsc']);
	while ($list=mysqli_fetch_assoc($class->ListaPrestadorEscala)){

		$fat += $list['valor_fat'];
		$rep += $list['valor_rep'];

		$html .= "<tr>";
		$html .= "<td> ".$list['id_prestador']."</td>";
		$html .= "<td> ".$class->NomePrestadorID($list['id_prestador'])."</td>";
		$html .= "<td> ".$class->FormataData($list['data_evento'])."</td>";
		$html .= "<td> ".$list['entrada']."</td>";
		$html .= "<td> ".$list['saida']."</td>";
		$html .= "<td> ".$list['extra']."</td>";
		$html .= "<td> ".$list['id_funcao']."</td>";
		$html .= "<td> ".$class->NomeFuncaoID($list['id_funcao'])."</td>";
		$html .= "<td> R$ ".round(number_format($list['valor_fat'],2))."</td>";
		$html .= "</tr>";

}

$html .= "<tr>";
$html .= "<td colspan='1'>&nbsp;</td>";
$html .= "<td id='col-val' colspan='3'><b>Total Faturamento:</b>R$ ".number_format($fat,2)."</td>";
$html .= "<td id='col-val2' colspan='3'><b>Total Repasse:</b>R$ ".number_format($rep,2)."</td>";
$html .= "<td style='display:none' id='col-val2B' colspan='3'>&nbsp;</td>";
$html .= "<td colspan='4'>&nbsp;</td>";
$html .= "</tr>";









$html .= '</table>';

// Envia o conteúdo do arquivo
echo $html;
				
				
				
				
				
				
				
				