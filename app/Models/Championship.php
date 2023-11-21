<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'championship';
    
    //bajnokság mentése
    public function championshipSave($request)
    {
        $adat = new Championship();
        $adat->championship_name = $request->name;
        $adat->save();

        return back();
    }
}
