<?php
// PDO connect *********
require_once(dirname(__FILE__) . '/../class/DB.class.php');

function connect()
{
	$db = DB::getInstance();
	$mysqli = $db->getConnection();
	// Converting mysqli to PDO for this specific script which was written in PDO style.
	// However, it's inefficient to mix or wrap just for this. 
	// Since codebase moved to Mysqli singleton, it's better to rewrite this small script to use the singleton mysqli.
	// BUT, the existing code uses PDO methods (prepare, bindParam, fetchAll).
	// Rewriting to mysqli is safer and consistent.
	return $mysqli;
}

$mysqli = connect();
$keyword = '%' . $_POST['keyword'] . '%';

$stmt = $mysqli->prepare("SELECT * FROM prestadores WHERE nome_prest LIKE ? AND status='1' ORDER BY id_prest ASC LIMIT 0, 10");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

while ($rs = $result->fetch_assoc()) {
	// put in bold the written text
	$country_name = str_replace($_POST['keyword'], '<b>' . $_POST['keyword'] . '</b>', "(" . $rs['id_prest'] . ") " . $rs['nome_prest']);
	// add new option
	echo '<li onclick="set_item(\'' . str_replace("'", "\'", $rs['nome_prest']) . '\', \'' . str_replace("'", "\'", $rs['id_prest']) . '\')">' . $country_name . '</li>';

}
$stmt->close();
?>