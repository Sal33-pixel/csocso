<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Championship;
use App\Models\MatchResult;
use App\Models\Team;
use App\Models\Gamer;

class PlayerZeroGoal extends Model
{
    use HasFactory;

    /**
     * Atributumok
     */
    protected function championshipName(): Attribute
    {
        return Attribute::make(
            get: fn () => Championship::select('championship_name')->where('id', $this->championship_id)->first()->championship_name,
        );
    }

    protected function gamerName(): Attribute
    {
        return Attribute::make(
            get: fn () => Gamer::select('name')->where('id', $this->gamer_id)->first()->name,
        );
    }

    //csapatok és játékoso 0 gólos eredményei
    public function topListZeroGoal($request)
    {
        $team = MatchResult::where('championship_id', '=', $request->selectChampionshipId)
            ->whereRaw('(team_1_result = 0 or team_2_result = 0)')
            ->get();

        $teamArray = [];
        foreach($team as $value)
        {
            if($value->attributes['team_1_result'] == 0)
            {
                array_push($teamArray, $value->attributes['team_1_id']);
            }elseif($value->attributes['team_2_result'] == 0)
            {
                array_push($teamArray, $value->attributes['team_2_id']);
            }
        }
        $teamArray = array_count_values($teamArray);

        $player = PlayerZeroGoal::where('championship_id', '=', $request->selectChampionshipId)
            ->selectRaw('COUNT(gamer_id) as gamerCount, gamer_id')
            ->groupBy('gamer_id')
            ->get();

        return ['team' => $team, 'teamArray' => $teamArray, 'player' => $player];
    }
}
