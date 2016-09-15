<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>
@extends('layouts.app')
@section('content_title','Patient Documents')
@section('content_description','View patient documents')
@section('content')

<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"> Patient Name: {{$data['patient']->fullname}} </h3>
    </div>
    <div class="box-body">
        @include('reception::partials.doc_list')
    </div>
</div>
@endsection
