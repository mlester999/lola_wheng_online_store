<?php
    session_start();
    require_once ('../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    $minDate = $_POST["minDate"];
    $maxDate = $_POST["maxDate"];

    $modifiedMinDate = new DateTime($minDate);
    $modifiedMaxDate = new DateTime($maxDate);

    $query = "SELECT ROW_NUMBER() OVER(ORDER BY sti.product_id) AS id, name, SUM(sti.quantity) AS total_sales
    FROM `sales_transactions_2_items` sti
    JOIN products p ON sti.product_id = p.id
    WHERE DATE(sti.datetime_added) BETWEEN '".$minDate."' AND '".$maxDate."'
    GROUP BY p.code;";

    $result = mysqli_query($conn, $query);

    $temp_forecast_report = array();
    $currentProduct = -1;

    while($modifiedMinDate <= $modifiedMaxDate) {
        $current_date = $modifiedMinDate->format('Y-m-d');

        $forecastQuery = "SELECT name, SUM(sti.quantity) AS total_sales
        FROM `sales_transactions_2_items` sti
        JOIN products p ON sti.product_id = p.id
        WHERE DATE(sti.datetime_added) >= '".$current_date."' - INTERVAL 2 WEEK
		AND DATE(sti.datetime_added) <= '".$current_date."'
        GROUP BY sti.datetime_added, sti.product_id
        ORDER BY name, sti.datetime_added;";

        $forecastResult = mysqli_query($conn, $forecastQuery);
        
        if(mysqli_num_rows($forecastResult) > 0)
        {
            $product = "";

            $i = 0;

            while($forecastRow = mysqli_fetch_array($forecastResult))
                {
                    if($forecastRow['name'] != $product) {
                         // If the product has changed, create a new object with the product name
                            $product = $forecastRow['name'];
                            $new_object = array(
                                'product_name' => $product,
                                'product_total_sales' => array()
                            );
                            // Push the new object into the main array
                            array_push($temp_forecast_report, $new_object);
                            
                            $currentProduct++;
                        } else {
                            // $current_object = end($temp_forecast_report);
                            
                            array_push($temp_forecast_report[$currentProduct]['product_total_sales'], $forecastRow['total_sales']);
                        }

                    $i++;
                }

            } else {
                echo "No records found";
            }
            
            $modifiedMinDate->modify('+1 day');
            
            if ($modifiedMinDate > $modifiedMaxDate) {
                break;
            }
        }

            // Getting the Mean
            function getMean($array) {
                return array_sum($array) / count($array);
            }

            // Forecast the Next Value
            function getForecastNextValue($data, $mean, $stdDev) {
                $weights = [0.1, 0.3, 0.6];
                $lastValues = array_slice($data, -3);
                $weightedValues = array_map(function($value, $weight) {
                    return $value * $weight;
                }, $lastValues, $weights);
                $forecast = $mean + array_sum($weightedValues);
                $forecast = max($forecast, $mean - $stdDev);
                $forecast = min($forecast, $mean + $stdDev);
                return $forecast;
            }

        $get_forecast = array_map(function($key) {
            $obj = new stdClass();
            $obj->name = $key['product_name'];

            $mean = getMean($key['product_total_sales']);

            $sumSquares = 0;
            foreach ($key['product_total_sales'] as $value) {
                $sumSquares += pow(($value - $mean), 2);
            }
            $variance = $sumSquares / (count($key['product_total_sales']) - 1);
            $stdDev = sqrt($variance);

            $nextDayForecast = getForecastNextValue($key['product_total_sales'], $mean, $stdDev);

            $obj->forecast = round($nextDayForecast);

            return $obj;
        }, $temp_forecast_report);

        // New array to store combined objects
        $combined_products = array();

        // Loop through products array
        foreach ($get_forecast as $product) {
        // Check if product name already exists in combined array
        if (array_key_exists($product->name, $combined_products)) {
            // If it exists, add the price to the existing object
            $combined_products[$product->name]->forecast += $product->forecast;
        } else {
            // If it doesn't exist, add the object to the combined array
            $combined_products[$product->name] = $product;
        }
        }

        echo json_encode(array("forecastData"=>$combined_products));

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
            {
            
                $return .= '
                <tr>
                    <td class="align-items-center text-center">'.$row['id'].'</td>
                    <td class="align-items-center">'.$row['name'].'</td>       
                    <td class="align-items-center">'.$row['total_sales'].'</td>       
                    <td class="align-items-center">'.$combined_products[$row['name']]->forecast.'</td>       
                </tr>
                ';
            }
            echo $return;
    } else {
        // echo "No records found";
    }

    ?>