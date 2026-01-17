<?php
	require_once("class/funcionarios.class.php");
	$class = new Funcionarios;

	$class->BuscaUsuario();
?>

<div class="cx-empresa-funcionarios">

	<div class="cont-tabela">
		<table class="table table-striped">
			<tr style="font-weight:bold">
				<td style="text-align:center">Nome</td>
				<td style="text-align:center">Sobrenome</td>
				<td style="text-align:center">Departamento</td>
				<td style="text-align:center">Ação</td>
			</tr>
<?php
	while($row=mysqli_fetch_assoc($class->BuscaUsuario)){
?>
			<tr>
				<td style="text-align:center"><?= $row['nome'] ?></td>
				<td style="text-align:center"><?= ($row['sobrenome']=="") ? "-" : $row['sobrenome'] ?></td>
				<td style="text-align:center"><?= $row['cargo'] ?></td>
				<td style="text-align:center">
					<button class="btn btn-danger">Excluir</button>
				</td>
			</tr>
<?php
	}
?>


		</table>
	</div>
</div>