<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status', 'user_id'];

    /*---------------------------------------------------------------------------------------------*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*---------------------------------------------------------------------------------------------*/

    public static function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'status' => 'required|in:pending,in-progress,completed'
        ];
    }
}
