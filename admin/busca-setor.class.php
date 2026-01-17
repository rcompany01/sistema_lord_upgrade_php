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

$stmt = $mysqli->prepare("SELECT * FROM setores WHERE setor LIKE ? ORDER BY setor ASC LIMIT 0, 10");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

while ($rs = $result->fetch_assoc()) {
	// put in bold the written text
	$country_name = str_replace($_POST['keyword'], '<b>' . $_POST['keyword'] . '</b>', "(" . $rs['id_setor'] . ") " . $rs['setor']);
	// add new option
	echo '<li onclick="set_item2(\'' . str_replace("'", "\'", $rs['setor']) . '\', \'' . str_replace("'", "\'", $rs['id_setor']) . '\', BuscaIDSet(' . $rs['id_setor'] . '))">' . $country_name . '</li>';

}
$stmt->close();
?>