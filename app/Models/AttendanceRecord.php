<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'attendance_id', 'attendance_type_id'
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
    public function attendanceType()
    {
        return $this->belongsTo(AttendanceType::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
