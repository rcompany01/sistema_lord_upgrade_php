<script language="JavaScript">
function enviaCod(cod){
	if (cod==""){
		alert("Código Inválido");
		window.location.href="?funcao=FaturamentoRepasse";
	}else{
	window.location.href="?funcao=FaturamentoRepasse&Cod="+cod;
	}
}
</script>

<script language="JavaScript">
function imprimir() {
	window.print();
}
</script>

<?php
	error_reporting(0);
	ini_set("display_errors", 0);

	// INSTANCIA A CLASSE
	require_once("class/financeiro.class.php");
	$class = new Financeiro;

	// SETORES
	require_once("clientes/class/setores.class.php");
	$set = new Setores;

	// PRESTADORES
	require_once('prestadores/class/prestadores.class.php');
	$pr = new Prestadores;

	// FUNCAO
	require_once('clientes/class/funcoes.class.php');
	$fc = new Funcoes;

	// CLIENTES
	require_once('clientes/class/clientes.class.php');
	$cl = new Clientes;


	// CASO EXISTA, COLOCA O VALOR DO GET NO CAMPO
	$cod="";
	if (!empty($_GET['Cod'])){
		$cod = $_GET['Cod'];
	}else{
		$cod="";
	}


	// BUSCA O NOME DO CLIENTE
	$nomeCl = "";
	if (!empty($_GET['Cod'])){
		$nomeCl = $class->BuscaNomeCliente($_GET['Cod']);
	}

?>



<?php
	if (!empty($_POST['cod'])){

	// TABELA DE DADOS DO FATURAMENTO
	if ($_POST['tipo']=='fat'){
		include('preFaturamento.php');

	}else{
		include('preRepasse.php');
	}
	}elseif(!empty($_POST['todos']) && $_POST['tipo']=='rep'){
		include('relTodosRepasse.php');
	}elseif(!empty($_POST['todos']) && $_POST['tipo']=='fat'){
		include('relTodosFaturamento.php');
	}else{

?>


<form id="form" method="post" class="form-inline">	
		<div class="cx-fat-rep">
			<fieldset>
				<legend>Seleção de Clientes 
					<?php
						if (!empty($_GET['Cod'])){
					?>
					<label style="float:right;margin:5px 30px 0px 50px;font-weight:bold;font-size:20px;color:#2024AD"> <?= $nomeCl ?></label>
					<?php
						}
					?>
				</legend>
				<div style='position:relative' class="a1">
					<h4 class="txt-cod_cl">Código:</h4>
						<input id="codigo" required value="<?= $cod ?>" onBlur="enviaCod(this.value)" name="cod" style="margin:5px 0px 0px 38px" type="text" class="input-mini">
					
					<label style='position:absolute;top:-12px;left:195px'>
						<h4 class="tipo-rel">Todos:</h4>
						<input name="todos" style="margin:5px 0px 0px 38px" type="checkbox" class="input-mini checkbox1">
					</label>

					<script type="text/javascript">
				      	$(document).ready(function() {
					      //set initial state.					      
					      $('.checkbox1').change(function() {
					          if($(this).is(":checked")) {
					              $(this).attr("checked", true);
					          }
					          var teste = $(this).is(':checked');

					          if (teste == true){
					            document.getElementById('codigo').value = '';
					            document.getElementById('codigo').disabled = true;
					            document.getElementById('codigo').required = false;
					          }else{					            
					            document.getElementById('codigo').disabled = false;
					            document.getElementById('codigo').required = true;
					          }
					      });
					  });
					</script>

					
					<h4 class="tipo-rel">Período:</h4>
						<label style="margin:5px 0px 0px 8px">De:</label> <input name="de"  required style="margin:5px 0px 0px 5px" type="date" class="input-medium"> <br>
						<label style="margin:5px 0px 0px 5px">Até:</label> <input name="ate" required style="margin:5px 0px 0px 5px" type="date" class="input-medium">
					

					<h4 class="tipo-rel">Tipo de Relatório:</h4>
					<select name="tipo" required style="margin:5px 0px 0px 40px" class="input-medium">
						<option value="">Selecione</option>
						<option value="fat">Faturamento</option>
						<option value="rep">Repasse</option>
					</select>
				</div>

				<div class="a2">
					<label><b>Setor:</b></label> <br>
							<small>Para buscar por todos os setores, basta <b>não</b> selecionar nenhum setor abaixo.</small>


						<table class="table table-striped">
							<?php
								// EXECUTA A BUSCA DE SETORES
								if (!empty($_GET['Cod'])){
									$class->SetorFaturamento($_GET['Cod']);
									while($row = mysqli_fetch_assoc($class->SetorFaturamento)){
							?>
								<tr>
									<td style="text-align:left!important;"><input value="<?= $row['setor'] ?>" name="setor[]" style="margin:4px 10px 0px 0px;float:left" type="checkbox"> <?= $set->NomeSetor($row['setor']) ?></td>
								</tr>
							<?php
								}
							}
							?>
						</table>
				</div>
					


					<button style="margin:15px 0px 0px 240px" class="btn btn-primary">Imprimir</button>

					
			</fieldset>
		</div>	
</form>


<?php
	}
?>