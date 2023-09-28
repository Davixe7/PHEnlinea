<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Visit extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $fillable = [
    'admin_id',
    'checkin',
    'checkout',
    'password',
    'start_date',
    'end_date',
    'extension_id',
    'extension_name',
    'visitor_id',
    'plate'
  ];

  protected $casts = [
    'start_date' => 'datetime:Y-m-d H:i:s',
    'end_date'   => 'datetime:Y-m-d H:i:s',
    'checkin'    => 'datetime:Y-m-d H:i:s',
    'checkout'   => 'datetime:Y-m-d H:i:s',
  ];

  public function extension(){
    return $this->belongsTo('App\Extension')->withDefault(['name'=>'ninguna']);
  }
  
  public function admin(){
    return $this->belongsTo(Admin::class);
  }

  public function visitor(){
    return $this->belongsTo(Visitor::class);
  }

}
