<?php

namespace App\Http\Controllers\API\v2\Admin;

use App\Admin;
use App\Http\Requests\StoreAdmin as StoreAdminRequest;
use App\Http\Resources\Admin as AdminResource;
use App\Http\Resources\Payment as PaymentResource;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExtensionsExport;
use App\Http\Controllers\Controller;
use App\Traits\Uploads;

class AdminController extends Controller
{
  use Uploads;
  
  public function __construct(){
    $this->authorizeResource(Admin::class, 'admin');
  }

  public function index()
  {
    $admins = Admin::orderBy('name', 'asc')->get();
    return response()->json(['data'=>$admins]);
  }

  public function store(StoreAdminRequest $request)
  { 
    $data = $request->all();
    $data['password'] = bcrypt($request->password);
    $admin = Admin::create($data);

    return new AdminResource( $admin );
  }

  public function show(Admin $admin)
  {
    return new AdminResource( $admin );
  }

  public function update(Request $request, Admin $admin)
  {
    $request->validate([
      'nit' => 'required|unique:admins,nit,' . $admin->id
    ]);

    $data = $request->all();
    $data['password'] = $request->password ? bcrypt($request->password) : $admin->password;
    $admin->update($data);

    foreach(['whatsapp_qr', 'picture'] as $key){
      if(!$request->hasFile($key)){ continue; }
      $admin->addMediaFromRequest($key)->toMediaCollection($key);
    }

    return new AdminResource( $admin );
  }

  public function destroy(Admin $admin)
  {
    $admin->delete();
    return response()->json(['message'=>'Admin deleted successfuly']);
  }

  public function editPermissions(Request $request, Admin $admin){
    $modules = \App\Module::all();
    return view('super.admins.edit-permissions',[
      'admin'   => $admin,
      'modules' => $modules
    ]);
  }

  public function updatePermissions(Request $request, Admin $admin){
    $admin->modules()->sync( $request->modules );
    return redirect()->route('admin.admins.edit-permissions', ['admin'=>$admin->id]);
  }
}

