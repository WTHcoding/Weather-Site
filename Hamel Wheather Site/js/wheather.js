"use strict";

function displayWeather(product, init, wheatherData) {
    const container = document.getElementById('wheatherBoxes');
    container.innerHTML = ''; // clear old

    const year = init.slice(0, 4);
    const month = init.slice(4, 6) - 1;
    const day = init.slice(6, 8);
    const hours = init.slice(8);

    const currentTime = new Date(year, month, day, hours);
    const localTime = new Date(year, month, day, hours);

    let count = 0;

    const readibleConditions = {
        "-9999": "Invalid",
        "clear": "Clear",
        "cloudy": "Very Cloudy",
        "fog": "Fog",
        "humid": "Humid",
        "ishower": "Isolated Showers",
        "lightrain": "Light Rain",
        "mcloudy": "Cloudy",
        "oshower": "Occasional Showers",
        "pcloudy": "Partly Cloudy",
        "rain": "Rainy",
        "RainSnow": "Wintery Mix",
        "snow": "Snow",
        "tsrain": "Thunderstorm Possible",
        "ts": "Thundertorm",
        "tsstrom": "Thunderstorm",
        "windy": "Windy"
    };
    while (count < 64) {  // One Box per day
        let dailyCond = "clear";
        let dailyMin = 9999;
        let dailyMax = -9999;

        while (count < 64) {
            const today = wheatherData[count];
            localTime.setHours(localTime.getHours() + today.timepoint);

            dailyCond = maxRating(dailyCond, today.weather);

            if (localTime.getDate() !== currentTime.getDate()) {
                localTime.setHours(localTime.getHours() - today.timepoint);
                break;
            }
            localTime.setHours(localTime.getHours() - today.timepoint);

            if (today.temp2m < dailyMin && today.temp2m > -9999) {
                dailyMin = today.temp2m;
            }
            if (today.temp2m > dailyMax) {
                dailyMax = today.temp2m;
            }

            count += 1;
        }

        const frame = document.createElement('div');
        frame.className = 'weatherBox col-6 col-md-4 col-lg-3 mb-3';

        let iconUrl = getIcon(dailyCond);

        frame.innerHTML = `
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
                <h6 class="card-title mb-1">
                    ${currentTime.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })}
                </h6>
                <img src="${iconUrl}" alt="${dailyCond}" class="img-fluid mb-2" style="max-height:80px;">
                <h7><br>${readibleConditions[dailyCond]}</h7>
                <hr>
                <p class = "temprature"> 
                Low: ${dailyMin}°C <br>
                High: ${dailyMax}°C
                </p>
            </div>
        </div>
        `;

        container.appendChild(frame);
        currentTime.setDate(currentTime.getDate() + 1);
    }
}

function getIcon(textSegment) {
    const prefix = "../images/";
    const postfix = ".png";
    if (textSegment === "ts") {
        return prefix + "tsrain" + postfix;
    }
    return prefix + textSegment + postfix;
}

function maxRating(rating1 = "clear", rating2 = "clear") {
    rating1 = rating1.replace("day", '').replace("night", '');
    rating2 = rating2.replace("day", '').replace("night", '');

    const severity = {
        "-9999": -9999,
        "clear": 1,
        "pcloudy": 2,
        "mcloudy": 3,
        "cloudy": 4,
        "fog": 5,
        "humid": 5,
        "windy": 6,
        "lightrain": 7,
        "oshower": 8,
        "ishower": 9,
        "lightsnow": 10,
        "rain": 11,
        "snow": 12,
        "rainsnow": 13,
        "ts": 14,
        "tsrain": 14,
        "tstorm": 15
    };

    return (severity[rating1] > severity[rating2]) ? rating1 : rating2;
}
