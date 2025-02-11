<?php

require("header.php");
require("footer.php");
require("functions.php");

// CHECK USER INPUT //

//htmlspecialchars?
if(isset($_POST['order_number']) && !empty($_POST['order_number'])) $order_number = trim($_POST['order_number']); 
if(isset($_POST['date_from']) && !empty($_POST['date_from'])) $date_from = trim($_POST['date_from']); 
if(isset($_POST['date_to']) && !empty($_POST['date_to'])) $date_from = trim(htmlspecialchars($_POST['date_to'])); 
if(isset($_POST['columns_selected']) && !empty($_POST['columns_selected'])) $columns_selected = trim($_POST['columns_selected']); 

// GENERATE SQL QUERY



// DISPLAY INPUT UI //

$order_numbers_array = [10100, 10101, 10102, 10103];    //fill dynamically with db query
$columns_array = ["Order Number", "Order Date", "Shipped Date", "Product Name", "Product Description", "Quantity Ordered", "Price Each"];

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
            echo create_multiselect_input("columns_selected", $columns_array);
            echo "</td>";
            echo "</tr>";

        echo "</tbody>
</table>";

echo create_button("Search Orders");

echo end_form();

// show SQL query
echo "<h3>SQL Query</h3>";
echo "<code>";
echo "SELECT orders.orders blackhdsiuvasdljsdnv";

echo "</code>";

// show result
echo "<h1>Result</h1>";



?>


