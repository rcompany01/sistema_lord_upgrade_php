<?php


class Clientes
{

	function NovoCliente(
		$nomeFant,
		$razao,
		$cnpj,
		$inscricao,
		$cep,
		$rua,
		$numero,
		$compl,
		$cl_bairro,
		$cidade,
		$uf,
		$cl_telefone,
		$cl_celular,
		$email,
		$tipo_inss,
		$pis,
		$irrf,
		$iss,
		$venc,
		$fat
	) {

		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM clientes WHERE cl_cnpj=?");
		$stmt->bind_param("s", $cnpj);
		$stmt->execute();
		$query = $stmt->get_result();
		$qtd = $query->num_rows;
		$stmt->close();

		if ($qtd > 0) {
			?>
			<script type="text/javascript">
				alert('Este Cliente já está cadastrado!');
			</script>
			<?php
		} else {
			$stmtInsert = $mysqli->prepare("INSERT INTO clientes (cl_nome_fantasia, cl_razao, cl_cnpj, cl_inscricao, cl_cep,
															cl_rua, cl_numero, cl_compl, cl_bairro, cl_cidade,
															cl_uf, cl_telefone, cl_celular, cl_email, tipo_inss,
															pis, irrf, iss, vencimento, faturamento) 
										VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

			$stmtInsert->bind_param(
				"ssssssssssssssssssss",
				$nomeFant,
				$razao,
				$cnpj,
				$inscricao,
				$cep,
				$rua,
				$numero,
				$compl,
				$cl_bairro,
				$cidade,
				$uf,
				$cl_telefone,
				$cl_celular,
				$email,
				$tipo_inss,
				$pis,
				$irrf,
				$iss,
				$venc,
				$fat
			);

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					alert('Cliente cadastrado!');
					window.location.href = "?funcao=listaClientes";
				</script>
				<?php
			}
			$stmtInsert->close();
		}
	}


	public $ListaClientes;
	function ListaClientes()
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();
		$this->ListaClientes = $mysqli->query("SELECT * FROM clientes");
	}



	public $ListaClientesID;
	function ListaClientesID($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM clientes WHERE id_cl=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->ListaClientesID = $stmt->get_result();
		$stmt->close();
	}


	function AlterarCliente(
		$nomeFant,
		$razao,
		$cnpj,
		$inscricao,
		$cep,
		$rua,
		$numero,
		$compl,
		$cl_bairro,
		$cidade,
		$uf,
		$cl_telefone,
		$cl_celular,
		$email,
		$tipo_inss,
		$pis,
		$irrf,
		$iss,
		$venc,
		$fat,
		$id
	) {
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE clientes SET cl_nome_fantasia=?, cl_razao=?, cl_cnpj=?, cl_inscricao=?, cl_cep=?,
													cl_rua=?, cl_numero=?, cl_compl=?, cl_bairro=?, cl_cidade=?,
													cl_uf=?, cl_telefone=?, cl_celular=?, cl_email=?, tipo_inss=?,
													pis=?, irrf=?, iss=?, vencimento=?, faturamento=?
													WHERE id_cl=?");

		$stmt->bind_param(
			"ssssssssssssssssssssi",
			$nomeFant,
			$razao,
			$cnpj,
			$inscricao,
			$cep,
			$rua,
			$numero,
			$compl,
			$cl_bairro,
			$cidade,
			$uf,
			$cl_telefone,
			$cl_celular,
			$email,
			$tipo_inss,
			$pis,
			$irrf,
			$iss,
			$venc,
			$fat,
			$id
		);

		if ($stmt->execute()) {
			?>
			<script type="text/javascript">
				alert('Cliente Atualizado!');
				window.location.href = "?funcao=listaClientes";
			</script>
			<?php
		}
		$stmt->close();
	}

	function DeletarCliente($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM clientes WHERE id_cl=?");
		$stmt->bind_param("i", $id);

		if ($stmt->execute()) {
			?>
			<script type="text/javascript">
				alert('Registro Deletado!');
				window.location.href = "?funcao=listaClientes";
			</script>
			<?php
		}
		$stmt->close();
	}


	function InserirFuncaoCliente($idCl, $funcao, $faturamento, $repasse)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT funcao FROM funcoes_clientes WHERE funcao=? AND id_cliente=?");
		$stmt->bind_param("ii", $funcao, $idCl);
		$stmt->execute();
		$query = $stmt->get_result();
		$qtd = $query->num_rows;
		$stmt->close();

		if ($qtd > 0) {
			?>
			<script type="text/javascript">
				alert('Esta Função Já está inserida!');
				var id = document.getElementById('idCliente').value;
				window.location.href = "?funcao=listaClientes&idCliente=" + id;
			</script>
			<?php
		} else {
			$stmtInsert = $mysqli->prepare("INSERT INTO funcoes_clientes(id_cliente, funcao, vl_faturamento, vl_repasse) VALUES (?, ?, ?, ?)");
			$stmtInsert->bind_param("iidd", $idCl, $funcao, $faturamento, $repasse); // Assuming faturamento/repasse are doubles/decimals, using 'd'

			if ($stmtInsert->execute()) {
				?>
				<script type="text/javascript">
					var id = document.getElementById('idCliente').value;
					window.location.href = "?funcao=listaClientes&idCliente=" + id;
				</script>
				<?php
			}
			$stmtInsert->close();
		}
	}


	public $BuscaFuncoesClientes;
	function BuscaFuncoesClientes($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM funcoes_clientes WHERE id_cliente=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->BuscaFuncoesClientes = $stmt->get_result();
		$stmt->close();
	}

	function BuscaFuncao($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT funcao FROM funcoes WHERE id_func=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$funcao = $vt['funcao'];
		$stmt->close();
		return $funcao;
	}


	public $FuncoesClientes;
	function FuncoesClientes($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT * FROM funcoes_clientes WHERE id=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$this->FuncoesClientes = $stmt->get_result();
		$stmt->close();
	}


	function AtualizarFuncaoCliente($func, $fat, $rep, $id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("UPDATE funcoes_clientes SET funcao=?, vl_faturamento=?, vl_repasse=? WHERE id=?");
		$stmt->bind_param("iddi", $func, $fat, $rep, $id);
		if ($stmt->execute()) {
			// No output JS here in original, assuming it's handled by caller or blind update
		}
		$stmt->close();
	}


	function DeletarFuncaoCliente($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("DELETE FROM funcoes_clientes WHERE id=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();
	}

	function BuscaCliente($id)
	{
		require_once(dirname(__FILE__) . '/../../../class/DB.class.php');
		$db = DB::getInstance();
		$mysqli = $db->getConnection();

		$stmt = $mysqli->prepare("SELECT cl_nome_fantasia FROM clientes WHERE id_cl=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$vt = $result->fetch_assoc();
		$cliente = $vt['cl_nome_fantasia'];
		$stmt->close();
		return $cliente;
	}


}