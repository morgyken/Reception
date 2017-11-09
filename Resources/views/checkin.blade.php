<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
extract($data);
?>
@extends('layouts.app')
@section('content_title','Checkin Patients')
@section('content_description','Add new patient sessions')

@section('content')
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Search</h3>
        </div>
        <div class="box-body">
            @include('reception::partials.search')
            <hr>
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
                @foreach($patients as $patient)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$patient->full_name}}</td>
                        <td>{{$patient->mobile}}</td>
                        <td>{{$patient->id}}</td>
                        <td>
                            <a class="btn  btn-xs" href="{{route('reception.view_patient',$patient->id)}}">
                                <i class="fa fa-eye-slash"></i> View</a>
                            <a href="{{route('reception.checkin',$patient->id)}}" class="btn btn-xs">
                                <i class="fa fa-sign-in"></i> Check in</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $patients->links() }}
        </div>
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#patients_table').DataTable({
                pageLength: 25,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ],
                "paginate": false,
                "lengthChange": true,
                "filter": false,
                "sort": true,
                "info": true,
                "autoWidth": true
            });
        });
    </script>
@endsection