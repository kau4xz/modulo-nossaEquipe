 const inputImagem = document.getElementById('imagem');
  const preview = document.getElementById('preview');

  inputImagem.addEventListener('change', function(event) {
    const arquivo = event.target.files[0];

    if (arquivo) {
      const leitor = new FileReader();
      
      leitor.onload = function(e) {
        preview.src = e.target.result;
      }
      
      leitor.readAsDataURL(arquivo);
      preview.classList.remove("hidden")
    }
  });