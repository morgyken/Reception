<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 *  Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>
@extends('layouts.app')
@section('content_title','Manage Patient Visits')
@section('content_description','View patient visits')

@section('content')
<div class="box box-info">
    <div class="box-body">
        <div class="form-horizontal">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#vitals" data-toggle="tab">Vitals</a></li>
                    <li><a href="#doctor" data-toggle="tab">Doctors' notes</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="vitals">
                        <div>
                            @include('system.reception.partials.patient_vitals')
                        </div>
                    </div>
                    <div class="tab-pane" id="doctor">
                        <div>
                            @include('system.reception.partials.doctors_notes')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection