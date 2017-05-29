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

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Upload Patient Documents from Folder</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {{ Form::open() }}
        <div class="row">
            <div class="col-md-6">
                <label>Path to Folder</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="path" style="width: 100%">
                    <span class="input-group-btn">
                        <input type="submit" value="Go" class="btn btn-primary btn-flat">
                    </span>
                </div>
                <!-- /input-group -->

            </div>
        </div>
        {{ Form::close() }}
    </div>
    <!-- /.box-body -->
    <div class="box-footer"></div>
</div>
<!-- /.box -->

@endsection
