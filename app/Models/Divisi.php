<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisis';

    protected $fillable = [
        'nama_divisi',
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->hasMany(User::class, 'divisi_id', 'id');
    }
}
