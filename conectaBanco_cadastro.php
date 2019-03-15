<?php
//Recebe os Dados
		$conexao = mysqli_connect("localhost","WaterAdm","Monitor","Water");
     
		 if(!$conexao)
		 {
			   die (" Erro de Coneccao: ".mysql_error());           
		 }

        $Y = 0;
        $n = 0;
		$resultado = mysqli_query($conexao,"SELECT * FROM Cadastro");
       
 	    while($valor = mysqli_fetch_array($resultado,MYSQLI_ASSOC)){
			$NUMERO[$Y] = $valor["numero "]; 
			$EMAIL[$Y] = $valor["email"];
			$ID[$Y] = $valor["ID"];
			$n = $valor["ID"];
			$Y++;
        }
		
        mysqli_free_result($resultado);
        mysqli_close($conexao);

?>