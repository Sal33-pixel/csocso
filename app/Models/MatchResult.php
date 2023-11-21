<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Championship;
use App\Models\PlayerZeroGoal;
use App\Models\Team;

class MatchResult extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'matchResult';

    /**
     * Attributumok
     */
    protected function firstTeam(): Attribute
    {
        return Attribute::make(
            get: fn () => Team::select('team_name')->where('id', $this->team_1_id)->first()->team_name,
        );
    }

    protected function secondTeam(): Attribute
    {
        return Attribute::make(
            get: fn () => Team::select('team_name')->where('id', $this->team_2_id)->first()->team_name,
        );
    }

    protected function championshipName(): Attribute
    {
        return Attribute::make(
            get: fn () => Championship::select('championship_name')->where('id', $this->championship_id)->first()->championship_name,
        );
    }

    //meccsek generálása és mentése
    public function matchGenerate($request)
    {
        //a létrehozott szezon id lekérése
        $championshipID = Championship::where('championship_name', $request->name)
            ->orderBy('created_at', 'asc')
            ->value('id');

        $teams = [];
        foreach($request->team as $row)
        {
            array_push($teams, $row);
        }

        //meccsek létrehozása
        $fact = [];
        for($i = 0; $i <= count($teams)-1; $i++)
        {
            if(isset($teams[$i+1]))
            {
                for($j = $i+1; $j <= count($teams)-1; $j++)
                {
                    $fact[] = [$teams[$i] => $teams[$j]];
                }
            }
        }

        //meccsek generálása és mentése
        foreach($fact as $row)
        {
            foreach($row as $key => $value)
            {
                $adat = new MatchResult();
                $adat->championship_id      = $championshipID;
                $adat->team_1_id            = $key;
                $adat->team_2_id            = $value;
                $adat->team_1_result        = null;
                $adat->team_2_result        = null;
                $adat->winner_team_id       = null;
                $adat->date                 = null;
                $adat->save();
            }
        }

        return back();
    }

    /**
     * meccs értékeinek mentése
     */
    public function matchSave($request)
    {
        $adat = MatchResult::where('id', $request->id)->first();
        $adat->championship_id      = $request->championship_id;
        $adat->team_1_id            = $request->team_1_id;
        $adat->team_2_id            = $request->team_2_id;
        $adat->team_1_result        = $request->team_1_result;
        $adat->team_2_result        = $request->team_2_result;
        $adat->winner_team_id       = ($request->team_1_result > $request->team_2_result)? $request->team_1_id : $request->team_2_id;
        $adat->date                 = $request->date;
        $adat->save();

        if($request->team_1_result == 0 || $request->team_2_result == 0)
        {
            $team = Team::where('id', '=', (($request->team_1_result > $request->team_2_result)? $request->team_2_id : $request->team_1_id))
                ->first();

            $zeroGoal = new PlayerZeroGoal();
            $zeroGoal->championship_id  = $request->championship_id;
            $zeroGoal->match_id         = $request->id;
            $zeroGoal->gamer_id         = $team->doorman_id;
            $zeroGoal->save();

            $zeroGoal = new PlayerZeroGoal();
            $zeroGoal->championship_id  = $request->championship_id;
            $zeroGoal->match_id         = $request->id;
            $zeroGoal->gamer_id         = $team->striker_id;
            $zeroGoal->save();
        }

        return back();
    }

    /**
     * meccs értékeinek szerkesztése
     */
    public function matchSet($request)
    {
        $adat = MatchResult::where('id', $request->id)->first();
        $adat->championship_id      = $request->championship_id;
        $adat->team_1_id            = $request->team_1_id;
        $adat->team_2_id            = $request->team_2_id;
        $adat->team_1_result        = $request->team_1_result;
        $adat->team_2_result        = $request->team_2_result;
        $adat->winner_team_id       = ($request->team_1_result > $request->team_2_result)? $request->team_1_id : $request->team_2_id;
        $adat->date                 = $request->date;
        $adat->save();

        $delete = PlayerZeroGoal::where('match_id', '=', $request->id)->delete();

        if($request->team_1_result == 0 || $request->team_2_result == 0)
        {
            $team = Team::where('id', '=', (($request->team_1_result > $request->team_2_result)? $request->team_2_id : $request->team_1_id))
            ->first();

            $zeroGoal = new PlayerZeroGoal();
            $zeroGoal->championship_id  = $request->championship_id;
            $zeroGoal->match_id         = $request->id;
            $zeroGoal->gamer_id         = $team->doorman_id;
            $zeroGoal->save();

            $zeroGoal = new PlayerZeroGoal();
            $zeroGoal->championship_id  = $request->championship_id;
            $zeroGoal->match_id         = $request->id;
            $zeroGoal->gamer_id         = $team->striker_id;
            $zeroGoal->save();
        }

        return back();
    }

    /**
     * Toplisták
     */
    public function topList($request)
    {
        //Első lista lekérés Meccsek listája
        $topListTeam = MatchResult::where('championship_id', '=', $request->selectChampionshipId)
            ->join('championships', 'championships.id', '=', 'match_results.championship_id')
            ->join('teams as first', 'first.id', '=', 'match_results.team_1_id')
            ->join('teams as second', 'second.id', '=', 'match_results.team_2_id')
            ->join('teams as winner', 'winner.id', '=', 'match_results.winner_team_id')
            ->select(
                'championships.championship_name as championship',
                'first.team_name as team1',
                'second.team_name as team2',
                'winner.team_name as winner',
                'team_1_result',
                'team_2_result',
                'date')
            ->orderBy('date', 'desc')
            ->get();

        //második lista lekérés Pont táblázat
        $topListResult = MatchResult::where('championship_id', '=', $request->selectChampionshipId)
            ->join('championships', 'championships.id', '=', 'match_results.championship_id')
            ->join('teams', 'teams.id', '=', 'match_results.winner_team_id')
            ->selectRaw('COUNT(match_results.winner_team_id) as winn, COUNT(match_results.winner_team_id) as originalWinn, match_results.winner_team_id, championships.championship_name, teams.team_name')
            ->where('match_results.winner_team_id', '!=', null)
            ->groupByRaw('match_results.winner_team_id, championships.championship_name, teams.team_name')
            ->orderBy('winn', 'desc')
            ->get();

        //Második lista rendezés
        $topListResultOrder = [];
        $winnKey = [];
        $same = [];
        //kigyüjtöm azokat a pont értékeket amik egyeznek az $same ba
        foreach($topListResult as $row)
        {
            if(($key = array_search($row->attributes['winn'], $winnKey)) !== false)
            {
                array_push($same, $row->attributes['winn']);
                array_push($topListResultOrder, $row->attributes);
            }else{
                array_push($winnKey, $row->attributes['winn']);
                array_push($topListResultOrder, $row->attributes);
            }
        }

        //kigyüjtöm azokat a csapatokat akiknek ugyan annyi pontja van $sameTeams ba
        $sameTeams = [];
        foreach($topListResultOrder as $row)
        {
            if(($key = array_search($row['winn'], $same)) !== false)
            {
                $sameTeams += [$row['winner_team_id'] => (($ered = array_search($row['winn'], $same)) !== false)? $row['winn'] : xxx];
            }
        }

        //meccs keresés
        $match = [];
        $i = 0;
        $sameTeamsCount = array_count_values($sameTeams);
        //amennyiben a value darabszáma több mint 2 hagya ezt ki
        foreach($sameTeamsCount as $keyCount => $valueCount)
        {
            if($valueCount == 2)
            {
                $sameSearch = [];
                foreach($sameTeams as $key => $value)
                {
                    array_push($sameSearch, $key);
                    //same csapatok id jére keresek a nyertesek között így vannak meg a mecsek
                    $goalsKicked = MatchResult::whereRaw('winner_team_id ='.$key)
                    ->get();

                    foreach($goalsKicked as $row)
                    {
                        array_push($match, $row->attributes);
                    }
                    $i++;
                }
                //kiszedi azokat a meccseket ahol a két csapat játszott
                $keySearch = array_keys($sameTeams);

                $search = MatchResult::whereRaw('team_1_id = '.$keySearch[0].' and team_2_id ='.$keySearch[1])
                    ->get();

                if(empty($search))
                {
                    for($j = 0; $j <= count($topListResultOrder)-1;$j++)
                    {
                        if($topListResultOrder[$j]['winner_team_id'] == $search[0]->attributes['winner_team_id'])
                        {
                            $topListResultOrder[$j]['winn']++;
                        }
                    }

                    for($j = 0; $j <= count($topListResultOrder)-1;$j++)
                    {
                        if($topListResultOrder[$j]['winner_team_id'] == $search[0]->attributes['winner_team_id'])
                        {
                            $topListResultOrder[$j]['winn']++;
                        }
                    }
                }
            }
        }

        $columns = array_column($topListResultOrder, 'winn');
        array_multisort($columns, SORT_DESC, $topListResultOrder);

        return ['topListTeam' => $topListTeam, 'topListResult' => $topListResult, 'topListResultOrder' => $topListResultOrder];
    }
}
