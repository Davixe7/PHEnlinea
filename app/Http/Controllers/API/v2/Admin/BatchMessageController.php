<?php

namespace App\Http\Controllers\API\v2\Admin;

use App\BatchMessage;
use App\Http\Controllers\Controller;
use App\Admin;
use Illuminate\Http\Request;

class BatchMessageController extends Controller
{
  function index(){
    $messages =  BatchMessage::with(['admin'=>function($query){
      $query->select(['name', 'id', 'phone', 'whatsapp_instance_id']);
    }])
    ->orderBy('created_at', 'DESC')
    ->get(['id', 'title', 'created_at', 'status', 'admin_id']);

    $statuses = [
      'pending'    => 'pendiente',
      'ready'      => 'listo',
      'processing' => 'enviando',
      'sent'       => 'enviado',
      'failed'     => 'fallido',
    ];

    $instances = Admin::orderBy('whatsapp_instance_id', 'DESC')
                ->get(['whatsapp_instance_id', 'name', 'id']);

    return response()->json(['data'=>$messages]);
  }

  function instances(){
    $admins = Admin::orderBy('whatsapp_instance_id', 'DESC')->get(['whatsapp_instance_id', 'name', 'id']);
    return response()->json(['data'=>$admins]);
  }

  function destroy(BatchMessage $batchMessage){
    $batchMessage->delete();
    return redirect()->route('admin.batch_messages.index')->with(['message'=>"mensaje $batchMessage->id eliminado con éxito"]);
  }

  function show(BatchMessage $batchMessage){
    return $batchMessage;
  }

  function clearInstance(Admin $admin){
    $admin->update(['whatsapp_instance_id'=>null]);
    return redirect()->route('admin.batch_messages.index')->with(['message'=>"Instancia $admin->whatsapp_instance_id eliminada con éxito"]);
  }

  function updateInstance(Admin $admin, Request $request){
    //$request->validate(['whatsapp_instance_id'=>'required']);
    $admin->update(['whatsapp_instance_id'=>$request->whatsapp_instance_id]);
    return redirect()->route('admin.batch_messages.index')->with(['message'=>"Instancia actualizada con éxito"]);
  }
}

