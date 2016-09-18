function validaCPF(cpf) 
{
  erro = new String;

  	// Pequena pog value == 14 
	if (cpf.value.length == 11 || cpf.value.length == 14)
	{
			cpf.value = cpf.value.replace('.', '');
			cpf.value = cpf.value.replace('.', '');
			cpf.value = cpf.value.replace('-', '');

			var nonNumbers = /\D/;
	
			if (nonNumbers.test(cpf.value)) 
			{
					erro = "A verificacao de CPF suporta apenas n�meros!"; 
			}
			else
			{
					if (cpf.value == "00000000000" || 
							cpf.value == "11111111111" || 
							cpf.value == "22222222222" || 
							cpf.value == "33333333333" || 
							cpf.value == "44444444444" || 
							cpf.value == "55555555555" || 
							cpf.value == "66666666666" || 
							cpf.value == "77777777777" || 
							cpf.value == "88888888888" || 
							cpf.value == "99999999999") {
							
							erro = "N�mero de CPF inv�lido!"
					}
	
					var a = [];
					var b = new Number;
					var c = 11;

					for (i=0; i<11; i++){
							a[i] = cpf.value.charAt(i);
							if (i < 9) b += (a[i] * --c);
					}
	
					if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11-x }
					b = 0;
					c = 11;
	
					for (y=0; y<10; y++) b += (a[y] * c--); 
	
					if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }
	
					if ((cpf.value.charAt(9) != a[9]) || (cpf.value.charAt(10) != a[10])) {
						erro = "N�mero de CPF inv�lido.";
					}
			}
	}
	else
	{
		if(cpf.value.length == 0)
			return false
		else
			erro = "N�mero de CPF inv�lido.";
	}
	if (erro.length > 0) {
			alert(erro);
			//cpf.focus();
			return false;
	} 	
	return true;	
}

/**
 * Retira pontos e tracos da string
 * 
 * @param value
 * @return
 */
function justNumber( value ) {
	return value.replace(/\D/g,"");	
}

//envento onkeyup
function maskCPF(CPF) {
	//var evt = window.event;
	//kcode = evt.which || evt.keyCode;
	/*if (window.event.keyCode) kcode = window.event.keyCode;  	
	else if (window.event.which) kcode = window.event.which; // Netscape 4.?
	else if (window.event.charCode) kcode = window.event.charCode; // Mozilla
*/	
	/*if(window.event)
		kcode = window.event.keyCode; // IE
	else
		kcode = e.which;*/
	
	if (kcode == 8) return;
	if (CPF.value.length == 3) { CPF.value = CPF.value + '.'; }
	if (CPF.value.length == 7) { CPF.value = CPF.value + '.'; }
	if (CPF.value.length == 11) { CPF.value = CPF.value + '-'; }
}

// evento onBlur
function formataCPF(CPF)
{
	with (CPF)
	{
		value = value.substr(0, 3) + '.' + 
				value.substr(3, 3) + '.' + 
				value.substr(6, 3) + '-' +
				value.substr(9, 2);
	}
}
function retiraFormatacao(CPF)
{
	with (CPF)
	{
		value = value.replace (".","");
		value = value.replace (".","");
		value = value.replace ("-","");
		value = value.replace ("/","");
	}
}