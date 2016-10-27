<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>
<div class="modal fade"  id="newSchedule" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            {!! Form::open(['route'=>'reception.appointments.new']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Appointment</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-horizontal">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('patient') ? ' has-error' : '' }}">
                                {!! Form::label('patient', 'Patient',['class'=>'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    <select name="patient" id="patient_select" class="form-control" style="width:100%;"></select>
                                    {!! $errors->first('patient', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
                                {!! Form::label('category', 'Category',['class'=>'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('category',get_schedule_cat(), old('category'), ['class' => 'form-control']) !!}
                                    {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('clinic') ? ' has-error' : '' }}">
                                {!! Form::label('clinic', 'Clinic',['class'=>'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('clinic',get_clinics(), old('clinic'), ['class' => 'form-control',]) !!}
                                    {!! $errors->first('clinic', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('doctor') ? ' has-error' : '' }}">
                                {!! Form::label('doctor', 'Doctor',['class'=>'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('doctor', get_doctors(), old('doctor'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('doctor', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group ">
                                {!! Form::label('dates', 'Date and Time',['class'=>'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    <div class="col-md-6 {{ $errors->has('date') ? ' has-error' : '' }}">
                                        {!! Form::text('date', old('date'), ['class' => 'form-control date', 'placeholder' => 'Date',]) !!}
                                        {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-6 {{ $errors->has('time') ? ' has-error' : '' }}">
                                        {!! Form::text('time', old('time'), ['class' => 'form-control time', 'placeholder' => 'Time',]) !!}
                                        {!! $errors->first('time', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('instructions') ? ' has-error' : '' }}">
                                {!! Form::label('instructions', 'Instructions',['class'=>'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('instructions', old('instructions'),
                                    ['class' => 'form-control', 'placeholder' => 'Instructions...','rows'=>3]) !!}
                                    {!! $errors->first('instructions', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="adds"><i class="fa fa-save"></i> Save</button>
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#patient_select").select2({
            tags: true,
            theme: "classic",
            ajax: {
                url: "{{route('api.reception.suggest_patients')}}",
                dataType: 'json',
                cache: true,
                data: function (term, page) {
                    return {
                        term: term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
                , cache: true
            },
            formatNoMatches: function () {
                return "No matches found";
            },
            formatInputTooShort: function (input, min) {
                return "Please enter " + (min - input.length) + " more characters";
            },
            formatInputTooLong: function (input, max) {
                return "Please enter " + (input.length - max) + " less characters";
            },
            formatSelectionTooBig: function (limit) {
                return "You can only select " + limit + " items";
            },
            formatLoadMore: function (pageNumber) {
                return "Loading more results...";
            },
            formatSearching: function () {
                return "Searching...";
            },
            minimumInputLength: 2
        });
    });
</script>
@section('styles')
<style>

</style>
@endsection