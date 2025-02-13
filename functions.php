<?php

//starts form entry
function start_form($action){
    echo "<form method=\"post\" action=$action>";
}

//ends form entry
function end_form(){
    echo "</form>";
}

//create select dropdown component
function create_select_input($label, $key, $options) {
    echo "<label>$label</label>";
    echo "<select name=\"$key\">\n"; //show label
    //create select
    $i = 0;
    foreach($options as $o) {
        create_select_option($options[$i++], $o, $key);
    }
    echo "</select>\n";
}

//create individual select option
function create_select_option($option_label, $o, $key) {
    $selected = '';  //get select value to keep selected
    if (isset($_POST[$key]) && $_POST[$key] == $o){
        $selected = 'selected';
    }
    echo "<option value=\"$o\" ";
    echo "$selected>$option_label</option>\n";

}

//creates text input component
function create_text_input($label, $key) {
    $input = '';    //get text input if set to keep dates visible
    if (isset($_POST[$key])){
        $input = $_POST[$key];
        echo "<label>$label</label>";
        echo "<input type=\"text\" name=\"$key\" value=\"$input\">";
    }else{
        echo "<label>$label</label>";
        echo "<input type=\"text\" name=\"$key\">";
    }
    
}

//create multiselect component
function create_multiselect_input($key, $option_label, $option_value) {
    $i = 0;
    foreach($option_label as $o) {
        create_multiselect_option($option_label[$i], $option_value[$i++], $key, $o);
    }
}

//create individual multiselect option
function create_multiselect_option($option_label, $option_value, $key, $o) {
    $checked = '';  //get value if set to keep checked
    if (isset($_POST[$key]) && in_array($option_value, $_POST[$key])){
        $checked = 'checked';
    }
    echo "<label>";
    echo "<input type=\"checkbox\" name=\"".$key."[]\" value=\"$option_value\" $checked>$o";
    echo "</label><br>";
}

//create button component
function create_button($label){
    echo "<input class=\"button\" type=\"submit\" value=\"$label\">";
}

// validate the date inputs (please excuse the pun)
function valiDate($date_input) {
    $date_format = 'Y-m-d'; // YYYY-MM-DD format
    $d = DateTime::createFromFormat($date_format, $date_input);
    return $d && $d->format($date_input) === $date_input;
}

?>