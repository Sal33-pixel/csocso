<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gamer;
use App\Models\Team;
use App\Models\Championship;
use App\Models\ChampionshipTeam;
use App\Models\MatchResult;
use App\Models\PlayerZeroGoal;
use App\Http\Requests\matchSetSaveRequest;
use App\Http\Requests\addTeamRequest;
use App\Http\Requests\gamerFormSaveRequest;
use App\Http\Requests\championshipFormSaveRequest;

class CsocsoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Bajnokság kiválasztása
     */
    public function championshipSelect()
    {
        $list = Championship::select('id', 'championship_name')->get();

        return view('championshipSelect', ['list' => $list]);
    }

    //meccs kiválasztás
    public function matchSelect(Request $request)
    {
        $matchList = MatchResult::where('championship_id', '=', $request->selectChampionshipId)
            ->select('id', 'team_1_id', 'team_2_id', 'date')
            ->get();

        return view('selectMatch', ['matchList' => $matchList]);
    }

    //meccs szerkesztése
    public function matchSetSaveForm(Request $request)
    {
        $team = MatchResult::where('id', '=', $request->selectMatchId)
            ->get();

        return view('matchSetSaveForm', ['team' => $team]);
    }

    /**
     * meccs mentése szerkesztése (mászólista kitöltése)
     */
    public function matchSetSave(matchSetSaveRequest $request)
    {
        if($request->winner_team_id == null)
        {
            try{
                $this->MatchResult = new MatchResult();
                $topListTeam = $this->MatchResult->matchSave($request);
            }catch (\ErrorException $e){
                abort(500, $e->getMessage());
            } 
        }else{
            try{
                $this->MatchResult = new MatchResult();
                $topListTeam = $this->MatchResult->matchSet($request);
            }catch (\ErrorException $e){
                abort(500, $e->getMessage());
            }
        }

        return redirect()->route('topList');
    }

    /**
     * Játékos set
     */
    public function gamerset(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:30', 'unique:App\Models\Gamer,name,'.$request->id],
        ]);

        try{
            $this->Gamer = new Gamer();
            $Gamer = $this->Gamer->gamerSet($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * játékos törlése
     */
    public function gamerdelete(Request $request)
    {
        try{
            $this->Gamer = new Gamer();
            $Gamer = $this->Gamer->gamerdelete($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Játékos hozzáadása form
     */
    public function gamerForm()
    {
        //játékosok listája
        $gamers = Gamer::select('id', 'name')->orderBy('created_at', 'asc')->get();

        return view('addGamer', ['gamers' => $gamers]);
    }

    /**
     * Toplisták bajnokság választás
     */
    public function topList()
    {
        //bajnokságok listája
        $list = Championship::select('id', 'championship_name')->get();

        return view('topList', ['list' => $list]);
    }

    /**
     * toplisták bajnokság alapján generálása
     */
    public function toplistCreated(Request $request)
    {
        //többi lista
        try{
            $this->MatchResult = new MatchResult();
            $topListTeam = $this->MatchResult->topList($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }

        //mászók listája
        try{
            $this->PlayerZeroGoal = new PlayerZeroGoal();
            $topListZeroGoal = $this->PlayerZeroGoal->topListZeroGoal($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }

        return view('topList', ['topListZeroGoal' => $topListZeroGoal, 'topListTeam' => $topListTeam]);
    }

    /**
     * csapat és játékos hozzáadása form
     */
    public function addTeam()
    {
        $teams = Team::select('id', 'team_name', 'doorman_id', 'striker_id')
            ->orderBy('created_at', 'asc')
            ->get();

        $gamers = Gamer::select('id', 'name')->get();

        return view('addTeam', ['gamers' => $gamers, 'teams' => $teams]);    
    }

    /**
     * csapat szerkesztés
     */
    public function teamSet(Request $request)
    {
        $request->validate([
            'team_name'     => ['required', 'max:30', 'unique:App\Models\Team,team_name,'.$request->id],
            'selectDoorman' => ['required', 'different:selectStriker'],
            'selectStriker' => ['required', 'different:selectDoorman'],
        ]);
        
        try{
            $this->Team = new Team();
            $Team = $this->Team->teamSet($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * bajnokság hozzáadása form
     */
    public function championship()
    {
        //csapatok listája
        $teams = Team::select('id', 'team_name')->orderBy('created_at', 'asc')->get();
    
        return view('addChampionship', ['teams' => $teams]);
    }

    /**
     * bajnokság mentése
     */
    public function championshipFormSave(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'max:30', 'unique:App\Models\Championship,championship_name'],
        ]);

        //bajnokság nevének mentése
        try{
            $this->Championship = new Championship();
            $championshipModel = $this->Championship->championshipSave($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }

        //bajnokságon résztvevő csapatok mentése
        try{
            $this->ChampionshipTeam = new ChampionshipTeam();
            $ChampionshipTeam = $this->ChampionshipTeam->ChampionshipTeamsSave($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }

        //meccsek generálása és mentése
        try{
            $this->MatchResult = new MatchResult();
            $MatchResult = $this->MatchResult->matchGenerate($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * játékos mentése
     */
    public function gamerFormSave(gamerFormSaveRequest $request)
    {
        try{
            $this->Gamer = new Gamer();
            $Gamer = $this->Gamer->gamerSave($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * csapat mentése
     */
    public function teamFormSave(addTeamRequest $request)
    {
        try{
            $this->Team = new Team();
            $Team = $this->Team->teamSave($request);
        }catch (\ErrorException $e){
            abort(500, $e->getMessage());
        }

        return redirect()->back();
    }
}
