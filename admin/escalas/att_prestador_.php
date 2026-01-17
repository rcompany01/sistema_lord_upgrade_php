<input type="hidden" id="idEsc" value="<?= $_GET['idEsc'] ?>">
<input type="hidden" id="EditEsc" value="<?= $_GET['EditEsc'] ?>">

<?php
	// LISTA OS PRESTADORES E OS DADOS DA ESCALA
	$class->ListaPrestadorEscalaAtt($_GET['idEsc'], $_GET['EditEsc']);
	$alt = mysqli_fetch_assoc($class->ListaPrestadorEscalaAtt);


	// ATUALIZA A ESCALA DE UM PRESTADOR
	if (isset($_POST['att_add_prest'])){
		$idPrest=$_POST['att_add_prest'];
		$dataEvent=$row['data_evento'];
		$entrada=$_POST['att_entrada'];
		$saida=$_POST['att_saida'];
		$extra=$_POST['att_extra'];
		$funcao=$_POST['att_add_funcao'];
		$id=$_GET['EditEsc'];
		$fat = $_POST['att_fat'];
		$rep = $_POST['att_rep'];
		$extraFat = $_POST['att_ex_fat'];
		$extraRep = $_POST['att_ex_rep'];

		$class->AtualizaPrestadorEscala($idPrest, $dataEvent, $entrada, $saida,$extra, $funcao,$id,$fat,$rep,$extraFat,$extraRep);
		?>
			<script type="text/javascript">
				var idEsc = document.getElementById('idEsc').value;
				var editEsc = document.getElementById('EditEsc').value;
				window.location.href="?funcao=escalas&idEsc="+idEsc;
			</script>
		<?php
	}
?>

<div id="editPrest" class="opaca">
	<div class="edit-prest-esc">
		<form method="post" class="form-inline">
			<fieldset>
				<legend>Editar Prestador</legend>

				<label style="margin:10px 0px 0px 30px" class="control-label">Prestador:</label>
					<select required class="input-xlarge" name="att_add_prest" id="">
						<option value="">Selecione..</option>
						<?php
							$class->ListaPrestadorAtivo();
							while ($prest = mysqli_fetch_assoc($class->ListaPrestadorAtivo)){
								$sel = "";
								if ($prest['id_prest']==$alt['id_prestador']){
									$sel = "selected";
								}else{
									$sel = "";
								}
						?>
							<option <?= $sel ?> value="<?= $prest['id_prest'] ?>">
								<?= $prest['nome_prest'] ?>
							</option>
						<?php
							}
						?>
					</select>

					<br>

					<label style="margin:20px 0px 0px 20px" class="control-label">Hr. Entrada:</label>
						<input value="<?= $alt['entrada'] ?>" required type="time" name="att_entrada" class="input-small">

					<label style="margin:20px 0px 0px 10px" class="control-label">Hr. Saida:</label>
						<input value="<?= $alt['saida'] ?>" required type="time" name="att_saida" class="input-small">

					<label style="margin:20px 0px 0px 10px" class="control-label">Hr. Extra:</label>
						<input value="<?= $alt['extra'] ?>" required type="time" name="att_extra" class="input-small">
					

					<br>


					<label style="margin:20px 0px 0px 45px" class="control-label">Função:</label>
					<select required class="input-large" name="att_add_funcao" id="">
						<option value="">Selecione..</option>
						<?php
							$funcao->ListaFuncoes();
							while($func=mysqli_fetch_assoc($funcao->ListaFuncoes)){
								$sel = "";
								if ($func['id_func']==$alt['id_funcao']){
									$sel = "selected";
								}else{
									$sel = "";
								}
						?>
							<option <?= $sel ?> value="<?= $func['id_func'] ?>"><?= $func['funcao'] ?></option>
						<?php
							}
						?>
					</select>

					<br>

					<label style="margin:20px 0px 0px 10px" class="control-label">Faturamento:</label>
						<input id="valor1" value="<?= $alt['valor_fat'] ?>" required type="text" name="att_fat" class="input-small">

					<label style="margin:20px 0px 0px 10px" class="control-label">Repasse:</label>
						<input id="valor2" value="<?= $alt['valor_rep'] ?>" required type="text" name="att_rep" class="input-small">
					<br>

					<label style="margin:20px 0px 0px 20px" class="control-label">Extra (Fat.):</label>
						<input id="extra" value="<?= $alt['valor_extra_fat'] ?>" required type="text" name="att_ex_fat" class="input-small">

					<label style="margin:20px 0px 0px 10px" class="control-label">Extra (Rep.):</label>
						<input id="extra2" value="<?= $alt['valor_extra_rep'] ?>" required type="text" name="att_ex_rep" class="input-small">


			</fieldset>

			<div class="form-actions">
				<input onClick="fechaEdit(<?= $_GET['idEsc'] ?>)" value="Cancelar" style='float:right;margin-right:10px' type="button" class="btn btn-danger">
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-success">Atualizar</button>	  
			</div>
		</form>
	</div>
</div>
