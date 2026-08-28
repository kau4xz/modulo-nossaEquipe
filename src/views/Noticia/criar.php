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
                <!-- ------------------------------- -->
                <!-- Input Titulo  -->
                <!-- ------------------------------- -->
                <div>
                    <label for="titulo" class="block text-sm font-medium text-slate-700">Título <span
                            class="text-rose-600">*</span></label>
                    <input type="text" id="titulo" name="titulo" required minlength="3" maxlength="150"
                        value="<?= old('titulo') ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('titulo') ?>
                </div>

                <!-- ------------------------------- -->
                <!-- Input Descricao  -->
                <!-- ------------------------------- -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-slate-700">Descrição</label>
                    <textarea id="descricao" name="descricao" maxlength="3000" rows="3"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"><?= old('descricao') ?></textarea>
                    <?= old_error('descricao') ?>
                </div>

                <!-- ------------------------------- -->
                <!-- Status Select  -->
                <!-- ------------------------------- -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                    <select id="status" name="status" class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
                 <?php $statusErr = old_error('status'); ?>
                    <?php if ($statusErr !== '') { ?>
                        <p class="mt-2 text-sm font-medium text-rose-700" id="status-error"><?php echo htmlspecialchars($statusErr); ?></p>
                    <?php } ?>
                
                <!-- ------------------------------- -->
                <!-- Input file da imagem            -->
                <!-- ------------------------------- -->
                <div class="space-y-4">
                    <span class="block text-sm font-medium text-slate-700">Imagem da Capa</span>

                    <!-- Container do preview da NOVA imagem (Oculto por padrão) -->
                    <!-- MODIFICADO: flex-col no mobile, sm:flex-row nas telas maiores, w-full -->
                    <div class="hidden flex-col sm:flex-row items-center sm:items-start gap-4 rounded-xl border border-blue-100 bg-blue-50/50 p-4 transition-all w-full" id="box_preview_novo">
                        <!-- MODIFICADO: w-full no mobile, sm:w-[300px] em telas maiores, shrink-0 para não amassar -->
                        <img id="preview" src="" alt="Pré-visualização" class="h-[300px] w-full sm:w-[200px] shrink-0 rounded-xl object-cover border border-slate-200 shadow-sm">
                        <div class="w-full text-center sm:text-left">
                            <p class="text-sm font-semibold text-blue-800">Nova imagem selecionada</p>
                            <!-- MODIFICADO: break-all para o nome do arquivo não estourar a div -->
                            <p class="text-xs text-blue-600 mt-0.5 break-all" id="nome_arquivo_selecionado"></p>
                        </div>
                    </div>

                    <!-- Botões: Selecionar e Excluir -->
                    <!-- MODIFICADO: flex-wrap para evitar sobreposição em telas muito finas -->
                    <div class="flex flex-wrap items-center gap-3">
                        <label for="imagem" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-upload"></i> 
                            <span id="label_texto_btn">Selecionar arquivo</span>
                        </label>
                        
                        <input id="imagem" type="file" accept="image/*" name="imagem" class="hidden">
                        
                        <!-- O Botão global de remover -->
                        <button type="button" id="btn_remover_imagem" class="hidden items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-medium text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                            <i class="fa-solid fa-trash"></i> Excluir imagem
                        </button>
                    </div>
                     <?php $imagemErr = old_error('imagem'); ?>
                    <?php if ($imagemErr !== '') { ?>
                        <p class="mt-2 text-sm font-medium text-rose-700" id="imagem-error"><?php echo htmlspecialchars($imagemErr); ?></p>
                    <?php } ?>
                    <p class="mt-1 text-xs text-slate-500">PNG, JPG ou JPEG (Tamanho máximo 2 MB).</p>
                </div>
            </div>

            <!-- ------------------------------- -->
            <!-- Botões finais  -->
            <!-- ------------------------------- -->
            <div class="mt-6 flex flex-wrap justify-end gap-3">
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
    </div>
</div>
<script src="public/js/preview-img.js" defer></script>