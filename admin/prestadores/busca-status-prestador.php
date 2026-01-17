<?php
$class->ListaPrestadoresStatus($_GET['status_prest']);
	while ($row=mysqli_fetch_assoc($class->ListaPrestadoresStatus)){
?>
		<tr>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'"><?= $row['id_prest'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'"><?= strtoupper($row['nome_prest']) ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'"><?= strtoupper($row['bairro_prest']) ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'"><?= $row['tel_prest'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'"><?= $row['cel_prest'] ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'"><?= $class->UltimaEscala($row['id_prest']) ?></td>
			<td style="cursor:pointer" onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'"><?= ($row['status']=='1') ? "<img src='../img/b2.png'>" : "<img src='../img/b1.png'>" ?></td>
			<td>
				<?php
					if ($row['status']=='1'){
				?>
					<button onClick="confirmacao(<?= $row['id_prest'] ?>)" class="btn btn-danger">Desativar</button>
				<?php
					}else{
						?>
						<button onClick="ativar(<?= $row['id_prest'] ?>)" class="btn btn-success">Ativar</button>
						<?php
					}
				?>
			</td>
		</tr>
<?php
	}
?>