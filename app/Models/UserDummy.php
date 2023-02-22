<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Blameable;

class UserDummy extends Model
{
    use HasFactory, Blameable;

    protected $table = 'userdummy';
	protected $dates = [];
	protected $guarded = [];
	protected $hidden = ['created_at','created_by','updated_at'];

    protected static $logAttributes = ['*'];
	protected static $ignoreChangedAttributes = ['created_at','created_by','updated_at'];
	protected static $logOnlyDirty = true;
	protected static $submitEmptyLogs = false;

    protected static function boot() {
		parent::boot();
		if (Auth::check()) {
            static::deleting(function ($model) {
                $model->deleted_by = auth()->id();
                $model->save();
            });
		}
	}
}
