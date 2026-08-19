<?php

use Src\App\Utils\Url;

?>

<div class="space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl italic font-medium tracking-tight text-[var(--color-primary)] font-serif">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-600">Bem-vindo, <span class="font-medium text-slate-900">{{nome}}</span>.</p>
        </div>
    </div>

    <!-- TODO: adicione um card por módulo do seu projeto, seguindo este exemplo -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Nossa Equipe</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{countIntegrante}}</p>
                    <p class="mt-1 text-xs text-slate-500">Registros cadastrados</p>
                </div>
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                    <i class="fa-solid fa-users-rectangle"></i>
                </div>
            </div>
        </div>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="flex items-center justify-between gap-3 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">Últimos registros</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Item</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Módulo</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Realizado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php if (empty($lastRegistros)) : ?>
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500">
                            Nenhuma atividade registrada.
                        </td>
                    </tr>
                    <?php else : ?>
                        <?php foreach ($lastRegistros as $log) : ?>
                            <?php
                            $acaoCores = [
                                'CREATE' => 'bg-emerald-100 text-emerald-700',
                                'UPDATE' => 'bg-amber-100 text-amber-700',
                                'DELETE' => 'bg-rose-100 text-rose-700',
                            ];
                            $cor = $acaoCores[strtoupper($log['acao'])] ?? 'bg-slate-100 text-slate-700';
                            ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 text-sm font-medium text-slate-900">#<?= $log['registro_id'] ?></td>
                        <td class="px-5 py-4 text-sm text-slate-600"><?= htmlspecialchars($log['modulo']) ?></td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold <?= $cor ?>">
                                <?= htmlspecialchars($log['acao']) ?>
                            </span>
                        </td>
                        <td class="px-2 py-4 text-right text-xs text-slate-500">
                            <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                        </td>
                    </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>