<div>
    <?php $entrega = $getRecord();?>

    <div class="fi-size-sm  fi-ta-text-item  fi-ta-text">

        <div class="mb-2">
            <b class="font-semibold">Proposta:</b> {{$entrega->proposta ?? 'Não informado'}} <br/>
            <b class="font-semibold">Cliente:</b> {{$entrega->cliente ?? 'Não informado'}} <br/>
        </div>

        <div class="mb-2">
            <b class="font-semibold">Vendedor:</b> {{$entrega->vendedor?->name ?? 'Não informado'}} <br/>
        </div>

    </div>
</div>


