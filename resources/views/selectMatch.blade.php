@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Meccs kiválasztása</div>

                <div class="card-body">
                    @if(empty($matchList[0])) 
                        <div class="alert alert-danger" role="alert">
                            Nincs mentett meccs!
                        </div>
                    @endif
                    @if(isset($matchList) && !empty($matchList[0]))
                        <form method="GET" action="{{ route('matchSetSaveForm') }}">
                        @csrf
                            <select name="selectMatchId" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                @foreach($matchList as $row)
                                    <option value='{{ $row->id }}'>{{(!empty($row->date))? "Rögzítve: ".$row->date." | " : "Még nincs lejátszva: | "}} {{ $row->firstTeam }} - {{ $row->secondTeam}}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary" type="submit" id="button-addon2">Kiválasztás</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
