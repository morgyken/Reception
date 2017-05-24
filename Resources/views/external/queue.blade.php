<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 *  Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>
@extends('layouts.app')
@section('content_title','Review Patient Visits')
@section('content_description','Review previous visits')

@section('content')

<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">External Orders</h3>
    </div>
    <div class="box-body">
        @if($data['orders'])
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Patient</th>
                    <th>Mobile</th>
                    <th>Institution</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['orders'] as $order)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>00{{$order->id}}</td>
                    <td>{{$order->patient->fullname}}</td>
                    <td>{{$order->patient->mobile}}</td>
                    <td>{{$order->from->name}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>
                        <a href="{{route('reception.external_checkin',$order->id)}}" class="btn btn-xs">
                            <i class="fa fa-sign-in"></i> Check in</a>

                        <a href="" class="btn btn-xs btn-primary">
                            <i class="fa fa-eye"></i> View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>External Order Queue is empty.</p>
        @endif
    </div>
    <div class="box-footer">
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        try {
            $('table').DataTable();
        } catch (e) {
        }
    });
</script>
@endsection