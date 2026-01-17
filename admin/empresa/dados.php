<script src="//code.jquery.com/jquery-2.1.4.min.js">
	
</script>

<script type="text/javascript">
	function NovaEmpresa(){
	$("#formulario-nova-empresa").fadeIn('slow');
	document.getElementById('tabela-empresas').style.display="none";
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
	// FAZ A CONFIRMAÇÃO DE REMOVER EMPRESA
?>
    <script type="text/javascript">
    	function confirmacao(id) {
		     var resposta = confirm("Deseja remover esse registro?");
		 
		     if (resposta == true) {
		          window.location.href = "?funcao=dados&delete="+id;
		     }
		}
    </script>

<?php
	require_once('class/empresa.class.php');
	$class = new Empresas;
	// CADASTRA UMA NOVA EMPRESA
	if (isset($_POST['nome_fantasia'])){
		$class->NovaEmpresa($_POST['nome_fantasia'],
							$_POST['razao_social'], 
							$_POST['cnpj'],
							$_POST['insc_estadual'],
							$_POST['cep'],
							$_POST['logradouro'], 
							$_POST['numero'],
							$_POST['compl'],
							$_POST['bairro'],
							$_POST['cidade'],
							$_POST['uf'],
							$_POST['tel'],
							$_POST['cel'],
							$_POST['site'],
							$_POST['email']);
	}


	// EXCLUSAO DO REGISTRO
	if (isset($_GET['delete'])){
		$class->DeletarEmpresa($_GET['delete']);
	}		
?>

<?php
	// FAZ O INCLUDE DA PAGINA DE ALTERACAO DOS DADOS
	if (isset($_GET['id'])){
		// ARQUIVO DE ALTERACAO DOS DADOS
		require_once('alt_dados.php');
	}else{
?>


<div class="cx-empresa-dados">
	
	<div id="cx-funcoes" class="cx-funcoes"></a>
		<img style="cursor:pointer" onClick="NovaEmpresa()" src="../img/bt_novo1.png" alt="">
		<img onClick="Impressao()" style="cursor:pointer" id='imprimir' src="../img/bt_imprimir1.png" alt="">
	</div>

	<table id='tabela-empresas' class="table table-striped">
		<tr style="font-weight:bold">
			<td>Nome Fantasia</td>
			<td>Razão Social</td>
			<td>CNPJ</td>
			<td>Endereço</td>
			<td>Número</td>
			<td>Ações</td>
		</tr>
<?php
	$class->ListaEmpresas();
	while ($row=mysqli_fetch_assoc($class->ListaEmpresas)){
?>
		<tr>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&id=<?= $row['emp_id'] ?>'"><?= $row['emp_nome_fantasia'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&id=<?= $row['emp_id'] ?>'"><?= $row['emp_razao_social'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&id=<?= $row['emp_id'] ?>'"><?= $row['emp_cnpj'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&id=<?= $row['emp_id'] ?>'"><?= $row['emp_logradouro'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&id=<?= $row['emp_id'] ?>'"><?= $row['emp_numero'] ?></td>
			<td>
				<button onClick="confirmacao(<?= $row['emp_id'] ?>)" class="btn btn-danger">Excluir</button>
			</td>
		</tr>
<?php
	}
?>
	</table>




	<div id='formulario-nova-empresa' class="formulario-nova-empresa">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados da Empresa</legend>
				<label class="control-label">Nome Fantasia:</label>
					<input required name="nome_fantasia" type="text">

				<label style="margin-left:20px" class="control-label">Razão Social:</label>
					<input required name="razao_social" class="input-xlarge" type="text">

				<br>

				<label style="margin:15px 0px 0px 60px" class="control-label">CNPJ:</label>
					<input required name="cnpj" class="input-medium" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Insc. Estadual:</label>
					<input required name="insc_estadual" class="input-medium" type="text">

			</fieldset>



			<fieldset style="margin-top:10px">
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




			<fieldset>
				<legend>Contato</legend>

				<label style="margin:0px 0px 0px 41px" class="control-label">Telefone:</label>
					<input required name="tel" class="input-medium" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Celular:</label>
					<input name="cel" class="input-medium" type="text">

				<br>

				<label style="margin:0px 0px 0px 73px" class="control-label">Site:</label>
					<input name="site" class="input-large" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Email:</label>
					<input required name="email" class="input-large" type="text">
			</fieldset>

			<div class="form-actions">
				<button onClick="window.location.href='?funcao=dados'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
			</div>
		</form>
	</div>

	<?php
		}
	?>

</div>