<?php
// Gerador de token para o GUIA de Prompts

// Tempo de vida do token em segundos
// use o mesmo que do Painel para ficar coerente
define('KPASS_TOKEN_TTL', 43200); // 12 horas

// arquivo de tokens, o mesmo usado pelo Painel
$tokensFile = __DIR__ . '/kpass_tokens.json';

// gera um ID de token aleatório e seguro
$tokenId = bin2hex(random_bytes(16));
$now     = time();

// lê tokens existentes
$tokens = [];
if (file_exists($tokensFile)) {
    $json   = file_get_contents($tokensFile);
    $tokens = $json ? json_decode($json, true) : [];
    if (!is_array($tokens)) {
        $tokens = [];
    }
}

// registra o novo token
$tokens[$tokenId] = [
    'created' => $now,
    'expires' => $now + KPASS_TOKEN_TTL,
    'used'    => false,
    'ip'      => $_SERVER['REMOTE_ADDR']     ?? '',
    'ua'      => $_SERVER['HTTP_USER_AGENT'] ?? ''
];

// salva o arquivo de tokens
file_put_contents(
    $tokensFile,
    json_encode($tokens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// redireciona para o GUIA com o token na URL
$target = 'https://fluxoteca.com.br/ferramenta/prompts-pme/manual/?t=' . $tokenId;
header('Location: ' . $target);
exit;
