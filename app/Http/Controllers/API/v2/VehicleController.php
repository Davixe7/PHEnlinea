<?php

namespace App\Http\Controllers\API\v2;

use App\Extension;
use App\Http\Controllers\Controller;
use App\Traits\Devices;
use App\Vehicle;
use Exception;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $extension = auth()->user()->extensions()->whereName( $request->apartment )->firstOrFail();
      $vehicles  = $extension->vehicles;
      return response()->json(['data'=>$vehicles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $extension = auth()->user()->extensions()->whereName( $request->apartment )->firstOrFail();
        $data = array_merge(['extension_id'=>$extension->id], $request->all());
        $vehicle = Vehicle::create($data);

        if( auth()->user()->device_community_id ){
          $devices = new Devices();
          if( $resident = $vehicle->resident ){
            $devices->updateResident( $resident, null );
          }
        }
        return response()->json(['data'=>$vehicle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Vehicle $vehicle, Request $request)
    {
        $request->validate([
          'resident_id' => 'required'
        ]);
        
        $vehicle->update($request->all());
        if( auth()->user()->device_community_id ){
          $devices = new Devices();
          $devices->updateResident($vehicle->resident, null);
        }
        return $vehicle;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
      $vehicle->delete();
      if(!$vehicle->resident){return $vehicle;}

      try {
        $devices = new Devices();
        $devices->updateResident($vehicle->resident, null);
        return $vehicle;
      } catch (Exception $e) {
        abort(422, $e->getCode() . " " . $e->getMessage());
      }
    }
}
