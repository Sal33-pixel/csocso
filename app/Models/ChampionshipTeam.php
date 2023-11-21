<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Championship;

class ChampionshipTeam extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'championshipTeams';

    //bajnokság csapat mentése
    public function ChampionshipTeamsSave($request)
    {
        $championshipID = Championship::where('championship_name', $request->name)
            ->orderBy('created_at', 'asc')
            ->value('id');

        foreach($request['team'] as $row)
        {
            $adat = new ChampionshipTeam();
            $adat->championship_id = $championshipID;
            $adat->team_id = $row;
            $adat->save();   
        }
    
        return back();
    }
}
