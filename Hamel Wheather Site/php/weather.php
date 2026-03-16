<!DOCTYPE html>

<html lang="en">

<!--http://localhost:8000/StaterFile/php/weather.php-->

<head>
    <title>Home | European Weather </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <link rel="stylesheet" href="../css/master.css">

</head>


<body>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>


    <button onclick="darkModeFunc()">Toggle dark mode</button>

    <script>
        function darkModeFunc() {
            var element = document.body;
            element.classList.toggle("darkMode");
        }
        function toggleUnits() {
            var unit = "metric"

        }

    </script>
    <script src = "../js/wheather.js"></script>

    <script src = "../js/API.js"></script>

    <noscript>
        <style type="text/css">
            .weatherDrop {
                display: none;
            }
        </style>
        <div class="noscriptmsg">
            Please Enable JS to make this website work
        </div>
    </noscript>

    <div class="container">
        <div class="row jumbotron text-center">
            <div class="col-md">

                <h1>European Weather</h1>

                <h2 class='section-subheading'>European Weather Forecast</h2>

                <!-- <p><code class="copyright-text">Powered by <a href="http://www.7timer.info/doc.php?lang=en" target= "_blank"  data-toggle="tooltip" data-placement="Top" title="Tap to visit 7Timer!" onclick="javascript:toolTipReset()"><span class="keyword-magnet">7Timer!</span></a></code></p> -->
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-center weatherDrop" id="cityDropdown">
        <form id="city-select">
            <select name="citySelected" id="citySelected" onchange="getCoord(this.value)" data-placement="bottom"
                title="">
                <option value="" disabled selected> -- select a city -- </option>

                <?php

            if (($handle = fopen("../city_coordinates.csv", "r")) !== FALSE) {
                while (($line = fgets($handle)) !== FALSE) {
                    $line = trim($line);
                    if ($line === "") continue; // skip empty lines
                    
                    $city = explode(",", $line);
                    // $city[0] = lat, $city[1] = lon, $city[2] = city, $city[3] = country
                    echo "<option value='{$city[0]}, {$city[1]}'>" . 
                            htmlspecialchars($city[2]) . "," . htmlspecialchars($city[3]) . 
                            "</option>";
                }
                fclose($handle);
            }
            else{
                echo "<option value=''>" . 
                            "INVALID FILE". 
                            "</option>";
            }
            ?>

            </select>
        </form>
    </div>

    <div class="container">
        <div class="row d-flex" id="wheatherBoxes">
            <script>

                function displayWeather(product,init,wheatherData) {

                    const container = document.getElementById('wheatherBoxes');
                    container.innerHTML = ''; // clear old

                    console.log(init);
                    const year = init.slice(0,4);
                    const month = init.slice(4,6) - 1;
                    const day = init.slice(6,8);
                    const hours = init.slice(8);

                    const currentTime = new Date(year, month, day, hours);
                    const localTime = new Date(year, month, day, hours);

                    console.log(year, month, day, hours);
                    // console.log(month);
                    console.log(currentTime);

                    // const today = wheatherData[i];
                    let count = 0;
                    while (count < 64) {  // One Box per day
                        let dailyCond = "clear";
                        let dailyMin = 9999;
                        let dailyMax = -9999;

                        while (count < 64){
                            const today = wheatherData[count];

                            localTime.setHours(localTime.getHours() + today.timepoint);

                            dailyCond = maxRating(dailyCond, wheatherData[count].weather);

                            if(localTime.getDate() != currentTime.getDate()){
                                localTime.setHours(localTime.getHours() - today.timepoint);
                                break
                            }
                            localTime.setHours(localTime.getHours() - today.timepoint);

                            // note: -9999 is invalid
                            if (today.temp2m < dailyMin && today.temp2m > -9999){
                                dailyMin = today.temp2m;
                            }
                            if (today.temp2m > dailyMax){
                                dailyMax = today.temp2m;
                            }

                            count += 1
                            
                        }
                        
                        

                        const frame = document.createElement('div');
                        
                        frame.className = 'weatherBox col-6 col-md-4 col-lg-3 mb-3';
                        
                        
                        
                        const iconUrl = getIcon(dailyCond);

                            frame.innerHTML = `
                            <div class="card h-100 shadow-sm text-center">
                                <div class="card-body">
                                    <h6 class="card-title mb-1">
                                        ${currentTime.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })}
                                    </h6>
                                    <img src="${iconUrl}" alt="${dailyCond}" class="img-fluid mb-2" style="max-height:80px;">
                                    <p> 
                                    Min: ${dailyMin}°C <br>
                                    Max: ${dailyMax}°C
                                    </p>
                                </div>
                            </div>
                        `;

                        container.appendChild(frame);
                        currentTime.setDate(currentTime.getDate() + 1); 
                    }

                    return

                }
                

                function getIcon(textSegment) {

                    const prefix = "../images/";
                    const postfix = ".png";
                    return prefix + textSegment + postfix;

                }
                function maxRating(rating1 = "clear", rating2 = "clear"){
                    
                    rating1.replace("day",'');
                    rating1.replace("night",'');

                    rating2.replace("day",'');
                    rating2.replace("night",'');

                    const severity = {
                        -9999: -9999,
                        clear: 1,
                        pcloudy: 2,
                        mcloudy: 3,
                        cloudy: 4,
                        fog: 5, 
                        humid: 5,
                        windy: 6,  
                        lightrain: 7,
                        oshower: 8,
                        ishower: 9,
                        lightsnow:10,
                        rain: 11,
                        snow: 12,
                        rainsnow: 13,
                        tsrain: 14,
                        tstorm: 15
                    };

                    if severity[rating1] > severity[rating2]:
                        return rating1;
                    return rating2; 
                }

            </script>
        </div>
    </div>









</body>


<footer>
    <code class="copyright-text fixed-bottom">© Copyright 2025</code>
</footer>