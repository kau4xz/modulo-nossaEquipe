document.addEventListener('DOMContentLoaded', function() {
    const inputImagem = document.getElementById('imagem');
    const boxImagemAtual = document.getElementById('box_imagem_atual'); 
    const boxPreviewNovo = document.getElementById('box_preview_novo'); 
    const preview = document.getElementById('preview'); 
    const nomeArquivoSelecionado = document.getElementById('nome_arquivo_selecionado');
    const labelTextoBtn = document.getElementById('label_texto_btn');
    
    const btnRemover = document.getElementById('btn_remover_imagem');
    const inputRemover = document.getElementById('input_remover_imagem');

    // 1. Ação do Botão "Excluir imagem" (Serve para a nova e para a antiga!)
    if (btnRemover) {
        btnRemover.addEventListener('click', function() {
            if (inputRemover) inputRemover.value = "1"; // Avisa o BD para apagar
            
            // Esconde os dois cards de imagem
            if (boxImagemAtual) boxImagemAtual.classList.add('hidden');
            if (boxPreviewNovo) boxPreviewNovo.classList.add('hidden');
            if (boxPreviewNovo) boxPreviewNovo.classList.remove('flex');
            
            // Limpa o arquivo recém escolhido (se houver)
            if (inputImagem) inputImagem.value = ""; 
            if (preview) preview.src = "";
            
            // Reseta os textos e esconde o botão de remover
            if (labelTextoBtn) labelTextoBtn.textContent = 'Selecionar arquivo';
            this.classList.add('hidden');
            this.classList.remove('inline-flex');
        });
    }

    // 2. Ação ao selecionar um NOVO arquivo do computador
    if (inputImagem) {
        inputImagem.addEventListener('change', function (event) {
            const arquivo = event.target.files[0];

            if (arquivo) {
                // Monta o preview da nova foto
                const leitor = new FileReader();
                leitor.onload = function (e) {
                    if (preview) preview.src = e.target.result;
                }
                leitor.readAsDataURL(arquivo);

                // Mostra o card do preview e esconde o da foto antiga
                if (boxImagemAtual) boxImagemAtual.classList.add('hidden');
                
                if (boxPreviewNovo) {
                    boxPreviewNovo.classList.remove('hidden');
                    boxPreviewNovo.classList.add('flex');
                }
                if (nomeArquivoSelecionado) nomeArquivoSelecionado.textContent = arquivo.name;

                // Garante que o PHP NÃO apague a foto antes da nova chegar
                if (inputRemover) inputRemover.value = "0"; 
                if (labelTextoBtn) labelTextoBtn.textContent = 'Trocar imagem';

                // Garante que o botão de excluir apareça
                if (btnRemover) {
                    btnRemover.classList.remove('hidden');
                    btnRemover.classList.add('inline-flex');
                }

            } else {
                // Se o usuário abrir a janela do PC mas clicar em "Cancelar", 
                // o navegador esvazia a foto. Escondemos o preview novo.
                if (boxPreviewNovo) {
                    boxPreviewNovo.classList.add('hidden');
                    boxPreviewNovo.classList.remove('flex');
                }
                
                // Se a pessoa NÃO clicou em remover a antiga antes, ela volta a aparecer.
                if (inputRemover && inputRemover.value === "0" && boxImagemAtual) {
                    boxImagemAtual.classList.remove('hidden');
                    if (labelTextoBtn) labelTextoBtn.textContent = 'Trocar imagem';
                }
            }
        });
    }
});