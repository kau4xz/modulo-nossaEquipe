<?php if (!empty($toast)) : ?>
    <?php $mensagens = is_array($toast['message']) ? $toast['message'] : [$toast['message']]; ?>
    <?php
    $isSuccess = ($toast['type'] ?? '') === 'success';
    $bg = $isSuccess ? 'bg-emerald-600' : 'bg-rose-600';
    $icon = $isSuccess ? 'fa-circle-check' : 'fa-circle-xmark';
    ?>
    <div class="fixed right-4 top-4 z-[60] w-[min(28rem,calc(100vw-2rem))]">
        <div id="toast" class="<?= $bg ?> flex items-start gap-3 rounded-2xl p-4 text-white shadow-xl transition-opacity duration-300">
            <div class="mt-0.5 inline-flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-white/15">
                <i class="fa-solid <?= $icon ?>"></i>
            </div>
            <div class="min-w-0 flex-1 text-sm">
                <?php if (count($mensagens) === 1) : ?>
                    <p class="font-medium"><?php echo htmlspecialchars((string) $mensagens[0]); ?></p>
                <?php else : ?>
                    <ul class="list-disc space-y-1 pl-5">
                        <?php foreach ($mensagens as $msg) : ?>
                            <li><?php echo htmlspecialchars((string) $msg); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <button type="button" class="inline-flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-white/15 hover:bg-white/20" onclick="fecharToast()" aria-label="Fechar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
    <script>
        function fecharToast() {
            const toast = document.getElementById('toast');
            if (toast) toast.parentElement.remove();
        }
        setTimeout(fecharToast, 5000);
    </script>
<?php endif; ?>
