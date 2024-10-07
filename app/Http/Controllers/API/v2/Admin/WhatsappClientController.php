<?php

namespace App\Http\Controllers\API\v2\Admin;

use App\WhatsappClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Whatsapp;
use Illuminate\Support\Facades\DB;

class WhatsappClientController extends Controller
{
  public function index()
  {
    $whatsapp_clients = WhatsappClient::all();
    return response()->json(['data' => $whatsapp_clients]);
  }

  public function update(Request $request, WhatsappClient $whatsapp_client)
  {
    $request->validate([
      'access_token'          => 'sometimes|required',
      'base_url'              => 'sometimes|required'
    ]);

    if ($whatsapp_client->enabled == false && $request->enabled) {
      DB::table('whatsapp_clients')->update(['enabled' => false]);
    }

    $whatsapp_client->update($request->all());
    return response()->json(['data' => $whatsapp_client]);
  }

  public function getClient()
  {
    return response()->json(['data' => WhatsappClient::where('enabled', true)->first()]);
  }

  public function scan(Request $request, WhatsappClient $whatsapp_client)
  {
    $whatsapp      = new Whatsapp();
    $instance_id   = '00000000000';
    $base64        = file_get_contents(storage_path('/app/qr'));

    //$whatsapp->setWebhook($instance_id, 'https://phenlinea.com/whatsapp/hook');

    return response()->json(['data'=>[
      'whatsapp_client' => $whatsapp_client,
      'instance_type'   => $request->instance_type,
      'instance_id'     => $instance_id,
      'base64'          => $base64,
      'labels'          => [
        'delivery_instance_id' => 'Encomiendas',
        'batch_instance_id'    => 'Masívos',
        'comunity_instance_id' => 'Comunidad',
      ]
    ]]);
    
    return response()->json(['data'=>[
      'whatsapp_client' => $whatsapp_client,
      'instance_type'   => $request->instance_type,
      'instance_id'     => $instance_id,
      'base64'          => $whatsapp->getQrCode($instance_id),
      'labels'          => [
        'delivery_instance_id' => 'Encomiendas',
        'batch_instance_id'    => 'Masívos',
        'comunity_instance_id' => 'Comunidad',
      ]
    ]]);
  }
}
