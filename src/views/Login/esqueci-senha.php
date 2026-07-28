<div class="flex min-h-screen w-full flex-col-reverse lg:flex-row-reverse">
    <div class="flex flex-1 flex-col items-center justify-center bg-slate-50 px-4 py-10 sm:px-6">
        <div class="w-full max-w-sm">
            <h1 class="text-2xl font-semibold text-slate-900">Recuperar Senha</h1>

            <?php if (!empty($emailVerificado)) : ?>
                <p class="mt-1 mb-5 text-sm text-slate-600">Crie sua nova senha</p>

                <form method="POST" action="{{formActionNovaSenha}}" class="form space-y-5 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($emailVerificado); ?>">

                    <div>
                        <label class="text-sm font-medium text-slate-700">Email verificado</label>
                        <div class="mt-1 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                            <i class="fa-solid fa-circle-check"></i>
                            <?php echo htmlspecialchars($emailVerificado); ?>
                        </div>
                    </div>

                    <div>
                        <label for="nova_senha" class="text-sm font-medium text-slate-700">Nova Senha</label>
                        <div class="relative mt-1">
                            <i class="fa-solid fa-lock pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="password"
                                id="nova_senha"
                                name="nova_senha"
                                placeholder="Digite a nova senha"
                                minlength="8"
                                required
                                class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-10 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                            <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" data-target="nova_senha">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <ul class="password-requirements mt-3 flex-col gap-1" id="obrigatoriaSenha" style="display:none;"></ul>
                        <?php $senhaErr = old_error('nova_senha'); ?>
                        <?php if ($senhaErr !== '') { ?>
                            <span class="mt-2 block text-sm font-medium text-rose-600" id="nova_senha-error"><?php echo htmlspecialchars($senhaErr); ?></span>
                        <?php } ?>
                    </div>

                    <div>
                        <label for="confirmar_senha" class="text-sm font-medium text-slate-700">Confirmar Nova Senha</label>
                        <div class="relative mt-1">
                            <i class="fa-solid fa-lock pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="password"
                                id="confirmar_senha"
                                name="confirmar_senha"
                                placeholder="Confirme a nova senha"
                                minlength="8"
                                required
                                class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-10 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                            <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" data-target="confirmar_senha">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <?= csrf() ?>

                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70">
                        <i class="fa-solid fa-save"></i>
                        Salvar Nova Senha
                    </button>

                    <div class="flex justify-center">
                        <a href="{{urlLogin}}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-arrow-left"></i>
                            Voltar ao login
                        </a>
                    </div>
                </form>

            <?php elseif (!empty($codigoEnviado)) : ?>
                <p class="mt-1 mb-3 text-sm text-slate-600">Enviamos um código de verificação para</p>
                <div class="mb-5 flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700">
                    <i class="fa-solid fa-envelope"></i>
                    <?php echo htmlspecialchars($emailParaCodigo ?? ''); ?>
                </div>

                <form method="POST" action="{{formActionVerificarCodigo}}" class="form space-y-5 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm" id="formCodigo">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($emailParaCodigo ?? ''); ?>">
                    <input type="hidden" name="codigo" id="codigoCompleto">

                    <div>
                        <label class="text-sm font-medium text-slate-700">Digite o código de 6 dígitos</label>
                        <div class="mt-2 flex items-center justify-center gap-2">
                            <input type="text" class="code-input h-14 w-12 rounded-xl border-2 border-slate-200 text-center text-xl font-bold text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100" maxlength="1" data-index="0" inputmode="numeric" data-mask="number" autocomplete="one-time-code" required>
                            <input type="text" class="code-input h-14 w-12 rounded-xl border-2 border-slate-200 text-center text-xl font-bold text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100" maxlength="1" data-index="1" inputmode="numeric" data-mask="number" required>
                            <input type="text" class="code-input h-14 w-12 rounded-xl border-2 border-slate-200 text-center text-xl font-bold text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100" maxlength="1" data-index="2" inputmode="numeric" data-mask="number" required>
                            <span class="text-xl font-light text-slate-300">-</span>
                            <input type="text" class="code-input h-14 w-12 rounded-xl border-2 border-slate-200 text-center text-xl font-bold text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100" maxlength="1" data-index="3" inputmode="numeric" data-mask="number" required>
                            <input type="text" class="code-input h-14 w-12 rounded-xl border-2 border-slate-200 text-center text-xl font-bold text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100" maxlength="1" data-index="4" inputmode="numeric" data-mask="number" required>
                            <input type="text" class="code-input h-14 w-12 rounded-xl border-2 border-slate-200 text-center text-xl font-bold text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100" maxlength="1" data-index="5" inputmode="numeric" data-mask="number" required>
                        </div>
                        <p class="mt-3 text-center text-xs text-slate-500">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            Verifique sua caixa de entrada e spam
                        </p>
                    </div>
                    <?php $codErr = old_error('codigo'); ?>
                    <?php if ($codErr !== '') { ?>
                        <span class="mt-2 block text-center text-sm font-medium text-rose-600" id="codigo-error"><?php echo htmlspecialchars($codErr); ?></span>
                    <?php } ?>
                    <div class="code-error hidden items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm text-rose-700" id="codeError" style="display: none;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>Código inválido. Tente novamente.</span>
                    </div>

                    <?= csrf() ?>
                    <button type="submit" id="btnVerificarCodigo" disabled class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-40">
                        <i class="fa-solid fa-shield-check"></i>
                        Verificar Código
                    </button>

                    <div class="code-resend flex flex-col items-center gap-1 pt-1" data-expira="<?php echo $timerExpira ?? 0; ?>">
                        <p class="text-sm text-slate-500">Não recebeu o código?</p>
                        <button type="button" id="btnReenviar" class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-900 hover:bg-slate-100 disabled:cursor-not-allowed disabled:text-slate-400 disabled:hover:bg-transparent" disabled>
                            Reenviar código <span id="timerReenviar"></span>
                        </button>
                    </div>

                    <div class="flex justify-center">
                        <a href="{{urlEsqueciSenha}}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </form>

            <?php else : ?>
                <p class="mt-1 mb-5 text-sm text-slate-600">Digite seu email para redefinir sua senha</p>

                <form method="POST" action="{{formActionVerificar}}" class="form space-y-5 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                    <div>
                        <label for="email" class="text-sm font-medium text-slate-700">Email da conta</label>
                        <div class="relative mt-1">
                            <i class="fa-solid fa-envelope pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="seu@email.com"
                                value="<?= old('email') ?>"
                                minlength="5"
                                maxlength="255"
                                required
                                class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        </div>
                        <?php $emailErr = old_error('email'); ?>
                        <?php if ($emailErr !== '') { ?>
                            <span class="mt-2 block text-sm font-medium text-rose-600" id="email-error"><?php echo htmlspecialchars($emailErr); ?></span>
                        <?php } ?>
                    </div>
                    <?= csrf() ?>

                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Verificar Email
                    </button>

                    <div class="flex justify-center">
                        <a href="{{urlLogin}}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-arrow-left"></i>
                            Voltar ao login
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex min-h-[220px] flex-1 items-center justify-center bg-gradient-to-br from-slate-900 to-slate-700 px-4 py-10 text-center text-white">
        <div>
            <span class="block text-sm tracking-[0.2em] text-slate-300">RECUPERACAO DE</span>
            <h2 class="mt-2 text-3xl font-bold sm:text-4xl">Senha</h2>
            <div class="mx-auto mt-4 h-0.5 w-14 bg-white/40"></div>
            <p class="mt-4 text-sm text-slate-300">Redefina sua senha de acesso</p>
        </div>
    </div>
</div>

<script type="module" src="js/esqueci-senha.js"></script>
