<?php

namespace App\Enums;

enum SyncList: string
{
    case SEMESTER = 'semester';
    // case SCHEDULES = 'schedules';
    case CLASSROOMS = 'classrooms';
    case LECTURES = 'lectures';
    case STUDENTS = 'students';
    case ATTENDANCES = 'attendances';

    public function lowercase(): string
    {
        return strtolower($this->name);
    }

    public function read(): string
    {
        return match ($this) {
            self::SEMESTER => 'Semester',
            // self::SCHEDULES => 'Jadwal',
            self::CLASSROOMS => 'Kelas',
            self::LECTURES => 'Jadwal Kuliah Umum',
            self::STUDENTS => 'Mahasiswa',
            self::ATTENDANCES => 'Kehadiran Mahasiswa',
        };
    }

    public function apiLoops(): bool
    {
        return $this === self::STUDENTS || $this === self::ATTENDANCES;
    }

    public function apiRoute(): string
    {
        return match ($this) {
            self::SEMESTER => 'api/semester',
            // self::SCHEDULES => 'api/schedules',
            self::CLASSROOMS => 'api/semester/:id/classrooms',
            self::LECTURES => 'api/semester/:id/lectures',
            self::STUDENTS => 'api/classrooms/:id',
            self::ATTENDANCES => 'api/attendance',
        };
    }
}
