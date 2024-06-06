<?php

namespace App\Http\Controllers\API\v2;

use App\Extension;
use App\Http\Controllers\Controller;
use App\Resident;
use App\Http\Requests\StoreResident as StoreResidentRequest;
use App\Http\Resources\Resident as ResidentResource;
use App\Traits\Devices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResidentController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function __construct(){
    
  }

  public function index(Request $request)
  {
    $extension = auth()->user()->extensions()->whereName($request->apartment)->firstOrFail();
    $residents = $extension->residents;
    return response()->json(['data'=>$residents]);
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function store(StoreResidentRequest $request)
  {
    $request->validate([
      'card'      => 'nullable|unique:residents',
    ]);

    $extension = auth()->user()->extensions()->whereName($request->apartment)->firstOrFail();

    $resident = Resident::create([
      'extension_id'    => $extension->id,
      'name'            => $request->name,
      'email'           => $request->email,
      'age'             => $request->age,
      'dni'             => $request->dni,
      'is_owner'        => $request->is_owner,
      'is_resident'     => $request->is_resident,
      'is_authorized'   => $request->is_authorized,
      'disability'      => $request->disability,
      'card'            => $request->card,
      'device_synced'   => false
    ]);

    $picturePath = null;

    if( $picture = $request->file('picture') ){
      $resident->addMedia( $picture )->toMediaCollection('picture');
      $picturePath = $resident->getFirstMediaPath('picture');
    }
    
    if( !auth()->user()->device_building_id ){
      return new ResidentResource( $resident );
    }
      
    try {
      $devices = new Devices();
      $devices->addResident( $resident, $picturePath );
      return new ResidentResource( $resident );
    }
    catch(Exception $e){
      $resident->delete();
      abort(422, 'Error al sincronizar con plataforma de dispositivos');
    }
  }

  /**
  * Display the specified resource.
  *
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function show(Resident $resident)
  {
    return new ResidentResource( $resident );
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function edit(Resident $resident)
  {
    return view('residents.edit', ['resident' => $resident]);
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function update(StoreResidentRequest $request, Resident $resident)
  {
    $request->validate([
      'name' => 'required',
      'dni'  => 'required',
      'card' => [
        'nullable',
        Rule::unique('residents')->ignore($resident->id)
      ]
    ]);

    $resident->name          = $request->name;
    $resident->email         = $request->email;
    $resident->age           = $request->age;
    $resident->dni           = $request->dni;
    $resident->is_owner      = $request->is_owner;
    $resident->is_resident   = $request->is_resident;
    $resident->is_authorized = $request->is_authorized;
    $resident->disability    = $request->disability;
    $resident->card          = $request->card;

    $path = $request->file('picture') ? $request->file('picture')->getPathName() : null;

    if( auth()->user()->device_building_id ){
      try {
        $devices = new Devices();
        $devices->updateResident($resident, $path);
      }
      catch( Exception $e ){
        abort(500, 'Error al sincronizar con el servidor de dispositivos');
      }
    }

    if( $path ) $resident->addMedia($path)->toMediaCollection('picture');
    $resident->save();
    return new ResidentResource( $resident );
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function destroy(Resident $resident)
  {
    if( !auth()->user()->device_community_id ){
      $resident->delete();
      return response()->json(['message'=>'Resident deleted successfuly']);
    }

    try {
      $devices = new Devices();
      $devices->deleteResident($resident);
      $resident->delete();
      return response()->json(['message'=>'Resident deleted successfuly']);
    }
    catch( Exception $e ){
      abort(500, $e->getMessage());
    }
  }
}