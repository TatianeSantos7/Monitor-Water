
<?php
//Recebe os dados e publica no banco de dados - BACKEND
// Inclui o código de conexão ao banco de dados 
	
       $conexao = mysqli_connect("localhost","WaterAdm","Monitor","Water");
     
	 if(!$conexao)
	 {
           die (" Erro de Coneccao: ".mysql_error());           
     }
	 else echo 'conectado ao banco<br>';
	
//Variáveis responsáveis  por guardar o valor enviado a Nodmcu
	
	$Temperatura = $_GET["temp"];
	$Ph = $_GET["ph"];
    $Turbidez = $_GET["Turb"];
	
//Captura Data e Hora  do Servidor

	$Data = date('Y-m-d');
	$Tempo = date('H:i:s');
	
// Insere no Banco de Dados 	
mysqli_query($conexao,"INSERT INTO Monitoramento (Tempo,Data,Temperatura,Turbidez,ph) VALUES ('$Tempo','$Data','$Temperatura','$Turbidez', '$Ph')");
//mysqli_query($conexao,"INSERT INTO Monitoramento (Temperatura,Turbidez,ph) VALUES ($Temperatura, $Turbidez,$Ph)");

	
		echo "<br> Salvo com sucesso <br> Temperatura = '$Temperatura' <br> pH = '$Ph' <br> Turbidez= '$Turbidez' <br> <br> Salvo em $Data as $Hora <br>";
	
	mysqli_close($conexao);
?>