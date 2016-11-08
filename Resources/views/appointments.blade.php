<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>
@extends('layouts.app')
@section('content_title','Patients Appointment Schedule')
@section('content_description','View and manage patient appointments')

@section('content')
<div class="box box-info">
    <div class="box-header">
        <div class="pull-right">
            Start Date: <input type="text" id="date1">
            End Date: <input type="text" id="date2">
            <button id="clearBtn" class="btn btn-warning btn-xs">Clear</button>
        </div>
    </div>
    <div class="box-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a class="prefetch" sam-ajax="0" data-toggle="tab" id="all">All Clinics</a></li>
                @foreach($data['clinics'] as $clinic)
                <li>
                    <a class="prefetch"
                       sam-ajax="{{$clinic->id}}" data-toggle="tab">{{$clinic->name}}
                    </a>
                </li>
                @endforeach
                <li class="pull-right">
                    <a href="{{route('reception.checkin')}}">
                        <i class="fa fa-map-marker"></i> Check in patients</a>
                </li>
                <li class="pull-right">
                    <a id="new" class="btn btn-primary">
                        <i class="fa fa-plus"></i> New Appointment</a>
                </li>
            </ul>
        </div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="header"><i class="fa fa-th"></i> Categories</li>
                <li class="active"><a data-toggle="tab" sam-ajax2="0" id="all_cat" class="categories">All</a></li>
                @foreach($data['categories'] as $category)
                <li>
                    <a data-toggle="tab" sam-ajax2="{{$category->id}}"
                       class="categories">{{$category->name}}</a>
                </li>
                @endforeach
            </ul>
        </div>
        <div id="appointments">
            <!--include('reception::partials.view_appointments')-->
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cancel Appointment?</h4>
                </div>
                <div class="modal-body">
                    <p>Do you want to cancel. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="delete">Yes, I am sure</button>
                    <button class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('reception::partials.add_appointment')
<script type="text/javascript">
    var CANCEL_URL = "{{route('api.reception.cancel_appointment')}}";
    var GET_SCHEDULE = "{{route('api.reception.get_schedule')}}";
    var RESCHEDULE_URL = "{{route('api.reception.reschedule')}}";
    var PATIENTS_URL = "{{route('api.reception.suggest_patients')}}";
</script>
@if(!$errors->isEmpty())
<script type="text/javascript">
    $(document).ready(function () {
        $("#newSchedule").modal('show');
    });
</script>
@endif
<script src="{{m_asset('reception:js/appointments.min.js')}}"></script>
@endsection