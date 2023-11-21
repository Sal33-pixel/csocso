<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gamer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'gamers';

    //játékos mentése
    public function gamerSave($request)
    {
        $adat = new Gamer();
        $adat->name = $request->name;
        $adat->save();

        return back();
    }

    //játékos módosítása
    public function gamerSet($request)
    {
        $gamerSet = Gamer::where('id', $request->id)->first();
        $gamerSet->name = $request->name;
        $gamerSet->save();
    }

    //játékos törlése
    public function gamerdelete($request)
    {
        dd($request);
    }
}
