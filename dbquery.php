<?php

require("header.php");


//create select dropdown component
function create_select_input($label, $options, $multiselect) {
    echo "<label>$label</label>";

    if ($multiselect){  //if multiselect add multiple attribute
        echo "<select multiple name=\"$label\">\n"; //show label
    }else{  //if single select make single
        echo "<select name=\"$label\">\n"; //show label
    }
    //create select
    $i = 0;
    foreach($options as $o) {
        create_select_option($options[$i++], $label, $o);
    }
    echo "</select>\n";
}

//create individual select option
function create_select_option($option_label, $input_name, $o) {
    global $$input_name;
    
    echo "<option value=\"$o\" ";
    if (!empty($label) && $label == $o){
        echo "selected"; 
    } 
    echo ">$option_label</option>\n";
}

//creates text input component
function create_text_input($label) {
    echo "<label>$label</label>";
    echo "<input type=\"text\" name=\"$label\">";
}


$order_numbers_array = [10100, 10101, 10102, 10103];    //fill dynamically with db query


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
            echo create_select_input("Order Number:", $order_numbers_array, False);
            echo "<label> or </label><br>";
            echo "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>";
            echo "<label>Order Date (YYYY-MM-DD)</label><br>";
            echo create_text_input("from: ");
            echo create_text_input(" to: ");
            echo "</td>";
            echo "</tr>";

        echo "</tbody>
</table>";






?>