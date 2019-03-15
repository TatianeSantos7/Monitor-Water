 <!DOCTYPE html>
<html>
<HEAD> <title> Gráficos </title>  </HEAD>
<script src="Chart.min.js"> </script>
<style>
canvas{
	-moz-user-select : none;
	-webkit-user-select: none;
	-ms-user-select: none;
}
</style>
<BODY onload="GerarGrafico()">
	<div style = "width:50%;">
		<canvas class="Temperatura"></canvas>
		<canvas class="Turbidez"></canvas>
		<canvas class="pH"></canvas>
		<canvas class="line-chart"></canvas>
	</div>
<?php include("receberdados.php");?>

<script type="text/javascript">
function GerarGrafico(){
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
		borderColor: 'rgba(77,166,253,0.85)',
		backgroundColor : 'transparent',
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
		borderColor: 'rgba(77,166,253,0.85)',
		backgroundColor : 'transparent',
	},
	{
		label: "Acima do Padrão",
		data : TurbidezMax,
		borderWidth: 5,
		borderColor: 'rgba(202,11,46,0.5)',
		backgroundColor : 'transparent',
	},
	]},
	options:{scales:{yAxes:[{ticks: { min: 0,max: 40,}}]},
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
		borderColor: 'rgba(77,166,253,0.85)',
		backgroundColor : 'transparent',
	},
	{
		label: "Acima do Padrão",
		data : phMax,
		borderWidth: 5,
		borderColor: 'rgba(202,11,46,0.5)',
		backgroundColor : 'transparent',
	},
	
	{
		label: "Abaixo do Padrão",
		data : phMin,
		borderWidth: 5,
		borderColor: 'rgba(202,11,46,0.5)',
		backgroundColor : 'transparent',
	},
	]},
	options:{scales:{yAxes:[{ticks: { min: 0,max: 14,}}]},
		 animation:{duration:0,},
		 hover:{animationDuration:0},
		 responsiveAnimationDuration:0,	
	}});
	
}



GerarGrafico();
//window.setTimeout("GerarGrafico()",3000);

</script>

</BODY>
</html>
