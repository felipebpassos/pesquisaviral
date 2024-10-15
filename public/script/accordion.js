const questions = document.querySelectorAll(".pergunta");

questions.forEach(q => {
    q.addEventListener("click", () => {
        questions.forEach(otherQ => {
            if (otherQ !== q) {
                otherQ.classList.remove("ativo");
            }
        });
        q.classList.toggle("ativo");
    });
});
