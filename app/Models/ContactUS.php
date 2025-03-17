<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUS extends Model
{
    use HasFactory;

    function Reply()
    {
        return $this->belongsToMany(ContactUs::class, 'reply_id');
    }
}
