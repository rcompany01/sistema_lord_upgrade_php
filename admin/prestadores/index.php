<!-- jQuery removed linked: logic uses parent layout jQuery -->

<script type="text/javascript">
	function NovaEmpresa() {
		$("#formulario-novo-prestador").fadeIn('slow');
		document.getElementById('tabela-empresas').style.display = "none";
		document.getElementById('cx-funcoes').style.display = "none";
		document.getElementById('busca-prest').style.display = "none";
		document.getElementById('busca-nome-prest').style.display = "none";
		document.getElementById('busca-status-prest').style.display = "none";
		document.getElementById('paginacao-prest').style.display = "none";
		document.getElementById('busca-ordem-prest').style.display = "none";
		document.getElementById('at-in').style.display = "none";
	}
</script>



<?php // WEB SERVICE DOS CORREIOS ?>
<script type="text/javascript">

	$(document).ready(function () {

		function limpa_formulário_cep() {
			// Limpa valores do formulário de cep.
			$("#rua").val("");
			$("#bairro").val("");
			$("#cidade").val("");
			$("#uf").val("");
		}

		//Quando o campo cep perde o foco.
		$("#cep").blur(function () {

			//Nova variável "cep" somente com dígitos.
			var cep = $(this).val().replace(/\D/g, '');

			//Verifica se campo cep possui valor informado.
			if (cep != "") {

				//Expressão regular para validar o CEP.
				var validacep = /^[0-9]{8}$/;

				//Valida o formato do CEP.
				if (validacep.test(cep)) {

					//Preenche os campos com "..." enquanto consulta webservice.
					$("#rua").val("Aguarde...")
					$("#bairro").val("Aguarde...")
					$("#cidade").val("Aguarde...")
					$("#uf").val("Aguarde...")

					//Consulta o webservice viacep.com.br/
					$.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

						if (!("erro" in dados)) {
							//Atualiza os campos com os valores da consulta.
							$("#rua").val(dados.logradouro);
							$("#bairro").val(dados.bairro);
							$("#cidade").val(dados.localidade);
							$("#uf").val(dados.uf);
						} //end if.
						else {
							//CEP pesquisado não foi encontrado.
							limpa_formulário_cep();
							alert("CEP não encontrado.");
						}
					});
				} //end if.
				else {
					//cep é inválido.
					limpa_formulário_cep();
					alert("Formato de CEP inválido.");
				}
			} //end if.
			else {
				//cep sem valor, limpa formulário.
				limpa_formulário_cep();
			}
		});
	});

</script>


<?php
// FAZ A CONFIRMAÇÃO PARA DESATIVAR O PRESTADOR
?>
<script type="text/javascript">
	function confirmacao(id) {
		var resposta = confirm("Deseja desativar este prestador?");

		if (resposta == true) {
			window.location.href = "?funcao=listaPrestadores&desativarPrest=" + id;
		}
	}
</script>



<?php
// FAZ A CONFIRMAÇÃO PARA ATIVAR O PRESTADOR
?>
<script type="text/javascript">
	function ativar(id) {
		var resposta = confirm("Deseja ativar este prestador?");

		if (resposta == true) {
			window.location.href = "?funcao=listaPrestadores&ativarPrest=" + id;
		}
	}
</script>

<?php
require_once('class/prestadores.class.php');
$class = new Prestadores;
// CADASTRA UMA NOVA EMPRESA
if (isset($_POST['nome'])) {
	$foto = 'prestadores/fotos/' . $_FILES['foto']['name'];
	$class->NovoPrestador(
		$_POST['nome'],
		$_POST['rg'],
		$_POST['orgao'],
		$_POST['expedicao'],
		$_POST['cpf'],
		$_POST['nacionalidade'],
		$_POST['nascimento'],
		$_POST['sexo'],
		$_POST['estado_civil'],
		$_POST['escolaridade'],
		$_POST['mae'],
		$_POST['pai'],
		$_POST['cep'],
		$_POST['logradouro'],
		$_POST['numero'],
		$_POST['compl'],
		$_POST['bairro'],
		$_POST['cidade'],
		$_POST['uf'],
		$_POST['tel'],
		$_POST['cel'],
		$_POST['email'],
		$foto,
		$_POST['status-prestador'],
		$_POST['inss'],
		$_POST['pis'],
		$_POST['ccm'],
		$_POST['titulo'],
		$_POST['zona'],
		$_POST['indicado']
	);
}


// DESATIVAR PRESTADOR
if (isset($_GET['desativarPrest'])) {
	$class->DesativarPrestador($_GET['desativarPrest']);
}

// ATIVAR PRESTADOR
if (isset($_GET['ativarPrest'])) {
	$class->AtivarPrestador($_GET['ativarPrest']);
}
?>

<?php
// FAZ O INCLUDE DA PAGINA DE ALTERACAO DOS DADOS
if (isset($_GET['idPrest'])) {
	// ARQUIVO DE ALTERACAO DOS DADOS
	require_once('alt_prestador.php');
} else {
	?>

	<div class="cx-lista-prestador">

		<div id="busca-prest" style="float:left">
			<form method="get">
				<input type="hidden" name="funcao" value="listaPrestadores">
				<h4 style="float:left;margin:20px 0px 0px 0px">Código:</h4>
				<input style="float:left;margin:15px 0px 0px 10px" class="input-mini" type="text" name="cod_prest">
				<button style="float:left;margin:15px 0px 0px 10px" class="btn btn-primary">
					<u class="icon-search icon-white"></u>
				</button>

				<input value="Ver Todos" type="button" style="float:left;margin:15px 0px 0px 10px"
					onClick="window.location.href='?funcao=listaPrestadores'" class="btn btn-warning">
			</form>
		</div>


		<div id="busca-nome-prest" style="float:left">
			<form style="margin:0" method="get">
				<input type="hidden" name="funcao" value="listaPrestadores">
				<h4 style="float:left;margin:20px 0px 0px 20px">Nome:</h4>
				<input style="float:left;margin:15px 0px 0px 10px" class="input-large" type="text" name="nome_prest">
				<button style="float:left;margin:15px 0px 0px 10px" class="btn btn-primary">
					<u class="icon-search icon-white"></u>
				</button>
			</form>
		</div>


		<div id="busca-status-prest" style="float:left">
			<form style="margin:0" method="get">
				<?php

				if (!isset($_GET['ordem'])) {
					?>
					<input type="hidden" name="funcao" value="listaPrestadores">
					<?php
				} else {
					// CASO SEJA POR ORDEM ALFABETICA, LISTA DOIS INPUTS COM OS VALORES ATUAIS DO GET
					?>
					<input type="hidden" name="funcao" value="listaPrestadores">
					<input type="hidden" name="ordem" value="<?= $_GET['ordem'] ?>">
					<?php
				}
				?>


				<?php
				// VERIFICAÇÃO PARA DEIXAR O INPUT SELECIONADO
			
				$selAt = "";
				$selIn = "";
				if (!empty($_GET['status_prest'])) {
					if ($_GET['status_prest'] == 'n') {
						$selIn = "selected";
					} elseif ($_GET['status_prest'] == '1') {
						$selAt = "selected";
					}
				} else {
					$selIn = "";
					$selAt = "";
				}
				?>
				<h4 style="float:left;margin:20px 0px 0px 20px">Status:</h4>
				<select style="float:left;margin:15px 0px 0px 10px" name="status_prest" class="input-medium">
					<option value="">Selecione</option>
					<option <?= $selAt ?> value="1">Ativo</option>
					<option <?= $selIn ?> value="n">Inativo</option>
				</select>
				<button style="float:left;margin:15px 0px 0px 10px" class="btn btn-primary">
					<u class="icon-search icon-white"></u>
				</button>
			</form>
		</div>


		<div id="busca-ordem-prest" style="float:left">
			<form style="margin:0" method="get">
				<?php
				if (!isset($_GET['status_prest'])) {
					?>
					<input type="hidden" name="funcao" value="listaPrestadores">
					<?php
				} else {
					// CASO SEJA POR ORDEM ALFABETICA, LISTA DOIS INPUTS COM OS VALORES ATUAIS DO GET
					?>
					<input type="hidden" name="funcao" value="listaPrestadores">
					<input type="hidden" name="status_prest" value="<?= $_GET['status_prest'] ?>">
					<?php
				}
				?>


				<?php
				// VERIFICAÇÃO PARA DEIXAR O INPUT SELECIONADO
			
				$sel = "";
				if (!empty($_GET['ordem'])) {
					$sel = "selected";
				} else {
					$sel = "";
				}
				?>
				<h4 style="float:left;margin:50px 0px 0px 0px">Ordem:</h4>
				<select style="float:left;margin:45px 0px 0px 10px" name="ordem" class="input-medium">
					<option value="">Selecione</option>
					<option <?= $sel ?> value="alfa">Afabética</option>
				</select>
				<button style="float:left;margin:45px 0px 0px 10px" class="btn btn-primary">
					<u class="icon-search icon-white"></u>
				</button>
			</form>
		</div>




		<div style="margin-left:210px;margin-top:50px" id="cx-funcoes" class="cx-funcoes"></a>
			<img style="cursor:pointer" onClick="NovaEmpresa()" src="../img/bt_novo1.png" alt="">
			<img onClick="impressao()" style="cursor:pointer" id='imprimir' src="../img/bt_imprimir1.png" alt="">
		</div>


		<?php
		// TESTA QUAL FILTRO DE STATUS ESTÁ APLICADO
	
		$rel = "";
		if (isset($_GET['status_prest'])) {
			if ($_GET['status_prest'] == 'n') {
				$rel = "Lista de Prestadores Inativos";
			} elseif ($_GET['status_prest'] == '1') {
				$rel = "Lista de Prestadores Ativos";
			}
		} else {
			$rel = "Lista de Todos os Prestadores";
		}
		?>
		<div class="cx-nome-rel">
			<div class="logo_dm">
				<img src="../img/logo.png" height="77" width="120" alt="">
			</div>

			<h4 class="rel-prest-txt"><?= $rel ?></h4>
		</div>


		<div id="at-in" class="info-total-status">
			<?php
			$ativos = $class->Ativos();
			$inativos = $class->Inativos();
			?>
			<h5 style="margin:3px 0px 0px 0px;font-weight:normal"><b>Total de:</b>
				<?= "<b style='color:green'>" . $ativos . " Ativo(s)</b> e <b style='color:red'>" . $inativos . " Inativo(s)</b>" ?>
			</h5>
		</div>

		<style type="text/css">
			#tabela-prest {
				display: none;
			}

			@media print {
				#tabela-prest {
					display: block;
				}

				#tabela-empresas {
					display: none;
				}
			}
		</style>

		<table id='tabela-empresas' class="table table-striped">
			<tr style="font-weight:bold">
				<td style="text-align:center">ID</td>
				<td style="text-align:center">Prestador</td>
				<td style="text-align:center">Bairro</td>
				<td style="text-align:center">Telefone</td>
				<td style="text-align:center">Celular</td>
				<td style="text-align:center">Última Escala</td>
				<td style="text-align:center">Status</td>
				<td style="text-align:center">Ações</td>
			</tr>
			<?php

			if (!empty($_GET['cod_prest'])) {
				require_once('busca-prestador.php');
			} elseif (!empty($_GET['nome_prest'])) {
				require_once('busca-nome-prestador.php');
			} elseif (!empty($_GET['status_prest'])) {
				require_once('busca-status-prestador.php');
			} else {

				$class->ListaPrestadores();
				while ($row = mysqli_fetch_assoc($class->ListaPrestadores)) {
					?>
					<tr>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= $row['id_prest'] ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= strtoupper($row['nome_prest']) ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= strtoupper($row['bairro_prest']) ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= $row['tel_prest'] ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= $row['cel_prest'] ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= $class->UltimaEscala($row['id_prest']) ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= ($row['status'] == '1') ? "<img src='../img/b2.png'>" : "<img src='../img/b1.png'>" ?>
						</td>
						<td style="text-align:center">
							<?php
							if ($row['status'] == '1') {
								?>
								<button onClick="confirmacao(<?= $row['id_prest'] ?>)" class="btn btn-danger">Desativar</button>
								<?php
							} else {
								?>
								<button onClick="ativar(<?= $row['id_prest'] ?>)" class="btn btn-success">Ativar</button>
								<?php
							}
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</table>







		<table id='tabela-prest' class="table table-striped">
			<tr style="font-weight:bold">
				<td style="text-align:center">ID</td>
				<td style="text-align:center">Prestador</td>
				<td style="text-align:center">Bairro</td>
				<td style="text-align:center">Telefone</td>
				<td style="text-align:center">Celular</td>
				<td style="text-align:center">Última Escala</td>
				<td style="text-align:center">Status</td>
				<td style="text-align:center">Ações</td>
			</tr>
			<?php

			if (!empty($_GET['cod_prest'])) {
				require_once('busca-prestador.php');
			} elseif (!empty($_GET['nome_prest'])) {
				require_once('busca-nome-prestador.php');
			} elseif (!empty($_GET['status_prest'])) {
				require_once('busca-status-prestador.php');
			} else {

				$class->ListaPrestadoresTotal();
				while ($row = mysqli_fetch_assoc($class->ListaPrestadoresTotal)) {
					?>
					<tr>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= $row['id_prest'] ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= strtoupper($row['nome_prest']) ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= strtoupper($row['bairro_prest']) ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= $row['tel_prest'] ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= $row['cel_prest'] ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= $class->UltimaEscala($row['id_prest']) ?>
						</td>
						<td style="cursor:pointer;text-align:center"
							onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>&idPrest=<?= $row['id_prest'] ?>'">
							<?= ($row['status'] == '1') ? "<img src='../img/b2.png'>" : "<img src='../img/b1.png'>" ?>
						</td>
						<td style="text-align:center">
							<?php
							if ($row['status'] == '1') {
								?>
								<button onClick="confirmacao(<?= $row['id_prest'] ?>)" class="btn btn-danger">Desativar</button>
								<?php
							} else {
								?>
								<button onClick="ativar(<?= $row['id_prest'] ?>)" class="btn btn-success">Ativar</button>
								<?php
							}
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</table>













		<?php

		if (isset($_GET['pagina'])) {
			if ($_GET['pagina'] == "0") {
				?>
				<script type="text/javascript">
					window.location.href = "?funcao=listaPrestadores";
				</script>
				<?php
			}
		}


		$pag = "0";
		$pagAv = "25";
		if (!isset($_GET['pagina'])) {
			$pag = "0";
		} else {
			$pag = $_GET['pagina'] - "25";
			$pagAv = $_GET['pagina'] + "25";
		}



		// SE CASO TENHA UM FILTRO POR STATUS, A BARRA DE PAGINAÇÃO SOME
		if (!isset($_GET['status_prest'])) {
			?>

			<div id="paginacao-prest" class="form-actions">
				<ul class="pager">

					<?php
					// BOTAO ANTERIOR
					$disabled = "disabled";
					$url = "#";
					if (isset($_GET['pagina'])) {
						if ($_GET['pagina'] == '0') {
							$disabled = "disabled";
							$url = "#";
						} else {
							$disabled = "active";
							$url = "?funcao=listaPrestadores&pagina=$pag";
						}
					}
					?>
					<li class="<?= $disabled ?>"><a href="<?= $url ?>">Anterior</a></li>





					<?php
					// BOTAO PROXIMO
					$qtd = $class->TotalPrestadores();
					if ($qtd > 25) {
						?>
						<li><a href="?funcao=listaPrestadores&pagina=<?= $pagAv ?>">Próxima</a></li>
						<?php
					} else {
						?>
						<li><a href="#">Próxima</a></li>
						<?php
					}
					?>
				</ul>
			</div>

			<?php
		}
		?>











		<?php
		// FORMULARIO DE CADASTRO
		?>
		<div id='formulario-novo-prestador' class="formulario-novo-prestador">
			<form enctype="multipart/form-data" class="form-inline" method="post">
				<fieldset>
					<legend>Dados do Prestador</legend>

					<label style="margin:0px 0px 0px 57px" class="control-label">Status:</label>
					<select required name="status-prestador" id="">
						<option value="">Selecione o Status</option>
						<option value="1">Ativo</option>
						<option value="n">Inativo</option>
					</select>

					<br>

					<label style="margin:15px 0px 0px 60px" class="control-label">Nome:</label>
					<input style="text-transform:uppercase" required name="nome" class="input-large" type="text">

					<br>

					<label style="margin:15px 0px 0px 77px" class="control-label">RG:</label>
					<input name="rg" class="input-small" type="text">

					<label style="margin:15px 0px 0px 30px" class="control-label">Orgão Emissor:</label>
					<input name="orgao" class="input-small" type="text">

					<label style="margin:15px 0px 0px 30px" class="control-label">Dt. Expedição:</label>
					<input name="expedicao" class="input-medium" type="date">

					<br>

					<label style="margin:15px 0px 0px 70px" class="control-label">CPF:</label>
					<input name="cpf" class="input-medium" type="text">



					<label style="margin:15px 0px 0px 20px" class="control-label">Nacionalidade:</label>
					<input style="text-transform:uppercase" name="nacionalidade" class="input-small" type="text">


					<label style="margin:15px 0px 0px 25px" class="control-label">Dt. de Nasc:</label>
					<input name="nascimento" class="input-medium" type="date">


					<br>

					<label style="margin:15px 0px 0px 65px" class="control-label">Sexo:</label>
					<label class="radio">
						<input type="radio" name="sexo" id="optionsRadios2" value="masculino">
						Masculino
					</label>

					<label class="radio">
						<input type="radio" name="sexo" id="optionsRadios2" value="feminino">
						Feminino
					</label>

					<br>

					<label style="margin:15px 0px 0px 22px" class="control-label">Estado Civil:</label>
					<select class="input-medium" name="estado_civil" id="">
						<option value="">Selecione</option>
						<option value="Casado">Casado(a)</option>
						<option value="Solteiro">Solteiro(a)</option>
						<option value="Divorciado">Divorciado(a)</option>
						<option value="Viuvo">Viúvo(a)</option>
					</select>


					<label style="margin:15px 0px 0px 22px" class="control-label">Escolaridade:</label>
					<select class="input-large" name="escolaridade" id="">
						<option value="">Selecione</option>
						<option value="Fundamental">Ensino Fundamental</option>
						<option value="Medio">Ensino Médio</option>
						<option value="Superior">Ensino Superior</option>
						<option value="Graduacao">Pós Graduação</option>
					</select>

					<br>


					<label style="margin:15px 0px 0px 7px" class="control-label">Nome da Mãe:</label>
					<input style="text-transform:uppercase" name="mae" class="input-large" type="text">


					<br>


					<label style="margin:15px 0px 0px 15px" class="control-label">Nome do Pai:</label>
					<input style="text-transform:uppercase" name="pai" class="input-large" type="text">

				</fieldset>



				<fieldset style="margin-top:20px">
					<legend>Endereço</legend>

					<label style="margin:0px 0px 0px 68px" class="control-label">CEP:</label>
					<input id="cep" name="cep" class="input-small" type="text">
					<small>Sem traço, apenas números.</small>

					<br>

					<label style="margin:15px 0px 0px 22px" class="control-label">Logradouro:</label>
					<input style="text-transform:uppercase" id="rua" name="logradouro" class="input-xlarge" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Número:</label>
					<input name="numero" class="input-mini" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Complemento:</label>
					<input style="text-transform:uppercase" name="compl" class="input-mini" type="text">
					<br>
					<label style="margin:15px 0px 0px 57px" class="control-label">Bairro:</label>
					<input style="text-transform:uppercase" id="bairro" name="bairro" class="input-large" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Cidade:</label>
					<input style="text-transform:uppercase" id="cidade" name="cidade" class="input-medium" type="text">


					<label style="margin:0px 0px 0px 20px" class="control-label">UF:</label>
					<input style="text-transform:uppercase" id="uf" name="uf" class="input-mini" type="text">

				</fieldset>




				<fieldset style="margin-top:20px">
					<legend>Contato</legend>

					<label style="margin:0px 0px 0px 41px" class="control-label">Telefone:</label>
					<input name="tel" class="input-medium" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">Celular:</label>
					<input name="cel" class="input-medium" type="text">

					<br>

					<label style="margin:15px 0px 0px 62px" class="control-label">Email:</label>
					<input style="text-transform:uppercase" name="email" class="input-large" type="email">
				</fieldset>


				<fieldset style="margin-top:20px">
					<legend>Outras Informações</legend>

					<label style="margin:0px 0px 0px 65px" class="control-label">INSS:</label>
					<input id="inss" onBlur="CopiaInss()" name="inss" class="input-medium" type="text">

					<label style="margin:0px 0px 0px 20px" class="control-label">PIS:</label>
					<input id="pis" name="pis" class="input-medium" type="text">

					<label style="margin:15px 0px 0px 20px" class="control-label">CCM:</label>
					<input name="ccm" class="input-medium" type="text">

					<br>

					<label style="margin:15px 0px 0px 61px" class="control-label">Titulo:</label>
					<input name="titulo" class="input-medium" type="text">

					<label style="margin:15px 0px 0px 20px" class="control-label">Zona:</label>
					<input name="zona" class="input-medium" type="text">

					<label style="margin:15px 0px 0px 20px" class="control-label">Indicado Por:</label>
					<input style="text-transform:uppercase" name="indicado" class="input-medium" type="text">

				</fieldset>


				<fieldset style="margin-top:20px">
					<legend>Foto</legend>

					<img id="preview"
						style="max-width:200px; max-height:200px; width: auto; height: auto; object-fit: contain;">


					<label style="margin:0px 0px 0px 41px" class="control-label">Imagem:</label>
					<input name="foto" id="imgChooser" class="input-medium" type="file">
				</fieldset>


				<div class="form-actions">
					<button onClick="window.location.href='?funcao=<?= $_GET['funcao'] ?>'" style='float:right'
						type="button" class="btn">Cancelar</button>
					<button style='float:right;margin-right:10px' type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
	<?php
}
?>


<script type="text/javascript">
	function readImage() {
		if (this.files && this.files[0]) {
			var file = new FileReader();
			file.onload = function (e) {
				document.getElementById("preview").src = e.target.result;
			};
			file.readAsDataURL(this.files[0]);
		}
	}

	document.getElementById("imgChooser").addEventListener("change", readImage, false);


</script>