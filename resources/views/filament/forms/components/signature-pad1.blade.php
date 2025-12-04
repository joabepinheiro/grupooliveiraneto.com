<?php
/** @var App\Filament\Forms\Components\SignaturePad $field */
?>

<div
    x-data="signaturePadComponent({ state: @entangle($getStatePath()) })"
    x-init="init()"
    class="w-full fi-fo-field"
    style="padding-top: 30px;"
>

    <div class="fi-fo-field-label-content" style="padding: 10px 0 {{$field->getExtraAttributeBag()}}">
        {{$field->getLabel()}}
    </div>

    <div id="signature-pad" class="signature-pad w-full border rounded p-2" style="padding-bottom: 30px; width: 100%;">
        <div id="canvas-wrapper" class="signature-pad--body w-full" style="width: 100%; height: 200px; position: relative; border: 1px solid #ddd">
            <canvas style="width: 102%; height: 200px;"></canvas>
        </div>

        <div class="signature-pad--footer mt-4" style="margin-top: 20px">
            <div class="signature-pad--actions grid grid-cols-2 gap-2" @click.prevent="handleAction($event)">
                <div class="space-y-2">
                    <button type="button" class="fi-btn clear" data-action="clear">Limpar</button>
                    <button type="button" class="fi-btn" data-action="undo">Desfazer</button>
                    <button type="button" class="fi-btn" data-action="redo">Refazer</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" x-model="state" />
</div>

@once
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>

    <script>
        function signaturePadComponent({ state }) {
            return {
                signaturePad: null,
                state: state,
                undoStack: [],
                ratio: 1,

                init() {
                    const canvasWrapper = this.$el.querySelector("#canvas-wrapper");
                    const canvas = this.$el.querySelector("canvas");
                    this.ratio = Math.max(window.devicePixelRatio || 1, 1);

                    this.signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(255,255,255)',
                    });

                    const resizeCanvas = this.resizeCanvas.bind(this, canvasWrapper, canvas);

                    window.addEventListener("resize", resizeCanvas);
                    resizeCanvas();

                    if (this.state) {
                        this.signaturePad.fromDataURL(this.state);
                    }

                    this.signaturePad.addEventListener("endStroke", () => {
                        this.undoStack = [];
                        this.state = this.signaturePad.toDataURL();
                    });
                },

                resizeCanvas(canvasWrapper, canvas) {
                    // Salva a assinatura atual como imagem
                    const dataURL = this.signaturePad.toDataURL();

                    const width = canvasWrapper.offsetWidth;
                    const height = canvasWrapper.offsetHeight;

                    canvas.width = width * this.ratio;
                    canvas.height = height * this.ratio;
                    canvas.getContext("2d").scale(this.ratio, this.ratio);

                    // Restaura a assinatura (sem limpar ao redimensionar)
                    if (!this.signaturePad.isEmpty()) {
                        this.signaturePad.fromDataURL(dataURL);
                    }
                },

                handleAction(event) {
                    const action = event.target.closest('[data-action]')?.dataset.action;
                    if (!action) return;

                    const sp = this.signaturePad;

                    switch (action) {
                        case 'clear':
                            sp.clear();
                            break;
                        case 'undo':
                            this.undo();
                            break;
                        case 'redo':
                            this.redo();
                            break;
                    }

                    if (['clear', 'undo', 'redo'].includes(action)) {
                        this.state = sp.toDataURL();
                    }
                },

                undo() {
                    const data = this.signaturePad.toData();
                    if (data.length) {
                        this.undoStack.push(data.pop());
                        this.signaturePad.fromData(data);
                    }
                },

                redo() {
                    if (this.undoStack.length) {
                        const data = this.signaturePad.toData();
                        data.push(this.undoStack.pop());
                        this.signaturePad.fromData(data);
                    }
                },

                download(dataURL, filename) {
                    const a = document.createElement("a");
                    a.href = dataURL;
                    a.download = filename;
                    a.click();
                }
            };
        }
    </script>
@endonce
