<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }
}
