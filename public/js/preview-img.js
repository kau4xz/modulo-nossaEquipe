const inputImagem = document.getElementById('imagem');
const preview = document.getElementById('preview');
const textoArquivo = document.getElementById('texto_arquivo');

inputImagem.addEventListener('change', function (event) {
  const arquivo = event.target.files[0];

  if (arquivo) {
    const leitor = new FileReader();

    leitor.onload = function (e) {
      preview.src = e.target.result;
    }

    leitor.readAsDataURL(arquivo);
    preview.classList.remove("hidden")
  }
});


if (inputImagem && textoArquivo) {
  inputImagem.addEventListener('change', function (e) {
    if (this.files && this.files.length > 0) {
      // Coloca o nome do arquivo que o usuário escolheu
      textoArquivo.textContent = this.files[0].name;
      textoArquivo.classList.replace('text-slate-500', 'text-slate-900');
      textoArquivo.classList.add('font-medium');
    } else {
      // Se ele cancelar a seleção da janela
      textoArquivo.textContent = 'Nenhum arquivo selecionado';
      textoArquivo.classList.replace('text-slate-900', 'text-slate-500');
      textoArquivo.classList.remove('font-medium');
    }
  });
}