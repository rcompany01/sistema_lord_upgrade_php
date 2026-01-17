<input type="hidden" id="idCliente" value="<?= $_GET['idCliente'] ?>">
<?php
		// BUSCA OS VALORES DE ACORDO COM O ID
		$class->FuncoesClientes($_GET['idFunc']);
		$vt = mysqli_fetch_assoc($class->FuncoesClientes);

		// ATUALIZA OS VALORES DA FUNÇÃO SELECIONADA
		if (isset($_POST['atualiza_att_funcao'])){
			$func = $_POST['atualiza_att_funcao'];
			$fat = $_POST['atualiza_vl_faturamento'];
			$rep = $_POST['atualiza_vl_repasse'];
			$id = $_GET['idFunc'];
			$class->AtualizarFuncaoCliente($func, $fat, $rep, $id);
			?>
				<script type="text/javascript">
					alert('Valores Atualizados!');					
					window.location.href="?funcao=listaClientes&idCliente=" + document.getElementById('idCliente').value;
				</script>
			<?php
		}
?>

<script type="text/javascript">
function Enviar(){
	document.getElementById("att_funcao").submit();
}
</script>

<form id="att_funcao" class="form-inline" method="post">			
			
			<fieldset style="margin-top:20px">
				<legend>Valor da Hora Trabalhada</legend>


				<label style="margin:0px 0px 0px 0px" class="control-label">Função:</label>
					<select required class="input-large" name="atualiza_att_funcao" id="">
						<option value="">Selecione a função</option>
						<?php
							while($func=mysqli_fetch_assoc($funcao->ListaFuncoes)){
								// DEIXA A OPTION SELECIONADA DE ACORDO COMO RESULTADO DO ARRAY
								$sel="";
								if ($vt['funcao']==$func['id_func']){
									$sel="selected";
								}else{
									$sel="";
								}
								
						?>
							<option <?= $sel; ?> value="<?= $func['id_func'] ?>">
								<?= $func['funcao'] ?>
							</option>
						<?php
							}
						?>
					</select>


				<label style="margin:0px 0px 0px 20px" class="control-label">Vlr Faturamento:</label>
					<div class="input-prepend input-append">
						<span class="add-on">R$</span>
					  <input data-thousands="" data-decimal="." value="<?= $vt['vl_faturamento'] ?>" required name="atualiza_vl_faturamento" style='border-radius:0px 5px 5px 0px' class="input-mini" id="valor" type="text">					  
					</div>


				<label style="margin:0px 0px 0px 20px" class="control-label">Vlr Repasse:</label>
					<div class="input-prepend input-append">
						<span class="add-on">R$</span>
					  <input data-thousands="" data-decimal="." value="<?= $vt['vl_repasse'] ?>" required name="atualiza_vl_repasse" style='border-radius:0px 5px 5px 0px' class="input-mini" id="repasse" type="text">
					</div>
					<button style='margin-left:10px' class="btn btn-success">Atualizar</button>

			</fieldset>
		</form>




		