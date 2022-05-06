@extends('layout')

@section('title')
Employee List
@endsection

@section('content')
<section id="content_wrapper">
  
  @if(Session::has('success'))
  <p class="alert alert-info">{{ Session('success') }}</p>
  @endif
  
  <section id="content">
    <div class="panel" id="spy2">
      <div class="panel-heading">
        <span class="panel-title">
          <span class="glyphicons glyphicons-table"></span>Employee data</span>
          <span class="pull-right lh20 h-20 panel-title">
            <a href="/add-employee" class="btn btn-success">Add Employee</a>
            
          </div>
          <div class="panel-body ">
            <table class="table">
              <thead>
                <tr>
                  <th>Sr.No</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  @foreach ($employees as $key=>$row)
                  <tr>
                    <td>{{++$key}}</td>   
                  </td>
                  <td>
                    {{ $row->name}}
                  </td>
                  <td>
                    <a href="/employee-edit/{{ $row->id}}" class="btn btn-success">Edit</a>|<form action="/data-delete/{{ $row->id}}" method="post"> 
                      @method('DELETE') 
                      @csrf
                      <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </section>
    @endsection
