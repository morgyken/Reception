<?php /*
 * =============================================================================
 *
 * Collabmed Solutions Ltd
 * Project: Collabmed Health Platform
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 *
 * =============================================================================
 */ ?>
<!-- Modal -->
<div class="modal fade" id="camera" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Capture Image</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="camera">
                            <video id="video">Video stream not available.</video>
                            <button id="startbutton">
                                <i class="fa fa-video-camera"></i> Capture</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <canvas id="canvas">
                        </canvas>
                        <div class="output">
                            <img id="thephoto" alt="The screen capture will appear in this box.">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="goodPhoto">
                    <i class="fa fa-camera"></i> Select</button>
                <button type="button" class="btn btn-default" id="cancelPlayback">
                    <i class="fa fa-close"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
<script src="{{url('/js/webcam.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#webcam").click(function () {
        $("#camera").modal({backdrop: "static"});
        $("#goodPhoto").hide();
        startup();
    });
    $("#goodPhoto").click(function () {
        $("#camera").modal('toggle');
        close_webcam();
    });
    $("#cancelPlayback").click(function () {
        $("#camera").modal('toggle');
        close_webcam();
    });
});
</script>
<style>
    #video {
        border: 1px solid black;
        box-shadow: 2px 2px 3px black;
        width:320px;
        height:240px;
    }

    #thephoto {
        border: 1px solid black;
        box-shadow: 2px 2px 3px black;
        width:320px;
        height:240px;
    }

    #canvas {
        display:none;
    }

    .camera {
        width: 340px;
        display:inline-block;
    }

    .output {
        width: 340px;
        display:inline-block;
    }

    #startbutton {
        display:block;
        position:relative;
        margin-left:auto;
        margin-right:auto;
        bottom:32px;
        background-color: rgba(0, 150, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0px 0px 1px 2px rgba(0, 0, 0, 0.2);
        font-size: 14px;
        font-family: "Lucida Grande", "Arial", sans-serif;
        color: rgba(255, 255, 255, 1.0);
    }
    .contentarea {
        font-size: 16px;
        font-family: "Lucida Grande", "Arial", sans-serif;
        width: 760px;
    }
</style>
