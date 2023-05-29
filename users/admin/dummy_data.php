<?php

            function get_random_string_max($length) {

                $array = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                $text = "";
        
                $length = rand(4,$length);
        
                for($i=0;$i<$length;$i++) {
        
                    $random = rand(0,61);
                    
                    $text .= $array[$random];
        
                }
        
                return $text;
            }
        
             // Generate dummy data for sales_transactions_2_items
             $itemQuantityData = array();
             $itemSalesData = array();
             $first_day_of_month = date('2023-03-01');
             $last_day_of_month = date('2023-03-t');
             $customer_ids = ['10', '11', '12', '13', '14', '15', '16'];
             $customer_names = ['Vince Alvarez', 'Mitchel Tapang', 'Jonas Candelario', 'Lebron James', 'Jose Rizal', 'Tutti Caringal', 'Ney Dimaculangan'];
             $customer_contact_numbers = ['09151392944', '09178891824', '09192741182', '09158991825', '09149928175', '09179928144', '09158829155'];
             $customer_addresses = ['Blk 6A Lot 71 Phase 3 Cluster Mabuhay City, Mamatid, Cabuyao, Laguna', 'Blk 11 Lot 96 Mt. Zion, Shanti Dope Street, Sta. Rosa, Laguna', 'Blk 17 Lot 192 Phase 2 Extension, Marinig, Cabuyao, Laguna', 'Blk 55 Lot 12 Phase 5 Cluster, Mabuhay City, San Isidro, Katapatan Homes, Laguna', 'Blk 192 Lot 51 Phase 4, Bagumbayan, Calamba City, Laguna', 'Blk 19 Lot 30 Phase 9 Cluster Extension Series', 'Blk 10 Lot 88 Phase 4 Extension Executive of The Billboards'];
        
             for ($i = 0; $i < 30; $i++) {
             $quantity = rand(1, 20);
             $session_id = get_random_string_max(50);
             $datetime_added = date("Y-m-d H:i:s", strtotime("$first_day_of_month +$i days"));
             $product_id = 8;
             $product_barcode = "48025522";
             $product_price = 3.75;
             $unique_key = $session_id;
             $user_id = 5;
        
             $random_number = rand(0, 6);
             $random_amount_rendered = rand(20, 100);
        
             $customer_id = $customer_ids[$random_number];
             $customer_name = $customer_names[$random_number];
             $customer_contact_number = $customer_contact_numbers[$random_number];
             $customer_home_address = $customer_addresses[$random_number];
        
             $transaction_number = "1681821004" + $i;
             $transaction_number_no_comma = str_replace(",", "", $transaction_number);
             $total_amount = $product_price * $quantity;
             $amount_rendered = $total_amount + $random_amount_rendered;
             $changed = $amount_rendered - $total_amount;
             $discount_pct = 0;
             $vat_pct = 12;
             $delivery_fee = 35;
             $overall_total = number_format($total_amount * 0.12 + $delivery_fee + $total_amount, 2);
             $overall_total_no_comma = str_replace(",", "", $overall_total);
             $status = 5;
             $datetime_checkedout = date("Y-m-d H:i:s", strtotime("$first_day_of_month +$i days"));
             $ordered_online = 0;
        
             $itemSalesData[] = "('$customer_id', '$customer_name', '$customer_contact_number', '$customer_home_address', '$transaction_number', '$session_id', '$total_amount', '$amount_rendered', '$changed', '$discount_pct', '$vat_pct', '$delivery_fee', '$overall_total_no_comma', '$status', '$datetime_checkedout', '$ordered_online')";
             $itemQuantityData[] = "('$quantity', '$session_id', '$datetime_added', '$product_id', '$product_barcode', '$unique_key', '$user_id')";
             }
        
            // echo json_encode(array("itemSalesData"=>$itemSalesData, "itemQuantityData"=>$itemQuantityData));
            echo implode(", ",$itemSalesData);
            echo "--------------------------------------------------------------------------------------------------";
            echo implode(", ",$itemQuantityData);

?>