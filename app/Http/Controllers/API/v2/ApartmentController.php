<?php

namespace App\Http\Controllers\API\v2;

use Illuminate\Http\Request;
use App\Extension;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExtension as StoreExtensionRequest;
use App\Http\Resources\Census as CensusResource;
use App\Http\Resources\ExtensionCensus;
use App\Traits\Devices;
use Exception;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $extensions = auth()->user()->extensions()->orderBy('name')->get([
        'id',
        'admin_id',
        'name',
        'pets_count',
        'deposit',
        'owner_phone',
        'phone_1',
        'phone_2',
      ]);
      return response()->json(['data'=>$extensions]);
    }
    
    public function show(Request $request){
      $apartment = auth()->user()->extensions()->whereName( $request->apartment )->firstOrFail();
      return new CensusResource( $apartment );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExtensionRequest $request)
    {
      $data = $request->only( Extension::getStoreAttributes() );
      $data = array_merge( $data, ['admin_id'=>auth()->id()] );
      $extension = Extension::create($data);

      if( !auth()->user()->device_community_id || !auth()->user()->device_building_id ){
        return response()->json(['data'=>new CensusResource( $extension )]);
      }

      try {
        $devices = new Devices();
        $devices->addRoom($extension);
      }
      catch(Exception $e){
        Storage::append('zhyaf.error.log', $e->getMessage() . " Borrando extension");
        $extension->delete();
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Extension $extension)
    {
      $extension->update([
        'name'             => ($request->name) ?: $extension->name,
        'email'            => ($request->email) ?: $extension->email,
        'phone_1'          => ($request->phone_1) ?: $extension->phone_1,
        'phone_2'          => $request->phone_2,
        'phone_3'          => $request->phone_3,
        'phone_4'          => $request->phone_4,
        'owner_phone'      => $request->owner_phone,
        'owner_name'       => $request->owner_name,
        'emergency_contact'=> $request->emergency_contact,
        'emergency_contact_name'=> $request->emergency_contact_name,
        'pets_count'       => $request->pets_count,
        'deposit'          => ($request->deposit != $extension->deposit) ? $request->deposit : $extension->deposit,
        'has_own_parking'  => ($request->has_own_parking != $extension->has_own_parking) ? $request->has_own_parking : $extension->has_own_parking,
        'parking_number1'  => $request->parking_number1,
        'parking_number2'  => $request->parking_number2,
        'vehicles'         => $request->vehicles,
        'observation'      => $request->observation,
        'resident_id'      => $request->resident_id,
        'resident_id_2'    => $request->resident_id_2,
        'resident_id_3'    => $request->resident_id_3,
        'resident_id_4'    => $request->resident_id_4,
      ]);

      return new CensusResource( $extension );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $extension = Extension::find($request->apartment);
      $community_id = $extension->admin->device_community_id;
      $building_id  = $extension->admin->device_building_id;

      if( !$community_id || !$building_id ){
        $extension->residents()->delete();
        $extension->delete();
        return response()->json(['data' => 'Extension deleted successfuly']);
      }

      try {
        $devices = new Devices();
        $devices->deleteRoom($extension);
        $extension->residents()->delete();
        $extension->delete();
        return response()->json(['data' => "Extension {$extension->id} deleted successfuly"]);
      }
      catch(Exception $e){
        if( $e->getCode() == 10000){
          abort(422, $e->getMessage());  
        }
        abort(500, $e->getMessage());
      }
    }

    public function getImport(Request $request){
      $admins = Admin::all();
      return view('admin.extensions.import', ['admins'=>$admins]);
    }

    public function import(Request $request) {
      $request->validate([
        'batch' => 'required|mimes:xls,xlsx|max:5000'
      ]);
      
      try{
        $path = $request->batch->store('imports');
        Excel::import(new ExtensionsImport, $path);
        
        $errors = ( session()->get('validation.errors') ) ?: [];
        session()->forget('validation.errors');
        
        return response()->json(['errors'=>$errors]);
      }catch (\Exception $e) {
        return response()->json(["message"=>"Error on import " . $e->getMessage()], 400);
      }
    }
}
