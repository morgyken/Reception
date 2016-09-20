<?php
/*
 * =============================================================================
 *
 * Collabmed Solutions Ltd
 * Project: Collabmed Health Platform
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 *
 * =============================================================================
 */
?>

<table class="table table-striped">
    <tbody>
        <?php /* foreach($data['appointments'] as $appointment)
          <tr id="row_id{{$appointment->visit_id}}">
          @if($appointment->is_guest)
          <td>{{$appointment->guest}}</td>
          @else
          <td>{{$appointment->patients->full_name}}</td>
          @endif
          <td>{{(new Date($appointment->time))->format('dS M')}}</td>
          <td>{{(new Date($appointment->time))->format('g:ia')}}</td>
          <td>{{$appointment->doctors->full_name}}</td>
          <td>{{config('system.visit_status.'.$appointment->status)}}</td>
          <td>
          <!-- TODO Create chekin for those who show up on that day
          <a  class="btn btn-primary btn-xs" title="Check In"
          href="{{route('reception.checkin',[$appointment->patient,$appointment->id])}}">
          <i class="fa fa-map-marker"></i></a>-->
          <button value='{{$appointment->id}}' class="btn btn-primary btn-xs res" title="Reschedule">
          <i class="fa fa-clock-o"></i></button>
          <button value='{{$appointment->id}}' class="btn btn-danger btn-xs delete" title="Cancel">
          <i class="fa fa-close"></i></button>
          </td>
          </tr>
          endforeach */ ?>
    </tbody>
    <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Doctor</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
<script type = "text/javascript">
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
            type: 'DELETE',
            url: CANCEL_URL,
            data: {'id': id},
            success: function () {
                $("#row_id" + id).remove();
                fetch_table();
            },
            error: function (data) {
                console.log(data);
            }
        });
        $("#myModal").modal('hide');
    });
    $('.res').click(function () {
        var for_reschedule = $(this).val();
        $.ajax({
            url: RESCHEDULE_URL,
            data: {'id': for_reschedule},
            type: "GET",
            success: function (data) {
                $('#appointments').html($(data)).fadeIn();
                if (tables)
                {
                    tables.destroy();
                }
            }
        });
    });
</script>