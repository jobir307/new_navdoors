<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Glass extends Model
{
    use HasFactory;
    protected $fillable = [
        'glasstype_id',
        'glassfigure_id',
        'price',
    ];

    public function getData($glasstype_id, $glass_figure_id)
    {
        $glass = DB::select('SELECT c.id,
                                    a.name glasstype,
                                    b.name glassfigure,
                                    c.price
                             FROM glasses c
                             INNER JOIN glass_types a ON a.id=c.glasstype_id
                             INNER JOIN glass_figures b ON b.id=c.glassfigure_id
                             WHERE c.glasstype_id=? AND c.glassfigure_id=?', [$glasstype_id, $glass_figure_id]);

        return $glass;
    }
}
