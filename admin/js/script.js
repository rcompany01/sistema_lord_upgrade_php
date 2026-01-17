// autocomplet : this function will be executed every time we change the text
function autocomplet() {
	var min_length = 0; // min caracters to display the autocomplete
	var keyword = $('#country_id').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'busca.class.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#country_list_id').show();
				$('#country_list_id').html(data);
			}
		});
	} else {
		$('#country_list_id').hide();
	}
}


// autocomplet : this function will be executed every time we change the text
function autocompletCl() {
	var min_length = 0; // min caracters to display the autocomplete
	var keyword = $('#country_id').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'busca-cliente.class.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#country_list_id').show();
				$('#country_list_id').html(data);
			}
		});
	} else {
		$('#country_list_id').hide();
	}
}


function autocompletSet() {
	var min_length = 0; // min caracters to display the autocomplete
	var keyword = $('#country_id_set').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'busca-setor.class.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#country_list_id_set').show();
				$('#country_list_id_set').html(data);
			}
		});
	} else {
		$('#country_list_id_set').hide();
	}
}

function autocompletFun() {
	var min_length = 0; // min caracters to display the autocomplete
	var keyword = $('#country_id_set_fun').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'busca-funcao.class.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#country_list_id_set_fun').show();
				$('#country_list_id_set_fun').html(data);
			}
		});
	} else {
		$('#country_list_id_set_fun').hide();
	}
}




// set_item : this function will be executed when we select an item
function set_item(item, item2) {
	// change input value
	$('#country_id').val(item);
	// change input value
	$('#idPrestAuto').val(item2);
	// hide proposition list
	$('#country_list_id').hide();
}

function set_item2(item, item2) {
	// change input value
	$('#country_id_set').val(item);
	// change input value
	$('#idPrestAuto').val(item2);
	// hide proposition list
	$('#country_list_id_set').hide();
}

function set_item3(item, item2) {
	// change input value
	$('#country_id_set_fun').val(item);
	// change input value
	$('#idPrestAuto').val(item2);
	// hide proposition list
	$('#country_list_id_set_fun').hide();
}


function FocusNome(){
	document.getElementById('country_id').focus();

	var esc = document.getElementById('idEsc').value;
	var func = document.getElementById('FuncID').value;
	var id = document.getElementById('idPrestAuto').value;
	if (id != ""){
		window.location.href="?funcao=escalas&idEsc="+esc+"&Value="+func+"&PrestID="+id+"#add";
	}else{
		alert('Digite o Nome do Prestador!');
	}

}


function FocusCl(){
	document.getElementById('country_id').focus();
	if (id != ""){
		window.location.href="?funcao=escalas&idEsc="+esc+"&Value="+func+"&PrestID="+id+"#add";
	}else{
		alert('Digite o Nome do Prestador!');
	}

}