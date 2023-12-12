<?php

namespace App\Http\Controllers\Admin;

use App\WhatsappClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Whatsapp;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WhatsappClientController extends Controller
{
  public function getClient(){
    return response()->json(['data' => WhatsappClient::where('enabled', true)->first()]);
  }

  public function index(){
    $clients = WhatsappClient::all();
    return Inertia::render('WhatsappClients', compact('clients'));
    return view('super.whatsappclients.index', compact('whatsapp_clients'));
  }

  public function scan(Request $request, WhatsappClient $whatsapp_client){
    $labels = [
      'delivery_instance_id' => 'Encomiendas',
      'batch_instance_id'    => 'Masívos',
      'comunity_instance_id' => 'Comunidad',
    ];
    $instance_type = $request->instance_type;
    $whatsapp      = new Whatsapp();
    $instance_id   = $whatsapp->getInstanceId();
    $base64        = $whatsapp->getQrCode( $instance_id );

    return Inertia::render('WhatsappClientScan', compact(
      'whatsapp_client',
      'instance_id',
      'base64',
      'instance_type',
      'labels'
    ));

    return view('super.whatsappclients.scan', compact(
      'whatsapp_client',
      'instance_id',
      'base64',
      'instance_type',
      'labels'
    ));
  }

  public function update(Request $request, WhatsappClient $whatsapp_client){
    // $request->validate([
    //   'comunity_instance_id'  => 'required',
    //   'access_token'          => 'required',
    //   'base_url'              => 'required'
    // ]);

    if( $whatsapp_client->enabled == false && $request->enabled ){
      DB::table('whatsapp_clients')->update(['enabled'=>false]);
    }

    $whatsapp_client->update($request->all());
    return redirect()->route('admin.whatsapp_clients.index');
  }
}
