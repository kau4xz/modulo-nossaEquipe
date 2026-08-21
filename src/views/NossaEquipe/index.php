<?php

/**
 * @var array  $itens
 * @var string $urlCriar
 * @var string $editarUrl
 * @var string $deletarUrl
 * @var string $visualizarUrl
 */

use Src\App\Utils\Url;

?>

<div class="space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl italic font-medium tracking-tight text-[var(--color-primary)] font-serif">Nossa Equipe</h1>
            <p class="mt-1 text-sm text-slate-600">Gerencie os integrantes da equipe</p>
        </div>
        <a href="<?= $urlCriar ?>"
            class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-white hover:bg-[var(--color-primary-hover)] hover:text-white">
            <i class="fa-solid fa-user-plus"></i>
            Novo integrante
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="sm:col-span-2">
                <label for="searchInput" class="text-sm font-medium text-slate-700">Buscar</label>
                <div class="mt-1 flex items-center gap-2">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" id="searchInput"
                        class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"
                        placeholder="Buscar por nome ou cargo...">
                </div>
            </div>

            <div>
                <label for="filterStatus" class="text-sm font-medium text-slate-700">Status</label>
                <select id="filterStatus"
                    class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <option value="">Todos os status</option>
                    <option value="1">Publicado</option>
                    <option value="0">Não Publicado</option>
                </select>
            </div>

            <div>
                <label for="orderBy" class="text-sm font-medium text-slate-700">Ordernar por</label>
                <select id="sortSelect"
                    class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <option value="">Padrão</option>
                    <option value="nome_asc">Nome (A-Z)</option>
                    <option value="nome_desc">Nome (Z-A)</option>
                    <option value="criado_desc">Mais recentes (Criação)</option>
                    <option value="criado_asc">Mais antigos (Criação)</option>
                    <option value="atualizado_desc">Mais recentes (Atualização)</option>
                    <option value="atualizado_asc">Mais antigos (Atualização)</option>

                </select>
            </div>
        </div>

        <p class="mt-3 text-xs font-medium text-slate-500" id="totalVisible"></p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <!-- CABEÇALHO -->
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Foto</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Nome</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Cargo</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Cadastrado em:</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Atualizado em:</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Ações</th>
                    </tr>
                </thead>

                <!-- CORPO -->
                <tbody id="integranteTableBody" class="divide-y divide-slate-200 bg-white">
                    <!-- CORPO SEM REGISTRO -->
                    <?php if (empty($itens)) : ?>
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-600">
                                Nenhum integrante cadastrado.
                            </td>
                        </tr>
                    <?php else : ?>
                        <tr id="semResultado" class="hidden">
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-600">
                                Nenhum integrante encontrado para esse filtro.
                            </td>
                        </tr>

                        <!-- CORPO COM REGISTRO -->
                        <?php foreach ($itens as $item) : ?>
                            <tr class="hover:bg-slate-50"
                                data-nome="<?= strtolower(htmlspecialchars($item->getNome())) ?>"
                                data-cargo="<?= strtolower(htmlspecialchars($item->getCargo() ?? '')) ?>"
                                data-criado="<?= strtolower(htmlspecialchars($item->getCreatedAt())) ?>"
                                data-atualizado="<?= strtolower(htmlspecialchars($item->getUpdatedAt())) ?>"
                                data-status="<?= $item->getStatus() ? '1' : '0' ?>">
                                <td class="px-5 py-4">
                                    <?php if ($item->getFoto()) : ?>
                                        <img src="<?= htmlspecialchars(Url::path($item->getFoto())) ?>"
                                            alt="Foto de <?= htmlspecialchars($item->getNome()) ?>"
                                            class="h-9 w-9 rounded-xl object-cover">
                                    <?php else : ?>
                                        <div class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-sm font-semibold text-slate-900"><?= htmlspecialchars($item->getNome()) ?></td>
                                <td class="px-5 py-4 text-sm text-slate-600"><?= htmlspecialchars($item->getCargo() ?? '—') ?></td>
                                <td class="px-5 py-4 text-sm font-semibold text-slate-900"><?= htmlspecialchars($item->getCreatedAt()) ?></td>
                                <td class="px-5 py-4 text-sm text-slate-900"><?= htmlspecialchars($item->getUpdatedAt() ?? '_') ?></td>
                                <td class="px-5 py-4">
                                    <?php if ($item->getStatus()) : ?>
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            <i class="fa-solid fa-circle text-[0.45rem]"></i>
                                            Publicado
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                            <i class="fa-solid fa-circle text-[0.45rem]"></i>
                                            Não Publicado
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="<?= $editarUrl . '?id=' . $item->getId() ?>"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50"
                                            title="Editar integrante">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button type="button"
                                            class="js-btn-excluir inline-flex h-9 w-9 items-center justify-center rounded-lg bg-rose-600 text-white hover:bg-rose-700"
                                            title="Excluir integrante"
                                            data-id="<?= htmlspecialchars($item->getId()) ?>"
                                            data-nome="<?= htmlspecialchars($item->getNome()) ?>"
                                            data-url="<?= $deletarUrl ?>"
                                            data-campo="id">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <a href="<?= $visualizarUrl . '?id=' . $item->getId() ?>" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" title="Ver detalhes">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="module" src="<?= Url::path('/js/nossa-equipe.js') ?>"></script>