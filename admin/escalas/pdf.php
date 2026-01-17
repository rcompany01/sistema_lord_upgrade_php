<?php
require_once('class/escalas.class.php');
$class = new Escalas;

// BUSCA OS DADOS DA ESCALA
if (isset($_GET['idEsc'])){
	$id = $_GET['idEsc'];
	$class->BuscaEscala($id);
	$row = mysqli_fetch_assoc($class->BuscaEscala);
}

// TOTAL DE PRESTADORES
$qtdPrest = $class->RelacaoEscalaPrestTotal($_GET['idEsc']);

/* Carrega a classe DOMPdf */
require_once("dompdf/dompdf/dompdf_config.inc.php");
 
/* Cria a instância */
$dompdf = new DOMPDF();

$html="<html>";
$html.="<div id='cabecalho-esc-print'>";
$html.="<div class='logo_dm'>";
$html.="<img src='".$_SERVER["DOCUMENT_ROOT"]."/img/logo.png' height='77' width='120'>";
$html.="</div>";
$html.="<h5 class='titulo-dm'>Cliente - ".$row['cliente']."</h5>";
$html.="<div style='margin-bottom:20px' class='cx-dados-previa'>";
$html.="<div style='float:left' class='caixas-dados-previa'>";
$html.="<h5 class='nome-campos-previa'><b>Evento: </b>".$class->NomeSetor($row['setor'])."</h5>";
$html.="</div>";
$html.="<div style='float:left' class='caixas-dados-previa'>";
$html.="<h5 class='nome-campos-previa'><b>Total de Prestadores: </b>".$qtdPrest."</h5>";
$html.="<h5 class='nome-campos-previa'><b>Data de Solicitação: </b>".$class->FormataData($row['data_solic'])."</h5>";
$html.="<h5 class='nome-campos-previa'><b>Data do Evento: </b>".$class->FormataData($row['data_evento'])."</h5>";
$html.="</div>";
$html.="</div>";
$html.="</div>";

$html.="<table style='font-size:12px' class='table table-striped'>";
$html.="<tr>";
$html.="<td>ID Solicitação</td>";
$html.="<td>ID Prest.</td>";
$html.="<td>Prestador</td>";
$html.="<td>Dt Evento</td>";
$html.="<td>Hr. Entrada</td>";
$html.="<td>Hr. Saida</td>";
$html.="<td>Hr. Extra</td>";
$html.="<td>ID Função</td>";
$html.="<td>Função</td>";
$html.="<td id='col-acoes'>Valor</td>";
$html.="</tr>";
$fat=0;
$rep=0;
$class->ListaPrestadorEscala($_GET['idEsc']);
while ($list=mysqli_fetch_assoc($class->ListaPrestadorEscala)){
include("calculos-escalas.php");	
$html.= "<tr>";
$html.= "<td>".$list['id_escala']."</td>";
$html.= "<td>".$list['id_prestador']."</td>";
$html.= "<td style='text-transform:uppercase'>".$class->NomePrestadorID($list['id_prestador'])."</td>";
$html.= "<td>".$class->FormataData($list['data_evento'])."</td>";
$html.= "<td>".$list['entrada']."</td>";
$html.= "<td>".$horarioSaida."</td>";
$html.= "<td>".$list['extra']."</td>";
$html.= "<td>".$list['id_funcao']."</td>";
$html.= "<td>".$class->NomeFuncaoID($list['id_funcao'])."</td>";
$html.= "<td id='col-acoes'>R$ ".(number_format($valorFinal,2))."</td>.";
$html.= "</tr>";
}
$html.= "<tr>";
$html.= "<td colspan='10'>&nbsp;</td>";
$html.= "</tr>";

$html.= "<tr>";
$html.= "<td colspan='1'>&nbsp;</td>";
$html.= "<td colspan='1'>&nbsp;</td>";
$html.= "<td colspan='1'>&nbsp;</td>";
$html.= "<td colspan='1'>&nbsp;</td>";
$html.= "<td colspan='1'>&nbsp;</td>";
$html.= "<td style='float:right!important' id='col-val' colspan='3'><b>Total Faturamento:</b>R$ ".number_format($fat,2)."</td>";
$html.= "<td style='display:none' id='col-valB' colspan='3'>&nbsp;</td>";
$html.= "<td style='float:right!important' id='col-val2' colspan='3'><b>Total Repasse:</b>R$ ".number_format($rep,2)."</td>";
$html.= "<td style='display:none' id='col-val2B' colspan='3'>&nbsp;</td>";
$html.= "<td colspan='4'>&nbsp;</td>";
$html.= "</tr>";
$html.="</table>";
$html.="</html>";
 
/* Carrega seu HTML */
$dompdf->load_html($html);
 
/* Renderiza */
$dompdf->render();
 
/* Exibe */
$dompdf->stream(
    "escala.pdf", /* Nome do arquivo de saída */
    array(
        "Attachment" => true /* Para download, altere para true */
    )
);
?>

