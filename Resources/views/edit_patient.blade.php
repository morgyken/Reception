<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
extract($data);
?>
@extends('layouts.app')
@section('content_title','View Patient')
@section('content_description','View and edit patients')

@section('content')
<div class="form-horizontal">
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Patient Information</h3>
        </div>
        <form method="post" action="{{ route('reception.update_patient') }}" accept-charset="UTF-8">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <input type="hidden" name="id" value="{{$patient->id}}">
            <div class="box-body">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                        {!! Form::label('name', 'First Name',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('first_name', old('first_name',$patient->first_name), ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                            {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('middle_name') ? ' has-error' : '' }}">
                        {!! Form::label('name', 'Middle Name',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('middle_name', old('middle_name',$patient->middle_name), ['class' => 'form-control', 'placeholder' => 'Middle Name']) !!}
                            {!! $errors->first('middle_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                        {!! Form::label('name', 'Last Name',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('last_name', old('last_name',$patient->last_name), ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
                            {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                        {!! Form::label('dob', 'Date of Birth',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('dob', old('dob',$patient->dob), ['class' => 'form-control', 'placeholder' => 'Date of Birth']) !!}
                            {!! $errors->first('dob', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('sex') ? ' has-error' : '' }}">
                        {!! Form::label('sex', 'Sex',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::radio('sex','male',(strtolower($patient->sex)==='male')) !!} Male
                            {!! Form::radio('sex','female',(strtolower($patient->sex)==='female')) !!} Female
                            {!! $errors->first('sex', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                        {!! Form::label('mobile', 'Mobile Number',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('mobile', old('mobile',$patient->mobile), ['class' => 'form-control', 'placeholder' => '07xxxxxxxx']) !!}
                            {!! $errors->first('mobile', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('id_number') ? ' has-error' : '' }}">
                        {!! Form::label('id_number', 'ID Number',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('id_number', old('id_number',$patient->id_no), ['class' => 'form-control', 'placeholder' => 'ID number']) !!}
                            {!! $errors->first('id_number', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('telephone',$patient->telephone) ? ' has-error' : '' }}">
                        {!! Form::label('telephone', 'Telephone Number',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('telephone', old('telephone'), ['class' => 'form-control', 'placeholder' => 'Telephone']) !!}
                            {!! $errors->first('telephone', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('alt_number') ? ' has-error' : '' }}">
                        {!! Form::label('alt_number', 'Alternative Number',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('alt_number', old('alt_number',$patient->alt_number), ['class' => 'form-control', 'placeholder' => 'Telephone']) !!}
                            {!! $errors->first('alt_number', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('email', 'Email Address',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::email('email', old('email',$patient->email), ['class' => 'form-control', 'placeholder' => 'Email address']) !!}
                            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                        {!! Form::label('address', 'Postal Address',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('address', old('address',$patient->address), ['class' => 'form-control', 'placeholder' => 'Postal Address']) !!}
                            {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('post_code') ? ' has-error' : '' }}">
                        {!! Form::label('post_code', 'Postal Code',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('post_code', old('post_code',$patient->post_code), ['class' => 'form-control', 'placeholder' => 'Postal Code']) !!}
                            {!! $errors->first('post_code', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('town') ? ' has-error' : '' }}">
                        {!! Form::label('town', 'City/Town',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('town', old('town',$patient->town), ['class' => 'form-control', 'placeholder' => 'City or Town']) !!}
                            {!! $errors->first('town', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('photo') ? ' has-error' : '' }}">
                        {!! Form::label('photo', 'Patient Image',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::file('photo',['class'=>'form-control']) !!}
                            {!! $errors->first('photo', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    @if(\Auth::user()->profile->partner_institution<0)
                    <div class="form-group {{ $errors->has('external_institution') ? ' has-error' : '' }}">
                        {!! Form::label('external_institution', 'Partner Institution',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('external_institution',get_external_institutions(), null, ['class' => 'form-control external_institution', 'placeholder' => 'Choose...']) !!}
                            {!! $errors->first('external_institution', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    @else
                    <input type="hidden" name="external_institution" value="{{\Auth::user()->profile->partner_institution}}">
                    @endif
                </div>
                <hr/>
                @include('reception::partials.nok')
                <hr/>
                @include('reception::partials.patient_insurance')
                <script>
                    $(document).ready(function () {
                        $("#company").change(function () {
                            var id = $(this).val();
                            var url = "{{url('/system/ajax/get_schemes')}}";
                            $.get(url + '/' + id, function (data) {
                                $("#scheme").empty();
                                var options = "";
                                $.each(data, function (key, value) {
                                    options += '<option value="' + key + '">' + value + '</option>';

                                });
                                $('#scheme').html(options);
                            });
                        });
                        $("#principal_dob").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, defaultDate: '-20y'});
                    });
                </script>

            </div>
            <div class="box-footer">
                <div class="pull-left">
                    <a href="{{URL::previous()}}" class="btn btn-default">
                        <i class="fa fa-arrow-circle-o-left"></i> Back
                    </a>
                </div>
                <div class="pull-right">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                </div>
            </div>
            {!! Form::close() !!}
    </div>

    <script type="text/javascript">
        var SCHEMES_URL = "{{route('api.settings.get_schemes')}}";
        $(document).ready(function () {
            $(".external_institution").select2();
        });
    </script>
    <script src="{{m_asset('reception:js/addpatient.js')}}"></script>
</div>
@endsection