<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\UrlGenerator;
use App\Models\User;
use App\Models\Roles;
use App\Models\UserPermissions;
use App\Models\Status;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\CampaignPartner;


function hasPermission($role)
{
    switch ($role) {
        case "users_view":
            $permission_id = 2;
            $modules_id = 1;
            break;
        case "users_create":
            $permission_id = 2;
            $modules_id = 2;
            break;
        case "users_edit":
            $permission_id = 2;
            $modules_id = 3;
            break;
        case "users_delete":
            $permission_id = 2;
            $modules_id = 4;
            break;
        case "roles_view":
            $permission_id = 1;
            $modules_id = 1;
            break;
        case "roles_create":
            $permission_id = 1;
            $modules_id = 2;
            break;
        case "roles_edit":
            $permission_id = 1;
            $modules_id = 3;
            break;
        case "roles_delete":
            $permission_id = 1;
            $modules_id = 4;
            break;
        case "clientGroup_view":
            $permission_id = 3;
            $modules_id = 1;
            break;
        case "clientGroup_create":
            $permission_id = 3;
            $modules_id = 2;
            break;
        case "clientGroup_edit":
            $permission_id = 3;
            $modules_id = 3;
            break;
        case "clientGroup_delete":
            $permission_id = 3;
            $modules_id = 4;
            break;
        case "campaigns_view":
            $permission_id = 4;
            $modules_id = 1;
            break;
        case "campaigns_create":
            $permission_id = 4;
            $modules_id = 2;
            break;
        case "campaigns_edit":
            $permission_id = 4;
            $modules_id = 3;
            break;
        case "campaigns_delete":
            $permission_id = 4;
            $modules_id = 4;
            break;
        case "tasks_view":
            $permission_id = 5;
            $modules_id = 1;
            break;
        case "tasks_create":
            $permission_id = 5;
            $modules_id = 2;
            break;
        case "tasks_edit":
            $permission_id = 5;
            $modules_id = 3;
            break;
        case "tasks_delete":
            $permission_id = 5;
            $modules_id = 4;
            break;
        case "comments_view":
            $permission_id = 6;
            $modules_id = 1;
            break;
        case "comments_create":
            $permission_id = 6;
            $modules_id = 2;
            break;
        case "comments_edit":
            $permission_id = 6;
            $modules_id = 3;
            break;
        case "comments_delete":
            $permission_id = 6;
            $modules_id = 4;
            break;
        default:
            return false;
    }

    $data = UserPermissions::where('role_id', Auth::user()->role_id)->where('permission_id', $permission_id)->where('modules_id', $modules_id)->count();
    if ($data > 0) {
        return true;
    } else {
        return false;
    }

    // case "_view":
    //     $permission_id = 4;
    //     $modules_id = 1;
    //     break;
    // case "_create":
    //     $permission_id = 4;
    //     $modules_id = 2;
    //     break;
    // case "_edit":
    //     $permission_id = 4;
    //     $modules_id = 3;
    //     break;
    // case "_delete":
    //     $permission_id = 4;
    //     $modules_id = 4;
    //     break;

    // echo "</br>";
    // echo $permission_id;
    // echo "</br>";
    // echo $modules_id;
    // echo "</br>";
    // echo Auth::user()->role_id;
    // echo "</br>";
}


function testmail()
{
    $mailData = [
        'title' => 'This is Test Mail',
        'files' => [
            public_path('attachments/test_image.jpeg'),
            public_path('attachments/test_pdf.pdf'),
        ]
    ];
    Mail::to('acctracking001@gmail.com')->send(new SendMail($mailData));

    echo "Mail send successfully !!";
}

function get_partner()
{
    return User::where('role_id', 6)->where('is_active', 1)->get();
}

function get_partner_campaigns($campaigns_id, $partner_id)
{
    $data = CampaignPartner::where('campaigns_id', $campaigns_id)->where('partner_id', $partner_id)->count();
    if ($data > 0) {
        return true;
    } else {
        return false;
    }
}
