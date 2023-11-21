@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bajnokság Felvitele</div>

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
                    <form method="POST" action="{{ route('championshipFormSave') }}">
                        @csrf
                        @if(empty($teams[0])) 
                            <div class="alert alert-danger" role="alert">
                                Nincs mentett csapat!
                            </div>
                        @endif
                        @if(isset($teams) && !empty($teams[0]))
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Új Bajnokság Neve:</span>
                            <input type="text" name="name" class="form-control" placeholder="Bajnokság Név" aria-label="Bajnokság Név" aria-describedby="basic-addon1" @if(empty($teams[0]))disabled @else required @endif>
                        </div>
                        <p>Csapatok kiválasztása</p>
                            @foreach($teams as $row)
                            <div class="input-group mb-3">
                                <div class="form-check form-switch">
                                    <input name="team[]" value="{{ $row->id }}" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">{{ $row->team_name }}</label>
                                </div>
                            </div>
                            @endforeach
                        <button type="submit" class="btn btn-outline-primary">Mentés</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection