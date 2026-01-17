<?php
// VERIFICA SE O ID FOI INFORMADO, CASO TENHA, LISTA A OS PRESTADORES DE ACORDO COM O ID
if (!empty($_GET['idPrest'])) {
	$class->ListaPrestadoresID($_GET['idPrest']);
	$row = mysqli_fetch_assoc($class->ListaPrestadoresID);
}


// FAZ A ATUALIZAÇÃO DOS DADOS ATUAIS
if (!empty($_POST['att_nome'])) {
	if ($_FILES['att_foto']['name'] == '') {
		$foto = $row['foto_prest'];
	} else {
		$foto = 'prestadores/fotos/' . $_FILES['att_foto']['name'];
	}
	$class->AttPrestador(
		$_GET['idPrest'],
		$_POST['att_nome'],
		$_POST['att_rg'],
		$_POST['att_orgao'],
		$_POST['att_expedicao'],
		$_POST['att_cpf'],
		$_POST['att_nacionalidade'],
		$_POST['att_nascimento'],
		$_POST['att_sexo'],
		$_POST['att_estado_civil'],
		$_POST['att_escolaridade'],
		$_POST['att_mae'],
		$_POST['att_pai'],
		$_POST['att_cep'],
		$_POST['att_logradouro'],
		$_POST['att_numero'],
		$_POST['att_compl'],
		$_POST['att_bairro'],
		$_POST['att_cidade'],
		$_POST['att_uf'],
		$_POST['att_tel'],
		$_POST['att_cel'],
		$_POST['att_email'],
		$foto,
		$_POST['att_inss'],
		$_POST['att_pis'],
		$_POST['att_ccm'],
		$_POST['att_titulo'],
		$_POST['att_zona'],
		$_POST['att_indicado']
	);
}
?>








<div id='formulario-att-prestador' class="formulario-att-prestador">
	<form enctype="multipart/form-data" class="form-inline" method="post">

		<fieldset style="margin-top:10px">
			<legend>Foto</legend>

			<?php
			// CORRECAO DE CAMINHO ABSOLUTO PARA RELATIVO (Performance)
			$foto_src = $row['foto_prest'];
			if (strpos($foto_src, 'C:/wamp/www/lorde/admin/') !== false) {
				$foto_src = str_replace('C:/wamp/www/lorde/admin/', '', $foto_src);
			}

			$nome_arquivo = basename($foto_src);

			// CASO NAO TENHA FOTO REGISTRADA
			if ($foto_src == "" || $foto_src == "prestadores/fotos/") {
				?>
				<img src="../img/sem_foto.png" id="preview" style="max-width:200px;">
				<?php
			} else {
				// Força o caminho relativo
				$url_exibicao = "prestadores/fotos/" . $nome_arquivo;
				?>
				<img src="<?= $url_exibicao ?>" onerror="this.src='../img/sem_foto.png'" id="preview"
					style="max-width:200px; max-height:200px; width: auto; height: auto; object-fit: contain; box-shadow: 2px 2px 5px rgba(0,0,0,0.2);">
				<?php
			}
			?>

			<label style="margin:0px 0px 0px 41px" class="control-label">Imagem:</label>
			<input name="att_foto" id="imgChooser" class="input-medium" type="file">
		</fieldset>



		<fieldset style="margin-top:30px">
			<legend>Dados do Prestador</legend>
			<label style="margin:0px 0px 0px 60px" class="control-label">Nome:</label>
			<input value="<?= strtoupper($row['nome_prest']) ?>" required name="att_nome" class="input-xlarge"
				type="text">

			<br>

			<label style="margin:15px 0px 0px 77px" class="control-label">RG:</label>
			<input value="<?= $row['rg_prest'] ?>" required name="att_rg" class="input-small" type="text">

			<label style="margin:15px 0px 0px 30px" class="control-label">Orgão Emissor:</label>
			<input value="<?= strtoupper($row['orgao_prest']) ?>" required name="att_orgao" class="input-small"
				type="text">

			<label style="margin:15px 0px 0px 30px" class="control-label">Dt. Expedição:</label>
			<input value="<?= $row['expedicao_prest'] ?>" required name="att_expedicao" class="input-medium"
				type="date">

			<br>

			<label style="margin:15px 0px 0px 70px" class="control-label">CPF:</label>
			<input value="<?= $row['cpf_prest'] ?>" required name="att_cpf" class="input-medium" type="text">



			<label style="margin:15px 0px 0px 20px" class="control-label">Nacionalidade:</label>
			<input value="<?= strtoupper($row['nacionalidade_prest']) ?>" required name="att_nacionalidade"
				class="input-medium" type="text">


			<label style="margin:15px 0px 0px 25px" class="control-label">Dt. de Nasc:</label>
			<input value="<?= $row['nascimento_prest'] ?>" required name="att_nascimento" class="input-medium"
				type="date">


			<br>
			<?php
			$fem = "";
			$masc = "";

			if ($row['sexo_prest'] == 'feminino') {
				$fem = "checked";
			} else {
				$masc = "";
			}

			if ($row['sexo_prest'] == 'masculino') {
				$masc = "checked";
			} else {
				$masc = "";
			}
			?>
			<label style="margin:15px 0px 0px 65px" class="control-label">Sexo:</label>
			<label class="radio">
				<input <?= $masc ?> type="radio" name="att_sexo" id="optionsRadios2" value="masculino">
				Masculino
			</label>

			<label class="radio">
				<input <?= $fem ?> type="radio" name="att_sexo" id="optionsRadios2" value="feminino">
				Feminino
			</label>

			<br>
			<?php

			// FAZ COM QUE A OPTION FIQUE SELECIONADA DE ACORDO COM O REGISTRO NO BD
			
			$casado = "";
			$solteiro = "";
			$divorciado = "";
			$viuvo = "";
			switch ($row['est_civil']) {
				case 'Casado':
					$casado = "selected";
					break;

				case 'Solteiro':
					$solteiro = "selected";
					break;

				case 'Divorciado':
					$divorciado = "selected";
					break;

				case 'Viuvo':
					$viuvo = "selected";
					break;


			}
			?>
			<label style="margin:15px 0px 0px 22px" class="control-label">Estado Civil:</label>
			<select required class="input-medium" name="att_estado_civil" id="">
				<option value="">Selecione</option>
				<option <?= $casado; ?> value="Casado">Casado(a)</option>
				<option <?= $solteiro; ?> value="Solteiro">Solteiro(a)</option>
				<option <?= $divorciado; ?> value="Divorciado">Divorciado(a)</option>
				<option <?= $viuvo; ?> value="Viuvo">Viúvo(a)</option>
			</select>



			<?php

			// FAZ COM QUE A OPTION FIQUE SELECIONADA DE ACORDO COM O REGISTRO NO BD
			
			$fundamental = "";
			$medio = "";
			$superior = "";
			$graduacao = "";
			switch ($row['escolaridade']) {
				case 'Fundamental':
					$fundamental = "selected";
					break;

				case 'Medio':
					$medio = "selected";
					break;

				case 'Superior':
					$superior = "selected";
					break;

				case 'Graduacao':
					$graduacao = "selected";
					break;


			}
			?>
			<label style="margin:15px 0px 0px 22px" class="control-label">Escolaridade:</label>
			<select class="input-large" name="att_escolaridade" id="">
				<option value="">Selecione</option>
				<option <?= $fundamental ?> value="Fundamental">Ensino Fundamental</option>
				<option <?= $medio ?> value="Medio">Ensino Médio</option>
				<option <?= $superior ?> value="Superior">Ensino Superior</option>
				<option <?= $graduacao ?> value="Graduacao">Pós Graduação</option>
			</select>

			<br>


			<label style="margin:15px 0px 0px 7px" class="control-label">Nome da Mãe:</label>
			<input value="<?= strtoupper($row['mae']) ?>" required name="att_mae" class="input-xlarge" type="text">


			<br>


			<label style="margin:15px 0px 0px 15px" class="control-label">Nome do Pai:</label>
			<input value="<?= strtoupper($row['pai']) ?>" required name="att_pai" class="input-xlarge" type="text">

		</fieldset>



		<fieldset style="margin-top:10px">
			<legend>Endereço</legend>

			<label style="margin:0px 0px 0px 68px" class="control-label">CEP:</label>
			<input value="<?= $row['cep_prest'] ?>" id="cep" required name="att_cep" class="input-small" type="text">
			<small>Sem traço, apenas números.</small>

			<br>

			<label style="margin:15px 0px 0px 22px" class="control-label">Logradouro:</label>
			<input value="<?= strtoupper($row['logradouro_prest']) ?>" id="rua" required name="att_logradouro"
				class="input-xlarge" type="text">

			<label style="margin:0px 0px 0px 20px" class="control-label">Número:</label>
			<input value="<?= $row['numero_prest'] ?>" required name="att_numero" class="input-mini" type="text">

			<label style="margin:0px 0px 0px 20px" class="control-label">Complemento:</label>
			<input value="<?= strtoupper($row['compl_prest']) ?>" name="att_compl" class="input-mini" type="text">
			<br>
			<label style="margin:15px 0px 0px 57px" class="control-label">Bairro:</label>
			<input value="<?= strtoupper($row['bairro_prest']) ?>" id="bairro" required name="att_bairro"
				class="input-large" type="text">

			<label style="margin:0px 0px 0px 20px" class="control-label">Cidade:</label>
			<input value="<?= strtoupper($row['cidade_prest']) ?>" id="cidade" required name="att_cidade"
				class="input-medium" type="text">


			<label style="margin:0px 0px 0px 20px" class="control-label">UF:</label>
			<input value="<?= strtoupper($row['uf_prest']) ?>" id="uf" required name="att_uf" class="input-mini"
				type="text">

		</fieldset>

		<fieldset style="margin-top:10px">
			<legend>Contato</legend>

			<label style="margin:0px 0px 0px 41px" class="control-label">Telefone:</label>
			<input value="<?= $row['tel_prest'] ?>" required name="att_tel" class="input-medium" type="text">

			<label style="margin:0px 0px 0px 20px" class="control-label">Celular:</label>
			<input value="<?= $row['cel_prest'] ?>" name="att_cel" class="input-medium" type="text">

			<br>

			<label style="margin:15px 0px 0px 62px" class="control-label">Email:</label>
			<input value="<?= strtoupper($row['email_prest']) ?>" name="att_email" class="input-large" type="email">
		</fieldset>


		<fieldset style="margin-top:10px">
			<legend>Outras Informações</legend>

			<label style="margin:0px 0px 0px 41px" class="control-label">INSS:</label>
			<input value="<?= $row['inss'] ?>" required name="att_inss" class="input-medium" type="text">

			<label style="margin:0px 0px 0px 20px" class="control-label">PIS:</label>
			<input value="<?= $row['pis'] ?>" name="att_pis" class="input-medium" type="text">

			<label style="margin:15px 0px 0px 20px" class="control-label">CCM:</label>
			<input value="<?= $row['ccm'] ?>" name="att_ccm" class="input-medium" type="text">

			<br>

			<label style="margin:15px 0px 0px 37px" class="control-label">Titulo:</label>
			<input value="<?= $row['titulo'] ?>" name="att_titulo" class="input-medium" type="text">

			<label style="margin:15px 0px 0px 20px" class="control-label">Zona:</label>
			<input value="<?= $row['zona'] ?>" name="att_zona" class="input-medium" type="text">

			<label style="margin:15px 0px 0px 20px" class="control-label">Indicado Por:</label>
			<input value="<?= strtoupper($row['indicado']) ?>" name="att_indicado" class="input-medium" type="text">

		</fieldset>


		<div class="form-actions">
			<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button"
				class="btn">Cancelar</button>
			<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>
		</div>
</div>
</form>
</div>