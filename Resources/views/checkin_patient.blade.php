<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
extract($data);
//$precharge = data['precharge'];
//dd($patient->insured);
?>
@extends('layouts.app')
@section('content_title','Checkin Patient')
@section('content_description','Add new patient sessions')

@section('content')
<div class="box box-info">
    <div class="box-body">
        <div class="col-md-6">
            <h4>Patient Information</h4>
            <dl class="dl-horizontal">
                <dt>Name:</dt><dd>{{$patient->full_name}}</dd>
                <dt>Date of Birth:</dt><dd>{{(new Date($patient->dob))->format('m/d/y')}}
                    <strong>({{(new Date($patient->dob))->age}} years old)</strong></dd>
                <dt>Gender:</dt><dd>{{$patient->sex}}</dd>
                <dt>Mobile Number:</dt><dd>{{$patient->mobile}}</dd>
                <dt>ID number:</dt><dd>{{$patient->id_no}}</dd>
                <dt>Email:</dt><dd>{{$patient->email}}</dd>
                <dt>Telephone:</dt><dd>{{$patient->telephone}}</dd>

            </dl>
            @if(!empty($patient->image))
            <hr/>
            <h5>Patient Image</h5>
            <img src="{{$patient->image}}"  alt="Patient Image" height="100px"/>
            @else
            <strong class="text-info">No image</strong>
            @endif
        </div>
        <!-- TODO Work on this-->
        <?php
        /*
          <div class="col-md-4">
          <h4>Patient Schedule</h4>
          if($visits->isEmpty())

          <p class="text-success">
          No previous appointments. <br/>
          No upcoming appointments too.
          </p>
          else
          <p>Previous and upcoming appointments</p>
          <table class="table table-condensed">

          <tbody>
          foreach($visits as $visit)
          <tr>
          <td>{{(new Date($visit->time))->format('dS M')}}</td>
          <td>{{$visit->doctors->full_name}}</td>
          <td>{{config('system.visit_status.'.$visit->status)}}</td>
          <td><a class="btn btn-xs" href="{{route('system.evaluation.nurse_manage',[$patient->patient_id,1])}}">
          <i class="fa fa-deafness"></i> Review</a></td>
          </tr>
          endforeach
          </tbody>
          <thead>
          <tr>
          <th>Date</th>
          <th>Doctor</th>
          <th>Status</th>
          <th>Action</th>
          </tr>
          </thead>
          </table>
          endif
          </div> */
        ?>
        <div class="col-md-6">
            <h4>Checkin details</h4>
            <div class="form-horizontal">
                {!! Form::open(['route'=>['reception.do_check',$patient->id]])!!}
                <input type="hidden" name="patient" value="{{$patient->id}}"/>
                <div class="form-group req {{ $errors->has('destination') ? ' has-error' : '' }}">
                    {!! Form::label('destination', 'Destination',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('destination',get_checkin_destinations(), old('destination'), ['class' => 'form-control']) !!}
                        {!! $errors->first('destination', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <label class="checkbox-inline"><input  type="checkbox" name="to_nurse" value="1" checked/> Also check in patient to Nurse </label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('time') ? ' has-error' : '' }}">
                    {!! Form::label('time', 'Check In time',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        <p class="form-control-static">{{date('dS M y g:i a')}}</p>
                    </div>
                </div>
                <div class="form-group req {{ $errors->has('clinic') ? ' has-error' : '' }}">
                    {!! Form::label('clinic', 'Clinic',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        <p class="form-control-static">{{get_clinic_name()}}</p>
                    </div>
                </div>
                <div class="form-group req {{ $errors->has('purpose') ? ' has-error' : '' }}">
                    {!! Form::label('purpose', 'Purpose of visit',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('purpose',mconfig('reception.options.checkin_purposes'), old('purpose'), ['class' => 'form-control','placeholder'=>'Select Purpose...']) !!}
                        {!! $errors->first('purpose', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('payment_mode') ? ' has-error' : '' }}">
                    {!! Form::label('name', 'Payment Mode',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8" id="mode">
                        <input checked name="payment_mode" type="radio" value="cash" id="cash_option"> Cash
                        @if($patient->insured>0)
                        <input name="payment_mode" type="radio" value="insurance" id="insurance_option"> Insurance
                        @endif
                        {!! $errors->first('payment_mode', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('scheme') ? ' has-error' : '' }}" id="schemes">
                    {!! Form::label('scheme', 'Insurance Scheme',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('scheme',get_patient_insurance_schemes($patient->id), old('scheme'), ['class' => 'form-control', 'placeholder' => 'Choose...']) !!}
                        {!! $errors->first('scheme', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('fees', 'Charge Consultation Fee',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8" id="cfees">
                        <div class="col-md-2">
                            <input type="checkbox" id="show_fees" name="consaltation" value="1">
                        </div>
                    </div>
                </div>

                <div class="form-group" id="fees">
                    {!! Form::label('fees', 'Select Consultation Fee',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8" id="cfees">
                        <select name="precharge" class="form-control">
                            @foreach($precharge as $p)
                            <option value="{{$p->id}}">{{$p->name}} - {{$p->cash_charge}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pull-right">
                    <button type="submit" class="btn btn-success"><i class="fa fa-map-marker"></i> Checkin</button>
                </div>
                {!! Form::close()!!}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#mode input").click(function () {
            show_schemes();
        });
        $("#show_fees").click(function () {
            show_fees();
        });
        function show_schemes() {
            var status = $("#insurance_option").is(':checked');
            if (status)
                $("#schemes").removeClass('hidden');
            else
                $("#schemes").addClass('hidden');
        }
        show_schemes();

        function show_fees() {
            var status = $("#show_fees").is(':checked');
            if (status)
                $("#fees").removeClass('hidden');
            else
                $("#fees").addClass('hidden');
        }
        show_fees();
    });
</script>
@endsection