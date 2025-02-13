<?php

require("header.php");
require("footer.php");
require("functions.php");

// CONNECT TO DB //
@$db = new mysqli($dbserver,$dbuser,$dbpass,$dbname);
if (mysqli_connect_errno()) {
    echo "Database connection error: ". mysqli_connect_errno();
	exit();
    //go to error page later 
}

// GET DB DATA TO POPULATE UI //

// get order numbers for select dropdown
$order_numbers_array = ["Select"]; //default option when not selecting by order number

$order_numbers_query_string = "SELECT orderNumber FROM orders";
$order_numbers_result = $db->query($order_numbers_query_string); 

while ($row = $order_numbers_result->fetch_array()) {   //put order numbers in array
	array_push($order_numbers_array, $row['orderNumber']);
};




// CHECK USER INPUT //

// if(isset($_POST['order_number']) && !empty($_POST['order_number']) && $_POST['order_number'] != "Select"){    //if order number is set (user has interacted with)

// // if tried to set both order number and order date, throw error
// if(($_POST['order_number'] != "Select") && ((isset($_POST['date_to']) && !empty($_POST['date_to'])) || isset($_POST['date_from']) && !empty($_POST['date_from']))){
//     echo "<h2>Please only search by order number OR order date range.</h2>";
//     // exit();
// }

// // if trying to use order date (not order number) and they didn't input date_from AND date_to throw error
// if(($_POST['order_number'] == "Select") && ((isset($_POST['order_number']) && empty($_POST['order_number'])) || isset($_POST['date_from']) && empty($_POST['date_from']))){
//     echo "<h2>To search by order date, please enter a complete date range including a start and end date.</h2>";
//     // exit();
// }

// }



//get user input as variables
if(isset($_POST['order_number']) && !empty($_POST['order_number'])) $order_number = trim($_POST['order_number']); 
if(isset($_POST['date_from']) && !empty($_POST['date_from'])) $date_from = trim($_POST['date_from']); 
if(isset($_POST['date_to']) && !empty($_POST['date_to'])) $date_to = trim($_POST['date_to']); 
if(isset($_POST['columns_selected']) && !empty($_POST['columns_selected'])) $columns_selected = ($_POST['columns_selected']); 


// get columns selected to query from form POST method
$query_columns = "*";   //default is all columns
if(isset($_POST['columns_selected']) && !empty($_POST['columns_selected'])){
    $query_columns = "";
    $i = 0;
    foreach($columns_selected as $c){
        $i ++;
        if ($i == count($columns_selected)){
            $query_columns = $query_columns.$c;
        }
        else{   //add a comma after columns only if they aren't the last one
            $query_columns = $query_columns.$c.", ";
        }
    }
}

// echo $query_columns;






// GENERATE SQL QUERY

$dbquery = "";

//if user has interacted with order number or date range
if((isset($_POST['order_number']) && !empty($_POST['order_number']) && $_POST['order_number'] != "Select")||(isset($_POST['date_from']) && !empty($_POST['date_from']) && isset($_POST['date_to']) && !empty($_POST['date_to']))){


    // if tried to set both order number and order date, throw error
    if(($_POST['order_number'] != "Select") && ((!empty($_POST['date_to'])) || !empty($_POST['date_from']))){
        echo "<p>Please only search by order number <strong>OR</strong> order date range.</p>";
        }

    // if using order number
    elseif(isset($_POST['order_number']) && !empty($_POST['order_number']) && $_POST['order_number'] != "Select"){

        if (str_contains($query_columns, 'products') && str_contains($query_columns, 'orderdetails')){  //join orders, orderdetails, products
            $dbquery = "SELECT $query_columns FROM orders JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber JOIN products ON orderdetails.productCode = products.productCode WHERE orders.orderNumber = $order_number;";
            echo "contains products and order details";
        }
        elseif (str_contains($query_columns, 'products')){ //join orders and products
            $dbquery = "SELECT $query_columns FROM orders JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber JOIN products ON orderdetails.productCode = products.productCode WHERE orders.orderNumber = $order_number;";
            echo "contains products";
        }
        elseif(str_contains($query_columns, 'orderdetails')){   //join orders and orderdetails
            $dbquery = "SELECT $query_columns FROM orders JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber;";
            echo "contains order details";
        }
        else{ //no join needed, only accessing orders
            $dbquery = "SELECT $query_columns FROM orders WHERE orders.orderNumber = $order_number;";
            echo "contains orders only";
        }
    }

    // using order date range
    elseif(isset($_POST['date_from']) && !empty($_POST['date_from']) && isset($_POST['date_to']) && !empty($_POST['date_to'])){
    
        //check that date inputs are valid
        if (valiDate($date_from) && valiDate($date_to)) {
            if (str_contains($query_columns, 'products') && str_contains($query_columns, 'orderdetails')){  //join orders, orderdetails, products
                $dbquery = "SELECT $query_columns FROM orders JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber JOIN products ON orderdetails.productCode = products.productCode WHERE orders.orderDate > '$date_from' AND orders.orderDate < '$date_to';";
                // echo "contains products and order details";
            }
            elseif (str_contains($query_columns, 'products')){ //join orders and products
                $dbquery = "SELECT $query_columns FROM orders JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber JOIN products ON orderdetails.productCode = products.productCode WHERE orders.orderDate > '$date_from' AND orders.orderDate < '$date_to';";
                // echo "contains products";
            }
            elseif(str_contains($query_columns, 'orderdetails')){   //join orders and orderdetails
                $dbquery = "SELECT $query_columns FROM orders JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber WHERE orders.orderDate > '$date_from' AND orders.orderDate < '$date_to';";
                // echo "contains order details";
            }
            else{ //no join needed, only accessing orders
                $dbquery = "SELECT $query_columns FROM orders WHERE orders.orderDate > '$date_from' AND orders.orderDate < '$date_to';";
                // echo "contains orders only";
            }
        } 
        else {
            echo "<p>Date inputs are invalid format, please check entries and retry.</p>";
        }
    }
}
else{
    // if trying to use order date (not order number) and they didn't input date_from AND date_to throw error

    if((isset($_POST['date_from']) && empty($_POST['date_from'])) || (isset($_POST['date_to']) && empty($_POST['date_to']))){
        echo "<p>To search by order date, please enter a <strong>complete date range including a start and end date.</strong></p>";
    }
    else{
        echo "<p>Please <strong>input values to query data.</strong></p>";
    }
}



// DISPLAY INPUT UI //

$column_labels_array = ["Order Number", "Order Date", "Shipped Date", "Product Name", "Product Description", "Quantity Ordered", "Price Each"];
$column_values_array = ["orders.orderNumber", "orders.orderDate", "orders.shippedDate", "products.productName", "products.productDescription", "orderdetails.quantityOrdered", "orderdetails.priceEach"];

// $column_values_assoc_array = array("orderNumber"=>"orders", "orderDate"=>"orders", "shippedDate"=>"orders", "productName"=>"products", "productDescription"=>"products", "quantityOrdered"=>"orderdetails", "priceEach"=>"orderdetails");


echo start_form("dbquery.php");

echo "<table border=\"1px\">
        <thead>
            <tr>
                <th>Select Order Parameters</th>
                <th>Select Columns to Display</th>
            </tr>
        </thead>
        <tbody>";
            echo "<tr>";
            echo "<td>";
            echo create_select_input("Order Number:", "order_number", $order_numbers_array);
            echo "<label> or </label><br>";

            echo "<label>Order Date (YYYY-MM-DD)</label><br>";
            echo create_text_input("from: ", "date_from");
            echo create_text_input(" to: ", "date_to");
            echo "</td>";

            echo "<td>";
            echo create_multiselect_input("columns_selected", $column_labels_array, $column_values_array);
            echo "</td>";
            echo "</tr>";

        echo "</tbody>
</table>";

echo create_button("Search Orders");

echo end_form();

// show SQL query
echo "<h3>SQL Query</h3>";
echo "<code>";  
echo $dbquery;
echo "</code>";

// DO AND SHOW RESULT OF SQL QUERY //work work
echo "<h1>Result</h1>";

if (isset($dbquery) && !empty($dbquery) && $dbquery != "*" || $dbquery != ""){   //if db query has been input
    // echo $dbquery;
    // echo "THERE IS INPUT";

    $query_result = $db->query($dbquery);  // actually query the db

    echo "<table border=\"1px\"><thead><tr>";


    
    while ($column_header = $query_result->fetch_field()) {
        echo "<th>".$column_header->name."</th>";
    }
    echo "</tr></thead><tbody>";

    // get row values
    while ($row = $query_result->fetch_array()) {
        $i = 0;
        echo "<tr>";
        // echo "initial".$i;
        while ($i < (count($row) - $query_result->field_count)){
            echo "<td align=\"left\">".$row[$i]."</td>";
            $i++;
            // echo "in while".$i;
        }
        echo "</tr>";
        // echo "out of while after".$i;
    };

    echo "</tbody></table>";
}
else{   // if no input, do not send query
    echo "<p>Select parameters to return data.</p>";
}


// display result
// while ($row = $query_result->fetch_assoc()) {
//     echo "<tr>";
//     foreach ($row as $column_value) {
//         echo "<td>" . htmlspecialchars($column_value) . "</td>"; // Display each cell value
//     }
//     echo "</tr>";
// }

// while ($row = $query_result->fetch_assoc()) {
    // foreach ($query_columns as $c){
    //     echo "<tr>";
    //     echo "<td>";
        // echo $row;
    //     echo "</tr>";
    // }


	// echo "<tr><td>".$row['orderNumber']."</td><td align=\"right\">" .
	// 	$row['orderDate']."</td></tr>\n";
// };





// while ($row = $order_numbers_result->fetch_array()) {
// 	array_push($order_numbers_array, $row['orderNumber']);
// };


?>


