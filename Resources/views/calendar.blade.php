<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
$calendar = $data['calendar'];
?>

@extends('layouts.app')
@section('content_title','Calendar')
@section('content_description','Patient Calendar')

@section('content')
<div class="box box-info">
    <div class="box-body">
        {!! $calendar->calendar() !!}
        {!! $calendar->script() !!}
    </div>
</div>
<script type="text/javascript">
// $(document).ready(function () {
    function changeView(view, date) {
        if (view.name !== 'month')
            return;
        $('#calendar-sam').fullCalendar('changeView', 'agendaDay');
        $('#calendar-sam').fullCalendar('gotoDate', date);
    }
//});
</script>
@endsection