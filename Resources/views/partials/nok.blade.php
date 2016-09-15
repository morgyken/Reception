<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>
<hr/>
<div class="col-md-12">
    <h4>Next of Kin</h4>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('first_name_nok') ? ' has-error' : '' }}">
            {!! Form::label('first_name_nok', 'First Name',['class'=>'control-label col-md-4']) !!}
            <div class="col-md-8">
                {!! Form::text('first_name_nok', old('first_name_nok'), ['class' => 'form-control', 'placeholder' => 'Next of Kin First Name']) !!}
                {!! $errors->first('first_name_nok', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('middle_name_nok') ? ' has-error' : '' }}">
            {!! Form::label('middle_name_nok', 'Middle Name',['class'=>'control-label col-md-4']) !!}
            <div class="col-md-8">
                {!! Form::text('middle_name_nok', old('middle_name_nok'), ['class' => 'form-control', 'placeholder' => 'Next of Kin Middle Name']) !!}
                {!! $errors->first('middle_name_nok', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('last_name_nok') ? ' has-error' : '' }}">
            {!! Form::label('last_name_nok', 'Last Name',['class'=>'control-label col-md-4']) !!}
            <div class="col-md-8">
                {!! Form::text('last_name_nok', old('last_name_nok'), ['class' => 'form-control', 'placeholder' => 'Next of Kin Last Name']) !!}
                {!! $errors->first('last_name_nok', '<span class="help-block">:message</span>') !!}
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('mobile_nok') ? ' has-error' : '' }}">
            {!! Form::label('mobile_nok', 'Mobile Number',['class'=>'control-label col-md-4']) !!}
            <div class="col-md-8">
                {!! Form::text('mobile_nok', old('mobile_nok'), ['class' => 'form-control', 'placeholder' => '07xxxxxxxx']) !!}
                {!! $errors->first('mobile_nok', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('nok_relationship') ? ' has-error' : '' }}">
            {!! Form::label('nok_relationship', 'Relationship',['class'=>'control-label col-md-4']) !!}
            <div class="col-md-8">
                {!! Form::select('nok_relationship',config('system.relationship'), old('nok_relationship'), ['class' => 'form-control', 'placeholder' => 'Choose...']) !!}
                {!! $errors->first('nok_relationship', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>
