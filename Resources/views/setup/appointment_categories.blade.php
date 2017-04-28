<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
extract($data);
?>

@extends('layouts.app')
@section('content_title','Appointment Categories')
@section('content_description','Add appointment categories')

@section('content')
<div class="box box-info">
    <div class="form-horizontal">
        {!! Form::open(['route'=>'reception.setup.app_category.save']) !!}
        {!! Form::hidden('id',$model->id)!!}
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }} req">
                    {!! Form::label('name', 'Category Name',['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('name', old('name',$model->name), ['class' => 'form-control', 'placeholder' => 'Category Name']) !!}
                        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>

        </div>
        <div class="box-footer">
            <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Procedure Categories</h3>
    </div>
    <div class="box-body">
        <table class="table table-responsive table-condensed table-striped">
            <tbody>
                @foreach($categories as $category)
                <tr id="row_id{{$category->id}}" >
                    <td>{{$category->id}}</td>
                    <td>{{$category->name}}</td>
                    <td>
                        <a class="btn btn-primary btn-xs"
                           href="{{route('reception.setup.app_category',$category->id)}}" >
                            <i class="fa fa-pencil-square-o"></i> Edit
                        </a> |
                        <button class="btn btn-danger btn-xs delete" value="{{$category->id}}">
                            <i class="fa fa-trash-o"></i> Delete</button></td>
                </tr>
                @endforeach
            </tbody>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade"  id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete appointment category?</h4>
                </div>
                <div class="modal-body">
                    <p>Do you want to delete this category?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="delete" >Yes, I am sure</button>
                    <button class="btn btn-primary" data-dismiss="modal">No, ignore</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
@todo Change route for deleting category
-->
<script type="text/javascript">
    var to_delete = null;
    $('.delete').click(function () {
        to_delete = $(this).val();
        $('#myModal').modal('show');
    });
    $('#delete').click(function () {
        if (!to_delete) {
            return;
        }
        id = to_delete;
        $.ajax({
            type: 'GET',
            url: "route('ajax.delete_schedule_cat')",
            data: {'id': id},
            success: function () {
                $("#row_id" + id).remove();
            },
            error: function () {
                alert('Could not delete category. Please contact admin');
            }
        });
        $("#myModal").modal('hide');
    });
</script>
@endsection