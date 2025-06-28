<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'school_code',
        'school_name',
        'udise_no',
        'school_address',
        'district_id',
        'block_id',
        'cluster_id',
        'id_card',
        'amount',
        'payment_details',
        'status',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            if (auth()->check()) $model->updated_by = auth()->id();
        });
    }

    public function district() { return $this->belongsTo(District::class); }
    public function block() { return $this->belongsTo(Block::class); }
    public function cluster() { return $this->belongsTo(Cluster::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function authority() { return $this->hasOne(User::class); }
    public function students() { return $this->hasMany(Student::class); }
}
