<?php

/**
 * @var \Src\App\Models\Integrante $item
 * @var string $voltarUrl
 */

use Src\App\Utils\Url;

$formatarData = static function (?string $data): string {
    if (! $data) {
        return '-';
    }

    $timestamp = strtotime($data);

    return $timestamp ? date('d/m/Y', $timestamp) . ' às ' . date('H:i', $timestamp) : '-';
};

?>

<div class="space-y-6">
    <div class="flex flex-col gap-2">
        <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left"></i>
            Voltar para Nossa Equipe
        </a>
        <div>
            <h1 class="text-3xl italic font-medium tracking-tight text-[var(--color-primary)] font-serif">Detalhes do integrante</h1>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <?php if ($item->getFoto()) : ?>
                    <img src="<?= htmlspecialchars(Url::path($item->getFoto())) ?>"
                        alt="Foto de <?= htmlspecialchars($item->getNome()) ?>"
                        class="h-30 w-30 rounded-2xl object-cover">
                <?php else : ?>
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                        <i class="fa-solid fa-user"></i>
                    </div>
                <?php endif; ?>

                <div class="min-w-0">
                    <div class="truncate text-base font-semibold text-slate-1000"><?= htmlspecialchars($item->getNome()) ?></div>
                    <div class="truncate text-sm text-slate-600"><?= htmlspecialchars($item->getCargo() ?? '—') ?></div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center">
                <button type="button"
                    class="js-btn-excluir inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700"
                    data-id="<?= htmlspecialchars($item->getId()) ?>"
                    data-nome="<?= htmlspecialchars($item->getNome()) ?>"
                    data-url="<?= Url::path('/nossa-equipe/deletar') ?>"
                    data-campo="id">
                    <i class="fa-solid fa-trash"></i>
                    Deletar
                </button>
                <a href="<?= Url::path('/nossa-equipe/editar') . '?id=' . $item->getId() ?>"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Editar
                </a>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-3 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Cargo</p>
                <p class="mt-1 text-sm font-semibold text-slate-900"><?= htmlspecialchars($item->getCargo() ?? '-') ?></p>
            </div>

            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Status</p>
                <p class="mt-1">
                    <?php if ($item->getStatus()) : ?>
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            <i class="fa-solid fa-circle text-[0.45rem]"></i>
                            Ativo
                        </span>
                    <?php else : ?>
                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                            <i class="fa-solid fa-circle text-[0.45rem]"></i>
                            Inativo
                        </span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Cadastrado em</p>
                <p class="mt-1 text-sm font-semibold text-slate-900"><?= $formatarData($item->getCreatedAt()) ?></p>
            </div>

            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Última atualização</p>
                <p class="mt-1 text-sm font-semibold text-slate-900"><?= $formatarData($item->getUpdatedAt()) ?></p>
            </div>

            <div class="rounded-2xl border border-slate-200 p-4 md:col-span-2">
                <p class="text-sm font-medium text-slate-600">ID</p>
                <p class="mt-1 break-all text-sm font-semibold text-slate-900"><?= htmlspecialchars($item->getId()) ?></p>
            </div>
        </div>
    </div>
</div>
