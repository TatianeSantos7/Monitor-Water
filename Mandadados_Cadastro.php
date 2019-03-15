<?php
// Inclui o código de conexão ao banco de dados 
	
       $conexao = mysqli_connect("localhost","WaterAdm","Monitor","Water");
     
	 if(!$conexao)
	 {
           die (" Erro de Coneccao: ".mysql_error());           
     }
	 else echo 'Cadastro realizado com sucesso<br>';
	
//Variáveis responsáveis  por guardar o valor enviado a Nodmcu
	
	        $NUMERO = $_GET["numero"]; 
			$NOME   = $_GET["nome"];
			
			echo "nome:".$NOME."<br>numero:".$NUMERO;
// Insere no Banco de Dados 	
mysqli_query($conexao,"INSERT INTO Cadastro(NUMERO, NOME) VALUES ('$NUMERO','$NOME')");
	
	mysqli_close($conexao);
?> 