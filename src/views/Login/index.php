<div class="flex min-h-screen w-full flex-col-reverse lg:flex-row-reverse">
    <div class="flex flex-1 flex-col items-center justify-center bg-slate-50 px-4 py-10 sm:px-6">
        <form method="POST" action="{{formAction}}" autocomplete="on" class="form w-full max-w-sm space-y-5 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Login</h1>
                <p class="mt-1 text-sm text-slate-600">Acesse sua conta para continuar</p>
            </div>

            <div>
                <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                <div class="relative mt-1">
                    <i class="ph ph-envelope pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" value="<?= old('email') ?>" minlength="5" maxlength="255" required
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                </div>
                <?php $emailErr = old_error('email'); ?>
                <?php if ($emailErr !== '') { ?>
                    <span class="mt-2 block text-sm font-medium text-rose-600" id="email-error"><?php echo htmlspecialchars($emailErr); ?></span>
                <?php } ?>
            </div>

            <div>
                <label for="senha" class="text-sm font-medium text-slate-700">Senha</label>
                <div class="relative mt-1">
                    <i class="ph ph-lock-key pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" minlength="8" required
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-10 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <button type="button" id="btnVerSenha" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <i class="ph ph-eye-slash"></i>
                    </button>
                </div>
                <?php $senhaErr = old_error('senha'); ?>
                <?php if ($senhaErr !== '') { ?>
                    <span class="mt-2 block text-sm font-medium text-rose-600" id="password-error"><?php echo htmlspecialchars($senhaErr); ?></span>
                <?php } ?>
            </div>

            <div class="error-auth hidden rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-center text-sm text-rose-700" id="auth-error">{{erro}}</div>
            <?= csrf() ?>

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70">
                <i class="fa-solid fa-right-to-bracket"></i>
                Entrar
            </button>

            <div class="flex justify-center pt-1">
                <a href="{{esqueciSenha}}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
                    <i class="fa-solid fa-key"></i>
                    Esqueci minha senha
                </a>
            </div>
        </form>
    </div>

    <div class="flex min-h-[220px] flex-1 items-center justify-center bg-gradient-to-br from-slate-900 to-slate-700 px-4 py-10 text-center text-white">
        <div>
            <span class="block text-sm tracking-[0.2em] text-slate-300">BEM-VINDO AO</span>
            <h2 class="mt-2 text-3xl font-bold sm:text-4xl">Projeto</h2>
        </div>
    </div>
</div>

<script type="module" src="js/login.js"></script>
