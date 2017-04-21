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
        <h3 class="box-title">Check Patient</h3>
    </div>

    <div class="box-body">
        <input type="text" size="20" id="search_patient" placeholder="Search Patient Name or or ID Number" class="col-xs-4">
        <a target="blank" class="btn btn-xs btn-primary pull-right" href="{{route('reception.show_patients','all')}}">View entire patient list</a><br>
        <hr>
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID No</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="results">
            </tbody>
        </table>
    </div>
</div>
</div>
<script type="text/javascript">
    var GET_PATIENTS = "{{route('api.reception.get_patients')}}";
    $('#search_patient').keyup(function () {
        get_patients(this.value);
    });
    $(document).ready(function () {
        //$('table').DataTable();
    });

    function get_patients(term) {
        $.ajax({
            url: GET_PATIENTS,
            data: {'term': term},
            success: function (data) {
                $('.results').html(data);
            }});
    }
</script>
@endsection