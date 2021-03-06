<?php

function getAirPressure() {
    $stations = fopen("stations.csv", 'r');
    //$csv_data = fopen(date("G") . '.csv', 'r');
    $csv_data = fopen('16.csv', 'r'); //fopen("/mnt/weather-data/" . date("Y/m/d/G") . ".csv", 'r');
    $nations = array("ROMANIA", "MOLDOVA", "UKRAINE", "BELARUS", "RUSSIA", "BULGARIA", "HUNGARY", "POLAND", "ROMANIA", "SLOVAKIA", "CZECH REPUBLIC");
    $data = array();
    while (($line = fgetcsv($csv_data)) !== FALSE) {
        if (!array_key_exists($line[2], $data) || $line[5] > $data[$line[2]]) {
            $data[$line[2]] = $line[5];
        }
    }

    echo "<table class='stations'>\n\n";
    echo "<tr><th>ID</th><th>Country</th><th>Region</th><th>Max hPa</th></tr>\n";

    while (($line = fgetcsv($stations)) !== FALSE) {
        for ($i = 0; $i < count($nations) - 1; $i++)
            if ($line[2] == $nations[$i]) {
                echo "<tr>";
                echo "<td>" . "<a href='station.php/?id=" . $line[0] . "'>" . $line[0] . "</td>";
                echo "<td>" . $line[2] . "</td>";
                echo "<td>" . $line[1] . "</td>";
                if (array_key_exists($line[0], $data))
                    echo "<td>" . $data[$line[0]] . " " . "hPa" . "</td>";
                else
                    echo "<td>No data yet</td>";
                echo "</tr>\n";
            }
    }
    fclose($stations);
    fclose($csv_data);
    echo "\n</table>";
}
?>

<?php

function getLangLong() {
    $stations = fopen("stations.csv", 'r');
    //$csv_data = fopen(date("G") . '.csv', 'r');
    $csv_data = fopen('16.csv', 'r'); //fopen("/mnt/weather-data/" . date("Y/m/d/G") . ".csv", 'r');
    $nations = array("ROMANIA", "MOLDOVA", "UKRAINE", "BULGARIA", "HUNGARY", "POLAND", "SLOVAKIA", "CZECH REPUBLIC", "ALBANIA", "BOSNIA AND HERZEGOVINA", "CROATIA", "ESTONIA", "LATVIA", "LITHUANIA", "MACEDONIA", "MONTENEGRO", "SERBIA", "SLOVENIA", "");
    $data = array();
    while (($line = fgetcsv($csv_data)) !== FALSE) {
        if (!array_key_exists($line[2], $data) || $line[5] > $data[$line[2]]) {
            $data[$line[2]] = $line[5];
        }
    }

    while (($line = fgetcsv($stations)) !== FALSE) {
        for ($i = 0; $i < count($nations) - 1; $i++)
            if ($line[2] == $nations[$i]) {
                //echo $line[2] . " ";
                //echo $line[3] . "," .$line[4]."<br>";
                echo 'id="' . $line[0] . '"' . ' place="' . $line[1] . '"' . ' country="' . $line[2] . '"' . ' lat="' . $line[3] . '"' . ' lng="' . $line[4] . '"' . ' elevation="' . $line[5] . '"/>' . '<br>';
            }
    }
    fclose($stations);
    fclose($csv_data);
}

function getInfo($id) {
    $stations = fopen("stations.csv", 'r');
    //$csv_data = fopen(date("G") . '.csv', 'r');
    $csv_data = fopen('16.csv', 'r'); //fopen("/mnt/weather-data/" . date("Y/m/d/G") . ".csv", 'r');
    echo "<th style='text-align: center;'>Time </th>";
    echo "<th style='text-align: center;'> Humidity</th>";
    $DAY_LENGTH = 60 * 60;
    $currentDate = date('U');
    while (($line = fgetcsv($csv_data)) !== FALSE) {
        $date = date('U', strtotime($line[1]));
        if ($date >= ($currentDate-$DAY_LENGTH) && $line[2] == $id) {
            $humidity = round(100 * (EXP((17.625 * $line[4]) / (243.04 + $line[4])) / EXP((17.625 * $line[3]) / (243.04 + $line[3]))), 2);
            echo "<tr class='values' style=''>";
            echo "<td style='text-align: center;'>" . $line[1] . "</td>";
            echo "<td style='text-align: center;'>" . $humidity . "</td>";
            echo "</tr>";
        }
    }
        fclose($stations);
        fclose($csv_data);
    }

  function getOverview() {
      $stations = fopen("stations.csv", 'r');
      //$csv_data = fopen(date("G") . '.csv', 'r');
      $csv_data = fopen('16.csv', 'r'); //fopen("/mnt/weather-data/" . date("Y/m/d/G") . ".csv", 'r');
      echo "<tr class='values' style=''>";
      echo "<th style='text-align: center;'>ID </th>";
      echo "<th style='text-align: center;'>Place </th>";
      echo "<th style='text-align: center;'> Humidity</th>";
      echo "</tr>";
      echo "<br>";

      if ($_GET) {
          $country = $_GET['country'];
      } else {
          $country = 'MOLDOVA';
      }

      $station_ids = array();

      while (($line = fgetcsv($stations, 0, "%")) !== FALSE) {
          if ($country == $line[2])
              $station_ids[$line[0]] = [$line[1], 0];
      }

       while (($line = fgetcsv($csv_data)) !== FALSE) {
           $humidity = round(100 * (EXP((17.625 * $line[4]) / (243.04 + $line[4])) / EXP((17.625 * $line[3]) / (243.04 + $line[3]))), 2);
           if (array_key_exists($line[2], $station_ids)) {
               $station_ids[$line[2]][1] = $humidity;
           }
       }

        foreach ($station_ids as $key => $value) {
                echo "<tr>";
                    echo "<td>" . $key . "</td>";
                    echo "<td>" . $value[0] . "</td>";
                    echo "<td>" . $value[1] . "</td>";
                echo "</tr>";
        }
}

function getTitle($id) {
    $stations = fopen("stations.csv", 'r');
    //$csv_data = fopen(date("G") . '.csv', 'r');
    $csv_data = fopen('16.csv', 'r'); //fopen("/mnt/weather-data/" . date("Y/m/d/G") . ".csv", 'r');
    $data = array();
    $DAY_LENGTH = 60 * 60;
    $currentDate = date('U');

    while (($line = fgetcsv($csv_data)) !== FALSE) {
        $date = date('U', strtotime($line[1]));
        if ($date >= ($currentDate-$DAY_LENGTH) && $line[2] == $id) {
            array_push($data, $line[1]);
        }
    }

    for ($i = 0; $i < count($data); $i++) {
        echo "'" . $data[$i] . "',";
    }
    fclose($stations);
    fclose($csv_data);
}

function getValues($id) {
    $stations = fopen("stations.csv", 'r');
    //$csv_data = fopen(date("G") . '.csv', 'r');
    $csv_data = fopen('16.csv', 'r'); //fopen("/mnt/weather-data/" . date("Y/m/d/G") . ".csv", 'r');
    $data = array();
    $DAY_LENGTH = 60 * 60;
    $currentDate = date('U');

    while (($line = fgetcsv($csv_data)) !== FALSE) {
        $date = date('U', strtotime($line[1]));

        if ($date >= ($currentDate-$DAY_LENGTH) && $line[2] == $id) {
            $humidity = round(100 * (EXP((17.625 * $line[4]) / (243.04 + $line[4])) / EXP((17.625 * $line[3]) / (243.04 + $line[3]))), 5);
            array_push($data, $humidity);
        }
    }
    //echo count($data);
    for ($i = 0; $i < count($data); $i++) {
        echo $data[$i] . ",";
    }
    fclose($stations);
    fclose($csv_data);
}





?>
<!DOCTYPE html >
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/sidenav.css" type="text/css"/>
        <link rel="stylesheet" href="css/main.css" type="text/css"/>
        <link rel="stylesheet" href="css/topnav.css" type="text/css"/>
        <link rel="stylesheet" href="css/graphview.css" type="text/css"/>
        <link rel="stylesheet" href="css/tableStyling.css" type="text/css"/>
        <script type="text/javascript" src="js/menumaker.js"></script>
        <title>Weather Pressure Application</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon"
              type="image/png"
              href="img/tab_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.0.1/Chart.bundle.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/graphcontrol.js"></script>
        <script type="text/javascript" src="js/graphs.js"></script>
        <script type="text/javascript" src="js/mapsquery1.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
</head>

<body>
    <?php
    session_start();
    require 'requirebars.php';
    if (isset($_SESSION['username'])) {
        ?>
        <img id="goto" class="schoollogographmode" src="img/schoollogo-small.png">
        <script>
            document.getElementById("goto").onclick = function () {
                window.location = "https://145.33.225.152";
            }
        </script>>
    
        <div class="sidenav">
                <center>
                    <p class = "category">Query Select</p>
                    <a id="QuerySelector" href='query1.php'>Query 1</a>
                    <a id="QuerySelector" href='query2.php'>Query 2</a>
                    <br>
                </center>
                <center><p class = "category">Graph Select</p></center>
                <li><button id="bar"><img id="iconVisual" src="img/graph.png"></button></li>
                <li><button id="line"><img id="iconVisual" src="img/lineChartIcon.png"></button></li>
                <li><button id="pie"><img id="iconVisual" src="img/pieChartIcon.png"></button></li>
                <li><button id="map"><img id="iconVisual" src="img/map.png"></button></li>
                <center> <p class ="category"> User Control</p> </center>
                <div id="WelcomeSection">
                    <p class="welcomeName"><?php
                        getUsername();
                        ?>
                    </p>
                    <a href="index.php"><img id="SettingsIcon" src="img/logout.png"></a>
                </div>
            </div>

        <div class="container-graph"><div class="menu-icon"><span></span></div></div>

        <div id="dashboard-graph" method="POST">
            <div id="cont">
                <canvas id="myChart"></canvas>

            </div>
            <H1 class="">Graph Viewer</br> </br></H1>
            <div id="googleMap"></div>
        </div>

        <div id="test" style="width: 100%; text-align: center; color:white;">
            <?php
            if (isset($_GET['id'])) {
                //echo date("Y/m/d/h") . "/" . $_GET['country'] . ".csv";
                getTitle($_GET['id']);
                //echo date("i");
            }
            ?>
        </div>

        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6S_YXL2ge22s_tfI1o43iIEQur5bEoCo&callback=initMap">

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6S_YXL2ge22s_tfI1o43iIEQur5bEoCo&callback=myMap"></script>
        <span class='fa-stack fa-lg'>


            <i class='fa fa=circle fa-stack-2x'></i>
            <i class='fa fa-lock fa-stack-1x'</i>
        </span>
    </p>

    </div>



    <script>
            Chart.defaults.global.defaultFontFamily = 'Lato';
            Chart.defaults.global.defaultFontSize = 24;
            Chart.defaults.global.defaultFontColor = '#FFF';
            var config = {
                type: 'line',
                data: {
                    labels: [<?php getTitle($_GET['id'])?>],
                    datasets: [{
                            label: "Humidity per time",
                            data: [<?php getValues($_GET['id']) ?>],
                            fill: true,
                            borderColor: "purple",
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)',
                                'rgba(255, 99, 132, 0.6)'
                            ],
                            borderwidth: 1,
                            borderColor: '#FFF',
                            hoverBorderWidth: 3,
                            hoverBorderColor: "#FFF",
                        }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Humidity',
                        fontsize: 25
                    },
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            fontColor: '#000'
                        }
                    },
                    layout: {
                        responsive: true,
                        padding: {
                            left: 50,
                            right: 0,
                            bottom: 0,
                            top: 0
                        }
                    },
                    tooltips: {
                        enabled: true
                    }
                }
            };
            var myChart;
            $("#line").click(function () {
                document.getElementById("googleMap").classList.add("disabled");
                $("#myChart").addClass("enabled");
                change('line');
            });
            $("#bar").click(function () {
                document.getElementById("googleMap").classList.add("disabled");
                $("#myChart").addClass("enabled");
                change('bar');
            });
            $("#pie").click(function () {
                $("#myChart").removeClass("noPointerEvents");
                document.getElementById("googleMap").classList.add("disabled");
                $("#myChart").addClass("enabled");
                change('pie');
            });
            $("#map").click(function () {
                document.getElementById("googleMap").classList.remove("disabled");
                google.maps.event.trigger(map, 'resize');
                change('');
            }
            );
            function change(newType) {
                var ctx = document.getElementById("myChart").getContext("2d");
                // Remove the old chart and all its event handles
                if (myChart) {
                    myChart.destroy();
                }

                // Chart.js modifies the object you pass in. Pass a copy of the object so we can use the original object later
                var temp = jQuery.extend(true, {}, config);
                temp.type = newType;
                myChart = new Chart(ctx, temp);
            }
            ;</script>
    <div class ="container-control">
        <div class="dashboard-control" method="POST">
            <H1>Graph Control</br> </br></H1>
            <span class='fa-stack fa-lg'>

                <img href='#' id="pdf" src="img/adobe-pdf-icon.png">
                <div id="pdfoverlay">
                    <div class="text">Download to PDF</div>
                </div>

                <img href='#' id="excel" src="img/excel.png" onclick="myFunction()" src="js/graphcontrol" />
                <div id="exceloverlay">
                    <div class="text">Download to CSV</div>
                </div>
                <i class='fa fa=circle fa-stack-2x'></i>
                <i class='fa fa-lock fa-stack-1x'</i>

            </span>
            </p>
        </div>
        <div class ="container-values">
            <div class="dashboard-values" method="POST">
                <h1 id="valuesTitle"> Values </h1>
                <span class='fa-stack fa-lg'>
                    <i class='fa fa=circle fa-stack-2x'></i>
                    <i class='fa fa-lock fa-stack-1x'</i>
                </span>
                </p>
                : <table class ='values' style="margin: auto; width: 74%; left: 12%; overflow-y: hidden;">
                    <?php
                    if (isset($_GET['id'])) {
                        $place = ucwords(strtolower($_GET['place']));
                        $country = ucwords(strtolower($_GET['country']));
                        echo "<center> <p id='placeTitle'>Location data for <b>" . $place . ", " . $country . "</b><br></center>";
                        ?>

                        <script>
                            setInterval(function() {
                                window.location.reload();
                            }, 60000);
                        </script>

                        <?php
                        getInfo($_GET['id']);
                    } else {
                        echo "<center> <p id='placeTitle'>Current Humidity for Moldova<br></center>";
                        getOverview();
                    }
                    ?>
            </div>
        </div>
        <?php
    } else {
        header('location:index.php');
        exit;
    }
    ?>
    <div class="underlay-photo"> </div>
    <div class="underlay-black"></div>
</body>
</html>
