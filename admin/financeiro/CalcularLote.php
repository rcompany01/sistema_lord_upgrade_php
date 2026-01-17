<?php
	require_once("class/financeiro.class.php");
	$class = new Financeiro;
	date_default_timezone_set('UTC');

	if (isset($_POST['lote'])){
		$lote=$_POST['lote'];
		$pgt=$_POST['data_pag'];
		$mes=$_POST['mes'];
		$ano=$_POST['ano'];
		$class->CalcularLote($lote,$pgt,$mes,$ano);
	}
?>


<form method="post" class="form-inline">
	<fieldset style="margin-top:30px">
		<legend>Calcular Lote</legend>

		<label style="margin:0px 0px 0px 131px" class="control-label"><b>Lote:</b></label>
			<input style="text-transform:uppercase" type="text" name="lote" class="input-medium"> <br>

		<label style="margin:15px 0px 0px 31px"><b>Data de Pagamento:</b></label>
			
			<input type="date" value="<?=  date ("D/M/Y") ;?>" class="input-medium"><br>			

		<label style="margin:15px 0px 0px 65px"><b>Mês Referente:</b></label>
			<select required name="mes" id="">
				<option value="">Selecione..</option>
				<option value="1">Janeiro</option>
				<option value="2">Fevereiro</option>
				<option value="3">Março</option>
				<option value="4">Abril</option>
				<option value="5">Maio</option>
				<option value="6">Junho</option>
				<option value="7">Julho</option>
				<option value="8">Agosto</option>
				<option value="9">Setembro</option>
				<option value="10">Outubro</option>
				<option value="11">Novembro</option>
				<option value="12">Dezembro</option>
			</select>


		<label style="margin:15px 0px 0px 35px"><b>Ano:</b></label>
			<input class="input-mini" type="hidden" name="ano" value="<?php echo date("Y")?>">
			<input disabled class="input-mini" type="text" value="<?php echo date ("Y") ?>">



		<div class="form-actions"> 	
			<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Calcular Lote</button>			  
		</div>


	</fieldset>
</form>
