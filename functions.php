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
        create_select_option($options[$i++], $o);
    }
    echo "</select>\n";
}

//create individual select option
function create_select_option($option_label, $o) {
    echo "<option value=\"$o\" ";
    echo ">$option_label</option>\n";
}

//creates text input component
function create_text_input($label, $key) {
    echo "<label>$label</label>";
    echo "<input type=\"text\" name=\"$key\">";
}

//create multiselect component
function create_multiselect_input($key, $options) {
    $i = 0;
    foreach($options as $o) {
        create_multiselect_option($options[$i++], $key, $o);
    }
}

//create individual multiselect option
function create_multiselect_option($option_label, $key, $o) {
    echo "<label>";
    echo "<input type=\"checkbox\" name=\"$key\" value=\"$o\">$o";
    echo "</label><br>";
}

//create button component
function create_button($label){
    echo "<input class=\"button\" type=\"submit\" value=\"$label\">";
}

?>