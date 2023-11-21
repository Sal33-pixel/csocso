@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Játékosok Felvitele és szerkesztése</div>

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
                    <form method="POST" action="{{ route('gamerFormSave') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Új Játékos Név:</span>
                            <input type="text" name="name" class="form-control" placeholder="Játékos Név" aria-label="Játékos Név" aria-describedby="basic-addon1">
                            <button type="submit" class="btn btn-outline-primary">Mentés</button>
                        </div>
                    </form>
                </div>
                @if(isset($gamers) &&!empty($gamers[0]))
                    <hr>
                    <div class="card-body">
                        @foreach($gamers as $row)
                            <form method="POST" action="{{ route('gamerset') }}">
                            @csrf
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Játékos Név:</span>
                                    <input type="hidden" name="id" value="{{$row->id}}">
                                    <input type="text" name="name" class="form-control" value="{{$row->name}}" placeholder="{{$row->name}}" aria-label="Játékos Név" aria-describedby="basic-addon1" required>
                                    <button class="btn btn-outline-primary" type="submit" id="button-addon2">Változtat</button>
                            </form>

                            <!--<form method="POST" action="{{ route('gamerdelete') }}">
                            @csrf
                                <input type="hidden" name="id" value="{{$row->id}}">
                                <button class="btn btn-outline-danger" type="submit" id="button-addon2">Törlés</button>
                            </form>-->
                                </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection