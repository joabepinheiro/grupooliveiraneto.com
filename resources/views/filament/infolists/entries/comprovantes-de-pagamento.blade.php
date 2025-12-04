<div>
    <div class="fi-in-entry">

        <div class="fi-in-entry-label-col">

            <div class="fi-in-entry-label-ctn">

                <dt class="fi-in-entry-label">
                    Comprovante de pagamento
                </dt>

            </div>

        </div>

        <div class="fi-in-entry-content-col">

            <dd class="fi-in-entry-content-ctn">

                <div class="fi-in-entry-content">

                    <div class="fi-size-sm  fi-in-text-item  fi-wrapped  fi-in-text">

                       <ul>
                           @foreach($getRecord()->getMedia("comprovantes_de_pagamento") as $file)

                               <li>
                                   <a href="{{asset($file->original_url)}}" target="_blank">
                                       {{$file->file_name}}
                                   </a>
                               </li>
                           @endforeach
                       </ul>
                    </div>

                </div>

            </dd>

        </div>
    </div>

</div>
