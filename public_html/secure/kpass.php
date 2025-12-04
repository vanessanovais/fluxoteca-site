<?php
// Gerador de token para o Painel de Prompts

// Tempo de vida do token em segundos
// 12 horas = 12 * 60 * 60 = 43200
define('KPASS_TOKEN_TTL', 43200);

$tokensFile = __DIR__ . '/kpass_tokens.json';

// Gera um ID de token aleatório e seguro
$tokenId = bin2hex(random_bytes(16));
$now     = time();

// Lê tokens existentes
$tokens = [];
if (file_exists($tokensFile)) {
    $json   = file_get_contents($tokensFile);
    $tokens = $json ? json_decode($json, true) : [];
    if (!is_array($tokens)) {
        $tokens = [];
    }
}

// Registra o novo token
$tokens[$tokenId] = [
    'created' => $now,
    'expires' => $now + KPASS_TOKEN_TTL,
    'used'    => false,
    'ip'      => $_SERVER['REMOTE_ADDR']      ?? '',
    'ua'      => $_SERVER['HTTP_USER_AGENT']  ?? ''
];

// Salva o arquivo de tokens
file_put_contents(
    $tokensFile,
    json_encode($tokens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// Redireciona para o Painel com o token na URL
$target = 'https://fluxoteca.com.br/ferramenta/prompts-pme/?t=' . $tokenId;
header('Location: ' . $target);
exit;
