<?php

class Empresas
{



	function NovaEmpresa(
		$nome_fantasia,
		$razao_social,
		$cnpj,
		$insc_estadual,
		$cep,
		$logradouro,
		$numero,
		$compl,
		$bairro,
		$cidade,
		$uf,
		$telefone,
		$celular,
		$site,
		$email
	) {

		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		// Verifica duplicidade via Prepared Statement
		$stmt = $mysqli->prepare("SELECT emp_cnpj FROM empresas WHERE emp_cnpj = ?");
		$stmt->bind_param("s", $cnpj);
		$stmt->execute();
		$stmt->store_result();
		$qtd = $stmt->num_rows;
		$stmt->close();

		if ($qtd == 0) {
			$stmtInsert = $mysqli->prepare("INSERT INTO empresas (
										emp_nome_fantasia, 
										emp_razao_social, 
										emp_cnpj, 
										emp_insc_estadual,
										emp_cep,
										emp_logradouro,
										emp_numero,
										emp_compl,
										emp_bairro,
										emp_cidade,
										emp_uf,
										emp_telefone,
										emp_celular,
										emp_site,
										emp_email
										) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

			$stmtInsert->bind_param(
				"sssssssssssssss",
				$nome_fantasia,
				$razao_social,
				$cnpj,
				$insc_estadual,
				$cep,
				$logradouro,
				$numero,
				$compl,
				$bairro,
				$cidade,
				$uf,
				$telefone,
				$celular,
				$site,
				$email
			);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Empresa Cadastrada!');
					window.location.href = "?funcao=dados";
				</script>
				<?php
			} else {
				die("Erro ao cadastrar empresa: " . $stmtInsert->error);
			}
			$stmtInsert->close();

		} else {
			?>
			<script type="text/javascript">
				alert('Este CNPJ já está cadastrado!');
			</script>
			<?php
		}
	}


	public $ListaEmpresas;
	function ListaEmpresas()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		// Não há input de usuário aqui, mas mantendo padrão da conexão
		$result = $mysqli->query("SELECT * FROM empresas ORDER BY emp_nome_fantasia ASC");
		if (!$result) {
			die("Erro ao listar empresas: " . $mysqli->error);
		}
		$this->ListaEmpresas = $result;
	}



	function DeletarEmpresa($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM empresas WHERE emp_id = ?");
		$stmt->bind_param("i", $id);

		if ($stmt->execute()) {
			?>
			<script type="text/javascript">
				alert('Registro Deletado!');
				window.location.href = "?funcao=dados";
			</script>
			<?php
		} else {
			die("Erro ao deletar empresa: " . $stmt->error);
		}
		$stmt->close();
	}



	public $ListaEmpresasID;
	function ListaEmpresasID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM empresas WHERE emp_id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->ListaEmpresasID = $stmt->get_result();
		$stmt->close();
	}



	function AtualizarEmpresa(
		$id,
		$nome_fan,
		$razao_soc,
		$cnpj,
		$insc_est,
		$cep,
		$logradouro,
		$numero,
		$compl,
		$bairro,
		$cidade,
		$uf,
		$tel,
		$cel,
		$site,
		$email
	) {

		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE empresas SET 
				emp_nome_fantasia=?, emp_razao_social=?, emp_cnpj=?, emp_insc_estadual=?,
				emp_cep=?, emp_logradouro=?, emp_numero=?, emp_compl=?,
				emp_bairro=?, emp_cidade=?, emp_uf=?, emp_telefone=?,
				emp_celular=?, emp_site=?, emp_email=?
				WHERE emp_id = ?");

		$stmt->bind_param(
			"sssssssssssssssi",
			$nome_fan,
			$razao_soc,
			$cnpj,
			$insc_est,
			$cep,
			$logradouro,
			$numero,
			$compl,
			$bairro,
			$cidade,
			$uf,
			$tel,
			$cel,
			$site,
			$email,
			$id
		);

		if ($stmt->execute()) {
			?>
			<script type="text/javascript">
				alert('Dados Atualizados!');
				window.location.href = "?funcao=dados";
			</script>
			<?php
		} else {
			die("Erro ao atualizar empresa: " . $stmt->error);
		}
		$stmt->close();
	}

}