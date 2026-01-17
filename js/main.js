function Logout(){
	document.getElementById("form-logout").submit();
}

function NovaEmpresa(){
	$("#formulario-nova-empresa").fadeIn('slow');
	document.getElementById('tabela-empresas').style.display="none";	
}

function NovoDepartamento(){
	$("#cx-form-departamento").fadeIn('slow');
	document.getElementById('cont-tabela-departamento').style.display="none";	
}
