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
    <div class="box-body">

        <table class="table table-stripped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID No</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                <tr>
                    <td>{{$patient->id}}</td>
                    <td>{{$patient->id_no}}</td>
                    <td>{{$patient->fullname}}</td>
                    <td>{{$patient->checked_in_status}}</td>
                    <td><a href="{{route('reception.checkin',$patient->id)}}">
                            <i class="fa fa-sign-in"></i> Check in</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('table').DataTable();
    });
</script>
@endsection