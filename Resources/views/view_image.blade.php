<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
extract($data);
?>
@extends('layouts.app')
@section('content_title','Patient Document (Image)')
@section('content_description','View patient documents (Image)')
@section('content')

<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"> Patient Name: {{$image->patients->fullname}} </h3>
    </div>
    <div class="box-body">
        <img src="data:{{$image->mime}};{{$image->document}}"  alt="Patient Image" style="width: 100%"/>
    </div>
</div>
@endsection
