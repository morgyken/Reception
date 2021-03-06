<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
extract($data);
?>
@extends('layouts.app')
@section('content_title','Patient Checkins')
@section('content_description','View checked in patients')

@section('content')
    <div class="box box-info">
        <div class="box-body">
            <h5>Showing for the last 24 hours</h5>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <tbody>
                @foreach($visits as $visit)
                    <tr id="row_id{{$visit->id}}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$visit->patients->full_name}}</td>
                        <td>{{$visit->created_at->format('dS M g:i a')}}</td>
                        <td>{{$visit->visit_destination}}</td>
                        <td>{{$visit->place}}</td>
                        <td>{{mconfig('reception.options.checkin_purposes.'.$visit->purpose,'N/A')}}</td>
                        <td>
                            <button value='{{$visit->id}}' class="btn btn-danger btn-xs delete">
                                <i class="fa fa-ban"></i> Cancel Check In
                            </button>
                            <a href="{{route('reception.checkin',$visit->patients->id)}}" class="btn btn-xs btn-primary destination">
                                <i class="fa fa-sign-in"></i> Change Destination</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Date / Time</th>
                    <th>Department</th>
                    <th><i class="fa fa-user-md"></i>Destination</th>
                    <th>Purpose</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cancel Checkin?</h4>
                    </div>
                    <div class="modal-body">
                        <p>Do you want to cancel this checkin.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" id="delete">Yes</button>
                        <button class="btn btn-primary" data-dismiss="modal">Ignore</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="destination" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Change Destination?</h4>
                    </div>
                    <div class="modal-body">
                        <p>Please select new destination below.</p>
                        {!! Form::select('destination',get_checkin_destinations(),null, ['class' => 'form-control','id'=>'list']) !!}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="changeDest">Save</button>
                        <button class="btn btn-default" data-dismiss="modal">Ignore</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var to_delete = null;
            var CANCEL_URL = "{{route('api.reception.cancel_checkin')}}";
            var CHANGE_DEST_URL = "{{route('api.reception.change_destination')}}";

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
                    type: 'DELETE',
                    data: {'id': id},
                    url: CANCEL_URL,
                    success: function () {
                        $("#row_id" + id).remove();
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
                $("#myModal").modal('hide');
            });

            $('.destination').click(function () {
                to_delete = $(this).val();
                $('#destination').modal('show');
            });

            $('#changeDest').click(function () {
                if (!to_delete) {
                    return;
                }
                var selected = $('#list').val();
                id = to_delete;
                $.ajax({
                    type: 'GET',
                    url: CHANGE_DEST_URL,
                    data: {'new_dest': selected, 'id': id},
                    success: function () {
                        // $("#row_id" + id).remove();
                        alert('Successful! Destination changed.');
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
                $("#destination").modal('hide');
            });
            $('table').DataTable({
                "aaSorting": []
            });
        });
    </script>
@endsection