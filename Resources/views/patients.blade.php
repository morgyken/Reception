<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
extract($data);
?>

@extends('layouts.app')
@section('content_title','Patients')
@section('content_description','Featured list of all patients')

@section('content')
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Patient List</h3>
    </div>
    <div class="box-body">
        @if($data['patients']->count()>0)
        <table class="table table-condensed table-responsive table-striped" id="patients">
            <tbody>
                @foreach($patients as $patient)
                <tr id="patient{{$patient->id}}">
                    <td>{{$patient->id}}</td>
                    <td>{{$patient->full_name}}</td>
                    <td>{{$patient->id_no}}</td>
                    <td>{{(new Date($patient->dob))->format('d/m/Y') }}</td>
                    <td>{{$patient->mobile}}</td>
                    <td>
                        <a class="btn  btn-xs" href="{{route('reception.view_patient',$patient->id)}}">
                            <i class="fa fa-eye-slash"></i> View</a>
                        <a class="btn  btn-xs" href="{{route('reception.add_patient',$patient->id)}}">
                            <i class="fa fa-pencil-square-o"></i> Edit
                        </a>
                        <a href="{{route('reception.checkin',$patient->id)}}" class="btn btn-xs">
                            <i class="fa fa-sign-in"></i> Check in</a>

                        <!--<a style="color: red" href="{{route('reception.purge_patient',$patient->id)}}" class="btn btn-xs">
                        <i class="fa fa-trash"></i>delete</a>
                        -->
                    </td>
                </tr>
                @endforeach
            </tbody>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>ID Number</th>
                    <th>DOB</th>
                    <th>Mobile</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
        @else
        <p class="text-warning"><i class="fa fa-info"></i> No patients. Strange!</p>
        @endif
    </div>
    <div class="box-footer">

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('table').DataTable();
    });
</script>
@endsection