<div>

    @dd($getState() )
    <div style="--col-span-default: span 1 / span 1; --col-span-lg: span 12 / span 12;" class="col-[--col-span-default] lg:col-[--col-span-lg]">
        <div class="fi-in-entry-wrp">
            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

            <div class="grid gap-y-2">
                <!--[if BLOCK]><![endif]-->            <div class="flex items-center gap-x-3 justify-between ">
                    <!--[if BLOCK]><![endif]-->                    <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">


    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
        Comprovante de pagamento
    </span>


                    </dt>
                    <!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
                </div>
                <!--[if ENDBLOCK]><![endif]-->

                <div class="grid auto-cols-fr gap-y-2">
                    <dd class="">
                        <!--[if BLOCK]><![endif]-->
                        <div class="fi-in-text w-full">
                            <!--[if BLOCK]><![endif]-->
                            <div class="fi-in-placeholder text-sm leading-6 text-gray-800 dark:text-gray-500">
                                @foreach ($getState() ?? [] as $index => $file)

                                    <li>
                                        <a
                                            href="{{ Storage::url($file) }}"
                                            target="_blank"
                                            class="text-primary-600 hover:text-primary-500 inline-flex items-center gap-1 text-sm"
                                        >
                                            Anexo {{$index + 1}}
                                        </a>
                                    </li>
                                @endforeach
                            </div>
                            <!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <!--[if ENDBLOCK]><![endif]-->
                    </dd>

                    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>


</div>
