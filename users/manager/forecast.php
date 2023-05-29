<?php
    session_start();
    require_once ('../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $category = $_POST['category'];
    $filter = $_POST['filter'];

    $query = "SELECT a.*
                        FROM (
                            SELECT `id`, `name`, `original_price`, `retail_price`, `product_image`, `quantity`, `code`, ROW_NUMBER()OVER(PARTITION BY `code` ORDER BY date_added DESC) AS row_id
                            FROM `products`
                            WHERE name = '$category'
                            ORDER BY id ASC 
                        ) a
                        WHERE a.row_id = 1
                    ";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        if($filter == "Weekly") {
            while($row = mysqli_fetch_array($result))
            {
            $barcode = $row['code'];

            $getAllSalesPerDayQuery = "SELECT DATE(datetime_added) AS day, SUM(quantity) AS total_sales
            FROM `sales_transactions_2_items`
            WHERE product_barcode = '$barcode'
            AND datetime_added >= DATE_FORMAT(NOW(), '%Y-%m-01')
            AND datetime_added <= LAST_DAY(NOW())
            GROUP BY DAY(datetime_added);
            ";

            $salesResult = $conn->query($getAllSalesPerDayQuery);

            $total_sales = array();
            while($salesRow = mysqli_fetch_array($salesResult)) {
                $obj = new stdClass();
                $obj->day = $salesRow['day'];
                $obj->total_sales = $salesRow['total_sales'];

                array_push($total_sales, $obj);
            }

            // define a function to search for an object by property value
            function search_array($array, $key, $value) {
                foreach ($array as $item) {
                    if (isset($item->$key) && $item->$key === $value) {
                        $total_sales = 'total_sales';
                        return $item->$total_sales;
                    }
                }
                return false;
            }

            // Getting the Mean
            function mean($array) {
                return array_sum($array) / count($array);
            }

            // Forecast the Next Value
            function forecastNextValue($data, $mean, $stdDev) {
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

            $first_day = date('Y-m-01');
            $current_month = date('m');
            $current_year = date('Y');
            $last_day = date('t', strtotime("$current_year-$current_month-01"));

            $odd_days = array();
            $overall_total_sales = array();
            $forecast_next_sales = array();

            $day = 1;
            while ($day <= $last_day) {
            $date = "$current_year-$current_month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $odd_days[] = $date;

            $noSalesData = search_array($total_sales, 'day', $date);

            if($noSalesData) {
                $overall_total_sales[] = intval($noSalesData);
            } else {
                $overall_total_sales[] = 0;
            }

            $temp_forecast_sales = array();

            $getAllSalesLast2Weeks = "SELECT DATE(datetime_added) AS day, quantity as total_sales FROM `sales_transactions_2_items`
            WHERE product_barcode = '$barcode'
            AND datetime_added >= DATE_SUB(DATE('$date'), INTERVAL 2 WEEK)
            AND datetime_added < DATE('$date');";

            $forecastNextSalesResult = $conn->query($getAllSalesLast2Weeks);

            while($nextSalesRow = mysqli_fetch_array($forecastNextSalesResult)) {
                  
                array_push($temp_forecast_sales, $nextSalesRow['total_sales']);
            }

            $mean = mean($temp_forecast_sales);

            $sumSquares = 0;
            foreach ($temp_forecast_sales as $value) {
                $sumSquares += pow(($value - $mean), 2);
            }
            $variance = $sumSquares / (count($temp_forecast_sales) - 1);
            $stdDev = sqrt($variance);

            $nextDayForecast = forecastNextValue($temp_forecast_sales, $mean, $stdDev);

            array_push($forecast_next_sales, round($nextDayForecast));

            $day++;
            }
        }
            
        echo json_encode(array("totalSales"=>$overall_total_sales, "dateLabel"=>$odd_days, "forecastSales"=>$forecast_next_sales));
        // echo json_encode($odd_days);
    } else if($filter == "Monthly") {
        while($row = mysqli_fetch_array($result))
            {
        $barcode = $row['code'];

        $getAllSalesPerDayQuery = "SELECT DATE_FORMAT(datetime_added, '%Y-%m') AS month, SUM(quantity) AS total_sales
        FROM `sales_transactions_2_items`
        WHERE product_barcode = '$barcode'
        GROUP BY month
        ORDER BY month;
        ";

        $salesResult = $conn->query($getAllSalesPerDayQuery);

        $total_sales = array();
        while($salesRow = mysqli_fetch_array($salesResult)) {
            $obj = new stdClass();
            $obj->month = $salesRow['month'];
            $obj->total_sales = $salesRow['total_sales'];

            array_push($total_sales, $obj);
        }
        
        $overall_total_sales = array();
        $forecast_next_sales = array();
        $all_months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // define a function to search for an object by property value
        function search_array($array, $key, $value) {
            $alphabet_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

            foreach ($array as $item) {
                if (isset($item->$key)) {
                    $parts = explode('-', $item->$key);
                    $month = $alphabet_months[intval($parts[1])-1];

                    if($month === $value) {
                        $total_sales = 'total_sales';
                        return $item->$total_sales;
                    }
                }
            }
            return false;
        }

        // Getting the Mean
        function mean($array) {
            return array_sum($array) / count($array);
        }

        // Forecast the Next Value
        function forecastNextValue($data, $mean, $stdDev) {
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

        foreach ($all_months as $month) {
            $strToDate = strtotime($month);
            $dateFormatMonth = date('Y-m-d', $strToDate);

            $noSalesData = search_array($total_sales, 'month', $month);

            if($noSalesData) {
                $overall_total_sales[] = intval($noSalesData);
            } else {
                $overall_total_sales[] = 0;
            }

            $temp_forecast_sales = array();

            $getAllSalesLast2Weeks = "SELECT YEAR(datetime_added) as year, MONTH(datetime_added) as month, SUM(quantity) as total_sales
            FROM `sales_transactions_2_items`
            WHERE product_barcode = '$barcode'
            AND datetime_added >= DATE_SUB(DATE('$dateFormatMonth'), INTERVAL 12 MONTH)
            AND datetime_added < DATE('$dateFormatMonth')
            GROUP BY YEAR(datetime_added), MONTH(datetime_added);";

            $forecastNextSalesResult = $conn->query($getAllSalesLast2Weeks);

            while($nextSalesRow = mysqli_fetch_array($forecastNextSalesResult)) {
                  
                array_push($temp_forecast_sales, intval($nextSalesRow['total_sales']));
            }

            $mean = mean($temp_forecast_sales);

            $sumSquares = 0;
            foreach ($temp_forecast_sales as $value) {
                $sumSquares += pow(($value - $mean), 2);
            }
            $variance = $sumSquares / (count($temp_forecast_sales) - 1);
            $stdDev = sqrt($variance);

            $nextDayForecast = forecastNextValue($temp_forecast_sales, $mean, $stdDev);

            array_push($forecast_next_sales, round($nextDayForecast));
        }

        }
            echo json_encode(array("dateLabel"=>$all_months, "totalSales"=>$overall_total_sales, "forecastSales"=>$forecast_next_sales));
        } else {
            echo json_encode('No products found.');
        }
    }
    else
    {
        echo json_encode('No products found.');
    }
?>
