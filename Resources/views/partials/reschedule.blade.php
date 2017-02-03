<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
$form = $data['form'];
?>
<div class="box box-info">
    <div class="form-horizontal">
        {!! Form::open(['route'=>['reception.appointments.res']]) !!}
        <div class="box-header">
            <h4 class="box-title">Reschedule Appointment</h4>
        </div>
        <?php if (isset($data['id'])) { ?>
            <input type="hidden" name="id" value="{{$data['id']}}">
            <?php
        }
        ?>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('patient') ? ' has-error' : '' }}">
                        {!! Form::label('name', 'Patient',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('patient',get_patients(), $form->patient, ['class' => 'form-control', 'placeholder' => '','readonly']) !!}
                            {!! $errors->first('patient', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
                        {!! Form::label('category', 'Category',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('category',get_schedule_cat(),$form->category, ['class' => 'form-control', 'placeholder' => '']) !!}
                            {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('procedure') ? ' has-error' : '' }}">
                        {!! Form::label('procedure', 'Procedure',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('procedure',get_procedures(), $form->procedure, ['class' => 'form-control', 'placeholder' => '']) !!}
                            {!! $errors->first('procedure', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('clinic') ? ' has-error' : '' }}">
                        {!! Form::label('clinic', 'Clinic',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('clinic',get_clinics(), $form->clinic, ['class' => 'form-control', 'placeholder' => 'Select...']) !!}
                            {!! $errors->first('clinic', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('doctor') ? ' has-error' : '' }}">
                        {!! Form::label('doctor', 'Doctor',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('doctor', get_checkin_destinations(), $form->doctor, ['class' => 'form-control', 'placeholder' => '']) !!}
                            {!! $errors->first('doctor', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group ">
                        {!! Form::label('dates', 'Date and Time',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            <div class="col-md-6 {{ $errors->has('date') ? ' has-error' : '' }}">
                                {!! Form::text('date', old('date'), ['class' => 'form-control date', 'placeholder' => 'Date','id'=>'date']) !!}
                                {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-6 {{ $errors->has('time') ? ' has-error' : '' }}">
                                {!! Form::text('time', old('time'), ['class' => 'form-control time', 'placeholder' => 'Time','id'=>'time']) !!}
                                {!! $errors->first('time', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('instructions') ? ' has-error' : '' }}">
                        {!! Form::label('instructions', 'Instructions',['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('instructions', $form->instructions,
                            ['class' => 'form-control', 'placeholder' => 'Instructions...','rows'=>3]) !!}
                            {!! $errors->first('instructions', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="box-footer">
            <button id="close" class="btn btn-success"><i class="fa fa-arrow-left"></i> Back</button>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary" id="adds"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script type="text/javascript">
    $('#close').click(function (e) {
        e.preventDefault();
        fetch_table();
    });
    $(".date").datepicker({minDate: 0, dateFormat: 'yy-mm-dd', yearRange: "1900:+0"});
    $('.time').timeAutocomplete({increment: 10, auto_value: false});
</script>