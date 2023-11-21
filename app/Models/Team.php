<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gamer;


class Team extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'teams';

    /**
     * Attributumok
     */
    protected function doormanName(): Attribute
    {
        return Attribute::make(
            get: fn () => Gamer::select('name')->where('id', $this->doorman_id)->first()->name,
        );
    }

    protected function strikerName(): Attribute
    {
        return Attribute::make(
            get: fn () => Gamer::select('name')->where('id', $this->striker_id)->first()->name,
        );
    }

    /*public function getStrikerNameAttribute()
    {
        return Gamer::select('name')->where('id', $this->striker_id)->first()->name;
    }*/

    //csapat mentése
    public function teamSave($request)
    {
        $adat = new Team();
        $adat->team_name = $request->team_name;
        $adat->doorman_id = $request->selectDoorman;
        $adat->striker_id = $request->selectStriker;
        $adat->save();

        return back();
    }

    //csapat szerkesztés
    public function teamSet($request)
    {
        $teamSet = Team::where('id', $request->id)->first();
        $teamSet->team_name = $request->team_name;
        $teamSet->doorman_id = $request->selectDoorman;
        $teamSet->striker_id = $request->selectStriker;
        $teamSet->save();
    }
}
