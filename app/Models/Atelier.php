<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atelier extends Model
{
    use HasFactory;

    /**
     * Get the groupes for the atelier.
     */
    public function groupes()
    {
        return $this->hasMany(Groupe::class);
    }
}
