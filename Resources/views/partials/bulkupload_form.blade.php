
<?php
/*
 * Collabmed Solutions Ltd
 * Project: iClinic
 *  Author: Samuel Okoth <sodhiambo@collabmed.com>
 */
?>

<div class="row">
    <div class="col-md-12">
        <div class="form-horizontal">
            {!! Form::open(['files'=>true,'route'=>['reception.bulk_upload']])!!}
            <div class="form-group req {{ $errors->has('file') ? ' has-error' : '' }}">
                {!! Form::label('doc', 'File',['class'=>'control-label col-md-4']) !!}
                <div class="col-md-8">
                    <div id="tags">
                        {!! Form::text('path', ['class' => 'path form-control']) !!}
                        {!! $errors->first('path', '<span class="help-block">:message</span>') !!}
                        <span class="img">
                            <input type="hidden" class="imgsrc" name="imagesrc" value="">
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-4 col-md-8">
                    <button type="submit" id="upload" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <hr/>
</div>