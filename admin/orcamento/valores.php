<input type="hidden" id="url" value="<?= $_GET['AdicionarValor'] ?>">



<?php
	// CLASSE DAS FUNCOES
	require_once('clientes/class/funcoes.class.php');
	$func= new Funcoes;
	$func->ListaFuncoes();

	// BUSCA O VALOR TOTAL DO ORCAMENTO
	$class->ValorTotalOrcamentoID($_GET['AdicionarValor']);
	$total = mysqli_fetch_assoc($class->ValorTotalOrcamentoID);

	// BUSCA O TOTAL DE PRESTADORES QUE PARTICIPARAO DO EVENTO
	$class->TotalPrestadoresEvento($_GET['AdicionarValor']);
	$total_prest=mysqli_fetch_assoc($class->TotalPrestadoresEvento);

	// ADICIONA OS VALORES
	if (isset($_POST['funcao'])){
		$qtd=$_POST['qtd'];
		$funcao_val=$_POST['funcao'];
		$valor=$_POST['valor'];
		$n_valor = $qtd*$valor;
		$class->AdicionaValores($qtd, $funcao_val, $n_valor);
		?>
			<script type="text/javascript">
				alert('Valores Atualizados!');
				var url = document.getElementById('url').value;
				window.location.href="?funcao=orcamento&AdicionarValor="+url;
			</script>
		<?php
	}


	// EXCLUI OS VALORES CADASTRADOS
	if (isset($_GET['ExcluirValores'])){
		$class->ExcluirValor($_GET['ExcluirValores']);
		?>
			<script type="text/javascript">
				var url = document.getElementById('url').value;
				window.location.href="?funcao=orcamento&AdicionarValor="+url;
			</script>
		<?php
	}
?>


<div class="cx-valores-orc">
	<form method="post" class="form-inline">
		<fieldset style='margin-top:20px;'>
				<legend>Orçamento</legend>

					<label style="margin:0px 0px 0px 30px" class="control-label">Quantidade:</label>
						<input required name="qtd" class="input-mini" type="number">

					<label style="margin:0px 0px 0px 20px" class="control-label">Função:</label>
						<select required name="funcao" id="">
							<option value="">Selecione..</option>
							<?php
								while($funcao = mysqli_fetch_assoc($func->ListaFuncoes)){
							?>
								<option value="<?= $funcao['id_func'] ?>"><?= $funcao['funcao'] ?></option>
							<?php
								}
							?>
						</select>

					<label style="margin:0px 0px 0px 20px" class="control-label">Valor:</label>
						<div class="input-prepend input-append">
							<span class="add-on">R$</span>
						  <input required name="valor" id="valor" style='border-radius:0px 5px 5px 0px' class="input-small" type="text">
						</div>

						<button style="margin-left:20px" type="submit" class="btn btn-success">Adicionar</button>

						<br>

					<label style="margin:20px 0px 0px 30px;color:blue;font-weight:bold;font-size:18px" class="control-label">Valor Total:</label>
						<label style="margin:20px 0px 0px 5px;color:#000;font-weight:bold;font-size:18px" class="control-label">
							<?= "R$ ".$total['total'] ?>
						</label>
					


			</fieldset>

			<div class="form-actions">
				<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right' type="button" class="btn">Cancelar</button>		  
			</div>
	</form>


	<table style="width:600px" class="table table-striped">
		<tr>
			<td>Quantidade</td>
			<td>Função</td>
			<td>Valor</td>
			<td>Ações</td>
		</tr>

<?php
	// BUSCA OS REGISTROS DO ORCAMENTO
	$class->BuscaValoresOrcamento($_GET['AdicionarValor']);
	while($val = mysqli_fetch_assoc($class->BuscaValoresOrcamento)){
?>
		<tr>
			<td><?= $val['qtd_val'] ?></td>
			<td><?= $func->NomeFuncao($val['funcao_val']) ?></td>
			<td><?= "R$ ".$val['valor'] ?></td>
			<td>
				<button onClick="window.location.href='?funcao=orcamento&AdicionarValor=<?= $_GET['AdicionarValor'] ?>&ExcluirValores=<?= $val['id_val'] ?>'" class="btn btn-danger">Excluir</button>
			</td>
		</tr>

<?php
	}
?>

		<tr>
			<td colspan="4"><b>Total:</b> <?= $total_prest['qtd_prest'] ?> Prestadores</td>
		</tr>
	</table>
</div>