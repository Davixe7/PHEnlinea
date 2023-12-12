<?php

namespace App\Http\Controllers;

use App\Http\Resources\VisitPorteria;
use Inertia\Inertia;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $visits = auth()->user()->visits()->orderBy('created_at', 'DESC')->with('visitor')->get();
      $visits = VisitPorteria::collection( $visits )->collection;
      // return $visits;
      return Inertia::render('Visitors', compact('visits'));
      return view('admin.visits', ['visits' => $visits]);
    }
}
