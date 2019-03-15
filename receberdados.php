<?php
        $conexao = mysqli_connect("localhost","WaterAdm","Monitor","Water");
     
		 if(!$conexao)
		 {
			   die (" Erro de Coneccao: ".mysql_error());           
		 }

        $Y = 0;
        $n = 0;
		$resultado = mysqli_query($conexao,"SELECT * FROM Monitoramento"); 
       
 	    while($valor = mysqli_fetch_array($resultado,MYSQLI_ASSOC)){
			$temp[$Y] = $valor["Temperatura"]; 
			$ph[$Y] = $valor["ph"];
			$turb[$Y]= $valor["Turbidez"];
			$Tempo[$Y]=$valor["Tempo"];
			$Data[$Y]=$valor["Data"];
			$ID[$Y] = $valor["ID"];
			$n = $valor["ID"];
			$Y++;
        }
		//for($i=0;$i<$Y;$i++){ 
		//echo $Tempo[$i]."<br>";
		//}
        mysqli_free_result($resultado);
        mysqli_close($conexao);
?>