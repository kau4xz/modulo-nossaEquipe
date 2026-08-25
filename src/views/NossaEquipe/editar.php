<?php

/**
 * @var string  $voltarUrl
 * @var string  $urlSalvar
 * @var string  $deletarUrl
 * @var \Src\App\Models\Integrante $item
 */

use Src\App\Utils\Url;

?>

<div class="space-y-6">
    <div>
        <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Editar Registro</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" action="<?= $urlSalvar ?>" enctype="multipart/form-data">
            <?= csrf() ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($item->getId()) ?>">

            <div class="space-y-4">
                <div>
                    <label for="nome" class="block text-sm font-medium text-slate-700">Nome <span class="text-rose-600">*</span></label>
                    <input type="text" id="nome" name="nome" required minlength="3" maxlength="150"
                        value="<?= $item->getNome() ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('nome') ?>
                </div>

                <div>
                    <label for="cargo" class="block text-sm font-medium text-slate-700">Cargo<span class="text-rose-600">*</span></label>
                    <input type="text" id="cargo" name="cargo" required minlength="3" maxlength="150"
                        value="<?= $item->getCargo() ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('cargo') ?>
                </div>

                <div class="mb-4">
                    <div class="flex flex-col gap-2 w-full text-center sm:text-left">
                        <label class="block text-sm font-medium text-slate-700">Trocar Foto</label>

                        <div class="flex items-center gap-3">
                            
                            <label for="foto" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                <i class="fa-solid fa-upload"></i>
                                <span>Escolher arquivo</span>
                                
                                <input type="file" id="foto" name="foto" onchange="previewFoto()" accept="image/jpeg, image/png, image/jpg" data-max-size-kb="2048" class="hidden">
                            </label>

                            <?php if ($item->getFoto()) : ?>
                                <label for="deletarFoto"
                                    class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    <input type="checkbox" id="deletarFoto" name="deletar_foto" value="1"
                                        class="h-4 w-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    <span>Remover foto atual</span>
                                </label>
                            <?php endif; ?>

                            <img id="img-preview"
                                <?php if ($item->getFoto()) : ?>
                                    src="<?= htmlspecialchars(Url::path($item->getFoto())) ?>"
                                <?php endif; ?>
                                alt="Foto de <?= htmlspecialchars($item->getNome()) ?>"
                                class="h-12 w-12 rounded-xl object-cover <?= $item->getFoto() ? '' : 'hidden' ?>">


                    </div>
                </div>

                <div class="md:col-span-1 bt-5px">
                    <label for="status" class="text-sm font-medium text-slate-700">Status</label>
                    <select id="status" name="status" class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        <option value="0" <?= $item ? '' : 'selected' ?>>Não Publicado</option>
                        <option value="1" <?= $item ? 'selected' : '' ?>>Publicado</option>
                    </select>

                    <?php $statusErr = old_error('status'); ?>
                    <?php if ($statusErr !== '') { ?>
                        <p class="mt-2 text-sm font-medium text-rose-700" id="status-error"><?php echo htmlspecialchars($statusErr); ?></p>
                    <?php } ?>
                </div>



            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="<?= $voltarUrl ?>"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    <i class="fa-solid fa-floppy-disk"></i> Atualizar
                </button>
            </div>
        </form>

        <script>
            var inputDeletarFoto = document.getElementById('deletarFoto');

            function previewFoto() {
                var foto = document.querySelector('input[name=foto]').files[0];
                var preview = document.getElementById('img-preview');
                var reader = new FileReader();

                reader.onloadend = function () {
                    preview.src = reader.result;
                    preview.classList.remove('hidden');
                };

                if (foto) {
                    // Arquivo novo tem precedencia sobre a remocao, igual ao Service.
                    if (inputDeletarFoto) {
                        inputDeletarFoto.checked = false;
                    }
                    reader.readAsDataURL(foto);
                } else {
                    preview.removeAttribute('src');
                    preview.classList.add('hidden');
                }
            }

            if (inputDeletarFoto) {
                inputDeletarFoto.addEventListener('change', function () {
                    var preview = document.getElementById('img-preview');
                    var campoArquivo = document.querySelector('input[name=foto]');

                    if (this.checked) {
                        campoArquivo.value = '';
                        preview.removeAttribute('src');
                        preview.classList.add('hidden');
                    } else if (campoArquivo.files.length === 0) {
                        preview.classList.remove('hidden');
                        preview.src = '<?= htmlspecialchars(Url::path($item->getFoto() ?? ''), ENT_QUOTES) ?>';
                    }
                });
            }
        </script>
    </div>
</div>