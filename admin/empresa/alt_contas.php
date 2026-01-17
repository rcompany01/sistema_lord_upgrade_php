<?php
	// VERIFICA SE O ID FOI INFORMADO, CASO TENHA, LISTA A EMPRESA DE ACORDO COM O ID
	if (!empty($_GET['idAccount'])){
		$class->ListaContasID($_GET['idAccount']);
		$row = mysqli_fetch_assoc($class->ListaContasID);
	}


	// FAZ A ATUALIZAÇÃO DOS DADOS ATUAIS
	if (!empty($_POST['att_id_banco'])){
		$class->AlterarConta( addslashes($_POST['att_id_banco']),
							addslashes($_POST['att_num_banco']),
							addslashes($_POST['att_agencia']),
							addslashes($_POST['att_ag_dig']),
							addslashes($_POST['att_cc']),
							addslashes($_POST['att_dig_cc']),
							addslashes($_POST['att_carteira']),
							addslashes($_POST['att_convenio']),
							addslashes($_POST['att_id_prest']),
							$_GET['idAccount']);
	}
?>


	

<div id='formulario-att-conta' class="formulario-att-conta">
		<form class="form-inline" method="post">
			<fieldset>
				<legend>Dados da Conta</legend>
				

				<label style="margin:15px 0px 0px 56px" class="control-label">Prestador:</label>
					<select required class="span2 input-xlarge" name="att_id_prest" id="">
						<option value="">Selecione..</option>
						<?php
							// LISTA TODOS OS PRESTADORES EM UM SELECT
							$prest->ListaPrestadoresAlf();
							while($row_prest=mysqli_fetch_assoc($prest->ListaPrestadoresAlf)){
								$sel = "";
								if ($row_prest['id_prest']==$row['id_prestador']){
									$sel = "selected";
								}else{
									$sel = "";
								}
						?>
						<option <?= $sel ?> value="<?= $row_prest['id_prest'] ?>"><?= $row_prest['nome_prest'] ?></option>
						<?php
							}
						?>
					</select>
	
				<br>

				<label style="margin:15px 0px 0px 78px" class="control-label">Banco:</label>
					<select required class="span2" name="att_id_banco" id="">
						<option value="">Selecione..</option>
						<?php
							// LISTA TODOS OS BANCOS EM UM SELECT
							$banco->ListaBanco();
							while($row_banco=mysqli_fetch_assoc($banco->ListaBanco)){
								$sel = "";
								if ($row_banco['banco_id']==$row['id_banco']){
									$sel = "selected";
								}else{
									$sel = "";
								}
						?>
							<option <?= $sel ?> value="<?= $row_banco['banco_id'] ?>"><?= $row_banco['banco'] ?></option>
						<?php
							}
						?>
					</select>

				<label style="margin-left:20px" class="control-label">Num. Banco:</label>
					<input value="<?= $row['num_banco'] ?>" required name="att_num_banco" class="input-small" type="text">

<br>
				<label style="margin:15px 0px 0px 67px" class="control-label">Agência:</label>
					<input value="<?= $row['agencia'] ?>" required name="att_agencia" class="input-small" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Digito:</label>
					<input value="<?= $row['ag_digito'] ?>" name="att_ag_dig" class="input-mini" type="text">
<br>
				<label style="margin:15px 0px 0px 20px" class="control-label">Conta Corrente:</label>
					<input value="<?= $row['conta'] ?>" required name="att_cc" class="input-small" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Digito CC:</label>
					<input value="<?= $row['cc_digito'] ?>" name="att_dig_cc" class="input-mini" type="text">
<br>
				<label style="margin:15px 0px 0px 67px" class="control-label">Carteira:</label>
					<input value="<?= $row['carteira'] ?>" name="att_carteira" class="input-small" type="text">

				<label style="margin:15px 0px 0px 20px" class="control-label">Convênio:</label>
					<input value="<?= $row['convenio'] ?>" name="att_convenio" class="input-small" type="text">

			</fieldset>


			<div class="form-actions">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>			  
			</div>
		</form>
	</div>