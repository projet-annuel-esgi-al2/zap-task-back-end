<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<style>
    body {
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #fafafa;
        font-family: sans-serif;
    }

    .spinner, .message {
        position: absolute;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 6px solid #f3f3f3;
        border-top: 6px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .message {
        font-size: 24px;
        color: #2ecc71;
        display: none;
    }
</style>
<body>
<div class="spinner" id="spinner"></div>
<div class="message" id="message">Success !</div>

<script>
    setTimeout(() => {
        document.getElementById('spinner').style.display = 'none';
        document.getElementById('message').style.display = 'block';
        setInterval(() => window && window.close(), 1000);
    }, 1000);
</script>
</body>
</html>
