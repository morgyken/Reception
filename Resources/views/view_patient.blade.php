<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
$patient = $data['patient'];
$docs = $data['docs'];
?>
@extends('layouts.app')
@section('content_title','View Patient')
@section('content_description','View detailed information for  patient')

@section('content')
<div class="box box-info">
    <div class="row">
        <div class="col-md-12">
            <h5>Basic Information</h5>
            <div class="col-md-6">
                <dl class="dl-horizontal">
                    <!-- <dt>Patient ID:</dt><dd>{{$patient->patient_id}}</dd>-->
                    <dt>Name:</dt><dd>{{$patient->full_name}}</dd>
                    <dt>Date of Birth:</dt><dd>{{(new Date($patient->dob))->format('m/d/y')}}
                        <strong>({{(new Date($patient->dob))->age}} years old)</strong></dd>
                    <dt>Sex:</dt><dd>{{$patient->sex}}</dd>
                    <dt>Mobile Number:</dt><dd>{{$patient->mobile}}</dd>
                    <dt>ID number:</dt><dd>{{$patient->id_no}}</dd>
                    <dt>Email:</dt><dd>{{$patient->email}}</dd>
                    <dt>Telephone:</dt><dd>{{$patient->telephone}}</dd>
                    <dt>Alternative Contact:</dt><dd>{{$patient->alt_number}}</dd>
                    <dt>Address</dt><dd>{{$patient->address}} -{{$patient->post_code}}</dd>
                    <dt>Town:</dt><dd>{{$patient->town}}</dd>
                </dl>
                <h5>Next of Kin</h5>
                <dl class="dl-horizontal">
                    <dt>Name:</dt><dd>{{$patient->nok->full_name}}</dd>
                    <dt>Relationship:</dt><dd>{{mconfig('reception.options.relationship.'.$patient->nok->relationship)}}</dd>
                    <dt>Mobile:</dt><dd>{{$patient->nok->mobile}}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                @if(!empty($patient->image))
                <img src="{{$patient->image}}"  alt="Patient Image" height="300px"/>
                @else
                <img src="{{m_asset('reception:img/ph.png')}}" alt="Patient Image" height="300px"/>
                <!--TODO enable image to be captured here-->
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('reception::partials.doc_list')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <h5>Insurance Schemes</h5>
                @if($patient->schemes->isEmpty())
                <p class="bg-warning text-warning"><i class="fa fa-warning"></i>
                    Patient not enrolled to any scheme</p>
                @else
                <table class="table table-striped table-responsive">
                    <tbody>
                        @foreach($patient->schemes as $scheme)
                        <tr>
                            <td>{{$scheme->schemes->companies->name}}</td>
                            <td>{{$scheme->schemes->name}}</td>
                            <td>{{$scheme->policy_number}}</td>
                            <td>{{$scheme->principal}}</td>
                            <td>{{(new Date($scheme->dob))->format('m/d/y')}}</td>
                            <td>{{config('system.relationship.'.$scheme->relationship)}}</td>
                            <td><button class="btn btn-xs btn-success">
                                    <i class="fa fa-minus-circle"></i>
                                    <!-- TODO work this magic here -->
                                </button></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Scheme</th>
                            <th>Policy No.</th>
                            <th>Principal</th>
                            <th>Principal DOB</th>
                            <th>Relationship</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection