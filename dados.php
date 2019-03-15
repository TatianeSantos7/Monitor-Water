<html>
<HEAD> 
	<title> Gráficos </title> 
	<link rel="shortcut icon" href="agua.png">
	<script src="Chart.min.js"> </script>
  </HEAD>
<style>
canvas{
	-moz-user-select : none;
	-webkit-user-select: none;
	-ms-user-select: none;
}
</style>

<BODY onload="GerarGrafico()">
 <p style="text-align:center"><img alt="" src="agua.jpg" /></p>
      <p style="text-align: center;">
	    <span style="font-size:24px;"><span style="font-family:times new roman,times,serif;">
	    <strong>MonitorWater:</strong> Monitoramento da Qualidade da &Aacute;gua</span></span></p>
		
	<div style = "width:75%;height:75%;margin:auto;text-align:center" >
		<canvas class="Temperatura" ></canvas>
		<canvas class="Turbidez"></canvas>
		<canvas class="pH"></canvas>
		 <form name="volta"  method="post" action="index.html">
		  <input type="submit" name="button"  value="Voltar"> 
		  </form>
	</div>
	
	
 <?php
        $conexao = mysqli_connect("localhost","WaterAdm","Monitor","Water");
     
		 if(!$conexao)
		 {
			   die (" Erro de Coneccao: ".mysql_error());           
		 }
		$data_escolhida = $_GET['diaa'];
        $Y = 0;
        $n = 0;
		$resultado = mysqli_query($conexao,"SELECT * FROM Monitoramento"); 
       
 	    while($valor = mysqli_fetch_array($resultado,MYSQLI_ASSOC)){
			if($valor["Data"] == $data_escolhida){
				$temp[$Y] = $valor["Temperatura"]; 
				$ph[$Y] = $valor["ph"];
				$turb[$Y]= $valor["Turbidez"];
				$Tempo[$Y]=$valor["Tempo"];
				$Data[$Y]=$valor["Data"];
				$ID[$Y] = $valor["ID"];
				$n = $valor["ID"];
				$Y++;
			}
        }
		//for($i=0;$i<$Y;$i++){ 
		//echo $Tempo[$i]."<br>";
		//}
        mysqli_free_result($resultado);
        mysqli_close($conexao);
?>

<script type="text/javascript">
function GerarGrafico(){
    	
	if("<?php echo $Y ?>" != 0){
		var tipoGrafico = document.getElementsByClassName("line-chart");
     	var GraficoTemperaturaConfig = document.getElementsByClassName("Temperatura");
     	var GraficoTurbidezConfig = document.getElementsByClassName("Turbidez");
     	var GraficoPHConfig = document.getElementsByClassName("pH");
		var IdData = new Array(), TempData= new Array(), phData = new Array(), TurbidezData = new Array();
		var	phMax = new Array(),phMin = new Array(),TurbidezMax = new Array();
		
		IdData   = "<?php $z = 1; foreach($Tempo as $IDs){ if($z<$n) echo $IDs . ","; else echo $IDs; $z++;}?>".split(",");
		TempData = "<?php $z = 1; foreach($temp as $temps){ if($z<$n) echo $temps . ","; else echo $temps; $z++;}?>".split(",");
		phData   = "<?php $z = 1; foreach($ph as $temps){ if($z<$n) echo $temps . ","; else echo $temps; $z++;}?>".split(",");
		TurbidezData = "<?php $z = 1; foreach($turb as $temps){ if($z<$n) echo $temps . ","; else echo $temps; $z++;}?>".split(",");
		phMax = "<?php $z =1; for( $i=0; $i<$n;$i++){ if($z<$n) echo "9,";else echo"9"; $z++;} ?>".split(",");
		phMin = "<?php $z =1; for( $i=0; $i<$n;$i++){ if($z<$n) echo "6.8,";else echo"6.8"; $z++;} ?>".split(",");
		TurbidezMax = "<?php $z =1; for( $i=0; $i<$n;$i++){ if($z<$n) echo "500,";else echo"500"; $z++;} ?>".split(",");
		
		//document.write(phMax);
		var graficoTemperatura = new Chart(GraficoTemperaturaConfig,{
		type: 'line',
		data: {
		 labels: IdData,
		 datasets: [
		{
			label: "Temperatura",
			data : TempData,
			borderWidth: 3,
			borderColor: 'rgba(0,0,255,1)',
			backgroundColor : 'transparent',
			pointRadius : 0,
		},
		]},
		options:{scales:{yAxes:[{ticks: { min: 0,max: 40,}}]},
			 animation:{duration:0,},
			 hover:{animationDuration:0},
			 responsiveAnimationDuration:0,	
		}});	
		
		var GraficoTurbidez = new Chart(GraficoTurbidezConfig,{
		type: 'line',
		data: {
		 labels: IdData,
		 datasets: [
		{
			label: "Turbidez",
			data : TurbidezData,
			borderWidth: 3,
			borderColor: 'rgba(0,0,255,1)',
			backgroundColor : 'transparent',
			pointRadius : 0,
		},
		{
			label: "Acima do Padrão",
			data : TurbidezMax,
			borderWidth: 5,
			borderColor: 'rgba(255,0,0,1)',
			backgroundColor : 'transparent',
			pointRadius : 0,
		},
		]},
		options:{scales:{yAxes:[{ticks: { min: 0,max: 600,}}]},
			 animation:{duration:0,},
			 hover:{animationDuration:0},
			 responsiveAnimationDuration:0,	
		}});
		
		var GraficoPH = new Chart(GraficoPHConfig,{
		type: 'line',
		data: {
		 labels: IdData,
		 datasets: [
		{
			label: "ph",
			data : phData,
			borderWidth: 3,
			borderColor: 'rgba(0,0,255,1)',
			backgroundColor : 'transparent',
			pointRadius : 0,
		},
		{
			label: "Acima do Padrão",
			data : phMax,
			borderWidth: 3,
			borderColor: 'rgba(255,0,0,1)',
			backgroundColor : 'transparent',
			pointRadius : 0,
		},
		
		{
			label: "Abaixo do Padrão",
			data : phMin,
			borderWidth: 3,
			borderColor: 'rgba(255,0,0,1)',
			backgroundColor : 'transparent',
			pointRadius : 0,
		},
		]},
		options:{scales:{yAxes:[{ticks: { min: 0,max: 14,}}]},
			 animation:{duration:0,},
			 hover:{animationDuration:0},
			 responsiveAnimationDuration:0,	
		}});
	}
	else{
		alert("Data não encontrada!");
	}
}



GerarGrafico();
window.setTimeout("GerarGrafico()",10000);

</script>

</BODY>
</html>

