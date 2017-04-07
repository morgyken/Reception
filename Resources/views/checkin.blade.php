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

        <table class="table table-striped table-condensed">
            <thead style="background-color: #e0e0e0">
                <!--
                <tr>
                    <td colspan="4">
                        {{ Form::open(array('route' => 'reception.patient.search')) }}
                        <input type="text" placeholder="ENTER PATIENT NAME TO SEARCH" size="70" name="key">
                        <input type="submit" value="SEARCH" class="btn btn-s btn-primary">
                        {{ Form::close() }}
                    </td>
                    <td></td>
                </tr>
                -->
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
            <tfoot>
                <tr>
                    <td colspan="5">
                        <p>{{ $patients->links() }} >> Next 1000 patients</p>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('table').DataTable();
    });
</script>
@endsection