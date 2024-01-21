<?php

$servername = "localhost";
$dBUsername = "id21765246_assr";
$dBPassword = "Assr$123456";
$dBName = "id21765246_assr";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
	die("Connection failed: ".mysqli_connect_error());
}


if (isset($_POST['toggle_LED'])) {
	$sql = "SELECT * FROM LED_status;";
	$result   = mysqli_query($conn, $sql);
	$row  = mysqli_fetch_assoc($result);
	
	if($row['status'] == 0){
		$update = mysqli_query($conn, "UPDATE LED_status SET status = 1 WHERE id = 1;");		
	}		
	else{
		$update = mysqli_query($conn, "UPDATE LED_status SET status = 0 WHERE id = 1;");		
	}
}



$sql = "SELECT * FROM LED_status;";
$result   = mysqli_query($conn, $sql);
$row  = mysqli_fetch_assoc($result);	

// Simular datos de temperatura y humedad que cambian cada vez que recargas la página, se debe cambiar
// por las que proporciona la ESP32
$temperature = rand(20, 30);
$humidity = rand(40, 60);

// Definir la ruta de las imágenes de la planta
$plantImageHot = "./images/planta-con-agua.jpg";
$plantImageNormal = "./images/planta-sin-agua.jpg";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FCOERI ASSR</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="./images/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Proyecto de Gestión del Riego
    </a>
    <ul class="navbar-nav ml-auto d-flex align-items-center">
        <li class="nav-item">
            <img src="./profile_picture.png" width="30" height="30" class="rounded-circle" alt="Foto de Perfil">
        </li>
        <li class="nav-item ml-2">
            <span class="navbar-text mr-2">Usuario</span>
        </li>
    </ul>
</nav>

<!-- Contenedor Principal -->
<div class="container mt-4">

    <!-- Filas y Columnas para Gráficas e Información -->
    <div class="row">

        <!-- Columna para Gráficas -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <canvas id="temperature-chart" width="400" height="200"></canvas>
                    <canvas id="humidity-chart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Columna para Información de Variables y Planta -->
        <div class="col-md-4">
            <div class="card">
				<?php
                // Condicional para seleccionar la imagen de la planta
                $plantImage = ($temperature > 25) ? $plantImageHot : $plantImageNormal;
                ?>
                <img src="<?= $plantImage ?>" class="card-img-top" alt="Imagen de Planta">
                <div class="card-body">
                    <h5 class="card-title">Variables del Suelo</h5>
                    <p class="card-text">Temperatura: <span id="temperature"><?= $temperature ?></span> °C</p>
                    <p class="card-text">Humedad: <span id="humidity"><?= $humidity ?></span>%</p>
                </div>
            </div>
        </div>

    </div>

    <script src="script.js"></script>
    <script>
        var temperatureChart = new Chart($('#temperature-chart')[0].getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperatura (°C)',
                    data: [],
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: [{
                        type: 'linear',
                        position: 'bottom',
                        ticks: {
                            stepSize: 1
                        }
                    }]
                }
            }
        });

        var humidityChart = new Chart($('#humidity-chart')[0].getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Humedad (%)',
                    data: [],
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: [{
                        type: 'linear',
                        position: 'bottom',
                        ticks: {
                            stepSize: 1
                        }
                    }]
                }
            }
        });

        setInterval(function () {

			//Aqui se pone las nuevas variables que se van actualizando
            var newTemperature = Math.floor(Math.random() * (30 - 20 + 1)) + 20;
            var newHumidity = Math.floor(Math.random() * (60 - 40 + 1)) + 40;

            $('#temperature').text(newTemperature);
            $('#humidity').text(newHumidity);

            temperatureChart.config.data.labels.push(new Date().toLocaleTimeString());
            temperatureChart.config.data.datasets[0].data.push(newTemperature);

            humidityChart.config.data.labels.push(new Date().toLocaleTimeString());
            humidityChart.config.data.datasets[0].data.push(newHumidity);

            if (temperatureChart.config.data.labels.length > 10) {
                temperatureChart.config.data.labels.shift();
                temperatureChart.config.data.datasets[0].data.shift();
            }

            if (humidityChart.config.data.labels.length > 10) {
                humidityChart.config.data.labels.shift();
                humidityChart.config.data.datasets[0].data.shift();
            }

            temperatureChart.update();
            humidityChart.update();
        }, 5000);
    </script>
</div>
</body>
</html>
