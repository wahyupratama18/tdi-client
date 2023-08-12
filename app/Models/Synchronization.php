<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synchronization extends Model
{
    use HasFactory;

    protected $fillable = [
        'sync',
    ];

    public const SYNC = [
        'semester' => 'Semester',
        // 'schedules' => 'Jadwal',
        'classrooms' => 'Kelas',
        'lectures' => 'Jadwal Kuliah Umum',
        'students' => 'Mahasiswa',
        'attendances' => 'Kehadiran Mahasiswa',
    ];

    public const API = ['students', 'attendances'];

    public const ROUTES = [
        'semester' => 'api/semester',
        // 'schedules' => 'api/schedules',
        'classrooms' => 'api/semester/:id/classrooms',
        'lectures' => 'api/semester/:id/lectures',
        'students' => 'api/classrooms/:id',
        'attendances' => 'api/attendance',
    ];
}
