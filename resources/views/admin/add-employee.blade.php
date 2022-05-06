@extends('layout')

@section('title')
Add Employee
@endsection

@section('content')
<section id="content_wrapper">
  <section id="content">
    <div class="col-md-12">
    <div class="panel" id="spy2">
      <div class="panel-heading">
          <span class="panel-title">
              <span class="panel-title"></span>Add Employee</span>
          
      </div>
        <div class="panel-body  ">
          <div class="row">
            <div class="col-md-8">
            <form action="{{url('/employee-list-add')}}" method="POST">
              @csrf

              <div class="card-body">
                @if(Session::has('error'))
                  <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
                @if(Session::has('success'))
                  <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif

     <div class="form-group">
       <br>
       <label class="col-lg-3 control-label" >Name</label>
       <input type="text" name="name"  class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">
       @if ($errors->has('name'))
                  <span class="invalid-feedback">
                    {{ $errors->first('name') }}
                  </span>
                @endif
   </div>
   <button type="submit" class="btn btn-primary">Submit </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</section>
@endsection

@section('scripts')

@endsection