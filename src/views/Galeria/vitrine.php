<?php

/**
 * @var array  $itens
 * @var string $urlCriar
 * @var string $editarUrl
 * @var string $deletarUrl
 * @var string $visualizarUrl
 *
 * OBS: assumindo que o item possui um método getImagem() que retorna
 * a URL/caminho da imagem. Ajuste o nome do método se for diferente
 * (ex: getFoto(), getArquivo(), getCaminhoImagem()...).
 */

?>

<div class="space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Galeria</h1>
            <p class="mt-1 text-sm text-slate-600">Gerencie os registros de Galeria</p>
        </div>
        <a href="<?= $urlCriar ?>"
            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            <i class="fa-solid fa-plus"></i>
            Novo Registro
        </a>
    </div>

    <?php if (empty($itens)) : ?>
    <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center">
        <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
            <i class="fa-solid fa-folder-open"></i>
        </div>
        <p class="mt-3 text-sm font-semibold text-slate-900">Nenhum registro cadastrado.</p>
        <p class="mt-1 text-sm text-slate-600">Clique em "Novo Registro" para começar.</p>
    </div>
    <?php else : ?>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <?php foreach ($itens as $item) : ?>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:shadow-xl">

            <div class="min-h-[220px] overflow-hidden bg-slate-100">
                <img src="<?= htmlspecialchars($item->getCaminho()) ?>"
                    alt="<?= htmlspecialchars($item->getTitulo()) ?>"
                    class="w-full">
            </div>

            <div class="space-y-1 p-4">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="truncate text-sm font-semibold text-slate-900" title="<?= htmlspecialchars($item->getTitulo()) ?>">
                        <?= htmlspecialchars($item->getTitulo()) ?>
                    </h3>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                        <?= $item->getStatus() ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' ?>">
                        <?= $item->getStatus() ? 'Ativo' : 'Inativo' ?>
                    </span>
                </div>
                <p class="truncate text-xs text-slate-600" title="<?= htmlspecialchars($item->getLegenda() ?? '') ?>">
                    <?= htmlspecialchars($item->getLegenda() ?? '—') ?>
                </p>
                <?php if ($item->getTipo()) : ?>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                    <?= htmlspecialchars($item->getTipo()) ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-3">
                <a href="<?= $visualizarUrl . '?id=' . $item->getId() ?>"
                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-50 transition-colors"
                    title="Ver detalhes da imagem">
                    <i class="fa-solid fa-eye"></i> Ver
                </a>

                <a href="<?= $editarUrl . '?id=' . $item->getId() ?>"
                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    <i class="fa-solid fa-pen"></i> Editar
                </a>

                <button type="button"
                    class="js-btn-excluir inline-flex items-center gap-1 rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700"
                    data-id="<?= htmlspecialchars($item->getId()) ?>"
                    data-nome="<?= htmlspecialchars($item->getTitulo()) ?>"
                    data-url="<?= $deletarUrl ?>"
                    data-campo="id">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>