<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'start_date', 'deadline'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_task')->withPivot('priority_id');
    }

    public function categoryTasks()
    {
        return $this->hasMany(CategoryTask::class, 'task_id');
    }
}
