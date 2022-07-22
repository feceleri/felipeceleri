function getText() {
    if (document.getSelection) {
        var text = document.getSelection().toString();
        if (text.length < 1) {
            let utternance = new SpeechSynthesisUtterance('Selecione um texto primeiro.');
            speechSynthesis.speak(utternance);
        } else {
            let utternance = new SpeechSynthesisUtterance(text);
            speechSynthesis.speak(utternance);
        }

    } else {
        if (document.selection) {
            var text2 = document.selection.createRange();
            let utternance = new SpeechSynthesisUtterance(text2);
            speechSynthesis.speak(utternance);
        }
    }
}

window.onload = function() {
    const botao = document.createElement("button");
    botao.setAttribute('id', '#botaoLer');
    botao.setAttribute('onclick', 'getText()');
    botao.setAttribute('style', 'position: fixed;top: 40%;right: 1px;    background: rgb(2,0,36); background: linear-gradient(90deg, rgba(20,152,191,1) 0%, rgba(0,95,162,1) 55%, rgba(0,74,152,1) 100%); color: white;border: 0px;border-radius: 15px;padding: 5px 10px;');

    const imagem = document.createElement("img");
    imagem.setAttribute('src', 'https://assets.website-files.com/5f906e5e35f79f2a13828e8b/6066185582de651fb4ae4445_play-button-branco.png');
    imagem.setAttribute('style', 'width:20px; vertical-align: text-bottom;');

    const body = document.body;

    imagem.addEventListener('mouseenter', () => imagem.setAttribute('style', 'width:25px; vertical-align: text-bottom;'));
    imagem.addEventListener('mouseleave', () => imagem.setAttribute('style', 'width:20px; vertical-align: text-bottom;'));

    botao.appendChild(imagem);
    body.appendChild(botao);


}