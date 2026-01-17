$("imprimir").click(function(){
    window.print();
});

$(document).ready(function(){
  $("#imprimir").click(function() {
    window.print();
  });
});

function CopiaInss(){
	var inss = document.getElementById('inss');
	var pis = document.getElementById('pis');

	pis.value = inss.value;
}

function EditPrest(){
	$('#editPrest').fadeIn();
}

function fechaEdit(id){
	window.location.href="?funcao=escalas&idEsc="+id;
}
