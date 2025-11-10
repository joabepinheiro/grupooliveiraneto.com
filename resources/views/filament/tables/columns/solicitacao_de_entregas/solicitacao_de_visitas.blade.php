<div>
    <?php $record = $getRecord();?>

    @if($record)
        <div class="fi-size-sm  fi-ta-text-item  fi-ta-text">
            <b class="font-medium">Modelo:</b> {{$record->modelo ?? 'Não infomado'}}<br/>
            <b class="font-medium">Cor:</b> {{$record->cor ?? 'Não infomado'}}<br/>
            <b class="font-medium">Chassi:</b> {{$record->chassi ?? 'Não infomado'}}<br/>
        </div>
    @endif
</div>


