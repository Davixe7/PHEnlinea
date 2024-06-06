<?php

namespace App\Http\Controllers\API\v2;

use App\Events\VisitCreatedEvent;
use App\Visit;
use App\Visitor;
use Illuminate\Http\Request;
use App\Http\Resources\VisitPorteria;
use App\Http\Controllers\Controller;
use App\Traits\Uploads;
use Illuminate\Support\Facades\Storage;

class VisitController extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $filter    = $request->filter ?: '';
      $date_from = $request->date_from;
      $date_to   = $request->date_to;
      $per_page  = $request->per_page ?: 200;

      $visits = auth()->user()->visits()->orderBy('created_at', 'DESC');

      if( $filter ){
        Storage::append('visitsfilter.log', $filter);
        $visits
        ->where('extension_name', 'like', "%" . $filter . "%")
        ->orWhere('plate', 'like', "%" . $filter . "%");
      }

      if( $date_from && $date_to ){
        $visits->whereBetween('created_at', [$date_from, $date_to]);
      }
      $visits = $visits->paginate($per_page);

      return VisitPorteria::collection( $visits );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visit $visit)
    {
      $visit->update(["checkout" => now()]);
      return new VisitPorteria( $visit );
    }
}
