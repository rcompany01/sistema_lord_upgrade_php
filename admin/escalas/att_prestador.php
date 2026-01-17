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
			<input type="hidden" id="clienteEscala" value="<?= $alt['id_cliente'] ?>">
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

					<label style="margin:20px 0px 0px 45px" class="control-label">Função:</label>
					<select onChange="DadosFuncao()" required class="input-large funcoes" name="att_add_funcao" id="">
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


					<label style="margin:20px 0px 0px 20px" class="control-label">Hr. Entrada:</label>
						<input value="<?= $alt['entrada'] ?>" required type="time" name="att_entrada" id="HrEntradaEdit" class="input-small">

					<label style="margin:20px 0px 0px 10px" class="control-label">Hr. Saida:</label>
						<input onBlur="CalculaFaturamento()" value="<?= $alt['saida'] ?>" required type="time" id="HrSaidaEdit" name="att_saida" class="input-small">

					<label style="margin:20px 0px 0px 10px" class="control-label">Hr. Extra:</label>
						<input onBlur="CalculaExtraEdit()" value="<?= $alt['extra'] ?>" required type="time" id="HrExtraEdit" name="att_extra" class="input-small">
					

					<br>

					<!-- HORAS DA FUNCAO + REPASSE E FATURAMENTO FIXOS -->
					<input type="hidden" value="<?= $alt['vl_faturamento'] ?>" id="faturamentoFunc">
					<input type="hidden" value="<?= $alt['vl_repasse'] ?>" id="repasseFunc">

					<input type="hidden" value="<?= $alt['horas_func'] ?>" id="horasFunc">
					<input type="hidden" value="<?= $alt['valor_rep'] ?>" id="repFix">
					<input type="hidden" value="<?= $alt['valor_fat'] ?>" id="fatFix">

					<!-- ALTERAM -->
					<input type="hidden" value="" id="repTotal">
					<input type="hidden" value="" id="fatTotal">


					
					<label style="margin:20px 0px 0px 10px" class="control-label">Faturamento:</label>
						<input id="valor1fat" value="<?= $alt['valor_fat'] ?>" required type="text" name="att_fat" class="input-small faturamento">

					<label style="margin:20px 0px 0px 10px" class="control-label">Repasse:</label>
						<input id="valor2rep" value="<?= $alt['valor_rep'] ?>" required type="text" name="att_rep" class="input-small repasse">
					<br>

					<label style="margin:20px 0px 0px 20px" class="control-label">Extra (Fat.):</label>
						<input id="extra" value="<?= $alt['valor_extra_fat'] ?>" type="text" name="att_ex_fat" class="input-small">

					<label style="margin:20px 0px 0px 10px" class="control-label">Extra (Rep.):</label>
						<input id="extra2" value="<?= $alt['valor_extra_rep'] ?>" type="text" name="att_ex_rep" class="input-small">


			</fieldset>

			<div class="form-actions">
				<input onClick="fechaEdit(<?= $_GET['idEsc'] ?>)" value="Cancelar" style='float:right;margin-right:10px' type="button" class="btn btn-danger">
				<button style='float:right;margin-right:10px' type="submit" class="btn btn-success">Atualizar</button>	  
			</div>
		</form>
	</div>
</div>


<script>
	function DadosFuncao(){

  	var funcao = $('.funcoes').val();
  	var cliente = $('#clienteEscala').val();

	    $.ajax({
	    type: "POST",
	    url: 'escalas/controller.php',
	    data: {numFuncao:funcao, idCliente:cliente},            
	    success: function(response) {
	    	var sep = response.split("/");

		    $('.faturamento').val(sep[0]);
			$('.repasse').val(sep[1]);   

			$('#repFix').val(sep[1]);
			$('#fatFix').val(sep[0]);
			$('#horasFunc').val(sep[2]);
	    }
    });
	}


	function CalculaFaturamento(){
		var start = document.getElementById('HrEntradaEdit').value;
	    var end = document.getElementById('HrSaidaEdit').value;


	   	start = start.split(":");
	    end = end.split(":");
	    var startDate = new Date(0, 0, 0, start[0], start[1], 0);
	    var endDate = new Date(0, 0, 0, end[0], end[1], 0);
	    var diff = endDate.getTime() - startDate.getTime();
	    var hours = Math.floor(diff / 1000 / 60 / 60);
	    diff -= hours * 1000 * 60 * 60;
	    var minutes = Math.floor(diff / 1000 / 60);

	    // If using time pickers with 24 hours format, add the below line get exact hours
	    if (hours < 0)
	       hours = hours + 24;

	    hour = (hours <= 9 ? "0" : "") + hours;
	    min= (minutes <= 9 ? "0" : "") + minutes;

	    // VALOR DO REPASSE
	    var rep = $('.repasse').val();


	    // VALÓR DO FATURAMENTO
	    var fat = $('.faturamento').val();


	    var repFx = document.getElementById('repasseFunc');
	    var fatFx = document.getElementById('faturamentoFunc');
	    var horas = document.getElementById('horasFunc');

	    var repTotal = document.getElementById('repTotal');
		var fatTotal = document.getElementById('fatTotal');		


	    var resultadoRep = Math.abs(((((repFx.value) * hour)) + (((repFx.value) * min) / 60))).toFixed(2);
	    var resultadoFat = Math.abs(((((fatFx.value) * hour)) + (((fatFx.value) * min) / 60))).toFixed(2);

	    $('.repasse').val(resultadoRep);
		$('.faturamento').val(resultadoFat);


		repTotal.value = rep;
		fatTotal.value = fat;

		

	}

	function CalculaExtraEdit(){
		var start = document.getElementById('HrEntradaEdit').value;
	    var end = document.getElementById('HrSaidaEdit').value;

	    s = start.split(':');
	    e = end.split(':');

	    min = e[1]-s[1];
	    hour_carry = 0;
	    if(min < 0){
	        min += 60;
	        hour_carry += 1;
	    }
	    hour = e[0]-s[0]-hour_carry;
	    diff = hour + "." + min;


		var extra = document.getElementById('HrExtraEdit').value;

		var rep = $('.repasse').val();
	    var fat = $('.faturamento').val();

	    var repFixo = document.getElementById('repasseFunc');
		var fatfixo = document.getElementById('faturamentoFunc');	

		ex = extra.split(":");
		hourEx =ex[0];
		if (hourEx > 0){
			hourEx = hourEx*60;
		}
		minEx = ex[1];
		difEx = ex[0]+"."+ex[1];

		// Total de Minutos
		totalMin = parseFloat(hourEx) + parseFloat(minEx);

		// CONTINUAR DAQUI
		var totalEx = (((repFixo.value) * totalMin) / 60);
		var totalFatEx = (((fatfixo.value) * totalMin) / 60);

		// Valores separados do extra
		var extraSepRep = (totalEx += (totalEx*0.5)).toFixed(2);
		var extraSepFat = (totalFatEx += (totalFatEx*0.5)).toFixed(2);

		$('#extra').val(extraSepFat);
		$('#extra2').val(extraSepRep);

		var resultadoRep = ((totalEx += (totalEx*0.5)) + (parseFloat(rep))).toFixed(2);	
		var resultadoFat = (totalFatEx += (totalFatEx*0.5) + (parseFloat(fat))).toFixed(2); 

		$('.repasse').val(resultadoRep);
		$('.faturamento').val(resultadoFat);

		// ====

	}


  
</script>
