<?php

use App\Models\Campaigns;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\UrlGenerator;
use App\Models\User;
use App\Models\Roles;
use App\Models\UserPermissions;
use App\Models\Status;
use App\Models\Tasks;
use App\Models\CampaignPartner;

function _table_actions($id, $table, $form_id)
{
    $id = encrypt_decrypt($id, 'e');
    return view('common.action', compact('id', 'table', 'form_id'));
}

function encrypt_decrypt($string, $action = 'e')
{
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
    $secret_iv = '5fgf5HJ5g27'; // user define secret key
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
    if ($action == 'e') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } elseif ($action == 'd') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function _table_action($id, $table, $view_option)
{
    $id = encrypt_decrypt($id, 'e');
    $html = '';
    // $html .= '<a href="' . url('/' . $table) . '/' . $id  . '" class=""  style="margin-right:10px;"><i class="fa fa-eye" title="View"></i></a>';
    if ($view_option[0]) {
        $html .= '<a href="' . url('/' . $table) . '/' . $id . '/edit' . '" class=""  id="edit_id_' . $id . '" style="margin-right: 5px;"><i class="fa fa-edit" title="Edit"></i></a> ';
    }
    if ($view_option[1]) {
        $html .= '<a href="' . url('/' . $table) . '/' . $id . '/delete' . '" class=""  id="delete_id_' . $id . '" style="margin-right: 5px;color:red;"><i class="fa fa-trash-alt" title="Delete"></i></a>';
    }
    return $html;
}

function _table_action_campaingn($id, $table, $view_option)
{
    $id = encrypt_decrypt($id, 'e');
    $html = '';
    // $html .= '<a href="' . url('/' . $table) . '/' . $id  . '" class=""  style="margin-right:10px;"><i class="fa fa-eye" title="View"></i></a>';
    if ($view_option[0]) {
        $html .= '<a href="javascript:void(0)" data-url="' . url('/' . $table) . '/' . $id . '/edit' . '" class="add_edit_modal_load"  id="edit_id_' . $id . '" style="margin-right: 5px;"><i class="fa fa-edit" title="Edit"></i></a> ';
    }
    if ($view_option[1]) {
        $html .= '<a href="' . url('/' . $table) . '/' . $id . '/delete' . '" class=""  id="delete_id_' . $id . '" style="margin-right: 5px;color:red;"><i class="fa fa-trash-alt" title="Delete"></i></a>';
    }
    return $html;
}


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

function get_roles()
{
    return Roles::where(['is_active' => 1])->pluck('name', 'id');
}

function get_status()
{
    return Status::where(['is_active' => 1])->pluck('name', 'id');
}

function _user_permission_modules($module, $sub_module, $role_id)
{
    return UserPermissions::where('permission_id', $module)->where('modules_id', $sub_module)->where('role_id', $role_id)->count();
}

function campaigns_count(){
    $campaign_count =  Campaigns::where('is_active', 1)->count();

    // dd($campaign_count);
    return $campaign_count;
}

function task_count(){
    $task_count =  Tasks::where('is_active', 1)->count();

    // dd($campaign_count);
    return $task_count;
}