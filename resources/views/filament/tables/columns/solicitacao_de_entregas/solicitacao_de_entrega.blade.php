<div>
    <?php $solicitacao_de_entregas = $getRecord();?>

    <div class="fi-size-sm  fi-ta-text-item  fi-ta-text">

        <div class="mb-2">
            <b class="font-semibold">Proposta:</b> {{$solicitacao_de_entregas->proposta ?? 'Não informado'}} <br/>
            <b class="font-semibold">Tipo:</b> {{$solicitacao_de_entregas->tipo_venda ?? 'Não informado'}} <br/>

        </div>

        <div class="mb-2">
            <b class="font-semibold">Cliente:</b> {{$solicitacao_de_entregas->cliente ?? 'Não informado'}} <br/>
        </div>

        <div class="mb-2">
            <b class="font-semibold">Vendedor:</b> {{$solicitacao_de_entregas->vendedor?->name ?? 'Não informado'}} <br/>
        </div>

    </div>
</div>


