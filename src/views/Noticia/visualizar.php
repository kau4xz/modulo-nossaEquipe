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
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Nova Notícia</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" enctype="multipart/form-data" action="<?= $urlSalvar ?>">
            <?= csrf() ?>

            <div class="space-y-4">
                <div>
                    <label for="titulo" class="block text-sm font-medium text-slate-700">Título <span
                            class="text-rose-600">*</span></label>
                    <input type="text" id="titulo" name="titulo" required minlength="3" maxlength="150"
                        value="<?= old('titulo') ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('titulo') ?>
                </div>

                <div>
                    <label for="descricao" class="block text-sm font-medium text-slate-700">Descrição</label>
                    <textarea id="descricao" name="descricao" maxlength="500" rows="3"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"><?= old('descricao') ?></textarea>
                    <?= old_error('descricao') ?>
                </div>

                <!-- Status Select  -->

                <!-- <label for="status" class="text-sm font-medium text-slate-700">Status</label>
                <label for="status" class="text-sm font-medium text-slate-700">Status</label>

                <select id="status" name="status" class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-500">

                    <option value="0">Inativo </option>

                    <option value="1">Ativo</option>

                </select> -->
                
               
            </div>

           
        </form>
    </div>
</div>
<script src="public/js/preview-img.js" defer />