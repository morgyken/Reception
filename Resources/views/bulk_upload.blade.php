<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Bravo Kiptoo bkiptoo@collabmed.net
 */
extract($data);
?>
@extends('layouts.app')
@section('content_title','Bulk Patient Document Upload')
@section('content_description','Upload many patient documents at once')

@section('content')
<div class="box box-info">
    <div class="box-body">

        <table class="table table-condensed table-striped">
            <tbody>
                <tr>
                    <td><a href="" class="btn btn-primary btn-xs">Bulk Upload</a></td>
                </tr>
            </tbody>
        </table>

        @include('reception::partials.bulkupload_form')

    </div>
</div>
@endsection
