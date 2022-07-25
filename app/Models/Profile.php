<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'student_id', 'email', 'phone', 'image','last_name'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
