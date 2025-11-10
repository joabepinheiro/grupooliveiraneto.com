<div>
    <?php $state = $getState();?>
    <div class="fi-size-sm  fi-ta-text-item  fi-ta-text">
        @if($state)
            {{$state->format('d/m/y')}}  {{$state->format('H:i')}} <br/>
            ({{$state->translatedFormat('l')}}) <br/>
        @else
            Não informado
        @endif
    </div>
</div>


