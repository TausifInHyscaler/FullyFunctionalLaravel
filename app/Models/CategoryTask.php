<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTask extends Model
{
    use HasFactory;

    protected $table = 'category_task';

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }
}
