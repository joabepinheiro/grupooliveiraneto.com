import Sortable from 'sortablejs';

function getLivewireComponentFromBoard() {
    const board = document.querySelector('[data-task-board="1"]');
    if (!board) return null;

    const root = board.closest('[wire\\:id]');
    if (!root) return null;

    const id = root.getAttribute('wire:id');
    return window.Livewire?.find(id) ?? null;
}

function initTaskBoardDnD() {
    const lists = document.querySelectorAll('[data-task-dropzone="1"]');

    console.log('[PainelDeTarefas DnD] init', { dropzones: lists.length });

    if (!lists.length) return;

    lists.forEach((listEl) => {
        // Evita inicializar duas vezes no mesmo elemento após re-render/navegação
        if (listEl._sortableInstance) return;

        listEl._sortableInstance = Sortable.create(listEl, {
            group: 'ocorrencias',
            animation: 150,
            ghostClass: 'opacity-60',
            draggable: '[data-task-card="1"]',

            onEnd: async (evt) => {
                const cardEl = evt.item;
                const fromEl = evt.from;
                const toEl = evt.to;

                const ocorrenciaId = Number(cardEl?.dataset?.ocorrenciaId);
                const fromStatus = fromEl?.dataset?.status;
                const toStatus = toEl?.dataset?.status;

                console.log('[PainelDeTarefas DnD] drop', { ocorrenciaId, fromStatus, toStatus });

                if (!ocorrenciaId || !fromStatus || !toStatus) return;
                if (fromStatus === toStatus) return;

                const lw = getLivewireComponentFromBoard();

                if (!lw) {
                    console.warn('[PainelDeTarefas DnD] Livewire component não encontrado (wire:id). Revertendo.');
                    evt.from.insertBefore(cardEl, evt.from.children[evt.oldIndex] ?? null);
                    return;
                }

                try {
                    await lw.call('moveOcorrencia', ocorrenciaId, toStatus);
                    await lw.call('$refresh');
                } catch (e) {
                    console.error('[PainelDeTarefas DnD] erro ao mover', e);
                    evt.from.insertBefore(cardEl, evt.from.children[evt.oldIndex] ?? null);
                }
            },
        });
    });
}

// Filament navega via Livewire (SPA). Estes hooks cobrem os casos comuns:
document.addEventListener('DOMContentLoaded', initTaskBoardDnD);
document.addEventListener('livewire:load', initTaskBoardDnD);
document.addEventListener('livewire:navigated', initTaskBoardDnD);
