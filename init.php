
<html>
<head>
<title> Testando PHP </title>
<body>
<?php
   // Conectar ao banco de dados
  
   $conexao = mysqli_connect("localhost","tati","WaterMonitor","Water");
     
	 if(!$conexao)
	 {
           die (" Erro de Conecção: ".mysql_error());       // Indica erro caso não haja conexão
         }
	echo 'Funcionando';
?>
</body>
</head>
</html>