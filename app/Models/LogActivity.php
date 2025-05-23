<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory, SoftDeletes;

    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'subject', 'url', 'method', 'ip', 'agent', 'user_id'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
