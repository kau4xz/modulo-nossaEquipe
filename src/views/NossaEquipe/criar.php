<?php

/**
 * @var string $voltarUrl
 * @var string $urlSalvar
 */

?>

<div class="space-y-6">
    <div>
        <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Novo Registro</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6  h-24">
        <form method="POST" action="<?= $urlSalvar ?>" enctype="multipart/form-data">
            <?= csrf() ?>

            <div class="space-y-4">
                <div>
                    <label for="nome" class="block text-sm font-medium text-slate-700">Nome <span class="text-rose-600">*</span></label>
                    <input type="text" id="nome" name="nome" required minlength="3" maxlength="150" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
                        value="<?= old('nome') ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('nome') ?>
                </div>

                <div>
                    <label for="cargo" class="block text-sm font-medium text-slate-700">Cargo<span class="text-rose-600">*</span></label>
                    <input type="text " id="cargo" name="cargo" required minlength="3" maxlength="150" oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"><?= old('cargo') ?></textarea>
                    <?= old_error('cargo') ?>
                </div>

                <div class="flex items-center gap-3">
                    <label for="foto" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <i class="fa-solid fa-upload"></i>
                        <span>Escolher arquivo</span>

                        <input type="file" id="foto" name="foto" onchange="previewFoto()" accept="image/jpeg, image/png, image/jpg" data-max-size-kb="2048" class="hidden">
                        <img id="img-preview"
                            class="h-12 w-12 rounded-xl object-cover border-5 fa-solid fa-user">
                    </label>



                </div>
                <div class="flex flex-col m-3px mt-2 text-xs text-slate-500">
                    <span class="ml-2px"> <i class="fa-solid fa-circle-info"></i> Extensões aceitas: .png, .jpg, .jpeg </span>
                    <span> <i class="fa-solid fa-triangle-exclamation"></i> Tamanho máximo: 2 Mb </span>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="<?= $voltarUrl ?>"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    <i class="fa-solid fa-floppy-disk"></i> Salvar
                </button>
            </div>
        </form>


        <script>
            function previewFoto() {
                var foto = document.querySelector('input[name=foto]').files[0];
                var preview = document.getElementById('img-preview');
                var reader = new FileReader();

                reader.onloadend = function() {
                    preview.src = reader.result;

                    preview.classList.remove('hidden');
                }

                if (foto) {
                    reader.readAsDataURL(foto);
                } else {
                    preview.src = "";

                    preview.classList.add('hidden');
                }
            }
        </script>
    </div>
</div>