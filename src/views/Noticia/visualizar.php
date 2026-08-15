<?php

/**
 * @var string $voltarUrl
 * @var string $editarUrl
 * @var object $item
 */

?>

<div class="space-y-6">
    <!-- Cabeçalho e Ações -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Voltar para a lista
            </a>
            <h1 class="mt-2 text-2xl font-semibold text-slate-900">Visualizar Notícia</h1>
        </div>

        <?php if (isset($editarUrl)) : ?>
            <a href="<?= $editarUrl . '?id=' . $item->getId() ?>"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-pen"></i> Editar notícia
            </a>
        <?php endif; ?>
    </div>

    <!-- Card Principal de Exibição -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        
        <!-- Banner / Imagem de Capa -->
        <?php if ($item->getImagem()) : ?>
            <div class="relative max-h-96 w-full overflow-hidden border-b border-slate-200 bg-slate-100">
                <img src="public<?= htmlspecialchars($item->getImagem()) ?>" 
                     alt="<?= htmlspecialchars($item->getTitulo()) ?>" 
                     class="h-full w-full object-cover">
            </div>
        <?php else : ?>
            <div class="flex h-44 w-full items-center justify-center border-b border-slate-200 bg-slate-50 text-slate-400">
                <div class="text-center">
                    <i class="fa-regular fa-image text-3xl mb-1"></i>
                    <p class="text-xs font-medium">Notícia sem imagem de capa</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Conteúdo do Card -->
        <div class="p-6 sm:p-8 space-y-6">
            
            <!-- Linha de Status e ID -->
            <div class="flex items-center justify-between gap-4">
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold
                    <?= $item->getStatus() ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' ?>">
                    <span class="h-2 w-2 rounded-full <?= $item->getStatus() ? 'bg-green-500' : 'bg-rose-500' ?>"></span>
                    <?= $item->getStatus() ? 'Ativo' : 'Inativo' ?>
                </span>

                <span class="text-xs font-medium text-slate-400">
                    ID: #<?= htmlspecialchars($item->getId()) ?>
                </span>
            </div>

            <!-- Título Principal -->
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    <?= htmlspecialchars($item->getTitulo()) ?>
                </h2>
            </div>

            <hr class="border-slate-100">

            <!-- Bloco da Descrição -->
            <div class="space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Descrição / Conteúdo</span>
                <p class="text-base leading-relaxed text-slate-700 whitespace-pre-line">
                    <?= $item->getDescricao() 
                        ? nl2br(htmlspecialchars($item->getDescricao())) 
                        : '<em class="text-slate-400">Nenhuma descrição foi informada para esta notícia.</em>' ?>
                </p>
            </div>

        </div>
    </div>
</div>