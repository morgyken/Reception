<?php
$pre_charged = get_precharged_fees();
?>
<div class="form-group">
    <label for="{{ $settingName }}">{{ $moduleInfo['description'] }}</label>
    <select multiple class="charges" name="{{ $settingName }}[]" id="{{ $settingName }}">
        <option value="none">none</option>
        <?php try { ?>
            @foreach($pre_charged as $p)
            <option value="{{$p->id}}" {{ (isset($data['db_settings'][$settingName]) && isset(array_flip(json_decode($data['db_settings'][$settingName]->value))[$p->id])) ? 'selected' : '' }}>{{$p->name}}</option>>
            @endforeach
        <?php } catch (\Exception $e) { ?>
            @foreach($pre_charged as $p)
            <option value="{{$p->id}}">
                {{$p->name}}
            </option>>
            @endforeach
        <?php } ?>
    </select>
    @if (!empty($moduleInfo['hint']))
    <p class="help-block">{{$moduleInfo['hint']}}</p>
    @endif
</div>
<script>
    $(document).ready(function () {
        $('.charges').selectize({
            delimiter: ',',
            allowEmptyOption: true,
            plugins: ['remove_button']
        });
    });
</script>