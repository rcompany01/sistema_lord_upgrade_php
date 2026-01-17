<?php
// PDO connect *********
require_once(dirname(__FILE__) . '/../class/DB.class.php');

function connect()
{
	$db = DB::getInstance();
	return $db->getConnection();
}

$mysqli = connect();
$keyword = '%' . $_POST['keyword'] . '%';

$stmt = $mysqli->prepare("SELECT * FROM clientes WHERE cl_nome_fantasia LIKE ? ORDER BY cl_nome_fantasia ASC LIMIT 0, 10");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

while ($rs = $result->fetch_assoc()) {
	// put in bold the written text
	$country_name = str_replace($_POST['keyword'], '<b>' . $_POST['keyword'] . '</b>', "(" . $rs['id_cl'] . ") " . $rs['cl_nome_fantasia']);
	// add new option
	echo '<li onclick="set_item(\'' . str_replace("'", "\'", $rs['cl_nome_fantasia']) . '\', \'' . str_replace("'", "\'", $rs['id_cl']) . '\', BuscaIDCL(' . $rs['id_cl'] . '))">' . $country_name . '</li>';

}
$stmt->close();
?>