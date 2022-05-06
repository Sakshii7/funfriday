@extends('layout')

@section('title')
Create Teams
@endsection

<style>
    table {
        display: flex;
        align-items: center;
        justify-content: left;
    }
    tbody {
        display: flex;
        flex-direction: row;
    }
    tr {
        display: flex;
        flex-direction: column;
    }
</style>

@section('content')
<section id="content_wrapper">
    <section id="content">
        <div class="col-md-12">
            <div class="panel" id="spy2">
                <div class="panel-heading">
                    <span class="panel-title"><b>Teams</b></span>
                </div>
                <div class="panel-body">
                    <div class="card-body">
                        <div class="row">
                            <table class="table" id="verticalTable">
                                @foreach ($result as $key => $row) 
                                <tr>
                                    <th>
                                        {{ $row->team_name}}
                                    </th>
                                    @foreach ($row->players as $key1 => $player)
                                    <td >
                                          {{$player->name}}
                                    </td>    
                                    @endforeach
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>                  
                </div>
            </div> 
        </div>
    </section>
</section>
@endsection

@section('scripts')

@endsection
