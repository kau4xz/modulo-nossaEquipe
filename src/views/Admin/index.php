<?php

use Src\App\Enums\Status;
use Src\App\Utils\Url;

$users = $users ?? [];

$totalUsers = count($users);
$ativos = count(array_filter($users, fn($u) => $u->getStatus() === Status::ATIVO));
$inativos = $totalUsers - $ativos;
$admins = count(array_filter($users, fn($u) => $u->isAdmin()));
?>

<div class="space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl italic font-medium tracking-tight  text-[var(--color-primary)] font-serif">Administração</h1>
            <p class="mt-1 text-sm text-slate-600">Gerencie os usuários do sistema</p>
        </div>
        <a href="<?= Url::path('/admin/novo') ?>" class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-primary)]  px-4 py-2 text-sm font-semibold text-white hover:text-white hover:bg-[var(--color-primary-hover)] ">
            <i class="fa-solid fa-user-plus"></i>
            Novo usuário
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900"><?= $totalUsers ?></p>
                    <p class="mt-1 text-xs text-slate-500">Usuários cadastrados</p>
                </div>
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Ativos</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900"><?= $ativos ?></p>
                    <p class="mt-1 text-xs text-slate-500">Status ativo</p>
                </div>
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Inativos</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900"><?= $inativos ?></p>
                    <p class="mt-1 text-xs text-slate-500">Status inativo</p>
                </div>
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Admins</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900"><?= $admins ?></p>
                    <p class="mt-1 text-xs text-slate-500">Perfil administrador</p>
                </div>
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-3">
            <div class="lg:col-span-1">
                <label for="searchInput" class="text-sm font-medium text-slate-700">Buscar</label>
                <div class="mt-1 flex items-center gap-2">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" id="searchInput" class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100" placeholder="Buscar por nome ou email...">
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                    <label for="filterRole" class="text-sm font-medium text-slate-700">Perfil</label>
                    <select id="filterRole" class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        <option value=''>Todos os perfis</option>
                        <option value="1">Usuário</option>
                        <option value="2">Admin</option>
                    </select>
                </div>
                <div>
                    <label for="filterStatus" class="text-sm font-medium text-slate-700">Status</label>
                    <select id="filterStatus" class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        <option value=''>Todos os status</option>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="flex items-center justify-between gap-3 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">Usuários</h2>
            <span id="totalVisible" class="text-sm text-slate-600"><?= $totalUsers ?> usuário(s)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Usuário</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Perfil</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Cadastrado em</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Ações</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-slate-200 bg-white">
                    <?php if (empty($users)) : ?>
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-600">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr class="hover:bg-slate-50"
                                data-nome="<?= strtolower(htmlspecialchars($user->getNome())) ?>"
                                data-email="<?= strtolower(htmlspecialchars($user->getEmail())) ?>"
                                data-role="<?= $user->getPerfil()->value ?>"
                                data-status="<?= $user->getStatus()->value ?>">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-sm font-semibold text-slate-700">
                                            <?= htmlspecialchars(strtoupper(mb_substr($user->getNome(), 0, 1))) ?>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($user->getNome()) ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-600"><?= htmlspecialchars($user->getEmail()) ?></td>
                                <td class="px-5 py-4">
                                    <?php if ($user->isAdmin()) : ?>
                                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                            <i class="fa-solid fa-shield-halved"></i>
                                            Admin
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                            <i class="fa-solid fa-user"></i>
                                            Usuário
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4">
                                    <?php if ($user->getStatus() === Status::ATIVO) : ?>
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            <i class="fa-solid fa-circle text-[0.45rem]"></i>
                                            <?= $user->getStatus()->toText() ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                            <i class="fa-solid fa-circle text-[0.45rem]"></i>
                                            <?= $user->getStatus()->toText() ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-600"><?= $user->getCreatedAt() ? $user->getCreatedAt()->format('d/m/Y') : '-' ?></td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="<?= Url::path('/admin/detalhes/' . $user->getId()) ?>" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" title="Ver detalhes">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?= Url::path('/admin/editar/' . $user->getId()) ?>" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" title="Editar usuário">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button type="button" class="js-btn-excluir inline-flex h-9 w-9 items-center justify-center rounded-lg bg-rose-600 text-white hover:bg-rose-700" title="Deletar usuário" data-id="<?= htmlspecialchars($user->getId()) ?>" data-nome="<?= htmlspecialchars($user->getNome()) ?>" data-url="<?= Url::path('/admin/deletar') ?>" data-campo="user_id">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
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

<script type="module" src="<?= Url::path('/js/admin.js') ?>"></script>
