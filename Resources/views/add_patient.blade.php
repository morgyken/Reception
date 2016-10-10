<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>

@extends('layouts.app')
@section('content_title','Patients')
@section('content_description','Add and edit patients')

@section('content')
<div class="form-horizontal">
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Patient Information</h3>
        </div>
        {!! Form::open(['files'=>true,'route'=>'reception.save_patient']) !!}
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group req {{ $errors->has('first_name') ? ' has-error' : '' }}">
                    {!! Form::label('first_name', 'First Name',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                        {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('middle_name') ? ' has-error' : '' }}">
                    {!! Form::label('middle_name', 'Middle Name',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('middle_name', old('middle_name'), ['class' => 'form-control', 'placeholder' => 'Middle Name']) !!}
                        {!! $errors->first('middle_name', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group req {{ $errors->has('last_name') ? ' has-error' : '' }}">
                    {!! Form::label('last_name', 'Last Name',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
                        {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group req {{ $errors->has('dob') ? ' has-error' : '' }}">
                    {!! Form::label('dob', 'Date of Birth',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('dob', old('dob'), ['class' => 'form-control date', 'placeholder' => 'Date of Birth']) !!}
                        {!! $errors->first('dob', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('sex') ? ' has-error' : '' }}">
                    <div class="req">
                        {!! Form::label('sex', 'Gender',['class'=>'control-label col-md-4']) !!}
                    </div>
                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" name="sex" value="male" checked/> Male </label>
                        <label class="radio-inline">
                            <input type="radio" name="sex" value="female"/> Female</label>
                        {!! $errors->first('sex', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }} req">
                    {!! Form::label('mobile', 'Mobile Number',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('mobile', old('mobile'), ['class' => 'form-control', 'placeholder' => '07xxxxxxxx']) !!}
                        {!! $errors->first('mobile', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('id_number') ? ' has-error' : '' }}">
                    {!! Form::label('id_number', 'ID Number',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('id_number', old('id_number'), ['class' => 'form-control', 'placeholder' => 'ID number']) !!}
                        {!! $errors->first('id_number', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('telephone') ? ' has-error' : '' }}">
                    {!! Form::label('telephone', 'Telephone Number',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('telephone', old('telephone'), ['class' => 'form-control', 'placeholder' => 'Telephone']) !!}
                        {!! $errors->first('telephone', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('alt_number') ? ' has-error' : '' }}">
                    {!! Form::label('alt_number', 'Alternative Number',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('alt_number', old('alt_number'), ['class' => 'form-control', 'placeholder' => 'Telephone']) !!}
                        {!! $errors->first('alt_number', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    {!! Form::label('email', 'Email Address',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email address']) !!}
                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                    {!! Form::label('address', 'Postal Address',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('address', old('address'), ['class' => 'form-control', 'placeholder' => 'Postal Address']) !!}
                        {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('post_code') ? ' has-error' : '' }}">
                    {!! Form::label('post_code', 'Postal Code',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('post_code', old('post_code'), ['class' => 'form-control', 'placeholder' => 'Postal Code']) !!}
                        {!! $errors->first('post_code', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('town') ? ' has-error' : '' }}">
                    {!! Form::label('town', 'City/Town',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('town', old('town'), ['class' => 'form-control', 'placeholder' => 'City or Town']) !!}
                        {!! $errors->first('town', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('photo') ? ' has-error' : '' }}">
                    {!! Form::label('photo', 'Patient Image',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        <div class="col-md-8">
                            {!! Form::file('photo',['class'=>'form-control']) !!}
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-default" id="webcam">
                                <i class="fa fa-camera-retro"></i> Capture</button>
                        </div>
                        {!! $errors->first('photo', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            @include('reception::partials.nok')
            @include('reception::partials.patient_insurance')
        </div>
        <div class="box-footer">
            <div class="pull-left">
                <a href="{{URL::previous()}}" class="btn btn-default">
                    <i class="fa fa-arrow-circle-o-left"></i> Back
                </a>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@include('reception::partials.webcam')
<script type="text/javascript">
    var SCHEMES_URL = "{{route('api.settings.get_schemes')}}";
</script>
<script src="{{m_asset('reception:js/addpatient.min.js')}}"></script>
@endsection