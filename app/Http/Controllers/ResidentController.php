<?php

namespace App\Http\Controllers;

use App\Extension;
use App\Resident;
use App\Http\Requests\StoreResident as StoreResidentRequest;
use App\Http\Resources\Resident as ResidentResource;
use App\Traits\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ResidentController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */

  public function index(Extension $extension)
  {
    $residents    = $extension->residents;
    return Inertia::render('Residents/Residents', compact('extension', 'residents'));
    // return view('admin.residents.index', compact('residents', 'extension'));
  }

  public function list(Request $request)
  {
    // $this->authorize('index', Resident::class);
    $residents = $request->user()->residents;
    return ResidentResource::collection( $residents );
  }


  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create(Request $request, Extension $extension)
  {
    $residents    = $extension->residents;
    return Inertia::render('Residents/ResidentsForm', compact('extension', 'residents'));
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
      'name' => 'required',
      'dni'  => 'required',
      'card' => 'nullable|unique:residents',
      'age'  => 'required|integer|min:0'
    ]);

    $resident = Resident::create([
      'extension_id'    => $request->extension_id,
      'name'            => $request->name,
      'age'             => $request->age,
      'dni'             => $request->dni,
      'is_owner'        => $request->is_owner,
      'is_resident'     => $request->is_resident,
      'is_authorized'   => $request->is_authorized,
      'disability'      => $request->disability,
      'card'            => $request->card,
    ]);

    $picturePath = null;

    if( $picture = $request->file('picture') ){
      $resident->addMedia( $picture )->toMediaCollection('picture');
      $picturePath = $resident->getFirstMediaPath('picture');
    }

    
    if( auth()->user()->device_building_id ){
      $devices = new Devices();
      if ( !$devices->addResident( $resident, $picturePath ) ){
        $resident->delete();
        abort(422, 'Error al sincronizar con plataforma de dispositivos');
      }
    }

    return to_route('extensions.residents.index', ['extension'=>$request->extension_id]);
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
  public function edit(Extension $extension, Resident $resident)
  {
    return Inertia::render('Residents/ResidentsForm', compact('extension', 'resident'));
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Resident $resident)
  {
    $request->validate([
      'name' => 'required',
      'dni'  => 'required',
    ]);

    $resident->name          = $request->name;
    $resident->age           = $request->age;
    $resident->dni           = $request->dni;
    $resident->is_owner      = $request->is_owner;
    $resident->is_resident   = $request->is_resident;
    $resident->is_authorized = $request->is_authorized;
    $resident->disability    = $request->disability;
    $resident->card          = $request->card;

    $path = $request->file('picture') ? $request->file('picture')->getPathName() : null;
    
    if( $resident->extension->admin->device_building_id ){
      $devices    = new Devices();
      if( !$devices->updateResident($resident, $path) ){
        abort(500, 'Error al sincronizar con el servidor de dispositivos');
      }
    }

    if( $path ) $resident->addMedia($path)->toMediaCollection('picture');
    
    $resident->save();
    return to_route( 'extensions.residents.index', ['extension'=>$request->extension_id] );
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function destroy(Resident $resident)
  {
    $resident->delete();
    $devices = new Devices();
    $devices->deleteResident($resident);
    return to_route( 'extensions.residents.index', ['extension'=>$resident->extension_id] );
  }
}
