@extends('layout')

@section('title')
Create Teams
@endsection

@section('content')
<section id="content_wrapper">
    <section id="content">
        <div class="col-md-12">
            <div class="panel" id="spy2">
                <div class="panel-heading">
                    <span class="panel-title"></span>Create Teams</span>
                </div>
                <div class="panel-body">
                    <div class="card-body">
                        <div class="row">
                            <form action="{{url('select-captains')}}" method="POST">
                                @csrf
                                @for ($i = 1; $i <= $no_of_teams; $i++)
                                
                                <div class="col-md-12" style="margin-bottom:10px !important">
                                    <div class="col-md-4">
                                        <label class="col-lg-8 panel-title" ><b>Team {{$i}} Name</b></label>
                                        <input type="text" name="team_name[]" class="unique" class="form-control" required unique>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-lg-8 panel-title" ><b>Team {{$i}} Captain</b></label>
                                        <select type="text" name="captain_id[]"  class="form-control" required>
                                            <option value="">Select Captain</option>
                                            @foreach ($present as $key=>$row)
                                            <option value="{{$row->id}}">{{ $row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endfor
                                
                                <div class="form-group-md-4">
                                    <button type="submit" class="btn btn-primary">Create Teams </button>
                                </div>
                            </form>
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
