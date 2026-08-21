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
                    <div class="flex items-center gap-4 w-full">
                        <div class="w-full text-center sm:text-left">
                            <label for="foto" class="block text-sm font-medium text-slate-700">Trocar Foto</label>
                            <label class="justify-center inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                <i class="fa-solid fa-upload"></i>
                                <input type="file" id="foto" name="foto" accept="image/jpeg,png,jpg" data-max-size-kb="2048"
                                    value="<?= $item->getFoto() ?>"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                            </label>


                            <button type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700"
                                data-foto="<?= htmlspecialchars($item->getFoto()) ?>"
                                data-url="<?= Url::path('/nossa-equipe/deletar') ?>"
                                data-campo="id">
                                <i class="fa-solid fa-trash"></i>
                                Deletar
                            </button>
                        </div>
                    </div>
                </div>


                <div class="md:col-span-1">
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
    </div>
</div>