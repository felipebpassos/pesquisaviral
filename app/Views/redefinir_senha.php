<form method="post">
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="senha">Nova Senha:</label>
    <input type="password" id="senha" name="senha" required>

    <label for="senha">Repetir nova senha:</label>
    <input type="password" id="senha" name="senha" required>
    
    <button type="submit">Enviar</button>

    <p><a href="<?php echo BASE_URL; ?>login/">Ir para login.</a></p>
</form>