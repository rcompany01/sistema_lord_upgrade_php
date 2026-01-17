<?php 
	// EXTRA DO FATURAMENTO
 	$extraFat = $list['valor_extra_fat'];

 	// EXTRA DO FATURAMENTO
 	$extraRep = $list['valor_extra_rep'];

	// VALOR DO FATURAMENTO
 	$valorFinal = $list['valor_fat'] + $extraFat;

 	// VALOR DO REPASSE
 	$valorFinalRep = $list['valor_rep'] + $extraRep;

	// FATURAMENTO / REPASSE (SOMA) 
	$fat = ($fat + $valorFinal);
	$rep = ($rep + $valorFinalRep);

	// VERIFICA SE A HORA É DA MEIA NOITE
	$horarioSaida = $list['saida'];
	$MeiaNoite = explode(":", $horarioSaida);

	if ($MeiaNoite[0] == "24"){
		$horarioSaida = "00:".$MeiaNoite[1].":".$MeiaNoite[2];
	}
		
?>