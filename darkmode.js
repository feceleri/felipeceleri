function contraste(){
    document.documentElement.classList.toggle("dark-mode");
    document.querySelectorAll('.inverted').forEach(el => el.classList.toggle('invert'))
}
