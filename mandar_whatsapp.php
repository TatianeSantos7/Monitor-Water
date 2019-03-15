<?php 
require_once 'vendor/autoload.php';
 
use Twilio\Rest\Client; 

//Armazena as mensagens do Nodmcu
$mensagem = $_GET["mensagem"];
//Cofiguração de Login do Twilio
$sid    = "AC380751f9ed32a6887e0ea53176cc6661"; 
$token  = "698aa1f5fd4a4b8feeb9741b7c78a34e"; 
$twilio = new Client($sid, $token); 


 
 $conexao = mysqli_connect("localhost","WaterAdm","Monitor","Water");
     
		 if(!$conexao)
		 {
			   die (" Erro de Coneccao: ".mysql_error());           
		 }

        $Y = 0;
        $n = 0;
		$resultado = mysqli_query($conexao,"SELECT * FROM Cadastro");
       
 	    while($valor = mysqli_fetch_array($resultado,MYSQLI_ASSOC)){
			
			
			$mandar = $valor["NOME"].", ".$mensagem;
			$message = $twilio->messages 
					  ->create("whatsapp:+". $valor["NUMERO"], // to 
							   array(        
								   "body" => $mandar,
								   "from" => "whatsapp:+14155238886"
							   ) 
					  ); 
			echo $mandar."<BR>";
			
        }
		
        mysqli_free_result($resultado);
        mysqli_close($conexao);
//print($message->sid);

