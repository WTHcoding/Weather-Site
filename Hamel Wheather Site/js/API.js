"use strict";

function getCoord(coords) {
            //current format is Long, Lat
            const [lon, lat] = coords.split(', ');

            // const now = new Date();
            // console.log(now); // This will output the current date and time in your local timezone.

            // console.log(coordsList)
            const unit = "metric";
            //const unit = "british";
            // const tzshift = 0;

            // console.log(jsonCoords);
            // http://www.7timer.info/bin/api.pl?lon=113.17&lat=23.09&product=civil&output=json
            const url = `https://www.7timer.info/bin/api.pl?lon=${lon}&lat=${lat}&product=civil&output=json`;

             fetchFromURL(url)
                .then(weatherData => {
                console.log(weatherData);
                displayWeather(weatherData.product ,weatherData.init, weatherData.dataseries);
                })
                .catch(error => {
                console.error('Error fetching weather data:', error);
                });


            // postData(wheatherData.dataseries);

            return
        }

        async function fetchFromURL(url) {
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }

                // Step 1: Get raw text
                let text = await response.text();

                // Step 2: Replace missing values ": ," with -9999
                text = text.replace(/:\s*,/g, ': -9999,');

                // Step 3: Remove trailing commas before closing brackets/braces
                text = text.replace(/,(\s*[\]}])/g, '$1');

                // Step 4: Parse the cleaned text
                const result = JSON.parse(text);

                console.log(result);
                return result;
            } catch (error) {
                console.error(error.message);
            }
        }