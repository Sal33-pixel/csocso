@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Csapat létrehozás és szerkesztés</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(empty($gamers[0])) 
                        <div class="alert alert-danger" role="alert">
                            Nincs mentett játékos!
                        </div>
                    @endif
                    @if(isset($gamers) &&!empty($gamers[0]))
                        <form method="POST" action="{{ route('teamFormSave') }}">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Új Csapat Neve:</span>
                                <input type="text" name="team_name" class="form-control" placeholder="Csapat Név" aria-label="team_name" aria-describedby="basic-addon1" @if(empty($gamers[0]))disabled @else required @endif>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Kapus választás:</span>
                                <select name="selectDoorman" class="form-select" aria-label="Default select example" @if(empty($gamers[0]))disabled @else required @endif>
                                    @if(isset($gamers) && !empty($gamers[0]))
                                        @foreach($gamers as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option> 
                                        @endforeach
                                    @endif
                                </select>
                                <span class="input-group-text" id="basic-addon1">Csatár választás:</span>
                                <select name="selectStriker" class="form-select" aria-label="Default select example" @if(empty($gamers[0]))disabled @else required @endif>
                                    @if(isset($gamers) && !empty($gamers[0]))
                                        @foreach($gamers as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option> 
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">Mentés</button>
                        </form>
                </div>
                <div class="card-body">
                    @if(isset($teams) && !empty($teams[0]))
                    <hr>
                    <p>Csapatok szerkesztése</p>
                        @foreach($teams as $teamsRow)
                        <form method="POST" action="{{ route('teamSet') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{$teamsRow->id}}">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Csapat Név:</span>
                                <input type="text" name="team_name" class="form-control" value="{{ $teamsRow->team_name }}" placeholder="{{ $teamsRow->team_name }}" aria-label="team_name" aria-describedby="basic-addon1" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Kapus választás:</span>
                                <select name="selectDoorman" class="form-select" aria-label="Default select example" required>
                                    @if(isset($gamers) && !empty($gamers[0]))
                                        <option value="{{ $teamsRow->doorman_id }}">{{ $teamsRow->doormanName }}</option>
                                        @foreach($gamers as $gamersRow)
                                            @if($gamersRow->id != $teamsRow->doorman_id)
                                                <option value="{{ $gamersRow->id }}">{{ $gamersRow->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <span class="input-group-text" id="basic-addon1">Csatár választás:</span>
                                <select name="selectStriker" class="form-select" aria-label="Default select example" required>
                                    @if(isset($gamers) && !empty($gamers[0]))
                                        <option value="{{ $teamsRow->striker_id }}">{{ $teamsRow->strikerName }}</option>
                                        @foreach($gamers as $gamersRow)
                                            @if($gamersRow->id != $teamsRow->striker_id)
                                                <option value="{{ $gamersRow->id }}">{{ $gamersRow->name }}</option>
                                            @endif 
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">Változtat</button>
                            </form>
                            <hr>
                        @endforeach
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection