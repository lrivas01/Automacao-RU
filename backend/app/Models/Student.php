<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable {
    use HasFactory, Notifiable;

    /** 
     *  
     * @var string
     * */ 

    protected $table = 'students';

     /**
      *  Atributos que precisam ser preenchidos 

      * @var list<string>
      */

    protected $fillable = [
        'ra',
        'name',
        'email',
        'password',
        'is_active',
        'course',
        'tokens_available',    
    ];

    /**
     *  atributos que devem ser escondidos 
     *  @var List<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tokens_available' => 'integer',
        'is_active' => 'boolean',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($student) {
            if (!preg_match('/^\d{6}$/', $student->ra)) {
                throw new \InvalidArgumentException('O RA deve ter exatamente 6 dígitos numéricos.');
            }
        });
    }
}
