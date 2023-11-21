@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Meccs Szerkesztése</div>

                <div class="card-body">
                    @if(empty($team[0])) 
                        <div class="alert alert-danger" role="alert">
                            Nincs mentett meccs!
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(isset($team) && !empty($team[0]))
                    <p>Szezon: {{$team[0]->championshipName}}</p>
                        <form method="POST" action="{{ route('matchSetSave') }}">
                        @csrf
                        @foreach($team as $row)
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Dátum</span>
                                <input type="date" name="date" value="{{ (isset($row->date))? $row->date : date("Y-m-d") }}" class="form-control" aria-describedby="basic-addon1">
                                <span class="input-group-text" id="basic-addon1">{{ $row->firstTeam }} :</span>
                                <input type="number" name="team_1_result" value="{{ $row->team_1_result }}" class="form-control" placeholder="{{ $row->team_1_result }}" aria-label="{{ $row->team_1_result }}" aria-describedby="basic-addon1" style="text-align:right;">
                                <span class="input-group-text"> : </span>
                                <input type="number" name="team_2_result" value="{{ $row->team_2_result }}" class="form-control" placeholder="{{ $row->team_2_result }}" aria-label="{{ $row->team_2_result }}">
                                <span class="input-group-text" id="basic-addon2">: {{ $row->secondTeam }}</span>

                                <input type="hidden" name="id" value="{{ $row->id }}">
                                <input type="hidden" name="championship_id" value="{{ $row->championship_id }}">
                                <input type="hidden" name="team_1_id" value="{{ $row->team_1_id }}">
                                <input type="hidden" name="team_2_id" value="{{ $row->team_2_id }}">
                                <input type="hidden" name="winner_team_id" value="{{ $row->winner_team_id }}">

                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">Mentés</button>
                            </div>
                        @endforeach
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection