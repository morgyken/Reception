<?php
/**
 * Created by PhpStorm.
 * User: bravoh
 * Date: 10/16/17
 * Time: 11:28 AM
 */
?>
@extends('layouts.app')
@section('content_title')
    Search Results for "{{$search}}"
@stop
@section('content_description','Search Patients')

@section('content')
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Perform another search</h3>
        </div>
        <div class="box-body">
            @include('reception::partials.search')
            <hr>
            @if(count($found)>0)
            <table class="table table-striped table-condensed" id="patients_table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Mobile</th>
                    <th>ID Number</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody class="results">
                @foreach($found as $patient)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$patient->full_name}}</td>
                        <td>{{$patient->mobile}}</td>
                        <td>{{$patient->id}}</td>
                        <td>
                            <a class="btn  btn-xs" href="{{route('reception.view_patient',$patient->id)}}">
                                <i class="fa fa-eye-slash"></i> View
                            </a>
                            <a href="{{route('reception.checkin',$patient->id)}}" class="btn btn-xs">
                                <i class="fa fa-sign-in"></i> Check in
                            </a>
                            <a class="btn  btn-xs" href="{{route('reception.add_patient',$patient->id)}}">
                                <i class="fa fa-pencil-square-o"></i> Edit
                            </a>
                            <a href="{{route('reception.upload_doc',$patient->id)}}" class="btn btn-xs btn-primary">
                                <i class="fa fa-folder-open-o"></i> Manage Files
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <h4>No results found for {{$search}}</h4>
            @endif
        </div>
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#patients_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ],
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
            });
        });
    </script>
@endsection
