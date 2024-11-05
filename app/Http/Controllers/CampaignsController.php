<?php

namespace App\Http\Controllers;

use App\Models\Campaigns;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Models\UserPermissions;
use App\Models\CampaignPartner;
use Illuminate\Support\Facades\Storage;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource. 
     */
    public function index()
    {
        $sideBar = 'dashboard';
        $title = 'dashboard';
        return view('campaigns.index', compact('title', 'sideBar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(hasPermission('campaigns_create') == false){
            return redirect('accessdenied');
        }
        $sideBar = 'master';
        $title = 'Create Campaign';
        $data = "";
        $status = Status::where('is_active', 1)->get();
        $route = route('campaigns.store');
        $method = 'POST';
        return view('campaigns.add_edit', compact('title', 'data', 'route', 'method', 'sideBar','status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'description' => 'required',
                'due_date' => 'required',
                'status_id' => 'required',
            ]
        );
        $data = new Campaigns();
        $data->name = $request->name;
        $data->description = $request->description;
        $data->due_date = $request->due_date;
        $data->status_id = $request->status_id;
        $data->is_active = $request->is_active;
        $data->save();
        $id = $data->id;
        if(isset($request->partner_id)){
            foreach($request->partner_id as $v){
                $data = new CampaignPartner();
                $data->campaigns_id = $id;
                $data->partner_id = $v;
                $data->save();
            }}
        
        return response()->json([
            'redirect_url' => url('campaigns')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(hasPermission('campaigns_edit') == false){
            return redirect('accessdenied');
        }

        $id = encrypt_decrypt($id, 'd');
        $sideBar = 'dashboard';
        $title = 'Edit Campaign';
        $status = Status::where('is_active', 1)->pluck('id','name');
        $data = Campaigns::find($id);
        $route = route('campaigns.update',encrypt_decrypt($data->id, 'e'));
        $method = 'PUT';
       
        return view('campaigns.add_edit', compact('title', 'data', 'route', 'method', 'sideBar','status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'description' => 'required',
                'due_date' => 'required',
                'status_id' => 'required',
                 'image' => 'required|image|max:2048'
            ]
        );

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $url = Storage::disk('backblaze')->put('/test', $request->file('image'));
            $url = Storage::disk('backblaze')->url($url);
            }

      

                   return response()->json([
            'msg' => $url   
        ], 401);

       
        $id = encrypt_decrypt($id, 'd');
        $data = Campaigns::find($id);
        $data->name = $request->name;
        $data->description = $request->description;
        $data->due_date = $request->due_date;
        $data->status_id = $request->status_id;
        $data->is_active = $request->is_active;
        // $data->username = $request->username;
        // $data->user_role_id = $request->user_role_id;
        $data->save();
        CampaignPartner::where('campaigns_id', $id)->delete();
        if(isset($request->partner_id)){
        foreach($request->partner_id as $v){
            $data = new CampaignPartner();
            $data->campaigns_id = $id;
            $data->partner_id = $v;
            $data->save();
        }}
        // return response()->json([
        //     'msg' => ""   
        // ], 401);
        return response()->json([
            'redirect_url' => url('campaigns')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
    //    $id = encrypt_decrypt($id, 'd');
       $sideBar = 'dashboard';
       $title = 'dashboard';
    //    Session::flash('deleted_table', 'users');
    //    Session::flash('deleted_id', $id);
    //    Session::flash('deleted_msg', 'Your record has been deleted!.');
    //    Session::flash('deleted_url', 'users');
       return view('campaigns.index', compact('title', 'sideBar'));
    }

    public function getData(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $query = Campaigns::where('name', '!=', null);
        // $query->where('is_active', 1);
        if ($request->search['value'] != '') {
            $query->where('name', 'LIKE', '%' . $request->search['value'] . '%');
        }
        $query->orderBy('id', 'ASC');
        $rec_count = $query;

        $totalRecords = $rec_count->get()->count();
        $totalRecordswithFilter = $rec_count->get()->count();

        $query->skip($start);
        $query->take($rowperpage);
        $records = $query->get();
        $data_arr = array();

        $permission = [hasPermission('campaigns_edit'),hasPermission('campaigns_delete')];

        foreach ($records as $record) {

            if($record->is_active == 1){$status = "Active";}else{$status = "InActive";}

            $data_arr[] = array(
                // "id" => '<input type="checkbox"  data-id="'.$record->id.'" class="selectedId sub_chk" name="checkall[]" value="'.$record->id.'"/>',
                "id" => $record->id,
                "name" => $record->name,
                "description" =>  $record->description,
                "due_date" =>  $record->due_date,
                "status_id" =>  @$record->taskstatus->name,
                "is_active" => $status,
                'action'   => _table_action_campaingn($record->id, 'campaigns', $permission)
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );
        return response()->json($response);
    }
}
