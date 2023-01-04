<?php

namespace App\Models;

use App\Models\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FieldValue extends Model
{
    use HasFactory;

    public function field()
    {        
        return $this->belongsTo(Field::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
