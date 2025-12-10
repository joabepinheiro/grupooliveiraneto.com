<div>
    <?php $entrega = $getRecord();?>

    <div class="fi-size-sm  fi-ta-text-item  fi-ta-text">

        <div class="mb-2">
            <b class="font-semibold">Modelo:</b> {{$entrega->modelo ?? 'Não informado'}} <br/>
            <b class="font-semibold">Cor:</b> {{$entrega->cor ?? 'Não informado'}} <br/>
            <b class="font-semibold">Chassi:</b> {{$entrega->chassi ?? 'Não informado'}} <br/>
        </div>
    </div>
</div>


