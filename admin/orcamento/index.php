
<script type="text/javascript">
	function NovaEmpresa(){
	$("#formulario-novo-orcamento").fadeIn('slow');
	document.getElementById('tabela-empresas').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";	
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

<?php // WEB SERVICE DOS CORREIOS ?>
<script type="text/javascript" >

        $(document).ready(function() {

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#rua").val("");                                           
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
            }
            
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#rua").val("Aguarde...")
                        $("#bairro").val("Aguarde...")
                        $("#cidade").val("Aguarde...")
                        $("#uf").val("Aguarde...")

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#rua").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });

    </script>

    <?php
		// FAZ A CONFIRMAÇÃO DE REMOVER O REGISTRO
	?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=orcamento&ExcluirOrc="+id;
		     }
		}
    </script>


<?php

	require_once('clientes/class/setores.class.php');
	$set = new Setores;

	require_once("class/orcamento.class.php");
	$class = new Orcamento;

	if (isset($_POST['solicitante'])){

		$solicitante=$_POST['solicitante'];
		$cep=$_POST['cep'];
		$rua=$_POST['rua'];
		$num=$_POST['numero'];
		$compl=$_POST['compl'];
		$bairro=$_POST['bairro'];
		$cidade=$_POST['cidade'];
		$uf=$_POST['uf'];
		$evento=$_POST['evento'];
		$data_sol=$_POST['solicitacao'];
		$data_event=$_POST['data_evento'];
		$publico=$_POST['publico'];
		$responsavel=$_POST['responsavel'];
		$voucher=$_POST['voucher'];
		$id_escala=$_POST['id_escala'];
		$class->NovoOrcamento(	$solicitante,
								$cep,
								$rua,
								$num,
								$compl,
								$bairro,
								$cidade,
								$uf,
								$evento,
								$data_sol,
								$data_event,
								$publico,
								$responsavel,
								$voucher,
								$id_escala);
	}
?>

<div class="cx-empresa-dados">

<?php
	// CASO O GET "AdicionarValor" SEJA SETADO, MOSTRAMOS A TELA PARA INSERIR OS VALORES DO ORÇAMENTO
if (isset($_GET['AdicionarValor'])){
	require_once("valores.php");
	// EDITA OS DADOS DO ORCAMENTO
}elseif (isset($_GET['EditarOrc'])){
	require_once("editarOrcamento.php");
	// EXCLUI OS DADOS DO ORCAMENTO
}elseif(isset($_GET['ExcluirOrc'])){
	$class->ExcluirOrcamento($_GET['ExcluirOrc']);
}else{
?>
	
	<div id="cx-funcoes" class="cx-funcoes"></a>
		<img style="cursor:pointer" onClick="NovaEmpresa()" src="../img/bt_novo1.png" alt="">
		<img style="cursor:pointer" id='imprimir' src="../img/bt_imprimir1.png" alt="">
	</div>

	<table id='tabela-empresas' class="table table-striped">
		<tr style="font-weight:bold">
			<td style="text-align:center">ID Orçamento</td>
			<td style="text-align:center">Solicitante</td>
			<td style="text-align:center">Evento</td>
			<td style="text-align:center">Dt. Evento</td>
			<td style="text-align:center">Ações</td>
		</tr>
<?php
	$class->BuscaOrcamento();
	while ($row=mysqli_fetch_assoc($class->BuscaOrcamento)){
?>
		<tr style="cursor:pointer">
			<td style="cursor:pointer;text-align:center"><?= $row['id_orc'] ?></td>
			<td style="cursor:pointer;text-align:center"><?= $row['solicitante_orc'] ?></td>
			<td style="cursor:pointer;text-align:center"><?= $row['evento_orc'] ?></td>
			<td style="cursor:pointer;text-align:center"><?= $class->FormataData($row['data_evento']) ?></td>
			<td style="text-align:center">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&EditarOrc=<?= $row['id_orc'] ?>'" class="btn btn-warning"><u class="icon-edit icon-white"></u> Editar</button>
				<button onClick="previa('orcamento/PreviaOrcamento.php?PreviaOrc=<?= $row['id_orc'] ?>&escala=<?= $row['id_escala'] ?>')" class="btn btn-primary"><u class="icon-search icon-white"></u> Prévia</button>
				<button onClick="confirmacao(<?= $row['id_orc'] ?>)" class="btn btn-danger"><u class="icon-remove icon-white"></u> Excluir</button>
			</td>
		</tr>
<?php
	// FIM DO WHILE
	}
?>
	</table>

<?php
	}
?>






	<div id='formulario-novo-orcamento' class="formulario-novo-orcamento">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados do Orçamento</legend>
				
				<label style="margin:15px 0px 0px 18px" class="control-label">ID da Escala:</label>
					<input required type="text" class="input-mini" name="id_escala"> <br>


				<label style="margin:15px 0px 0px 33px" class="control-label">Solicitante:</label>
					<input required name="solicitante" type="text">

				<br>

				<label style="margin:15px 0px 0px 71px" class="control-label">CEP:</label>
					<input required name="cep" id="cep" class="input-small" type="text">
					<small>Sem traços, apenas números.</small>

				<br>

				<label style="margin:15px 0px 0px 25px" class="control-label">Logradouro:</label>
					<input required name="rua" id="rua" class="input-xlarge" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Número:</label>
					<input required name="numero" class="input-mini" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Complemento:</label>
					<input name="compl" class="input-mini" type="text">

				<label style="margin:15px 0px 0px 62px" class="control-label">Bairro:</label>
					<input required name="bairro" id="bairro" class="input-large" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Cidade:</label>
					<input required name="cidade" id="cidade" class="input-medium" type="text">
				

				<label style="margin:0px 0px 0px 20px" class="control-label">UF:</label>
					<input required name="uf" id="uf" class="input-mini" type="text">

				<br>

				<label style="margin:15px 0px 0px 56px" class="control-label">Evento:</label>
					<select required class="input-medium" name="evento" id="">
						<option value=""></option>
						<?php
							$set->ListaSetores();
							while ($setor=mysqli_fetch_assoc($set->ListaSetores)){
						?>
							<option value="<?= $setor['id_setor'] ?>"><?= $setor['setor'] ?></option>
						<?php
							}
						?>
					</select>


				

			</fieldset>



			


			<fieldset style='margin-top:20px;'>
				<legend>Otras Informações</legend>

					<label style="margin:0px 0px 0px 13px" class="control-label">Dt. Solicitação:</label>
						<input name="solicitacao" required class="input-medium" type="date">

					<label style="margin:0px 0px 0px 20px" class="control-label">Dt. Evento:</label>
						<input required name="data_evento" class="input-medium" type="date">

						<br>

					<label style="margin:15px 0px 0px 58px" class="control-label">Público:</label>
						<input name="publico" class="input-mini" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Responsável:</label>
						<input name="responsavel" class="input-large" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Voucher:</label>
						<input name="voucher" class="input-mini" type="text">
			</fieldset>

			<div class="form-actions">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
			</div>

			<!-- <fieldset style='margin-top:20px;'>
				<legend>Função</legend>

					<label style="margin:0px 0px 0px 30px" class="control-label">Quantidade:</label>
						<input class="input-mini" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Função:</label>
						<select name="" id="">
							<option value=""></option>
						</select>

					<label style="margin:0px 0px 0px 20px" class="control-label">Valor:</label>
						<div class="input-prepend input-append">
							<span class="add-on">R$</span>
						  <input id="valor" style='border-radius:0px 5px 5px 0px' class="input-mini" id="appendedPrependedInput" type="text">
						</div>

			</fieldset> -->

		</form>
	</div>
</div>
