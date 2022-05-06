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
          <span class="panel-title">
            <span class="panel-title"></span>Create Teams</span>
          </div>
          <div class="panel-body  ">
            <div class="row">
              <div class="col-md-8">
                <form action="{{url('filtered-list')}}" method="POST">
                  @csrf
                  <div class="card-body">
                    <div class="form-group">
                      <label class="col-lg-12 panel-title" >Select Absent Employee</label>
                      <div class="col-sm-12 pl15">
                        @foreach ($employees as $key=>$row)
                        <br><div class="col-sm-8 pl15">
                          <div class="checkbox-custom mb5">
                            <input type="checkbox"  id="checkboxDefault{{$key}}" name="absent_employees[]" value="{{$row->id}}">
                            <label for="checkboxDefault{{$key}}">{{ $row->name}}</label>
                          </div>
                          <br> 
                        </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-8 panel-title" ><b>Select No. of Teams</b></label>
                      <input type="number" name="no_of_teams"  class="form-control"> 
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary">Submit </button>
                    </div>
                  </div>
                </form>
              </div>
              <br>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
  @endsection
  
  @section('scripts')
  
  @endsection
  