<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function teachers()
{
    return $this->belongsToMany(User::class);
}

public function teachingAssignments()
{
    return $this->hasMany(ClassSubjectTeacher::class);
}


}