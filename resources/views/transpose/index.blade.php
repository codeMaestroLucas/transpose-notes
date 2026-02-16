@extends('layouts.app')

@section('title', 'Transpositor de Notas')

@section('content')
<div x-data="transposeApp()" x-init="init()">

    {{-- Toolbar discreta --}}
    <div class="d-flex justify-content-end gap-2 mb-3 no-print">
        {{-- PDF button --}}
        <button class="btn btn-outline-secondary btn-sm btn-pdf bg-white"
                @click="downloadPdf()"
                :disabled="notes.do.length === 0 || isDownloading"
                title="Baixar PDF">
            <span x-show="!isDownloading">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                </svg>
            </span>
            <span x-show="isDownloading" class="spinner-border spinner-border-sm" role="status"></span>
        </button>
    </div>

    {{-- Cards de tonalidade --}}
    <div class="row g-4 justify-content-center">

        {{-- ══════════════════════════════════════════════
             DÓ — Card unificado: input + resultado
             ══════════════════════════════════════════════ --}}
        <div class="col-lg-4 col-md-6">
            <div class="tone-card tone-card--do h-100">
                <div class="tone-header tone-header--do text-center">
                    <textarea
                        class="song-title-input"
                        placeholder="Nome do Louvor"
                        x-model="songTitle"
                        rows="1"
                        @input="autoResizeTitle($event)"
                        @keydown.tab.prevent="startEditing()"
                        x-init="$nextTick(() => autoResizeTitle({ target: $el }))"
                    ></textarea>
                    <div class="tone-subtitle">{{ $scales['do']->label() }}</div>
                </div>

                {{-- Área unificada: edição ou visualização --}}
                <div class="do-editable-area" @click="startEditing()">

                    {{-- MODO EDIÇÃO: textarea visível --}}
                    <div x-show="isEditing" class="editing-wrapper" x-transition>
                        <textarea
                            x-ref="notesTextarea"
                            class="notes-input-inline"
                            x-model="inputText"
                            @input="onInput()"
                            @blur="stopEditing()"
                        ></textarea>

                        {{-- Loading dentro do modo edição --}}
                        <div x-show="isLoading || isTyping" class="loading-indicator loading-indicator--editing">
                            <div class="spinner-border spinner-border-sm" role="status" style="color: #6c757d;"></div>
                            <span>Processando notas...</span>
                        </div>
                    </div>

                    {{-- MODO VISUALIZAÇÃO: badges agrupados por linha --}}
                    <div x-show="!isEditing" class="notes-display" x-transition>
                        <template x-for="(line, li) in notes.do" :key="'do-line-'+li">
                            <div :class="line.length === 0 ? 'notes-line--empty' : 'notes-line'">
                                <template x-for="(note, ni) in line" :key="'do-'+li+'-'+ni">
                                    <span class="note-badge note-badge--do" x-text="note"></span>
                                </template>
                            </div>
                        </template>
                        <span x-show="notes.do.length === 0 && !inputText" class="text-muted" style="font-size: 1rem; letter-spacing: 2px">
                            Clique aqui para digitar as notas
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             MIb — Card de resultado
             ══════════════════════════════════════════════ --}}
        <div class="col-lg-4 col-md-6">
            <div class="tone-card tone-card--mib h-100">
                <div class="tone-header tone-header--mib text-center">
                    <div class="song-title-display" x-text="songTitle || '\u00A0'">&nbsp;</div>
                    <div class="tone-subtitle">{{ $scales['mib']->label() }}</div>
                </div>
                <div class="notes-display">
                    <template x-for="(line, li) in notes.mib" :key="'mib-line-'+li">
                        <div :class="line.length === 0 ? 'notes-line--empty' : 'notes-line'">
                            <template x-for="(note, ni) in line" :key="'mib-'+li+'-'+ni">
                                <span class="note-badge note-badge--mib" x-text="note"></span>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             SIb — Card de resultado
             ══════════════════════════════════════════════ --}}
        <div class="col-lg-4 col-md-6">
            <div class="tone-card tone-card--sib h-100">
                <div class="tone-header tone-header--sib text-center">
                    <div class="song-title-display" x-text="songTitle || '\u00A0'">&nbsp;</div>
                    <div class="tone-subtitle">{{ $scales['sib']->label() }}</div>
                </div>
                <div class="notes-display">
                    <template x-for="(line, li) in notes.sib" :key="'sib-line-'+li">
                        <div :class="line.length === 0 ? 'notes-line--empty' : 'notes-line'">
                            <template x-for="(note, ni) in line" :key="'sib-'+li+'-'+ni">
                                <span class="note-badge note-badge--sib" x-text="note"></span>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    {{-- Toast de download --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="download-toast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="download-toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
</div>

<script>
function transposeApp() {
    return {
        inputText: '',
        songTitle: '',
        // Agora cada tonalidade é um array de linhas, cada linha é um array de notas
        // Ex: { do: [['Dó','Ré','Mi'], ['Fá','Sol'], ['Lá','Si']], mib: [...], sib: [...] }
        notes: { do: [], mib: [], sib: [] },
        debounceTimer: null,
        isTyping: false,
        isLoading: false,
        isEditing: false,
        darkMode: false,

        init() {
            if (localStorage.getItem('darkMode') === 'true') {
                this.darkMode = true;
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }
        },

        autoResizeTitle(event) {
            const el = event.target;
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        },

        startEditing() {
            this.isEditing = true;
            this.$nextTick(() => {
                this.$refs.notesTextarea.focus();
            });
        },

        stopEditing() {
            setTimeout(() => {
                this.isEditing = false;
            }, 150);
        },

        onInput() {
            this.isTyping = true;
            this.isLoading = false;

            clearTimeout(this.debounceTimer);

            this.debounceTimer = setTimeout(() => {
                this.isTyping = false;
                this.fetchTranspose();
            }, 2500);
        },

        async fetchTranspose() {
            const text = this.inputText.trim();

            if (!text) {
                this.notes = { do: [], mib: [], sib: [] };
                return;
            }

            this.isLoading = true;

            try {
                const response = await axios.post('{{ route("transpose.api") }}', {
                    notes: text,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });

                this.notes = response.data;
            } catch (error) {
                console.error('Erro na transposição:', error);
            } finally {
                this.isLoading = false;
            }
        },

        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            document.documentElement.setAttribute(
                'data-bs-theme',
                this.darkMode ? 'dark' : 'light'
            );
            localStorage.setItem('darkMode', this.darkMode);
        },

        isDownloading: false,

        async downloadPdf() {
            this.isDownloading = true;

            try {
                const response = await axios.post('{{ route("transpose.pdf") }}', {
                    notes: this.inputText,
                    song_title: this.songTitle,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    responseType: 'blob',
                });

                // Extrair nome do arquivo do header Content-Disposition
                const disposition = response.headers['content-disposition'] || '';
                const filenameMatch = disposition.match(/filename="?([^";\n]+)"?/);
                const filename = filenameMatch ? filenameMatch[1] : 'notas-transpostas.pdf';

                // Criar link temporário para download
                const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
                const link = document.createElement('a');
                link.href = url;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);

                // Mostrar toast de sucesso
                this.showToast('PDF baixado com sucesso!');
            } catch (error) {
                console.error('Erro ao baixar PDF:', error);
                this.showToast('Erro ao gerar o PDF. Tente novamente.');
            } finally {
                this.isDownloading = false;
            }
        },

        showToast(message) {
            const toastEl = document.getElementById('download-toast');
            const toastBody = document.getElementById('download-toast-body');
            toastBody.textContent = message;
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        },
    }
}
</script>
@endsection
