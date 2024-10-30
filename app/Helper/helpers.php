<?php 

function _select_option($option = [], $name = null, $check = null, $class = null, $id = null)
{
    $html = '<select name="' . $name . '" class="' . $class . '" id="' . $id . '">';
    $html .= '<option value="">-- Select Option --</option>';
    if (!empty($option)) {
        foreach ($option as $key => $op) {
            if ($check == $key) {
                $selected = "selected";
            } else {
                $selected = "";
            }
            $html .= '<option value="' . $key . '" ' . $selected . '>' . @$op . '</option>';
        }
    }
    $html .= '</select>';
    echo $html;
}
if (!function_exists('get_roles')) {
    function get_roles() {
        // Assuming you have a Role model to fetch roles from the database
        return \App\Models\Role::pluck('name', 'id')->toArray(); // Change 'name' and 'id' as per your database schema
    }
}