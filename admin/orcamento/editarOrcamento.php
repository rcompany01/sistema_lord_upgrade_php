<?php

	// BUSCA OS DADOS DO ORCAMENTO
	$class->BuscaOrcamentoID($_GET['EditarOrc']);
	$vt = mysqli_fetch_assoc($class->BuscaOrcamentoID);


	// ATUALIZA OS DADOS DO ORCAMENTO
	if (isset($_POST['att_solicitante'])){
		$class->AtualizaOrcamento(	$_POST['att_solicitante'],
									$_POST['att_cep'],
									$_POST['att_rua'],
									$_POST['att_numero'],
									$_POST['att_compl'],
									$_POST['att_bairro'],
									$_POST['att_cidade'],
									$_POST['att_uf'],
									$_POST['att_evento'],
									$_POST['att_solicitacao'],
									$_POST['att_data_evento'],
									$_POST['att_publico'],
									$_POST['att_responsavel'],
									$_POST['att_voucher'],
									$_POST['att_id_escala'],
									$_GET['EditarOrc']);
									?>
										<script type="text/javascript">
											alert('Dados Atualizados!');
											window.location.href="?funcao=orcamento";
										</script>
									<?php
	}
?>



<div id='formulario-editar-orcamento' class="formulario-editar-orcamento">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados do Orçamento</legend>
				
				<label style="margin:15px 0px 0px 18px" class="control-label">ID da Escala:</label>
					<input value="<?= $vt['id_escala'] ?>" required type="text" class="input-mini" name="att_id_escala"> <br>


				<label style="margin:15px 0px 0px 33px" class="control-label">Solicitante:</label>
					<input value="<?= $vt['solicitante_orc'] ?>" required name="att_solicitante" type="text">

				<br>

				<label style="margin:15px 0px 0px 71px" class="control-label">CEP:</label>
					<input value="<?= $vt['cep_orc'] ?>" required name="att_cep" id="cep" class="input-small" type="text">
					<small>Sem traços, apenas números.</small>

				<br>

				<label style="margin:15px 0px 0px 25px" class="control-label">Logradouro:</label>
					<input value="<?= $vt['rua_orc'] ?>" required name="att_rua" id="rua" class="input-xlarge" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Número:</label>
					<input value="<?= $vt['num_orc'] ?>" required name="att_numero" class="input-mini" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Complemento:</label>
					<input value="<?= $vt['compl_orc'] ?>" name="att_compl" class="input-mini" type="text">
<br>
				<label style="margin:15px 0px 0px 62px" class="control-label">Bairro:</label>
					<input value="<?= $vt['bairro_orc'] ?>" required name="att_bairro" id="bairro" class="input-large" type="text">

				<label style="margin:0px 0px 0px 20px" class="control-label">Cidade:</label>
					<input value="<?= $vt['cidade_orc'] ?>" required name="att_cidade" id="cidade" class="input-medium" type="text">
				

				<label style="margin:0px 0px 0px 20px" class="control-label">UF:</label>
					<input value="<?= $vt['uf_orc'] ?>" required name="att_uf" id="uf" class="input-mini" type="text">

				<br>

				<label style="margin:15px 0px 0px 56px" class="control-label">Evento:</label>
					<select required class="input-medium" name="att_evento" id="">
						<option value=""></option>
						<?php
							$set->ListaSetores();
							while ($setor=mysqli_fetch_assoc($set->ListaSetores)){
								$sel = "";
								if ($setor['id_setor']==$vt['evento_orc']){
									$sel = "selected";
								}else{
									$sel="";
								}
						?>
							<option <?= $sel ?> value="<?= $setor['id_setor'] ?>"><?= $setor['setor'] ?></option>
						<?php
							}
						?>
					</select>

			</fieldset>



			


			<fieldset style='margin-top:20px;'>
				<legend>Outras Informações</legend>

					<label style="margin:0px 0px 0px 13px" class="control-label">Dt. Solicitação:</label>
						<input value="<?= $vt['data_sol'] ?>" name="att_solicitacao" required class="input-medium" type="date">

					<label style="margin:0px 0px 0px 20px" class="control-label">Dt. Evento:</label>
						<input value="<?= $vt['data_evento'] ?>" required name="att_data_evento" class="input-medium" type="date">

						<br>

					<label style="margin:15px 0px 0px 58px" class="control-label">Público:</label>
						<input value="<?= $vt['publico'] ?>" name="att_publico" class="input-mini" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Responsável:</label>
						<input value="<?= $vt['responsavel'] ?>" name="att_responsavel" class="input-large" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Voucher:</label>
						<input value="<?= $vt['voucher'] ?>" name="att_voucher" class="input-mini" type="text">
			</fieldset>

			<div class="form-actions">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Atualizar</button>			  
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