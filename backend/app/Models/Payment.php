<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    use HasFactory;

    protected $fillable = ['student_id', 'tokens', 'amount', 'payment_method','status'];

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
