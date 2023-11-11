<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jamb extends Model
{
    use HasFactory;
    
    protected $table = 'jambs';
    
    protected $fillable = [
        'height',
        'width',
        'name',
        'dealer_price',
        'retail_price',
        'jobs'
    ];
    
    protected $hidden = [
        'dealer_price', 
        'retail_price',
        'jobs'
    ];
    
    // bu stoyka nalichnik
    public function getDataWithHeight($name, $height)
    {
        $jamb = DB::select('SELECT * FROM jambs
                            WHERE name=? AND height > ?
                            ORDER BY height
                            LIMIT 1', [$name, $height + 80]);

        return $jamb;
    }
    
    public function getDataWithHeightForCrown($name, $height)
    {
        $jamb = DB::select('SELECT * FROM jambs
                            WHERE name=? AND height > ?
                            ORDER BY height
                            LIMIT 1', [$name, $height - 250]);

        return $jamb;
    }

    // bu tepa nalichnik 
    public function getDataWithHalfWidth($name, $width)
    {
        $half_height = DB::table('jamb_names')->where('name', $name)->first()->half_height;

        $jamb = DB::select('SELECT * FROM jambs
                            WHERE name=? AND height > ?
                            ORDER BY height
                            LIMIT 1', [$name, 2 * ($width + $half_height)]);

        return $jamb;
    }

    public function getDataWithWidth($name, $width)
    {
        $half_height = DB::table('jamb_names')->where('name', $name)->first()->half_height;

        $jamb = DB::select('SELECT * FROM jambs
                            WHERE name=? AND height > ?
                            ORDER BY height
                            LIMIT 1', [$name, $width + $half_height]);

        return $jamb;
    }

    public function getDataByCrown($crown_id)
    {
        $jamb_id = DB::table('ccbjs')->where('crown_id', $crown_id)->first()->jamb_id;
        
        $jamb_name = DB::table('jamb_names')->where('id', $jamb_id)->first()->name;
        
        return $jamb_name;
    }

    public function getData($id)
    {
        $jamb = Jamb::find($id);
        
        return $jamb;
    }
    
    // Kubik+Sapog uchun nalichnik tanlash
    public function getDataWithHeightForBootCube($boot_id, $cube_id, $height) 
    {
        $findJamb = DB::select('SELECT b.name 
                                FROM jamb_names b
                                INNER JOIN ccbjs a ON a.jamb_id=b.id
                                WHERE a.crown_id IS NULL AND 
                                    a.boot_id=? AND
                                    a.cube_id=?', [$boot_id, $cube_id]);
        if (!empty($findJamb)) {
            $jamb = DB::select('SELECT * FROM jambs
                                WHERE name=? AND height > ?
                                ORDER BY height
                                LIMIT 1', [$findJamb[0]->name, $height - 250]);
            return $jamb;
        }
    }
    
    public function getDataWithHalfWidthForBootCube($boot_id, $cube_id, $width)
    {
        $findJamb = DB::select('SELECT b.name
                                FROM jamb_names b
                                INNER JOIN ccbjs a ON a.jamb_id=b.id
                                WHERE a.crown_id IS NULL AND 
                                        a.boot_id=? AND
                                        a.cube_id=?', [$boot_id, $cube_id]);
        if (!empty($findJamb)) {
            $jamb = DB::select('SELECT * FROM jambs
                                WHERE name=? AND height > ?
                                ORDER BY height
                                LIMIT 1', [$findJamb[0]->name, 2 * $width]);
            return $jamb;
        }

    }

    public function getDataWithWidthForBootCube($boot_id, $cube_id, $width)
    {
        $findJamb = $findJamb = DB::select('SELECT b.name
                                            FROM jamb_names b
                                            INNER JOIN ccbjs a ON a.jamb_id=b.id
                                            WHERE a.crown_id IS NULL AND 
                                                  a.boot_id=? AND
                                                  a.cube_id=?', [$boot_id, $cube_id]);
        if(!empty($findJamb)) {
            $jamb = DB::select('SELECT * FROM jambs
                                WHERE name=? AND height > ?
                                ORDER BY height
                                LIMIT 1', [$findJamb[0]->name, $width]);
    
            return $jamb;
        }
    }
}
