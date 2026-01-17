<input type="hidden" id="idCliente" value="<?= $_GET['idCliente'] ?>">
<?php
// VERIFICA SE O ID FOI INFORMADO, CASO TENHA, LISTA O CLIENTE DE ACORDO COM O ID
	if (!empty($_GET['idCliente'])){
		$class->ListaClientesID($_GET['idCliente']);
		$row = mysqli_fetch_assoc($class->ListaClientesID);
	}


	// FAZ A ATUALIZAÇÃO DOS DADOS ATUAIS
	if (isset($_POST['att_cl_nome_fantasia'])){
		$class->AlterarCliente($_POST['att_cl_nome_fantasia'],
							$_POST['att_razao'],
							$_POST['att_cnpj'],
							$_POST['att_incricao'],
							$_POST['att_cep'],
							$_POST['att_logradouro'],
							$_POST['att_numero'],
							$_POST['att_compl'],
							$_POST['att_cl_bairro'],
							$_POST['att_cidade'],
							$_POST['att_uf'],
							$_POST['att_cl_telefone'],
							$_POST['att_cl_celular'],
							$_POST['att_email'],
							$_POST['att_cl_inss'],
							$_POST['att_pis'],
							$_POST['att_irrf'],
							$_POST['att_iss'],
							$_POST['att_vencimento'],
							$_POST['att_faturamento'],
							$_GET['idCliente']);
	}


	// DELETA A FUNCAO DO CLIENTE
	if (isset($_GET['DelFunc'])){
		$id = $_GET['DelFunc'];
		$class->DeletarFuncaoCliente($id);
		?>
				<script type="text/javascript">				
					window.location.href="?funcao=listaClientes&idCliente=" + document.getElementById('idCliente').value;
				</script>
			<?php
	}

?>

<div id='formulario-att-cliente' class="formulario-att-cliente">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados do Cliente</legend>
				<label style="margin:15px 0px 0px 0px" class="control-label">Nome Fantasia:</label>
					<input value="<?= $row['cl_nome_fantasia'] ?>" required name="att_cl_nome_fantasia" type="text" class="input-large">

				<label style="margin:15px 0px 0px 20px" class="control-label">Razão Social:</label>
					<input value="<?= $row['cl_razao'] ?>" required name="att_razao" type="text" class="input-medium">
	
				<br>

				<label style="margin:15px 0px 0px 61px" class="control-label">CNPJ:</label>
					<input value="<?= $row['cl_cnpj'] ?>" required name="att_cnpj" type="text" class="input-medium">
				
				<label style="margin:15px 0px 0px 20px" class="control-label">Insc. Estadual:</label>
					<input value="<?= $row['cl_inscricao'] ?>" required name="att_incricao" type="text" class="input-medium">

			</fieldset>
	
		
		<fieldset style="margin-top:20px">
			<legend>Endereço</legend>		
				<label style="margin:0px 0px 0px 68px" class="control-label">CEP:</label>
					<input value="<?= $row['cl_cep'] ?>" required name="att_cep" maxlength="8" id="cep" class="input-small" type="text">
					<small>Apenas Números</small>

				<br>

				<label style="margin:15px 0px 0px 22px" class="control-label">Logradouro:</label>
					<input value="<?= $row['cl_rua'] ?>" required name="att_logradouro" id="rua" class="input-xlarge" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Número:</label>
					<input value="<?= $row['cl_numero'] ?>" required name="att_numero" class="input-mini" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Complemento:</label>
					<input value="<?= $row['cl_compl'] ?>" name="att_compl" class="input-mini" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Bairro:</label>
					<input value="<?= $row['cl_bairro'] ?>" required name="att_cl_bairro" id="bairro" class="input-large" type="text">

				<label style="margin:15px 0px 0px 50px" class="control-label">Cidade:</label>
					<input value="<?= $row['cl_cidade'] ?>" required name="att_cidade" id="cidade" class="input-medium" type="text">
				

				<label style="margin:0px 0px 0px 20px" class="control-label">UF:</label>
					<input value="<?= $row['cl_uf'] ?>" required name="att_uf" id="uf" class="input-mini" type="text">
		</fieldset>


			<fieldset style="margin-top:20px">
				<legend>Contato</legend>
				<label style="margin:0px 0px 0px 40px" class="control-label">Telefone:</label>
					<input value="<?= $row['cl_telefone'] ?>" required name="att_cl_telefone" class="input-medium" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Celular:</label>
					<input value="<?= $row['cl_celular'] ?>" name="att_cl_celular" class="input-medium" type="text">

				<br>

				<label style="margin: 15px 0px 0px 62px" class="control-label">Email:</label>
					<input value="<?= $row['cl_email'] ?>" name="att_email" class="input-large" type="email">

			</fieldset>

<?php
	$ind ="";
	$col = "";
	if($row['tipo_inss']=='Individual'){
		$ind ="selected";
	} if ($row['tipo_inss']=='Coletiva'){
		$col ="selected";
	}
?>
			<fieldset style="margin-top:20px">
				<legend>Outras Informações</legend>

				<label style="margin:0px 0px 0px 30px" class="control-label">Tipo INSS:</label>
					<select name="att_cl_inss" id="">
						<option value=""></option>
						<option <?= $ind; ?> value="Individual">Individual</option>
						<option <?= $col; ?> value="Coletiva">Coletiva</option>
					</select>
				<br>

				<label style="margin:15px 0px 0px 0px" class="control-label">Dt. Vencimento:</label>
					<input value="<?= $row['vencimento'] ?>" name="att_vencimento" class="input-mini" type="text">


				<label style="margin:15px 0px 0px 20px" class="control-label">Tipo de Faturamento:</label>
					<input value="<?= $row['faturamento'] ?>" name="att_faturamento" class="input-medium" type="text">
			</fieldset>




			<fieldset style="margin-top:20px">
				<legend>Impostos e Recolhimentos</legend>

				<label style="margin:0px 0px 0px 70px" class="control-label">PIS:</label>
					<div class="input-prepend input-append">
					  <input value="<?= $row['pis'] ?>" name="att_pis" style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>


				<label style="margin:0px 0px 0px 20px" class="control-label">IRRF:</label>
					<div class="input-prepend input-append">
					  <input value="<?= $row['irrf'] ?>" name="att_irrf" style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>

				<label style="margin:0px 0px 0px 20px" class="control-label">ISS:</label>
					<div class="input-prepend input-append">
					  <input value="<?= $row['iss'] ?>" name="att_iss" style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>
			</fieldset>


			<div class="form-actions">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Atualizar</button>			  
			</div>
		</form>


<?php

	// busca as funcoes cadastradas e lista em options
	$funcao->ListaFuncoes();


	// caso o post tenha sido enviado, insere uma novo registro
		if (isset($_POST['funcao'])){
			$class->InserirFuncaoCliente($_POST['id_cliente'], $_POST['funcao'], $_POST['vl_faturamento'], $_POST['vl_repasse']);
		} 


	// CASO NAO TENHA SIDO SOLICITADO A ALTERACAO DAS FUNCOES EXIBE NORMAMENTE O FORUMLARIO
	if (isset($_GET['idFunc'])){
		include('att_funcao_cliente.php');
	}else{
?>

		<form class="form-inline" method="post">			
			<input type="hidden" id="idCliente" name="id_cliente" value="<?= $_GET['idCliente'] ?>">
			<fieldset style="margin-top:20px">
				<legend>Valor da Hora Trabalhada</legend>


				<label style="margin:0px 0px 0px 0px" class="control-label">Função:</label>
					<select required class="input-large" name="funcao" id="">
						<option value="">Selecione a função</option>
						<?php
							while($func=mysqli_fetch_assoc($funcao->ListaFuncoes)){
						?>
							<option value="<?= $func['id_func'] ?>">
								<?= $func['funcao'] ?>
							</option>
						<?php
							}
						?>
					</select>


				<label style="margin:0px 0px 0px 20px" class="control-label">Vlr Faturamento:</label>
					<div class="input-prepend input-append">
						<span class="add-on">R$</span>
					  <input data-thousands="" data-decimal="." id="valor" required name="vl_faturamento" style='border-radius:0px 5px 5px 0px' class="input-mini" type="text">				  
					</div>


				<label style="margin:0px 0px 0px 20px" class="control-label">Vlr Repasse:</label>
					<div class="input-prepend input-append">
						<span class="add-on">R$</span>
					  <input data-thousands="" data-decimal="." id="repasse" required name="vl_repasse" style='border-radius:0px 5px 5px 0px' class="input-mini" type="text">
					</div>
					<button style='margin-left:10px' type="submit" class="btn btn-success">Inserir</button>

			</fieldset>
		</form>

<?php
	}
?>

			<table style="margin-top:20px;width:713px" id='tabela-contas' class="table table-striped">
				<tr>
					<td>Função</td>
					<td>Valor Faturamento</td>
					<td>Valor Repasse</td>
					<td>Ações</td>
				</tr>
<?php
	// BUSCA AS FUNÇÕES INSERIDAS NO CLIENTE
	$class->BuscaFuncoesClientes($_GET['idCliente']);
	while($func=mysqli_fetch_assoc($class->BuscaFuncoesClientes)){
?>
				<tr>
					<td><?= $class->BuscaFuncao($func['funcao']) ?></td>
					<td><?= $func['vl_faturamento'] ?></td>
					<td><?= $func['vl_repasse'] ?></td>
					<td>
						<button onClick="window.location.href='?funcao=listaClientes&idCliente=<?= $_GET['idCliente'] ?>&idFunc=<?= $func['id'] ?>'" style='margin-left:10px' class="btn btn-warning">Alterar</button>

						<button onClick="window.location.href='?funcao=listaClientes&idCliente=<?= $_GET['idCliente'] ?>&DelFunc=<?= $func['id'] ?>'" style='margin-left:10px' class="btn btn-danger">Excluir</button>
					</td>
				</tr>
<?php
	}
?>
			</table>

		
	</div>

