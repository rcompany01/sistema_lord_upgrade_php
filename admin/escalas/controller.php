<?php
// BUSCA OS DADOS DA ESCALA
if (isset($_GET['idEsc'])){
	$id = $_GET['idEsc'];
	$class->BuscaEscala($id);
	$row = mysqli_fetch_assoc($class->BuscaEscala);
}

//	 ATUALIZACAO DA ESCALA
if (isset($_POST['att_data_sol'])){
	$sol=$_POST['att_solicitante'];
	$idCl=$_POST['att_id_cliente'];
	$setor=$_POST['att_setor'];
	$dataSol=$_POST['att_data_sol'];
	$dataEvent=$_POST['att_data_event'];
	$id=$_GET['idEsc'];
	$class->AtualizarEscala($sol,$idCl,$setor,$dataSol,$dataEvent, $id);
	?>
		<script type="text/javascript">
			alert('Escala Atualizada!');
			var id = document.getElementById('idEsc').value;
			window.location.href="?funcao=escalas&idEsc="+id;
		</script>
<?php
	}

	// FORMULARIO QUE INCLUI OS PRESTADORES NA ESCALA
	if (isset($_POST['add_prest'])){
		// VERIFICA SE A SAIDA FOI MEIA NOITE
		$saida = $_POST['saida'];

		$saida2 = explode(":", $saida);
			if ($saida2[0] == "00"){
				$saida2[0] = "24";
			}
		$saida = $saida2[0].":".$saida2[1];
		$idEscala=$_GET['idEsc'];
		$idPrest=$_POST['add_prest'];
		$dataEvento=$row['data_evento'];
		$entrada=$_POST['entrada'];
		$extra=$_POST['extra'];
		$idFuncao=$_POST['add_funcao'];
		$idCl = $row['id_cliente'];
		$class->AddPrestadoEscala(	$idEscala,
									$idPrest,
									$dataEvento,
									$entrada,
									$saida,
									$extra,
									$idFuncao,
									$idCl);
?>
	<script type="text/javascript">
		var id = document.getElementById('idEsc').value;
		window.location.href="?funcao=escalas&idEsc="+id+"#add";
	</script>
<?php
	}


// EXCLUI O PRESTADOR DA ESCALA
if (isset($_GET['DelEsc'])){
	$id = $_GET['DelEsc'];
	$class->ExcluirPrestadorEscala($id);
	?>
	<script type="text/javascript">
		var id = document.getElementById('idEsc').value;
		window.location.href="?funcao=escalas&idEsc="+id;
	</script>
<?php
}


// Busca os dados da funcao (valores de faturamento/repasse)
if (!empty($_POST['numFuncao'])){
	$idCliente = $_POST['idCliente'];
	$funcao = $_POST['numFuncao'];
	include('class/escalas.class.php');
	$class = new Escalas;
	$class->DadosFuncao($idCliente, $funcao);
}
?>

