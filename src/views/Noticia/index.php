<?php
/**
 * @var array  $itens
 * @var string $urlCriar
 * @var string $editarUrl
 * @var string $deletarUrl
 * @var string $visualizarUrl
 */
?>

<div class="space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Notícias</h1>
            <p class="mt-1 text-sm text-slate-600">Crie, edite e exclua as notícias</p>
        </div>
        <a href="<?= $urlCriar ?>"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            <i class="fa-solid fa-plus"></i>
            Nova notícia
        </a>
    </div>

    <?php if (empty($itens)) : ?>
    <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center">
        <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
            <i class="fa-solid fa-folder-open"></i>
        </div>
        <p class="mt-3 text-sm font-semibold text-slate-900">Nenhuma notícia registrada.</p>
        <p class="mt-1 text-sm text-slate-600">Clique em "Nova notícia" para começar.</p>
    </div>
    <?php else : ?>
    
    <!-- Modificações feitas nesta div principal da tabela -->
    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <!-- Adicionado min-w-[700px] para forçar o scroll no mobile e evitar esmagamento -->
            <table class="w-full text-sm min-w-[700px]">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/50">
                        <!-- Adicionado whitespace-nowrap nos headers -->
                        <th class="whitespace-nowrap px-5 py-3 text-left font-semibold text-slate-700">Título</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left font-semibold text-slate-700">Descrição</th>
                        <th class="whitespace-nowrap px-5 py-3 text-left font-semibold text-slate-700">Status</th>
                        <th class="whitespace-nowrap px-5 py-3 text-right font-semibold text-slate-700">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($itens as $item) : ?>
                    <tr class="hover:bg-slate-50/50">
                        <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900">
                            <?= htmlspecialchars($item->getTitulo()) ?>
                        </td>

                        <!-- Descrição (Cortada estritamente em 50 caracteres com ... no final) -->
                        <td class="whitespace-nowrap px-5 py-4 text-slate-600" title="<?= htmlspecialchars($item->getDescricao() ?? '') ?>">
                            <?= $item->getDescricao() 
                                ? htmlspecialchars(mb_strimwidth($item->getDescricao(), 0, 50, '...')) 
                                : '—' ?>
                        </td>

                        <td class="whitespace-nowrap px-5 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?= $item->getStatus() ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' ?>">
                                <?= $item->getStatus() ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- NOVO BOTÃO: Ver Notícia -->
                                <a href="<?= $visualizarUrl . '?id=' . $item->getId() ?>"
                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-50"
                                    title="Ver detalhes da notícia">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>

                                <a href="<?= $editarUrl . '?id=' . $item->getId() ?>"
                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </a>
                                <button type="button"
                                    class="js-btn-excluir inline-flex items-center gap-1 rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700"
                                    data-id="<?= htmlspecialchars($item->getId()) ?>"
                                    data-nome="<?= htmlspecialchars($item->getTitulo()) ?>" data-url="<?= $deletarUrl ?>"
                                    data-campo="id">
                                    <i class="fa-solid fa-trash"></i> Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>