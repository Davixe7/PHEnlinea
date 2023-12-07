<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Store as StoreResource;
use App\Http\Resources\User as UserResource;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $user   = auth()->user();
      $driver = 'web';
      
      if( $user && $user->admin_id ){
        $driver = 'extension';
      }elseif( $user && $user->nit ){
        $driver = 'admin';
      }
      
      switch ($driver) {
        case 'extension':
          return view('resident.home', ['posts'=>auth()->user()->posts()->whereType('post')->get()]);
          break;
        case 'admin':
          $routes = [
            [
            'route' => route('extensions.index'),
            'icon' => 'citofonia.png'
            ],
            [
            'route' => route('visits.index'),
            'icon' => 'visitas.png'
            ],
            [
            'route' => route('novelties.index'),
            'icon' => 'novedades.png'
            ],
            [
            'route' => route('invoices.index'),
            'icon' => 'facturas.png'
            ],
            [
              'route' => route('resident_invoice_batches.index'),
              'icon' => 'facturas.png'
            ]
          ];

          $routes_2 = [
            [
              'route' => route('petitions.index'),
              'icon' => 'pqrs.png'
            ],
            [
              'route' => route('whatsapp.index'),
              'icon' => 'whatsapp.png'
            ],
            [
              'route' => route('whatsapp.comunity'),
              'icon' => 'comunidad.png'
            ],
          ];
          return Inertia::render('Home', compact('routes', 'routes_2'));
          break;
        case 'web':
          return view('super.users.index', ['users'=>UserResource::collection( \App\User::all() )]);
          break;
      }
    }
}
