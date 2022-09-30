<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}
echo "Bienvenid@ " . $_SESSION['user']['username'];
?>
<style>
    .chat {
        width: 300px;
        display: grid;
        gap: 1rem;
    }

    .msg {
        background-color: #ddd;
        border-radius: 4px;
        padding: .5rem 1rem;
    }
</style>
<div class="chat"></div>
<textarea id="textarea" placeholder="Envia un mensaje" style="margin-top: 1rem; padding: .5rem;"></textarea>
<button type="submit">ENVIAR</button>

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script>
    function connect() {
        socket = new WebSocket('ws://localhost:8080');
        socket.addEventListener("open", function() {
            console.log("Te has conectado correctamente");
            $('.chat').append(`<div class="msg">Te has conectado correctamente.</div>`)
        });
        socket.addEventListener("message", function(event) {
            $('.chat').append(`<div class="msg"><h3 style="margin:0; padding:0;">${JSON.parse(event.data).sender}</h3>${JSON.parse(event.data).msg}</div>`);
        });
        socket.addEventListener("close", function(event) {
            console.log("la conexión se encuentra caída.")
            connect();
        });
    }
    document.addEventListener('DOMContentLoaded', (event) => {
        connect();
    })

    $('button').click(function(e) {
        e.preventDefault();
        data = { msg: $('textarea').val(), sender: '<?= $_SESSION['user']['username'] ?>' }
        socket.send(JSON.stringify(data));
        $('textarea').val('')
    });
    $('textarea').keypress(function(e) {
        if (e.key == 'Enter') {
            e.preventDefault();
            $('button').click();
        }
    });
</script>