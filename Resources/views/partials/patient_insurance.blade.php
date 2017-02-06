<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>
<hr/>
<div class="col-md-12">
    <h4>Insurance</h4>
    <div class="col-md-6">
        <div class="insured form-group {{ $errors->has('insured') ? ' has-error' : '' }}">
            {!! Form::label('insured', 'Patient Has Insurance',['class'=>'control-label col-md-4']) !!}
            <div class="col-md-8">
                <label class="radio-inline">
                    <input type="radio" value="1" name="insured" id="s_schemes"/> Yes</label>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div id="schemes"  class="schemes hidden">
        <button class="add_button btn btn-xs btn-primary"><i class="fa fa-plus-square-o"></i> Add Record</button>
        <div id="wrapper1">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('company') ? ' has-error' : '' }}">
                    {!! Form::label('company', 'Insurance Company',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('company1',get_insurance_companies(), null, ['class' => 'form-control company', 'placeholder' => 'Choose...']) !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('scheme') ? ' has-error' : '' }}">
                    {!! Form::label('scheme[]', 'Insurance Schemes',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('scheme1',[], null, ['class' => 'form-control scheme', 'placeholder' => 'Choose...']) !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('policy_number') ? ' has-error' : '' }}">
                    {!! Form::label('policy_number', 'Policy Number',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('policy_number1', null, ['class' => 'form-control', 'placeholder' => 'Policy Number']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('principal') ? ' has-error' : '' }}">
                    {!! Form::label('principal', 'Principal',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('principal1', null, ['class' => 'form-control', 'placeholder' => 'Principal Name']) !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('principal_dob') ? ' has-error' : '' }}">
                    {!! Form::label('principal_dob', 'Date of Birth',['class'=>'date control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('principal_dob1', null, ['class' => 'form-control date', 'placeholder' => 'Date of Birth']) !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('principal_relationship') ? ' has-error' : '' }}">
                    {!! Form::label('principal_relationship', 'Relationship',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('principal_relationship1',mconfig('reception.options.relationship'), null, ['class' => 'form-control', 'placeholder' => 'Choose...']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
