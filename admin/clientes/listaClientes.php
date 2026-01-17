<script type="text/javascript">
	function NovaEmpresa(){
	$("#formulario-novo-cliente").fadeIn('slow');
	document.getElementById('tabela-contas').style.display="none";
	document.getElementById('cx-funcoes').style.display="none";	
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
	
	require_once('class/funcoes.class.php');
	$funcao = new Funcoes;

	require_once('class/clientes.class.php');
	$class = new Clientes;


	// CADASTRA UM NOVO CLIENTE
	if (isset($_POST['nome_fantasia'])){
		$class->NovoCliente($_POST['nome_fantasia'],
							$_POST['razao'],
							$_POST['cnpj'],
							$_POST['incricao'],
							$_POST['cep'],
							$_POST['logradouro'],
							$_POST['numero'],
							$_POST['compl'],
							$_POST['bairro'],
							$_POST['cidade'],
							$_POST['uf'],
							$_POST['tel'],
							$_POST['cel'],
							$_POST['email'],
							$_POST['inss'],
							$_POST['pis'],
							$_POST['irrf'],
							$_POST['iss'],
							$_POST['vencimento'],
							$_POST['faturamento']);
	}
?>

<?php
	// FAZ A CONFIRMAÇÃO DE REMOVER O REGISTRO
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=listaClientes&deleteCliente="+id;
		     }
		}
    </script>

 <?php
 	// DELETA O DEPARTAMENTO
 	if (isset($_GET['deleteCliente'])){
 		$class->DeletarCliente($_GET['deleteCliente']);
 	}
 ?>


<?php
	if (isset($_GET['idCliente'])){
		require_once('alt_clientes.php');
	}else{
?>
<div class="cx-lista-clientes">
	
	<div id="cx-funcoes" class="cx-funcoes"></a>
		<img style="cursor:pointer" onClick="NovaEmpresa()" src="../img/bt_novo1.png" alt="">
		<img style="cursor:pointer" id='imprimir' src="../img/bt_imprimir1.png" alt="">
	</div>

	<table id='tabela-contas' class="table table-striped">
		<tr style="font-weight:bold">
			<td>ID</td>
			<td>Nome Fantasia</td>
			<td>Bairro</td>
			<td>Telefone</td>
			<td>Tipo Informe INSS</td>
			<td>Ações</td>
		</tr>
<?php
	$class->ListaClientes();
	while($row=mysqli_fetch_assoc($class->ListaClientes)){
?>
		<tr>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idCliente=<?= $row['id_cl'] ?>'"><?= $row['id_cl'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idCliente=<?= $row['id_cl'] ?>'"><?= $row['cl_nome_fantasia'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idCliente=<?= $row['id_cl'] ?>'"><?= $row['cl_bairro'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idCliente=<?= $row['id_cl'] ?>'"><?= $row['cl_telefone'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idCliente=<?= $row['id_cl'] ?>'"><?= $row['tipo_inss'] ?></td>
			<td>
				<button onClick="confirmacao(<?= $row['id_cl'] ?>)" class="btn btn-danger">Excluir</button>
			</td>
		</tr>
<?php
	}
?>
	</table>

	<div id='formulario-novo-cliente' class="formulario-novo-cliente">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados do Cliente</legend>
				<label style="margin:15px 0px 0px 0px" class="control-label">Nome Fantasia:</label>
					<input required name="nome_fantasia" type="text" class="input-large">

				<label style="margin:15px 0px 0px 20px" class="control-label">Razão Social:</label>
					<input required name="razao" type="text" class="input-medium">
	
				<br>

				<label style="margin:15px 0px 0px 61px" class="control-label">CNPJ:</label>
					<input required name="cnpj" type="text" class="input-medium">
				
				<label style="margin:15px 0px 0px 20px" class="control-label">Insc. Estadual:</label>
					<input required name="incricao" type="text" class="input-medium">

			</fieldset>
	
		
		<fieldset style="margin-top:20px">
			<legend>Endereço</legend>		
				<label style="margin:0px 0px 0px 68px" class="control-label">CEP:</label>
					<input required name="cep" maxlength="8" id="cep" class="input-small" type="text">
					<small>Apenas Números</small>

				<br>

				<label style="margin:15px 0px 0px 22px" class="control-label">Logradouro:</label>
					<input required name="logradouro" id="rua" class="input-xlarge" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Número:</label>
					<input required name="numero" class="input-mini" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Complemento:</label>
					<input name="compl" class="input-mini" type="text">

				<label style="margin:15px 0px 0px 57px" class="control-label">Bairro:</label>
					<input required name="bairro" id="bairro" class="input-large" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Cidade:</label>
					<input required name="cidade" id="cidade" class="input-medium" type="text">
				

				<label style="margin:0px 0px 0px 20px" class="control-label">UF:</label>
					<input required name="uf" id="uf" class="input-mini" type="text">
		</fieldset>


			<fieldset style="margin-top:20px">
				<legend>Contato</legend>
				<label style="margin:0px 0px 0px 40px" class="control-label">Telefone:</label>
					<input required name="tel" class="input-medium" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Celular:</label>
					<input name="cel" class="input-medium" type="text">

				<br>

				<label style="margin: 15px 0px 0px 62px" class="control-label">Email:</label>
					<input name="email" class="input-large" type="email">

			</fieldset>


			<fieldset style="margin-top:20px">
				<legend>Outras Informações</legend>

				<label style="margin:0px 0px 0px 30px" class="control-label">Tipo INSS:</label>
					<select name="inss" id="">
						<option value=""></option>
						<option value="Individual">Individual</option>
						<option value="Coletiva">Coletiva</option>
					</select>
				<br>

				<label style="margin:15px 0px 0px 0px" class="control-label">Dt. Vencimento:</label>
					<input name="vencimento" class="input-mini" type="text">


				<label style="margin:15px 0px 0px 20px" class="control-label">Tipo de Faturamento:</label>
					<input name="faturamento" class="input-medium" type="text">
			</fieldset>




			<fieldset style="margin-top:20px">
				<legend>Impostos e Recolhimentos</legend>

				<label style="margin:0px 0px 0px 70px" class="control-label">PIS:</label>
					<div class="input-prepend input-append">
					  <input name="pis" style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>


				<label style="margin:0px 0px 0px 20px" class="control-label">IRRF:</label>
					<div class="input-prepend input-append">
					  <input name="irrf" style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>

				<label style="margin:0px 0px 0px 20px" class="control-label">ISS:</label>
					<div class="input-prepend input-append">
					  <input name="iss" style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>
			</fieldset>



<!-- 
			<fieldset style="margin-top:20px">
				<legend>Valor da Hora Trabalhada</legend>


				<label style="margin:0px 0px 0px 45px" class="control-label">Função:</label>
					<select class="input-large" name="funcao" id="">
						<option value=""></option>
					</select>


				<label style="margin:0px 0px 0px 20px" class="control-label">Vlr Faturamento:</label>
					<div class="input-prepend input-append">
					  <input style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>


				<label style="margin:0px 0px 0px 20px" class="control-label">Vlr Repasse:</label>
					<div class="input-prepend input-append">
					  <input style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>
			</fieldset> -->


			<div class="form-actions">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
			</div>
		</form>
	</div>
</div>

<?php
	}
?>