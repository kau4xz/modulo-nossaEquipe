<?php

/**
 * @var string  $voltarUrl
 * @var string  $urlSalvar
 * @var \Src\App\Models\Galeria $item
 */

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
                    <label for="titulo" class="block text-sm font-medium text-slate-700">Título <span class="text-rose-600">*</span></label>
                    <input type="text" id="titulo" name="titulo" required minlength="3" maxlength="150"
                        value="<?= old('titulo', htmlspecialchars($item->getTitulo())) ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('titulo') ?>
                </div>

                <div>
                    <label for="legenda" class="block text-sm font-medium text-slate-700">Legenda</label>
                    <textarea id="legenda" name="legenda" maxlength="500" rows="3"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"
                    ><?= old('legenda', htmlspecialchars($item->getLegenda() ?? '')) ?></textarea>
                    <?= old_error('legenda') ?>
                </div>
            </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-slate-700">Tipo</label>
                    <textarea id="tipo" name="tipo" maxlength="500" rows="3"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"
                    ><?= old('tipo', htmlspecialchars($item->getTipo() ?? '')) ?></textarea>
                    <?= old_error('tipo') ?>
                </div>

                <div>
                    <label for="imagem" class="block text-sm font-medium text-slate-700">Enviar Imagem</label>
                    <input type="file" id='imagem' name='imagem' accept="image/jpeg, image/png" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
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
    </div>
</div>
