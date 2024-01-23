<?php
$servername = "localhost";
$dBUsername = "id21765246_assr";
$dBPassword = "Assr$123456";
$dBName = "id21765246_assr";
$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Actualizar el estado del LED

// Actualizar datos del sensor DHT
if (isset($_POST['update_sensor'])) {
    $sensor_data = explode(",", $_POST['update_sensor']);
    $temperature = $sensor_data[0];
    $humidity = $sensor_data[1];

    // Puedes hacer lo que quieras con los datos, aquí los estoy insertando en una tabla llamada 'sensor_data'
    $sql = "UPDATE LED_status SET temperatura = '$temperature', humedad = '$humidity' WHERE id = 1 ";
    if (mysqli_query($conn, $sql)) {
        #echo "Sensor data updated successfully";
    } else {
        #echo "Error updating sensor data: " . mysqli_error($conn);
    }
}

    $sql = "SELECT * FROM LED_status WHERE id = 1;";
    $result   = mysqli_query($conn, $sql);
    $row  = mysqli_fetch_assoc($result);
    if ($row['status'] == 0) {
        #$update = mysqli_query($conn, "UPDATE LED_status SET status = 1 WHERE id = 1;");
        echo "LED_is_on";
    } else {
        #$update = mysqli_query($conn, "UPDATE LED_status SET status = 0 WHERE id = 1;");
        echo "LED_is_off";
    }
mysqli_close($conn);
?>