@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bajnokság kiválasztása</div>

                <div class="card-body">
                    @if(empty($list[0])) 
                        <div class="alert alert-danger" role="alert">
                            Nincs mentett bajnokság!
                        </div>
                    @endif
                    @if(isset($list) && !empty($list[0]))
                        <form method="GET" action="{{ route('matchSelect') }}">
                        @csrf
                            <select name="selectChampionshipId" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                @foreach($list as $row)
                                    <option value='{{ $row->id }}'>{{ $row->championship_name }}</option>
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
