<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BatchMessage extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = ['id'];
    
    protected $casts = [ 'created_at' => 'datetime:Y-m-d H:i:s' ];

    public function admin(){
      return $this->belongsTo(Admin::class);
    }

    public function registerMediaCollections(): void {
      $this->addMediaCollection('attachment')->singleFile();
    }
}
