<?php
	// VERIFICA SE O ID FOI INFORMADO, CASO TENHA, LISTA A EMPRESA DE ACORDO COM O ID
	if (!empty($_GET['id'])){
		$class->ListaEmpresasID($_GET['id']);
		$row = mysqli_fetch_assoc($class->ListaEmpresasID);
	}


	// FAZ A ATUALIZAÇÃO DOS DADOS ATUAIS
	if (!empty($_POST['att_nome_fantasia'])){
		$class->AtualizarEmpresa($_GET['id'],
							$_POST['att_nome_fantasia'],
							$_POST['att_razao_social'], 
							$_POST['att_cnpj'],
							$_POST['att_insc_estadual'],
							$_POST['att_cep'],
							$_POST['att_logradouro'], 
							$_POST['att_numero'],
							$_POST['att_compl'],
							$_POST['att_bairro'],
							$_POST['att_cidade'],
							$_POST['att_uf'],
							$_POST['att_tel'],
							$_POST['att_cel'],
							$_POST['att_site'],
							$_POST['att_email']);
	}
?>


<div id='formulario-alt-empresa' class="formulario-alt-empresa">
		<form id="form-att" class="form-inline" method="post">
			<fieldset>
				<legend>Dados da Empresa</legend>
				<label class="control-label">Nome Fantasia:</label>
					<input value="<?= $row['emp_nome_fantasia'] ?>" required name="att_nome_fantasia" type="text">

				<label style="margin-left:20px" class="control-label">Razão Social:</label>
					<input value="<?= $row['emp_razao_social'] ?>" required name="att_razao_social" class="input-xlarge" type="text">

				<br>

				<label style="margin:15px 0px 0px 60px" class="control-label">CNPJ:</label>
					<input value="<?= $row['emp_cnpj'] ?>" required name="att_cnpj" class="input-medium" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Insc. Estadual:</label>
					<input value="<?= $row['emp_insc_estadual'] ?>" required name="att_insc_estadual" class="input-medium" type="text">

			</fieldset>



			<fieldset style="margin-top:10px">
				<legend>Endereço</legend>

				<label style="margin:0px 0px 0px 68px" class="control-label">CEP:</label>
					<input value="<?= $row['emp_cep'] ?>" required name="att_cep" maxlength="8" id="cep" class="input-small" type="text">
					<small>Apenas Números</small>

				<br>

				<label style="margin:15px 0px 0px 22px" class="control-label">Logradouro:</label>
					<input value="<?= $row['emp_logradouro'] ?>" required name="att_logradouro" id="rua" class="input-xlarge" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Número:</label>
					<input value="<?= $row['emp_numero'] ?>" required name="att_numero" class="input-mini" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Complemento:</label>
					<input value="<?= $row['emp_compl'] ?>" name="att_compl" class="input-mini" type="text">

				<label style="margin:15px 0px 0px 57px" class="control-label">Bairro:</label>
					<input value="<?= $row['emp_bairro'] ?>" required name="att_bairro" id="bairro" class="input-large" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Cidade:</label>
					<input value="<?= $row['emp_cidade'] ?>" required name="att_cidade" id="cidade" class="input-medium" type="text">
				

				<label style="margin:0px 0px 0px 20px" class="control-label">UF:</label>
					<input value="<?= $row['emp_uf'] ?>" required name="att_uf" id="uf" class="input-mini" type="text">

			</fieldset>




			<fieldset>
				<legend>Contato</legend>

				<label style="margin:0px 0px 0px 41px" class="control-label">Telefone:</label>
					<input value="<?= $row['emp_telefone'] ?>" required name="att_tel" class="input-medium" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Celular:</label>
					<input value="<?= $row['emp_celular'] ?>" name="att_cel" class="input-medium" type="text">

				<br>

				<label style="margin:0px 0px 0px 73px" class="control-label">Site:</label>
					<input value="<?= $row['emp_site'] ?>" name="att_site" class="input-large" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Email:</label>
					<input value="<?= $row['emp_email'] ?>" required name="att_email" class="input-large" type="text">
			</fieldset>



			<!-- <fieldset style='margin-top:10px;'>
				<legend>Imposto de Renda</legend>

			<div class="descontos">
				<label style="margin:0px 0px 0px 41px" class="control-label">Acima de:</label>
					<input class="input-small" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Descontar:</label>
					<div class="input-prepend input-append">
					  <input style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>
			

				<label style="margin:0px 0px 0px 20px" class="control-label">Deduções:</label>
					<div class="input-prepend input-append">
						<span class="add-on">R$</span>
					  <input style='border-radius:0px 5px 5px 0px' class="input-mini" id="appendedPrependedInput" type="text">
					</div>
			</div>

			
			<div class="inss">
				<label style="margin:0px 0px 0px 51px" class="control-label">INSS:</label>
					<div class="input-prepend input-append">
					  <input style='border-radius:5px 0px 0px 5px' class="input-mini" id="appendedPrependedInput" type="text">
					  <span class="add-on">%</span>
					</div>


				<label style="margin:15px 0px 0px 16px" class="control-label">Teto INSS:</label>
					<div class="input-prepend input-append">
						<span class="add-on">R$</span>
					  	<input style='border-radius:0px 5px 5px 0px' class="input-mini" id="appendedPrependedInput" type="text">
					</div>

				<label style="margin:15px 0px 0px 4px" class="control-label">Dependente:</label>
					<div class="input-prepend input-append">
						<span class="add-on">R$</span>
					  	<input style='border-radius:0px 5px 5px 0px' class="input-mini" id="appendedPrependedInput" type="text">
					</div>

				<label style="margin:15px 0px 0px 8px" class="control-label">Tx. Cheque:</label>
					<div class="input-prepend input-append">
						<span class="add-on">R$</span>
					  	<input style='border-radius:0px 5px 5px 0px' class="input-mini" id="appendedPrependedInput" type="text">
					</div>
			</div>

			</fieldset> -->


			<div class="form-actions">
				<button onClick="window.location.href='?funcao=dados'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' class="btn btn-primary">Atualizar</button>			  
			</div>
		</form>
	</div>