<?php

use Src\App\Utils\Url;

$user = $user ?? null;

?>

<?php if ($user !== null) : ?>
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <a href="<?= Url::path('/admin') ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-slate-900">
                <i class="fa-solid fa-arrow-left"></i>
                Voltar para Administração
            </a>
            <div>
                <h1 class="text-3xl italic font-medium tracking-tight  text-[var(--color-primary)] font-serif">Detalhes do usuário</h1>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-base font-semibold text-slate-700">
                        <?= htmlspecialchars(strtoupper(mb_substr($user->getNome(), 0, 1))) ?>
                    </div>
                    <div class="min-w-0">
                        <div class="truncate text-base font-semibold text-slate-900"><?= htmlspecialchars($user->getNome()) ?></div>
                        <div class="truncate text-sm text-slate-600"><?= htmlspecialchars($user->getEmail()) ?></div>
                        <div class="mt-2">
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
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center">
                    <button type="button" class="js-btn-excluir inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700" data-id="<?= htmlspecialchars($user->getId()) ?>" data-nome="<?= htmlspecialchars($user->getNome()) ?>" data-url="<?= Url::path('/admin/deletar') ?>" data-campo="user_id">
                        <i class="fa-solid fa-trash"></i>
                        Deletar
                    </button>
                    <a href="<?= Url::path('/admin/editar/' . $user->getId()) ?>" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Editar
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-medium text-slate-600">ID</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900"><?= htmlspecialchars($user->getId()) ?></p>
                </div>

                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-medium text-slate-600">Status</p>
                    <div class="mt-2">
                        <?php if ($user->isAtivo()) : ?>
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
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-medium text-slate-600">Cadastrado em</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900"><?= $user->getCreatedAt() ? $user->getCreatedAt()->format('d/m/Y \à\s H:i') : '-' ?></p>
                </div>

                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-sm font-medium text-slate-600">Última atualização</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900"><?= $user->getUpdatedAt() ? $user->getUpdatedAt()->format('d/m/Y \à\s H:i') : '-' ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
