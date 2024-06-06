<?php

namespace App\Http\Controllers\API;

use App\Extension;
use Illuminate\Http\Request;
use App\Http\Resources\ApartmentPorteria;
use App\Http\Controllers\Controller;
use App\Http\Resources\Census as CensusResource;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $apartments = auth()->user()->extensions()->get();
      return ApartmentPorteria::collection( $apartments );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Extension $extension)
    {
      // $apartment = auth()->user()->extensions()->whereName($request->apartment)->first();
      // if( !$apartment ){
      //   abort(404, 'No existe el apartamento');
      // }
      return response()->json(['data'=>$extension]);
    }

    public function store(Request $request){
      $data = $request->only( Extension::getStoreAttributes() );
      $data = array_merge( $data, ['admin_id'=>auth()->id()] );
      $extension = Extension::create($data);

      // if( auth()->user()->device_community_id && auth()->user()->device_building_id ){
      //   try {
      //     $devices = new Devices();
      //     $devices->addRoom($extension);
      //   }
      //   catch(Exception $e){
      //     Storage::append('zhyaf.error.log', $e->getMessage() . " Borrando extension");
      //     $extension->delete();
      //   }
      // }

      return response()->json([new CensusResource( $extension )]);
    }

    public function update(Request $request, Extension $extension){
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
}
