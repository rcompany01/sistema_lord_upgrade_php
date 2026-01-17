<?php
	// BUSCA OS DADOS DO DESCONTO
		if (isset($_GET['AlterarDesc'])){
			$class->BuscaDescontoID($_GET['AlterarDesc']);
			$datafiles = mysqli_fetch_assoc($class->BuscaDescontoID);
		}

	// ATUALIZA OS DADOS DO DESCONTO
		if (isset($_POST['alt_prest'])){
			$id_prestador=$_POST['alt_prest'];
			$valor=$_POST['alt_valor'];
			$data_sol=$_POST['alt_data_sol'];
			$dia=$_POST['alt_dia'];
			$descricao=$_POST['alt_descricao'];
			$id = $_GET['AlterarDesc'];
			$status = $_POST['status'];
			$class->AttDesc($id_prestador, $valor, $data_sol, $dia, $descricao, $id, $status);
		}
?>

<div id="formulario-alt-desconto" class="formulario-alt-desconto">
				<form class="form-inline" method="post">
					<fieldset>
						<legend>Descontos</legend>

						<label style="margin:0px 0px 0px 32px" class="control-label">Prestador:</label>
			                    <input value="<?= $prest->NomePrestador($datafiles['id_prest_desc']) ?>" required name="alt_prest" type="text" id="country_id" onkeyup="autocomplet()">
			                    <ul id="country_list_id"></ul>					
						

						<label style="margin:0px 0px 0px 20px" class="control-label">Valor:</label>		                    

			                    <div class="input-prepend input-append">
									<span class="add-on">R$</span>
								  	<input value="<?= $datafiles['valor_desc'] ?>" data-thousands="" data-decimal="." class="input-small" type="text" id="valor" name="alt_valor" style='border-radius:0px 5px 5px 0px'>
								</div>
						

						<?php 
							$sel_1 ="";
							$sel_2="";
							if ($datafiles['status_desc']=='1'){
								$sel_1 = "selected";
							}else{
								$sel_1="";
							}


							if ($datafiles['status_desc']=='0'){
								$sel_2 = "selected";
							}else{
								$sel_2="";
							}
						?>
						<label style="margin:0px 0px 0px 20px" class="control-label">Status:</label>		                    
							<select class="input-medium" name="status" id="">
								<option <?= $sel_1 ?> value="1">Em Aberto</option>
								<option <?= $sel_2 ?> value="0">Finalizado</option>
							</select>			                   


						<br>

						<label style="margin:55px 0px 0px 0px" class="control-label">Dt. do Evento :</label>
			                    <input value="<?= $datafiles['data_sol_desc'] ?>" name="alt_data_sol" type="date">


			             <label style="margin:55px 0px 0px 20px" class="control-label">Desconto a Partir de :</label>
			                    <input value="<?= $datafiles['dia_desc'] ?>" name="alt_dia" type="date">

			              <br>

						<label style="margin:15px 0px 0px 27px" class="control-label">Descrição :</label>
							<textarea class="area-sol" name="alt_descricao" id=""><?= $datafiles['descricao_desc'] ?></textarea>
						

						<div class="form-actions">
							<button onClick="window.location.href='descontos.php'" style='float:right' type="button" class="btn">Cancelar</button>
							<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Atualizar</button>			  
						</div>
					</fieldset>
				</form>
			</div>