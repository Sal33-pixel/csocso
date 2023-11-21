@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Toplisták</div>

                @if(empty($list[0]) && !isset($topListZeroGoal))
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            Nincs mentett bajnokság!
                        </div>
                    </div>
                @endif
                @if(isset($list) && !empty($list[0]) && !isset($topListZeroGoal))
                    <div class="card-body">
                        <form method="GET" action="{{ route('toplistCreated') }}">
                        @csrf
                            <select name="selectChampionshipId" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                @foreach($list as $row)
                                    <option value='{{ $row->id }}'>{{ $row->championship_name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary" type="submit" id="button-addon2">Kiválasztás</button>
                        </form>
                    </div>
                @endif
                @if(isset($topListZeroGoal))
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                        <p>Meccsek listája</p>
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Első csapat</th>
                            <th scope="col">Második csapat</th>
                            <th scope="col">Eredmény</th>
                            <th scope="col">Nyertes</th>
                            <th scope="col">Dátum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topListTeam['topListTeam'] as $row)
                                <tr>
                                <th scope="row">#</th>
                                <td>{{ $row->team1 }}</td>
                                <td>{{ $row->team2 }}</td>
                                <td>{{ $row->team_1_result.':'.$row->team_2_result }}</td>
                                <td>{{ $row->winner }}</td>
                                <td>{{ $row->date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>

                    <div class="card-body">
                        <p>Pont táblázat</p>
                        <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Csapat</th>
                            <th scope="col">pontszám</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topListTeam['topListResultOrder'] as $row)

                                <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{ $row['team_name'] }}</td>
                                <td>{{ $row['originalWinn'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>

                    <div class="card-body">
                        <p>Csapat mászás táblázat</p>
                        <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Csapat</th>
                            <th scope="col">Mászás száma</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topListZeroGoal['teamArray'] as $key => $value)
                                <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{ $teamName = DB::table('teams')->where('id', $key)->select('team_name')->first()->team_name }}</td>
                                <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>

                    <div class="card-body">
                        <p>Játékos mászás táblázat</p>
                        <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Játékos</th>
                            <th scope="col">Mászás száma</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topListZeroGoal['player'] as $row)
                                <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{ $row->gamerName }}</td>
                                <td>{{ $row->gamerCount}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
