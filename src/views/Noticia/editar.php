<?php

/**
 * @var string  $voltarUrl
 * @var string  $urlSalvar
 * @var \Src\App\Models\Noticia $item
 */

?>


<div class="space-y-6">
    <div>
        <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Editar Notícia</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" enctype="multipart/form-data" action="<?= $urlSalvar ?>">
            <?= csrf() ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($item->getId()) ?>">

            <div class="space-y-4">
                <div>
                    <label for="titulo" class="block text-sm font-medium text-slate-700">Título <span
                            class="text-rose-600">*</span></label>
                    <input type="text" id="titulo" name="titulo" required minlength="3" maxlength="150"
                        value="<?=  htmlspecialchars($item->getTitulo()) ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('titulo') ?>
                </div>

                <div>
                    <label for="descricao" class="block text-sm font-medium text-slate-700">Descrição</label>
                    <textarea id="descricao" name="descricao" maxlength="500" rows="3"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"><?= htmlspecialchars($item->getDescricao()) ?? '' ?></textarea>
                    <?= old_error('descricao') ?>
                </div>
                <label class="block mb-2 text-sm font-medium text-slate-700" for="imagem">
                    Imagem
                </label>

                <!-- TODO: Estilizar melhor -->
                <input
                    class="file:mr-4 file:rounded-full file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-600 dark:file:text-violet-100 dark:hover:file:bg-violet-500"
                    id="imagem" type="file" accept="image/*" name="imagem">
                <?php if($item->getImagem()): ?>
                <img src="public<?= htmlspecialchars($item->getImagem()) ?>" alt="Pré-visualização"
                    class="max-h-32 max-w-32" id="preview">
                <?php endif; ?>
                <p class="mt-1 text-sm text-slate-500" id="file_input_help">PNG, JPG ou JPEG (Tamanho máximo 2 MB).</p>
            </div>


            <div class="mt-6 flex justify-end gap-3">
                <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium
                text-slate-700 hover:bg-slate-50">
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
<script src="public/js/preview-img.js" defer />