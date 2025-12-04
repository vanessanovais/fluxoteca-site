<?php
// Guarda de acesso por token para o GUIA de Prompts

// atenção no caminho, aqui estamos em /manual, então sobe 3 níveis
$tokensFile = __DIR__ . '/../../../secure/kpass_tokens.json';

// Página amigável quando o acesso é inválido ou expirou
function kpass_manual_deny_access() {
    http_response_code(403);
    echo "
    <!DOCTYPE html>
    <html lang='pt-br'>
    <head>
      <meta charset='UTF-8'>
      <title>Acesso ao Guia expirado</title>
      <style>
        body{
          margin:0;
          padding:40px 16px;
          font-family:-apple-system,BlinkMacSystemFont,'Inter',system-ui,sans-serif;
          background:#f3f6fb;
          color:#0c1b33;
        }
        .wrap{
          max-width:520px;
          margin:40px auto;
          background:#ffffff;
          border-radius:18px;
          padding:28px 24px 30px;
          box-shadow:0 18px 40px rgba(6,22,58,0.12);
          text-align:center;
        }
        .logo{
          font-weight:700;
          letter-spacing:0.08em;
          font-size:11px;
          text-transform:uppercase;
          color:#1b6fff;
          margin-bottom:6px;
        }
        h1{
          font-size:22px;
          margin:6px 0 10px;
        }
        p{
          font-size:15px;
          line-height:1.6;
          margin:6px 0;
        }
        .hint{
          font-size:13px;
          color:#6c7a96;
          margin-top:10px;
        }
        a.btn{
          display:inline-block;
          margin-top:18px;
          padding:12px 22px;
          border-radius:999px;
          background:linear-gradient(135deg,#1b6fff,#00b0ff);
          color:#ffffff;
          font-weight:600;
          text-decoration:none;
          font-size:14px;
          box-shadow:0 10px 25px rgba(8,53,130,0.35);
        }
        a.btn:hover{
          filter:brightness(1.05);
        }
      </style>
    </head>
    <body>
      <div class='wrap'>
        <div class='logo'>Fluxoteca</div>
        <h1>Link do Guia expirado</h1>
        <p>Por segurança, este link de acesso ao <strong>Guia de Prompts PME</strong> não é mais válido.</p>
        <p>Para abrir novamente o Guia, volte à
        <strong>Área de Membros da Kiwify</strong> e clique no botão de acesso ao Guia.</p>
        <p class='hint'>Se o erro continuar aparecendo, faça login de novo na Kiwify e clique outra vez no botão.</p>
        <a class='btn' href='https://members.kiwify.com/login?club=0267d635-a721-409e-9f86-bb7a253e95b8'>
  Ir para a Área de Membros
</a>
      </div>
    </body>
    </html>";
    exit;
}

// Lê e valida o token da URL
$tokenId = isset($_GET['t']) ? strtolower($_GET['t']) : '';
$tokenId = preg_replace('/[^a-f0-9]/', '', $tokenId); // apenas hex

// Sem token já bloqueia
if ($tokenId === '') {
    kpass_manual_deny_access();
}

// Arquivo de tokens não encontrado
if (!file_exists($tokensFile)) {
    kpass_manual_deny_access();
}

// Lê tokens
$json   = file_get_contents($tokensFile);
$tokens = $json ? json_decode($json, true) : [];

if (!is_array($tokens) || !isset($tokens[$tokenId])) {
    kpass_manual_deny_access();
}

$info = $tokens[$tokenId];

$currentIp = $_SERVER['REMOTE_ADDR'] ?? '';
$currentUa = $_SERVER['HTTP_USER_AGENT'] ?? '';

if (!empty($info['ip']) && $info['ip'] !== $currentIp) {
    // IP diferente, provavelmente outro dispositivo ou rede
    kpass_manual_deny_access(); // ou kpass_deny_access() no painel
}

if (!empty($info['ua']) && $info['ua'] !== $currentUa) {
    // Navegador diferente
    kpass_manual_deny_access(); // ou kpass_deny_access() no painel
}

// Verifica se expirou
if (!isset($info['expires']) || $info['expires'] < time()) {
    kpass_manual_deny_access();
}

// se quiser bloquear depois do primeiro uso, descomente o bloco abaixo
/*
if (!empty($info['used'])) {
    kpass_manual_deny_access();
}
$info['used'] = true;
$tokens[$tokenId] = $info;
file_put_contents(
    $tokensFile,
    json_encode($tokens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);
*/
// se deixar comentado, o token vale para Painel e Guia até o horário de expiração
?>

<!doctype html>
<html lang="pt-BR" data-theme="dark">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guia Inteligente — Prompts para PMEs | Fluxoteca</title>
  <meta name="description" content="Guia visual e interativo para aplicar prompts inteligentes nas áreas de gestão, finanças, vendas, RH, operações e muito mais.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="canonical" href="https://fluxoteca.com.br/guia-prompts-pme" />
<meta property="og:type" content="article">
<meta property="og:title" content="Guia Inteligente — Prompts para PMEs | Fluxoteca">
<meta property="og:description" content="Guia visual e prático para transformar prompts em entregas profissionais em gestão, finanças, marketing, RH e operações.">
<meta property="og:url" content="https://fluxoteca.com.br/guia-prompts-pme">
<meta property="og:image" content="https://fluxoteca.com.br/assets/og/guia-prompts-pme.png">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Guia Inteligente — Prompts para PMEs">
<meta name="twitter:description" content="Prompts estruturados, decisões melhores, menos retrabalho.">
<meta name="twitter:image" content="https://fluxoteca.com.br/assets/og/guia-prompts-pme.png">

<style>
/* =============================================
   SISTEMA DE DESIGN - VARIÁVEIS E TOKENS
   ============================================= */

:root {
    /* 🎯 CORES - Escala de Cinza */
    --bg-primary: #0a0a0f;
    --bg-secondary: #111118;
    --bg-tertiary: #1a1a24;
    --bg-quaternary: #252530;
    
    --surface: rgba(255, 255, 255, 0.03);
    --surface-hover: rgba(255, 255, 255, 0.06);
    --surface-glass: rgba(255, 255, 255, 0.05);
    
    --text-primary: #ffffff;
    --text-secondary: #a1a1aa;
    --text-tertiary: #71717a;
    --text-muted: #52525b;

    /* 🎯 CORES - Semânticas */
    --accent-primary: #0ea5e9;
    --accent-secondary: #0284c7;
    --accent-tertiary: #0369a1;
    --accent-gradient: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
    --accent-gradient-alt: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 50%, #0284c7 100%);
    
    --success: #10b981;
    --error: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;

    /* 🎯 BORDAS */
    --border-subtle: rgba(255, 255, 255, 0.08);
    --border-default: rgba(255, 255, 255, 0.12);
    --border-strong: rgba(255, 255, 255, 0.18);
    --border-accent: rgba(14, 165, 233, 0.3);

    /* 🎯 SOMBRAS */
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-glow: 0 0 20px rgba(14, 165, 233, 0.15);
    --shadow-glow-strong: 0 0 40px rgba(14, 165, 233, 0.25);
    --blur-md: blur(8px);
    --blur-xl: blur(24px);

    /* 🎯 ESPAÇAMENTOS */
    --spacing-xs: 4px;
    --spacing-sm: 8px;
    --spacing-md: 16px;
    --spacing-lg: 24px;
    --spacing-xl: 32px;
    --spacing-2xl: 48px;
    --spacing-3xl: 64px;

    /* 🎯 BORDER RADIUS */
    --radius-sm: 6px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    --radius-2xl: 24px;

    /* 🎯 TRANSIÇÕES */
    --transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-normal: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-bounce: 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* =============================================
   TEMAS ALTERNATIVOS
   ============================================= */

:root[data-theme="light"] {
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    --bg-quaternary: #e2e8f0;
    --surface: rgba(0, 0, 0, .04);
    --surface-glass: rgba(255, 255, 255, 0.9);
    --surface-hover: rgba(0, 0, 0, .08);
    --text-primary: #0f172a;
    --text-secondary: #1e293b;
    --text-tertiary: #334155;
    --text-muted: #475569;
    --accent-primary: #0369a1;
    --accent-secondary: #0284c7;
    --accent-tertiary: #075985;
    --accent-gradient: linear-gradient(135deg, #0284c7 0%, #0ea5e9 50%, #0369a1 100%);
    --success: #065f46;
    --error: #dc2626;
    --warning: #d97706;
    --info: #1d4ed8;
    --border-subtle: rgba(0, 0, 0, .12);
    --border-default: rgba(0, 0, 0, .20);
    --border-strong: rgba(0, 0, 0, .30);
    --border-accent: rgba(3, 105, 161, .4);
    --shadow-glow: 0 0 20px rgba(2, 132, 199, 0.15);
    --shadow-glow-strong: 0 0 40px rgba(3, 105, 161, .3);
}

[data-contrast="high"] {
    --bg-primary: #000000;
    --bg-secondary: #000000;
    --bg-tertiary: #1a1a1a;
    --bg-quaternary: #2a2a2a;
    --surface: #1a1a1a;
    --surface-glass: rgba(255, 255, 255, 0.05);
    --surface-hover: #333333;
    --text-primary: #ffffff;
    --text-secondary: #ffffff;
    --text-tertiary: #ffffff;
    --text-muted: #ffffff;
    --accent-primary: #00ffff;
    --accent-secondary: #66ffff;
    --accent-tertiary: #33ccff;
    --accent-gradient: linear-gradient(135deg, #00ffff 0%, #66ffff 50%, #33ccff 100%);
    --success: #00ff00;
    --error: #ff3333;
    --warning: #ffff00;
    --info: #00ffff;
    --border-subtle: #666666;
    --border-default: #999999;
    --border-strong: #cccccc;
    --border-accent: #00ffff;
    --shadow-glow: 0 0 20px rgba(14, 165, 233, 0.15);
    --shadow-glow-strong: 0 0 40px #00ffff;
}

  :root[data-theme="light"] header {
    background: rgba(255, 255, 255, 0.85);
  }
  
  :root[data-theme="light"] header.scrolled {
    background: rgba(255, 255, 255, 0.95);
  }


/* =============================================
   ESTILOS BASE E RESET
   ============================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    line-height: 1.6;
    overflow-x: hidden;
    font-synthesis: none;
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* =============================================
   SISTEMA DE LAYOUT
   ============================================= */

.main {
    padding-top: 80px;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 var(--spacing-lg);
}

.section {
    padding: var(--spacing-3xl) 0;
    position: relative;
}

/* =============================================
   COMPONENTES DE NAVEGAÇÃO
   ============================================= */

/* Header principal, igual ao Painel */
/* Header */
  header {
    position: sticky;
    top: 0;
    z-index: 50;
    padding: var(--spacing-lg);
    background: var(--surface-glass);
    backdrop-filter: var(--blur-xl);
    border-bottom: 1px solid var(--border-subtle);
    transition: all var(--transition-normal);
  }

  header.scrolled {
    background: var(--bg-primary);
    box-shadow: var(--shadow-lg);
    border-bottom-color: var(--border-default);
  }

  .header-content {
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .brand {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    font-weight: 800;
    font-size: 20px;
    letter-spacing: -0.025em;
    color: var(--text-primary);
    transition: all var(--transition-normal);
  }

  .brand:hover { transform: scale(1.02); }

  .brand-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-md);
    background: var(--accent-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-lg), var(--shadow-glow);
    position: relative;
    overflow: hidden;
    transition: all var(--transition-normal);
  }

  .brand-icon:hover {
    transform: rotate(5deg) scale(1.1);
    box-shadow: var(--shadow-xl), var(--shadow-glow-strong);
  }

  .brand-icon::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
    transform: translateX(-100%);
    animation: shimmer 3s infinite;
  }

  .brand-icon svg {
    width: 20px;
    height: 20px;
    color: white;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
  }

/* Navegação Rápida */
.quick-nav {
    position: fixed;
    top: 0;
    right: -320px;
    width: 320px;
    height: 100%;
    background: var(--bg-secondary);
    backdrop-filter: blur(25px) saturate(200%);
    border-left: 1px solid var(--border-subtle);
    z-index: 400;
    transition: right var(--transition-normal);
    overflow-y: auto;
    padding: var(--spacing-lg);
}

.quick-nav.open {
    right: 0;
    box-shadow: -10px 0 40px rgba(0, 0, 0, 0.4);
	z-index: 999;
}

.quick-nav-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-xl);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border-subtle);
}

.quick-nav-title {
    font-weight: 600;
    font-size: 1.125rem;
    color: var(--text-primary);
}

.quick-nav-close {
    background: none;
    border: none;
    cursor: pointer;
    padding: var(--spacing-sm);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    transition: all var(--transition-normal);
}

.quick-nav-close:hover {
    background: var(--surface-hover);
    color: var(--text-primary);
}

.quick-nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
    margin-bottom: var(--spacing-xl);
}

.nav-group {
    margin-bottom: var(--spacing-md);
}

.nav-group-title {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: var(--spacing-xs) var(--spacing-md);
    margin-bottom: var(--spacing-xs);
    border-bottom: 1px solid var(--border-subtle);
}

.nav-sublist {
    list-style: none;
    margin: 0;
    padding: 0;
}

.quick-nav-link {
    display: block;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-sm);
    transition: all var(--transition-normal);
    position: relative;
}

.quick-nav-link:hover {
    color: var(--text-primary);
    background: var(--surface-hover);
}

.quick-nav-link.active {
    color: var(--accent-primary);
    background: rgba(14, 165, 233, 0.15);
    border-left: 4px solid var(--accent-primary);
    padding-left: calc(var(--spacing-md) - 4px);
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(14, 165, 233, 0.2);
    transform: translateX(2px);
}

.quick-nav-toggle {
    position: fixed;
    top: 18%;
    right: 24px;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    background: var(--accent-gradient);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    transition: all var(--transition-normal);
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
    color: white;
}

.quick-nav-toggle:hover {
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
}

/* =============================================
   SIDEBAR MODERNA
   ============================================= */

.sidebar-nav {
    position: fixed;
    right: 16px;
    top: 80%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    z-index: 200;
    background: var(--bg-secondary);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-xl);
    padding: 16px 12px;
    backdrop-filter: blur(20px) saturate(180%);
    box-shadow: var(--shadow-lg), var(--shadow-glow);
    transition: all var(--transition-normal);
}

.sidebar-nav:hover {
    box-shadow: var(--shadow-xl), var(--shadow-glow-strong);
    transform: translateY(-50%) scale(1.02);
}

.nav-item {
    position: relative;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--surface);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-md);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all var(--transition-normal);
}

.nav-item:hover {
    background: var(--surface-hover);
    border-color: var(--border-accent);
    color: var(--text-primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-glow);
}

.nav-item:active {
    transform: translateY(0);
}

.nav-divider {
    width: 24px;
    height: 1px;
    background: var(--border-subtle);
    margin: 4px 0;
}

/* Tooltips */
.nav-item::after {
    content: attr(data-tooltip);
    position: absolute;
    right: 100%;
    top: 50%;
    transform: translateY(-50%);
    margin-right: 12px;
    padding: 8px 12px;
    background: var(--bg-primary);
    color: var(--text-primary);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: all var(--transition-normal);
    z-index: 998;
    box-shadow: var(--shadow-md);
}

.nav-item:hover::after {
    opacity: 1;
    transform: translateY(-50%) translateX(-4px);
}

/* Estados dos botões */
.contrast-toggle[aria-pressed="true"],
.theme-toggle[aria-pressed="true"] {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
}

/* Animações dos ícones */
.sun-icon, .moon-icon {
    position: absolute;
    transition: all var(--transition-normal);
}

.sun-icon {
    opacity: 0;
    transform: rotate(180deg) scale(0.8);
}

.moon-icon {
    opacity: 1;
    transform: rotate(0deg) scale(1);
}

[data-theme="light"] .sun-icon {
    opacity: 1;
    transform: rotate(0deg) scale(1);
}

[data-theme="light"] .moon-icon {
    opacity: 0;
    transform: rotate(-180deg) scale(0.8);
}

/* Responsividade */
    .sidebar-nav {
        right: 16px;
        padding: 12px 8px;
        gap: 8px;
    }
    
    .quick-nav-toggle {
        right: 16px;
    }
    
    /* Em mobile, sidebar fica mais discreta */
    .sidebar-nav {
        transform: translateY(-50%) scale(0.9);
    }
    
    .sidebar-nav:hover {
        transform: translateY(-50%) scale(0.95);
    }
}

@media (max-width: 480px) {
    .sidebar-nav {
        right: 12px;
        padding: 10px 6px;
        transform: translateY(-50%) scale(0.85);
    }
    
    .quick-nav-toggle {
        right: 12px;
        width: 40px;
        height: 40px;
    }
    
    /* Em telas muito pequenas, sidebar some */
    @media (max-width: 360px) {
        .sidebar-nav {
            display: none;
        }
    }
}

/* =============================================
   ANIMAÇÕES DE CONVIVÊNCIA
   ============================================= */

/* Quando quick-nav abre, sidebar recua */
.quick-nav.open ~ .sidebar-nav {
    transform: translateY(-50%) translateX(-20px) scale(0.95);
    opacity: 0.7;
    pointer-events: none;
}

/* Feedback widget também recua */
.quick-nav.open ~ .feedback-widget {
    transform: translateX(-20px);
    opacity: 0.7;
    pointer-events: none;
}

/* =============================================
   COMPONENTES DE MARCA E CONTROLES
   ============================================= */

/* Performance optimizations */
    .hero::before {
      animation: none !important;
    }
    
    .brand-icon::before,
    .btn::before {
      animation: none !important;
    }
  }

/* Controles de Acessibilidade */
.accessibility-controls {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.contrast-toggle,
.theme-toggle {
    position: relative;
    width: 40px;
    height: 40px;
    border: 1px solid var(--border-subtle);
    background: var(--surface);
    border-radius: var(--radius-md);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-normal);
    overflow: hidden;
}

.contrast-toggle::before,
.theme-toggle::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--accent-gradient);
    opacity: 0;
    transition: opacity var(--transition-normal);
    z-index: -1;
}

.contrast-toggle:hover,
.theme-toggle:hover {
    background: var(--surface-hover);
    border-color: var(--border-accent);
    box-shadow: var(--shadow-glow);
    transform: translateY(-1px);
}

.contrast-toggle:hover::before,
.theme-toggle:hover::before {
    opacity: 0.1;
}

.contrast-toggle[aria-pressed="true"] {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
}

.sun-icon,
.moon-icon {
    position: absolute;
    width: 18px;
    height: 18px;
    transition: all var(--transition-normal);
}

.sun-icon {
    opacity: 0;
    transform: rotate(180deg) scale(0.8);
}

.moon-icon {
    opacity: 1;
    transform: rotate(0deg) scale(1);
}

[data-theme="light"] .sun-icon {
    opacity: 1;
    transform: rotate(0deg) scale(1);
}

[data-theme="light"] .moon-icon {
    opacity: 0;
    transform: rotate(-180deg) scale(0.8);
}

/* =============================================
   COMPONENTES DE HERO
   ============================================= */

.hero {
    padding: var(--spacing-3xl) 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, rgba(14, 165, 233, 0.1) 0%, transparent 70%);
    transform: translate(-50%, -50%);
    animation: pulse 4s ease-in-out infinite;
    pointer-events: none;
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm) var(--spacing-lg);
    background: var(--surface-glass);
    border: 1px solid var(--border-accent);
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    color: var(--accent-primary);
    margin-bottom: var(--spacing-lg);
    backdrop-filter: var(--blur-md);
    box-shadow: var(--shadow-md), var(--shadow-glow);
    transition: all var(--transition-normal);
    animation: fadeInUp 0.6s ease-out;
}

.hero-badge:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: var(--shadow-lg), var(--shadow-glow-strong);
}

.hero-badge::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--accent-primary);
    animation: pulse-dot 2s infinite;
    box-shadow: 0 0 10px var(--accent-primary);
}

.hero-title {
    font-size: clamp(36px, 5vw, 72px);
    font-weight: 900;
    margin-bottom: var(--spacing-lg);
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--accent-primary) 50%, var(--accent-tertiary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
    animation: fadeInUp 0.8s ease-out 0.2s both;
    position: relative;
}

.hero-subtitle {
    font-size: clamp(18px, 2.5vw, 24px);
    color: var(--text-secondary);
    max-width: 800px;
    margin: 0 auto var(--spacing-2xl);
    line-height: 1.2;
    animation: fadeInUp 1s ease-out 0.4s both;
}

/* =============================================
   COMPONENTES DE SEÇÃO
   ============================================= */

.section-title {
    font-size: clamp(1.875rem, 3vw, 2.5rem);
    font-weight: 700;
    letter-spacing: -0.025em;
    margin-bottom: var(--spacing-xl);
    text-align: center;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    width: 100px;
    height: 4px;
    background: var(--accent-gradient);
    transform: translateX(-50%);
    border-radius: 2px;
    animation: expandWidth 1s ease-out 1s both;
}

.section-subtitle {
    font-size: 1.125rem;
    color: var(--text-secondary);
    text-align: center;
    max-width: 800px;
    margin: 0 auto var(--spacing-2xl);
    position: relative;
    z-index: 1;
}

/* =============================================
   SISTEMA DE CARDS
   ============================================= */

/* Cards Base */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--spacing-lg);
    gap: var(--spacing-md);
}

.standard-card,
.comparison-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    transition: all var(--transition-normal);
    position: relative;
    overflow: hidden;
    margin-bottom: var(--spacing-lg);
}

.standard-card:hover,
.comparison-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.standard-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--spacing-lg);
    gap: var(--spacing-md);
}

/* Badges */
.standard-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 600;
}

.standard-badge.primary {
    background: rgba(56, 189, 248, 0.1);
    color: var(--accent-primary);
    border: 1px solid rgba(56, 189, 248, 0.2);
}

.standard-badge.success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.standard-badge.warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

/* Cards de Comparação */
.before-card {
    border-left: 4px solid var(--error);
}

.after-card {
    border-left: 4px solid var(--success);
}

.comparison-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

/* Componentes Especiais */
.definition-box {
    background: var(--surface);
    border-radius: var(--radius-md);
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    border-left: 4px solid var(--accent-primary);
}

.definition-box p {
    margin: 0;
    line-height: 1.5;
}

.definition-box p:first-child {
    font-weight: 600;
    color: var(--text-primary);
}

.tip-card {
    border-left: 4px solid var(--warning);
}

.tip-content {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
}

.tip-content h4 {
    margin: 0 0 var(--spacing-xs) 0;
    color: var(--text-primary);
}

.tip-content p {
    margin: 0;
    color: var(--text-secondary);
    line-height: 1.6;
}

/* =============================================
   SISTEMA DE GRADE
   ============================================= */

.standard-grid {
    display: grid;
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.standard-grid-2 { grid-template-columns: 1fr 1fr; }
.standard-grid-3 { grid-template-columns: 1fr 1fr 1fr; }

.standard-grid-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    background: var(--surface);
}

.standard-grid-item.positive { background: rgba(16, 185, 129, 0.05); }
.standard-grid-item.negative { background: rgba(239, 68, 68, 0.05); }
.standard-grid-item.neutral { background: rgba(56, 189, 248, 0.05); }

/* =============================================
   COMPONENTES DE CONTEÚDO
   ============================================= */

/* FAQ System */
.faq-container {
    max-width: 800px;
    margin: 0 auto var(--spacing-2xl);
}

.faq-item {
    background: var(--bg-secondary);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-md);
    overflow: hidden;
    transition: all var(--transition-normal);
    opacity: 0;
    transform: translateY(20px);
}

.faq-item:hover {
    border-color: var(--border-default);
    transform: translateY(-1px);
}

.faq-item[open] {
    border-color: var(--border-accent);
    box-shadow: var(--shadow-glow);
}

.faq-item summary {
    padding: var(--spacing-lg);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 600;
    font-size: 1rem;
    color: var(--text-primary);
    transition: all var(--transition-normal);
    list-style: none;
}

.faq-item summary:hover {
    background: var(--surface-hover);
}

.faq-item .details-content {
    padding: 0 var(--spacing-lg) var(--spacing-lg);
    color: var(--text-secondary);
    line-height: 1.7;
    font-size: 0.95rem;
}

/* Details/Summary General */
details {
    background: var(--bg-secondary);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-lg);
    overflow: hidden;
    transition: all var(--transition-normal);
}

details:hover {
    border-color: var(--border-default);
}

details[open] {
    border-color: var(--border-accent);
    box-shadow: var(--shadow-glow);
}

summary {
    padding: var(--spacing-lg);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 600;
    font-size: 1.125rem;
    color: var(--text-primary);
    transition: all var(--transition-normal);
    list-style: none;
}

summary:hover {
    background: var(--surface-hover);
}

.summary-icon {
    width: 20px;
    height: 20px;
    transition: transform var(--transition-normal);
}

details[open] .summary-icon {
    transform: rotate(180deg);
}

.details-content {
    padding: 0 var(--spacing-lg) var(--spacing-lg);
    color: var(--text-secondary);
    line-height: 1.7;
}

/* =============================================
   COMPONENTES DE INTERAÇÃO
   ============================================= */

.standard-copy-btn {
    background: var(--surface);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 8px 12px;
    font-size: 12px;
    color: var(--text-secondary);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all var(--transition-normal);
}

.standard-copy-btn:hover {
    background: var(--surface-hover);
    border-color: var(--border-accent);
}

/* =============================================
   COMPONENTES DE EXEMPLO
   ============================================= */

.standard-example {
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.875rem;
    line-height: 1.6;
    background: var(--bg-primary);
    border: 1px solid var(--border-accent);
}

.standard-example-content {
    color: var(--text-secondary);
}

.prompt-example {
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.875rem;
    line-height: 1.6;
}

.prompt-example.generic {
    background: rgba(239, 68, 68, 0.05);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: var(--error);
    font-style: italic;
}

.prompt-example.structured {
    background: var(--bg-primary);
    border: 1px solid var(--border-accent);
}

.example-section {
    margin-bottom: var(--spacing-xl);
}

.example-section:last-child {
    margin-bottom: 0;
}

.example-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: var(--spacing-md);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.resultado-box {
    background: rgba(16, 185, 129, 0.05);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
}

.resultado-descricao {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-top: var(--spacing-md);
    margin-bottom: 0;
    line-height: 1.6;
}

/* =============================================
   COMPONENTES DE FEEDBACK
   ============================================= */

.toast {
    position: fixed;
    left: 50%;
    transform: translateX(-50%);
    bottom: calc(env(safe-area-inset-bottom, 0px) + 16px);
    padding: var(--spacing-md) var(--spacing-lg);
    border-radius: var(--radius-md);
    border: 1px solid;
    font-weight: 500;
    font-size: 0.875rem;
    z-index: 1100;
    max-width: 90vw;
    animation: slideUp 0.3s ease-out;
}

.toast.success {
    background: rgba(16, 185, 129, .1);
    border-color: var(--success);
    color: var(--success);
}

.toast.error {
    background: rgba(239, 68, 68, .1);
    border-color: var(--error);
    color: var(--error);
}

/* =============================================
   COMPONENTES DE PROGRESSO
   ============================================= */

.scroll-progress {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 4px;
    background: var(--accent-gradient);
    z-index: 1000;
    transition: width 0.1s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 0 20px rgba(14, 165, 233, 0.5), 0 0 40px rgba(14, 165, 233, 0.3);
}

.scroll-progress::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 20px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4));
    animation: shimmer 2s ease-in-out infinite;
}

.reading-progress {
    padding: var(--spacing-lg);
    background: var(--surface);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-subtle);
}

.reading-progress-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: var(--spacing-sm);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.reading-progress-bar {
    width: 100%;
    height: 8px;
    background: var(--surface-elevated);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: var(--spacing-sm);
}

.reading-progress-fill {
    height: 100%;
    background: var(--accent-gradient);
    border-radius: 4px;
    transition: width var(--transition-normal);
    width: 0%;
}

.reading-progress-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--accent-primary);
    text-align: center;
}

/* =============================================
   UTILITÁRIOS E COMPONENTES ESPECÍFICOS
   ============================================= */

.step-indicator {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.step-number {
    width: 24px;
    height: 24px;
    background: var(--accent-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 12px;
    margin-right: 8px;
}

.step-label {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1.125rem;
}

.standard-icon {
    flex-shrink: 0;
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.prompt-number {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    background: var(--accent-gradient);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

/* =============================================
   LAYOUTS ESPECÍFICOS
   ============================================= */

.vertical-comparison {
    margin-bottom: var(--spacing-3xl);
    position: relative;
}

.comparison-title,
.section-standard-title {
    font-size: 1.5rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: var(--spacing-xl);
    color: var(--text-primary);
    position: relative;
}

.comparison-title::after,
.section-standard-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 2px;
    background: var(--accent-gradient);
    border-radius: 1px;
}

.problem-grid,
.benefit-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-sm);
}

.problem-item,
.benefit-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
}

.problem-item {
    background: rgba(239, 68, 68, 0.05);
    color: var(--text-secondary);
}

.benefit-item {
    background: rgba(16, 185, 129, 0.05);
    color: var(--text-secondary);
}

.problem-icon,
.benefit-icon {
    flex-shrink: 0;
    font-size: 1rem;
}

.transition-arrow {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: var(--spacing-lg) 0;
    position: relative;
}

.arrow-line {
    width: 2px;
    height: 40px;
    background: var(--accent-gradient);
    margin-bottom: 4px;
}

.arrow-head {
    color: var(--accent-primary);
    margin-bottom: var(--spacing-xs);
}

.arrow-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--accent-primary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.section-standard {
    margin-bottom: var(--spacing-3xl);
    position: relative;
}

.card-intro {
    color: var(--text-secondary);
    margin-bottom: var(--spacing-lg);
    font-size: 0.9375rem;
    line-height: 1.6;
}

.criteria-comparison {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
}

.criteria-column {
    display: flex;
    flex-direction: column;
}

.criteria-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-md);
}

.criteria-header.success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.criteria-header.error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.criteria-header h4 {
    margin: 0;
    color: var(--text-primary);
}

.criteria-icon {
    font-size: 1.25rem;
}

.cards-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.test-question {
    background: var(--surface);
    border-radius: var(--radius-md);
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    font-style: italic;
    border-left: 4px solid var(--warning);
}

.refinement-cycle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-md);
    flex-wrap: wrap;
    gap: var(--spacing-sm);
}

.cycle-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-xs);
    flex: 1;
}

.cycle-step .step-number {
    width: 32px;
    height: 32px;
    background: var(--accent-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.step-text {
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    color: var(--text-secondary);
}

.cycle-arrow {
    color: var(--accent-primary);
    font-weight: 600;
}

.cycle-note {
    font-size: 0.875rem;
    color: var(--text-secondary);
    text-align: center;
    margin: 0;
    font-style: italic;
}

.success-tips {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.tip-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-sm);
    border-radius: var(--radius-md);
    background: var(--surface);
    font-size: 1.25rem;
    flex-shrink: 0;
}

.tip-item div:last-child {
    color: var(--text-secondary);
    font-size: 0.875rem;
    line-height: 1.4;
}

/* =============================================
   SISTEMA DE FEEDBACK
   ============================================= */

.feedback-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1000;
}

.feedback-trigger {
    background: var(--accent-gradient);
    border: none;
    border-radius: 50px;
    padding: 12px 20px;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: var(--shadow-lg);
    transition: all var(--transition-normal);
}

.feedback-trigger:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.feedback-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all var(--transition-normal);
    padding: var(--spacing-lg);
}

.feedback-modal.active {
    opacity: 1;
    visibility: visible;
}

.feedback-content {
    background: var(--bg-secondary);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    width: 100%;
    max-width: 500px;
    border: 1px solid var(--border-subtle);
    box-shadow: var(--shadow-xl);
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
}

.feedback-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: var(--spacing-lg);
}

.feedback-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.25rem;
}

.feedback-close {
    background: var(--surface);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-md);
    padding: 8px;
    cursor: pointer;
    color: var(--text-secondary);
    transition: all var(--transition-normal);
    flex-shrink: 0;
    margin-left: var(--spacing-md);
}

.feedback-close:hover {
    background: var(--surface-hover);
    color: var(--text-primary);
}

.feedback-form {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.feedback-group {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.feedback-group label {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.feedback-group select,
.feedback-group textarea,
.feedback-group input {
    padding: var(--spacing-md);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-md);
    background: var(--bg-primary);
    color: var(--text-primary);
    font-family: inherit;
    font-size: 0.875rem;
    transition: all var(--transition-normal);
}

.feedback-group select:focus,
.feedback-group textarea:focus,
.feedback-group input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.feedback-group textarea {
    resize: vertical;
    min-height: 120px;
    line-height: 1.5;
}

.feedback-actions {
    display: flex;
    justify-content: flex-end;
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.feedback-submit {
    background: var(--accent-gradient);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    padding: var(--spacing-md) var(--spacing-lg);
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all var(--transition-normal);
}

.feedback-submit:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.feedback-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* =============================================
   ANIMAÇÕES
   ============================================= */

@keyframes pulse {
    0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
    50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.2); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes expandWidth {
    from { width: 0; }
    to { width: 100px; }
}

@keyframes shimmer {
    0%, 100% { opacity: 0; }
    50% { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

/* =============================================
   ACESSIBILIDADE
   ============================================= */

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

*:focus-visible {
    outline: 3px solid var(--accent-primary);
    outline-offset: 2px;
}

[data-contrast="high"] *:focus-visible {
    outline: 4px solid #00ffff;
    outline-offset: 3px;
}

.quick-nav-link:focus-visible {
    outline: 3px solid var(--accent-primary);
    outline-offset: 3px;
    transform: translateY(-2px);
    box-shadow: var(--shadow-glow);
}

button:focus-visible,
.contrast-toggle:focus-visible,
.theme-toggle:focus-visible,
.quick-nav-toggle:focus-visible {
    outline: 3px solid var(--accent-primary);
    outline-offset: 3px;
}

a:focus-visible {
    outline: 3px solid var(--accent-primary);
    outline-offset: 2px;
    border-radius: 2px;
}

details summary:focus-visible {
    outline: 3px solid var(--accent-primary);
    outline-offset: 2px;
    border-radius: var(--radius-sm);
    background: var(--surface-hover);
}

/* =============================================
   ANIMAÇÕES DE SCROLL
   ============================================= */

.section.visible {
    opacity: 1;
    transform: translateY(0);
}

.animate-in {
    opacity: 1;
    transform: translateY(0);
}

.reveal-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.reveal-on-scroll.revealed {
    opacity: 1;
    transform: translateY(0);
}

.section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%) scaleY(0);
    width: 1px;
    height: 100px;
    background: linear-gradient(to bottom, transparent, var(--accent-primary), transparent);
    opacity: 0.3;
    transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    transform-origin: top;
}

.section.visible::before {
    transform: translateX(-50%) scaleY(1);
}

/* =============================================
   RESPONSIVIDADE
   ============================================= */

@media (max-width: 768px) {
    .standard-card-header,
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }
    
    .standard-grid-2,
    .standard-grid-3,
    .problem-grid,
    .benefit-grid,
    .comparison-grid,
    .criteria-comparison,
    .cards-grid-2 {
        grid-template-columns: 1fr;
    }
    
    .standard-card,
    .comparison-card {
        padding: var(--spacing-lg);
    }
    
    .prompt-example {
        padding: var(--spacing-md);
        font-size: 0.8125rem;
    }
    
    .container,
    .header-content {
        max-width: 100%;
        padding: 0 var(--spacing-md);
    }

    /* Feedback Mobile */
    .feedback-widget {
        bottom: 16px;
        right: 16px;
    }
    
    .feedback-trigger {
        padding: 10px 16px;
        font-size: 0.8125rem;
    }
    
    .feedback-content {
        padding: var(--spacing-lg);
        margin: var(--spacing-md);
    }
    
    .feedback-modal {
        padding: var(--spacing-md);
    }
.sidebar-nav {
    top: auto;
    bottom: calc(16px + env(safe-area-inset-bottom, 0px));
    right: calc(16px + env(safe-area-inset-right, 0px));
  }
}

@media (max-width: 480px) {
    .brand-text {
        display: none;
    }
    
    .accessibility-controls {
        gap: var(--spacing-xs);
    }
    
    .contrast-toggle,
    .theme-toggle {
        width: 36px;
        height: 36px;
    }
    
    .hero {
        padding: var(--spacing-xl) 0;
    }
    
    .hero-title {
        font-size: 1.875rem;
        margin-bottom: var(--spacing-sm);
    }
    
    .hero-subtitle {
        font-size: 0.9375rem;
    }
    
    .section {
        padding: var(--spacing-xl) 0;
    }
    
    .section-title {
        font-size: 1.5rem;
        margin-bottom: var(--spacing-md);
    }
    
    .section-subtitle {
        font-size: 0.9375rem;
        margin-bottom: var(--spacing-lg);
    }
    
    .standard-card,
    .comparison-card {
        padding: var(--spacing-md);
    }
    
    .comparison-title,
    .section-standard-title {
        font-size: 1.25rem;
    }
    
    .prompt-element {
        flex-direction: column;
        gap: var(--spacing-xs);
    }
    
    .quick-nav-toggle {
        right: var(--spacing-sm);
        width: 36px;
        height: 36px;
    }

    /* Feedback Mobile Small */
    .feedback-trigger span {
        display: none;
    }
    
    .feedback-trigger {
        padding: 12px;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        justify-content: center;
    }
}

@media (max-width: 360px) {
    :root {
        --spacing-xs: 2px;
        --spacing-sm: 6px;
        --spacing-md: 12px;
        --spacing-lg: 18px;
        --spacing-xl: 24px;
        --spacing-2xl: 36px;
        --spacing-3xl: 48px;
    }
    
    .container {
        padding: 0 var(--spacing-sm);
    }
    
    .header-content {
        padding: var(--spacing-xs) var(--spacing-sm);
        gap: var(--spacing-sm);
    }
    
    .contrast-toggle,
    .theme-toggle {
        width: 32px;
        height: 32px;
        min-width: 32px;
        min-height: 32px;
    }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

</head>

<body>
  <div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>

<header id="header">
  <div class="header-content">
    <a href="https://fluxoteca.com.br" class="brand" aria-label="Fluxoteca página inicial">
      <div class="brand-icon">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
      </div>
      <span>Fluxoteca</span>
    </a>
  </div>
</header>

  <!-- Modern Sidebar Navigation -->
  <nav class="sidebar-nav" aria-label="Navegação rápida">      
    <button class="nav-item" onclick="scrollToTop()" data-tooltip="Voltar ao topo" aria-label="Voltar ao Topo">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
      </svg>
    </button>
    
    <div class="nav-divider" aria-hidden="true"></div>
    
    <button id="theme-toggle-sidebar" class="nav-item theme-toggle" onclick="toggleTheme()" aria-label="Alternar tema" data-tooltip="Alternar tema">
      <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/>
      </svg>
      <svg class="moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z"/>
      </svg>
    </button>

    <button id="contrast-toggle-sidebar" class="nav-item contrast-toggle" onclick="toggleContrast()" aria-label="Alternar contraste" data-tooltip="Alto contraste">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 2a10 10 0 0 0 0 20z"/>
      </svg>
    </button>
  </nav>

  <!-- Quick Navigation Sidebar -->
    <nav class="quick-nav" id="quick-nav" role="navigation" aria-label="Navegação rápida" tabindex="-1">
   <div class="quick-nav-header"><span class="quick-nav-title">Navegação</span> <button class="quick-nav-close" id="quick-nav-close" aria-label="Fechar navegação rápida">
     <svg width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line> <line x1="6" y1="6" x2="18" y2="18"></line>
     </svg></button>
   </div>
   <ul class="quick-nav-list">
    <li><a href="#hero" class="quick-nav-link" data-section="hero">Início</a></li>
    <li class="nav-group"><span class="nav-group-title">Fundamentos</span>
     <ul class="nav-sublist">
      <li><a href="#por-que" class="quick-nav-link" data-section="por-que">Por que usar</a></li>
      <li><a href="#estrutura" class="quick-nav-link" data-section="estrutura">Estrutura</a></li>
      <li><a href="#diferenca" class="quick-nav-link" data-section="diferenca">Diferença</a></li>
     </ul></li>
    <li class="nav-group"><span class="nav-group-title">Prática</span>
     <ul class="nav-sublist">
      <li><a href="#exemplo" class="quick-nav-link" data-section="exemplo">Exemplo</a></li>
      <li><a href="#contexto-info" class="quick-nav-link" data-section="contexto-info">Contexto vs Info</a></li>
      <li><a href="#medicao" class="quick-nav-link" data-section="medicao">Medição</a></li>
     </ul></li>
    <li class="nav-group"><span class="nav-group-title">Recursos</span>
     <ul class="nav-sublist">
      <li><a href="#tecnicas" class="quick-nav-link" data-section="tecnicas">Técnicas</a></li>
      <li><a href="#bonus" class="quick-nav-link" data-section="bonus">Prompts Bônus</a></li>
      <li><a href="#faq" class="quick-nav-link" data-section="faq">FAQ</a></li>
      <li><a href="#recursos" class="quick-nav-link" data-section="recursos">Recursos</a></li>
     </ul></li>
   </ul>

	<!-- Reading Progress -->
   <div class="reading-progress">
    <div class="reading-progress-label">
     Progresso de leitura
    </div>
    <div class="reading-progress-bar">
     <div class="reading-progress-fill" id="reading-progress-fill"></div>
    </div>
    <div class="reading-progress-text" id="reading-progress-text">
     0%
    </div>
   </div>
  </nav>

	<!-- Quick Nav Toggle Button --> 
<button class="quick-nav-toggle" id="quick-nav-toggle"
  aria-label="Abrir navegação rápida"
  aria-controls="quick-nav"
  aria-expanded="false" title="Navegação rápida">
   <svg width="20" height="20" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line> <line x1="3" y1="6" x2="21" y2="6"></line> <line x1="3" y1="18" x2="21" y2="18"></line>
   </svg></button> 

 
<main class="main" id="app-main">

<!-- Hero Section -->
<section class="hero" aria-labelledby="hero-title" id="hero">
  <div class="container">
    <div class="hero-content">
      <h1 class="hero-title reveal-on-scroll revealed" id="hero-title">
  <span class="sr-only">Guia Inteligente de Prompts para PMEs</span>
  <span aria-hidden="true">Guia de Prompts</span>
  <br aria-hidden="true">
  <span aria-hidden="true">Inteligentes para PMEs</span>
</h1>
      <p class="hero-subtitle reveal-on-scroll revealed" id="hero-subtitle">
        Transforme suas interações com IA em resultados práticos, com prompts estruturados para seu negócio
      </p>     
    </div>
  </div>
</section>

<!-- Por que prompts inteligentes ajudam PMEs -->
<section class="section section-transition" id="por-que">
  <div class="container">
    <h2 class="section-title">O impacto prático dos prompts inteligentes no seu negócio</h2>
    <p class="section-subtitle">Como prompts bem estruturados transformam a maneira como sua equipe resolve problemas e toma decisões</p>
    
    <div class="section-standard">
      <!-- Benefício 1 -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle><polyline points="12,6 12,12 16,14"></polyline>
            </svg>
            Economia de Tempo
          </div>
        </div>
        <div class="card-content">
          <div class="standard-example">
            <div class="standard-example-content">
              <strong>Antes:</strong> Várias tentativas, respostas genéricas, tempo perdido ajustando perguntas<br>
              <strong>Com prompts inteligentes:</strong> Respostas completas e aplicáveis de primeira
            </div>
          </div>
          <div class="standard-grid standard-grid-2">
            <div class="standard-grid-item negative">
              <div class="standard-icon">❌</div>
              <div>3–4 horas para análise de concorrência</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">✅</div>
              <div>15 minutos para análise completa</div>
            </div>
            <div class="standard-grid-item negative">
              <div class="standard-icon">❌</div>
              <div>Resultado: informações desorganizadas</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">✅</div>
              <div>Resultado: relatório estruturado e profissional</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Benefício 2 -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22,4 12,14.01 9,11.01"></polyline>
            </svg>
            Padronização de Qualidade
          </div>
        </div>
        <div class="card-content">
          <div class="standard-example">
            <div class="standard-example-content">
              <strong>Desafio comum:</strong> Cada colaborador obtém resultados diferentes da IA<br>
              <strong>Solução:</strong> Prompts estruturados garantem padrão profissional para todos
            </div>
          </div>
          <div class="standard-grid standard-grid-2">
            <div class="standard-grid-item positive">
              <div class="standard-icon">👥</div>
              <div>Estagiário e sócio geram a mesma qualidade</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">📊</div>
              <div>Documentos com padrão de consultoria</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">🎯</div>
              <div>Formato consistente em todos os departamentos</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">⚡</div>
              <div>Redução de retrabalho e revisões</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Benefício 3 -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge warning">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            Redução de Custos
          </div>
        </div>
        <div class="card-content">
          <div class="standard-example">
            <div class="standard-example-content">
              <strong>Substitui:</strong> Horas de consultores especializados em cada área<br>
              <strong>Entrega:</strong> Análises profundas usando apenas sua assinatura de IA
            </div>
          </div>
          <div class="standard-grid standard-grid-2">
            <div class="standard-grid-item negative">
              <div class="standard-icon">💸</div>
              <div>Consultoria SWOT: R$ 3.000</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">💰</div>
              <div>Prompt inteligente: custo zero adicional</div>
            </div>
            <div class="standard-grid-item negative">
              <div class="standard-icon">⏱️</div>
              <div>Espera por especialista externo</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">🚀</div>
              <div>Resposta imediata da IA</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

	<!-- O que torna um prompt inteligente -->
<section class="section" id="estrutura">
  <div class="container">
    <h2 class="section-title">O que torna um prompt inteligente?</h2>
    <p class="section-subtitle">Todo prompt eficaz segue uma estrutura de 5 camadas fundamentais</p>
    
    <div class="section-standard">
      <!-- Camada 1 -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <div class="step-number">1</div>
            "Atue como" (Especifique o Especialista)
          </div>
        </div>
        <div class="card-content">
          <div class="standard-example">
            <div class="standard-example-content">
              <strong>Por que importa:</strong> Define o conhecimento e perspectiva que a IA usará para responder.<br><br>
              <strong>❌ Genérico:</strong> "especialista em marketing"<br>
              <strong>✅ Específico:</strong> "consultor de marketing digital para pequenas lojas de roupas com orçamento limitado"
            </div>
          </div>
          <div class="standard-grid">
            <div class="standard-grid-item positive">
              <div class="standard-icon">🎯</div>
              <div>Terminologia correta para seu setor</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">💡</div>
              <div>Perspectiva adequada ao tamanho da empresa</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">⚡</div>
              <div>Respostas com profundidade contextual</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Camada 2 -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <div class="step-number">2</div>
            "Cenário" (Contexto do Negócio)
          </div>
        </div>
        <div class="card-content">
          <div class="standard-example">
            <div class="standard-example-content">
              <strong>Por que importa:</strong> A IA precisa entender onde sua empresa está inserida para dar conselhos relevantes.<br><br>
              <strong>❌ Vago:</strong> "Quero aumentar vendas"<br>
              <strong>✅ Contextualizado:</strong> "Minha loja de roupas fitness está em um bairro comercial, concorrendo com 3 grandes redes..."
            </div>
          </div>
          <div class="standard-grid">
            <div class="standard-grid-item positive">
              <div class="standard-icon">🏢</div>
              <div>Localização e ambiente de mercado</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">🎯</div>
              <div>Público-alvo específico</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">⚡</div>
              <div>Situação atual e desafios</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Camada 3 -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <div class="step-number">3</div>
            "Informações" (Dados Concretos)
          </div>
        </div>
        <div class="card-content">
          <div class="standard-example">
            <div class="standard-example-content">
              <strong>Por que importa:</strong> Dados reais permitem recomendações práticas dentro da sua realidade.<br><br>
              <strong>❌ Sem dados:</strong> "Preciso de um plano de marketing"<br>
              <strong>✅ Com dados:</strong> "Orçamento: R$ 800/mês, equipe: 2 pessoas, histórico: 15% de conversão..."
            </div>
          </div>
          <div class="standard-grid">
            <div class="standard-grid-item positive">
              <div class="standard-icon">💰</div>
              <div>Orçamento realista</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">👥</div>
              <div>Recursos humanos disponíveis</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">📈</div>
              <div>Métricas e histórico</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Camada 4 -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <div class="step-number">4</div>
            "Diretrizes" (Regras do Jogo)
          </div>
        </div>
        <div class="card-content">
          <div class="standard-example">
            <div class="standard-example-content">
              <strong>Por que importa:</strong> Controla o formato, profundidade e limites da resposta.<br><br>
              <strong>❌ Sem direção:</strong> "Me ajude com isso"<br>
              <strong>✅ Com diretrizes:</strong> "Use tom profissional, mas acessível; evite jargões técnicos; máximo 5 estratégias..."
            </div>
          </div>
          <div class="standard-grid">
            <div class="standard-grid-item positive">
              <div class="standard-icon">🎨</div>
              <div>Tom de voz definido</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">📏</div>
              <div>Limites de extensão</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">🚫</div>
              <div>O que evitar especificado</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Camada 5 -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <div class="step-number">5</div>
            "Resultado" (Formato Esperado)
          </div>
        </div>
        <div class="card-content">
          <div class="standard-example">
            <div class="standard-example-content">
              <strong>Por que importa:</strong> Garante que você receba a informação no formato que pode usar imediatamente.<br><br>
              <strong>❌ Aberto:</strong> "Me dê algumas ideias"<br>
              <strong>✅ Específico:</strong> "Apresente em tópicos numerados com: estratégia, investimento, prazo, resultado esperado"
            </div>
          </div>
          <div class="standard-grid">
            <div class="standard-grid-item positive">
              <div class="standard-icon">📋</div>
              <div>Estrutura clara de resposta</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">🎯</div>
              <div>Camadas obrigatórias definidas</div>
            </div>
            <div class="standard-grid-item positive">
              <div class="standard-icon">⚡</div>
              <div>Pronto para implementação</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Diferença entre perguntar e direcionar -->
<section class="section" id="diferenca">
  <div class="container">
    <h2 class="section-title">Do genérico ao específico: a evolução do seu comando</h2>
    <p class="section-subtitle">Veja como a estrutura transforma perguntas vagas em soluções práticas para seu negócio</p>
    
    <!-- Primeiro Exemplo - Marketing -->
    <div class="vertical-comparison">
      <h3 class="comparison-title">Exemplo: Estratégia de Marketing</h3>
      
      <!-- Card Antes - Topo -->
      <div class="comparison-column">
        <div class="step-indicator">          
          <div class="step-label">Abordagem Genérica</div>
        </div>
        <div class="comparison-card before-card">
          <div class="card-header">
            <div class="card-badge error">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>
              </svg>
              O que não fazer
            </div>
          </div>
          <div class="card-content">
            <div class="prompt-example generic">
              <div class="example-text">
                "Como fazer marketing digital para minha empresa?"
              </div>
            </div>
            <div class="analysis-section">
              <h4>Por que isso não funciona bem:</h4>
              <div class="problem-grid">
                <div class="problem-item">
                  <div class="problem-icon">❌</div>
                  <div class="problem-text">Sem contexto do negócio</div>
                </div>
                <div class="problem-item">
                  <div class="problem-icon">❌</div>
                  <div class="problem-text">Nenhuma informação sobre recursos</div>
                </div>
                <div class="problem-item">
                  <div class="problem-icon">❌</div>
                  <div class="problem-text">Formato de resposta indefinido</div>
                </div>
                <div class="problem-item">
                  <div class="problem-icon">❌</div>
                  <div class="problem-text">Foco genérico, não específico</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Seta de Transição -->
      <div class="transition-arrow">
        <div class="arrow-line"></div>
        <div class="arrow-head">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14m0 0l-7-7m7 7l7-7"/>
          </svg>
        </div>
        <div class="arrow-text">Transforme em</div>
      </div>

      <!-- Card Depois - Base -->
      <div class="comparison-column">
        <div class="step-indicator">          
          <div class="step-label">Prompt Inteligente</div>
        </div>
        <div class="comparison-card after-card">
          <div class="card-header">
            <div class="card-badge success">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22,4 12,14.01 9,11.01"></polyline>
              </svg>
              Abordagem Estruturada
            </div>
            <button class="standard-copy-btn" onclick="copyPrompt(this)">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                  </svg>
                  Copiar Prompt
                </button>
          </div>
          <div class="card-content">
            <div class="prompt-example structured">
              <div class="example-text">
                <div class="prompt-text">
                  <div class="prompt-element">
                    <span class="element-icon">🧠</span>
                    <strong>Atue como:</strong> Especialista em marketing digital para PMEs do setor alimentício.
                  </div>
                  <div class="prompt-element">
                    <span class="element-icon">🎯</span>
                    <strong>Cenário:</strong> Sou dono de uma padaria artesanal em bairro residencial, com 15 anos de tradição, querendo atrair clientes de 25 a 45 anos.
                  </div>
                  <div class="prompt-element">
                    <span class="element-icon">📊</span>
                    <strong>Informações:</strong> Orçamento R$ 800/mês, equipe de 3 pessoas, sem experiência em redes sociais, produtos: pães artesanais, doces caseiros, café especial.
                  </div>
                  <div class="prompt-element">
                    <span class="element-icon">⚙️</span>
                    <strong>Diretrizes:</strong> Estratégias simples de executar, foco em redes sociais orgânicas, tom acolhedor e familiar, evitar jargões técnicos.
                  </div>
                  <div class="prompt-element">
                    <span class="element-icon">📝</span>
                    <strong>Resultado:</strong> Plano de 90 dias com 3 ações específicas por mês, incluindo tipos de conteúdo, frequência de posts e métricas para acompanhar.
                  </div>
                </div>
              </div>
            </div>
            <div class="analysis-section">
              <h4>Por que esta abordagem funciona:</h4>
              <div class="benefit-grid">
                <div class="benefit-item">
                  <div class="benefit-icon">✅</div>
                  <div class="benefit-text">Contexto específico do negócio</div>
                </div>
                <div class="benefit-item">
                  <div class="benefit-icon">✅</div>
                  <div class="benefit-text">Orçamento e recursos definidos</div>
                </div>
                <div class="benefit-item">
                  <div class="benefit-icon">✅</div>
                  <div class="benefit-text">Formato de resposta claro</div>
                </div>
                <div class="benefit-item">
                  <div class="benefit-icon">✅</div>
                  <div class="benefit-text">Estratégias realistas e aplicáveis</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Segundo Exemplo - Vendas -->
    <div class="vertical-comparison" style="margin-top: var(--spacing-3xl);">
      <h3 class="comparison-title">Exemplo: Otimização de Vendas</h3>
      
      <!-- Card Antes - Topo -->
      <div class="comparison-column">
        <div class="step-indicator">          
          <div class="step-label">Abordagem Genérica</div>
        </div>
        <div class="comparison-card before-card">
          <div class="card-header">
            <div class="card-badge error">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>
              </svg>
              O que não fazer
            </div>
          </div>
          <div class="card-content">
            <div class="prompt-example generic">
              <div class="example-text">
                "Como melhorar as vendas da minha loja?"
              </div>
            </div>
            <div class="analysis-section">
              <h4>Por que isso não funciona bem:</h4>
              <div class="problem-grid">
                <div class="problem-item">
                  <div class="problem-icon">❌</div>
                  <div class="problem-text">Nenhum dado sobre a situação atual</div>
                </div>
                <div class="problem-item">
                  <div class="problem-icon">❌</div>
                  <div class="problem-text">Sem informações sobre concorrência</div>
                </div>
                <div class="problem-item">
                  <div class="problem-icon">❌</div>
                  <div class="problem-text">Público-alvo não definido</div>
                </div>
                <div class="problem-item">
                  <div class="problem-icon">❌</div>
                  <div class="problem-text">Resultado esperado vago</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Seta de Transição -->
      <div class="transition-arrow">
        <div class="arrow-line"></div>
        <div class="arrow-head">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14m0 0l-7-7m7 7l7-7"/>
          </svg>
        </div>
        <div class="arrow-text">Transforme em</div>
      </div>

      <!-- Card Depois - Base -->
      <div class="comparison-column">
        <div class="step-indicator">          
          <div class="step-label">Prompt Inteligente</div>
        </div>
        <div class="comparison-card after-card">
          <div class="card-header">
            <div class="card-badge success">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22,4 12,14.01 9,11.01"></polyline>
              </svg>
              Abordagem Estruturada
            </div>
            <button class="standard-copy-btn" onclick="copyPrompt(this)">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                  </svg>
                  Copiar Prompt
                </button>
          </div>
          <div class="card-content">
            <div class="prompt-example structured">
              <div class="example-text">
                <div class="prompt-text">
                  <div class="prompt-element">
                    <span class="element-icon">🧠</span>
                    <strong>Atue como:</strong> Consultor de vendas especializado em varejo de moda feminina
                  </div>
                  <div class="prompt-element">
                    <span class="element-icon">🎯</span>
                    <strong>Cenário:</strong> Tenho uma boutique de roupas femininas no centro da cidade, com queda de 30% nas vendas nos últimos 6 meses devido à concorrência online.
                  </div>
                  <div class="prompt-element">
                    <span class="element-icon">📊</span>
                    <strong>Informações:</strong> Ticket médio atual: R$ 120, público-alvo: mulheres de 25 a 50 anos, classe B/C, localização: rua comercial com alto fluxo, concorrentes: 3 lojas similares em um raio de 500 m
                  </div>
                  <div class="prompt-element">
                    <span class="element-icon">⚙️</span>
                    <strong>Diretrizes:</strong> Soluções que não exijam investimento alto, foco em diferenciação da concorrência, aproveitar localização física como vantagem, estratégias implementáveis em 30 dias
                  </div>
                  <div class="prompt-element">
                    <span class="element-icon">📝</span>
                    <strong>Resultado:</strong> Lista de 5 estratégias priorizadas por impacto vs. esforço, com cronograma de implementação e investimento necessário para cada uma.
                  </div>
                </div>
              </div>
            </div>
            <div class="analysis-section">
              <h4>Por que esta abordagem funciona:</h4>
              <div class="benefit-grid">
                <div class="benefit-item">
                  <div class="benefit-icon">✅</div>
                  <div class="benefit-text">Dados concretos da situação</div>
                </div>
                <div class="benefit-item">
                  <div class="benefit-icon">✅</div>
                  <div class="benefit-text">Análise competitiva incluída</div>
                </div>
                <div class="benefit-item">
                  <div class="benefit-icon">✅</div>
                  <div class="benefit-text">Público bem definido</div>
                </div>
                <div class="benefit-item">
                  <div class="benefit-icon">✅</div>
                  <div class="benefit-text">Resultado estruturado e priorizado</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Exemplo Prático Completo -->
<section class="section" id="exemplo">
  <div class="container">
    <h2 class="section-title">Exemplo Prático Completo</h2>
    <p class="section-subtitle">Veja como as 5 camadas se conectam em um caso real do início ao fim</p>
    
    <div class="section-standard">
      <!-- Card Principal do Exemplo -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 11l3-3-3-3"></path><path d="M12 4h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-7"></path><path d="M3 12h11"></path>
            </svg>
            Cenário: Otimização de Processo de Vendas
          </div>
        </div>
        
        <div class="card-content">
          <!-- Prompt Completo -->
          <div class="example-section">            
            <div class="standard-example">
              <div class="example-header">
                <button class="standard-copy-btn" onclick="copyPrompt(this)">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                  </svg>
                  Copiar Prompt
                </button>
              </div>
              <div class="standard-example-content prompt-text">
                🧠 <strong>Atue como:</strong> Consultor especializado em otimização de processos para PMEs do setor de serviços.<br><br>
                🎯 <strong>Entenda o seguinte cenário:</strong> Minha empresa de consultoria em marketing digital tem 5 colaboradores e processo de vendas desorganizado. Perdemos oportunidades por falta de acompanhamento sistemático dos leads.<br><br>
                📊 <strong>Considere as informações disponíveis:</strong> 1. Ticket médio R$ 2.500; 2. Taxa de conversão atual de 8%; 3. Tempo médio de fechamento: 45 dias; 4. Usamos apenas WhatsApp e e-mail para follow-up.<br><br>
                ⚙️ <strong>Siga estas diretrizes:</strong> • Foco em soluções de baixo custo; • Máximo 3 ferramentas sugeridas; • Processo simples para equipe pequena; • Evitar jargões complexos.<br><br>
                📝 <strong>Apresente o resultado assim:</strong> Fluxograma do processo de vendas otimizado em 5 etapas, lista de 3 ferramentas essenciais com custo mensal, e plano de implementação em 30 dias.
              </div>
            </div>
          </div>

          <!-- Análise das Camadas Aplicadas -->
          <div class="example-section">
            <h4 class="example-title">🔍 Análise das Camadas Aplicadas</h4>
            <div class="standard-grid">
              <div class="standard-grid-item positive">
                <div class="standard-icon">1</div>
                <div>
                  <strong>Atue como:</strong> Especifica a expertise exata necessária para o problema
                </div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">2</div>
                <div>
                  <strong>Cenário:</strong> Contexto empresarial real com desafio específico
                </div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">3</div>
                <div>
                  <strong>Informações:</strong> Dados concretos e métricas mensuráveis
                </div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">4</div>
                <div>
                  <strong>Diretrizes:</strong> Limitações práticas e restrições do negócio
                </div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">5</div>
                <div>
                  <strong>Resultado:</strong> Formato e escopo claros para implementação
                </div>
              </div>
            </div>
          </div>

          <!-- Resultado Esperado -->
          <div class="example-section">
            <h4 class="example-title">🎯 Resultado Esperado</h4>
            <div class="resultado-box">
              <div class="standard-grid standard-grid-2">
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">📈</div>
                  <div>Fluxograma visual do processo</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">🛠️</div>
                  <div>Lista de ferramentas com custos</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">📅</div>
                  <div>Cronograma de implementação</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">⚡</div>
                  <div>Plano acionável em 30 dias</div>
                </div>
              </div>
              <p class="resultado-descricao">Análise específica para sua realidade, plano executável e recomendações adaptadas ao tamanho e recursos da empresa.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Entendendo a Diferença: Cenário vs Informações -->
<section class="section" id="contexto-info">
  <div class="container">
    <h2 class="section-title">Entendendo a Diferença: Cenário vs Informações</h2>
    <p class="section-subtitle">Muitos confundem essas duas camadas. Veja a diferença prática entre elas</p>
    
    <div class="section-standard">
      <!-- Comparação em Grid -->
      <div class="comparison-grid">
        <!-- Card Cenário -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
              </svg>
              Cenário (Contexto Macro)
            </div>
          </div>
          <div class="card-content">
            <div class="definition-box">
              <p><strong>Responde à pergunta:</strong> "Onde estamos?"</p>
              <p>Contexto geral do negócio e ambiente de atuação</p>
            </div>
            <div class="standard-grid">
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🏢</div>
                <div>Situação geral do negócio</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🌍</div>
                <div>Ambiente de mercado</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">👥</div>
                <div>Público-alvo principal</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🎯</div>
                <div>Objetivos estratégicos</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🚧</div>
                <div>Desafios enfrentados</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">📊</div>
                <div>Posicionamento no mercado</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Card Informações -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge success">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line>
              </svg>
              Informações (Dados Específicos)
            </div>
          </div>
          <div class="card-content">
            <div class="definition-box">
              <p><strong>Responde à pergunta:</strong> "Com o que contamos?"</p>
              <p>Dados concretos e recursos disponíveis</p>
            </div>
            <div class="standard-grid">
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🔢</div>
                <div>Números e métricas</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">💼</div>
                <div>Recursos disponíveis</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">💰</div>
                <div>Orçamento específico</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">⏰</div>
                <div>Prazos concretos</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">📈</div>
                <div>Histórico de resultados</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🔧</div>
                <div>Limitações técnicas</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dica Prática -->
      <div class="standard-card tip-card">
        <div class="standard-card-header">
          <div class="standard-badge warning">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Dica Prática
          </div>
        </div>
        <div class="card-content">
          <div class="tip-content">
            <div class="tip-icon">💡</div>
            <div>
              <h4>Juntos criam o contexto completo.</h4>
              <p><strong>Cenário</strong> responde "onde estamos?" e <strong>Informações</strong> responde "com o que contamos?".</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Medição e Iteração -->
<section class="section" id="medicao">
  <div class="container">
    <h2 class="section-title">Medição e Iteração</h2>
    <p class="section-subtitle">Como avaliar a qualidade das respostas e refinar seus prompts para resultados cada vez melhores</p>
    
    <div class="section-standard">
      <!-- Critérios de Qualidade -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20,6 9,17 4,12"></polyline>
            </svg>
            Critérios de Qualidade
          </div>
        </div>
        <div class="card-content">
          <p class="card-intro">Como saber se a resposta da IA é realmente útil para seu negócio</p>
          
          <div class="criteria-comparison">
            <!-- Resposta de Qualidade -->
            <div class="criteria-column">
              <div class="criteria-header success">
                <div class="criteria-icon">✅</div>
                <h4>Resposta de Qualidade</h4>
              </div>
              <div class="standard-grid">
                <div class="standard-grid-item positive">
                  <div class="standard-icon">🎯</div>
                  <div>Específica para seu contexto</div>
                </div>
                <div class="standard-grid-item positive">
                  <div class="standard-icon">⚡</div>
                  <div>Acionável (você pode implementar)</div>
                </div>
                <div class="standard-grid-item positive">
                  <div class="standard-icon">📏</div>
                  <div>Considera suas limitações</div>
                </div>
                <div class="standard-grid-item positive">
                  <div class="standard-icon">📋</div>
                  <div>Formato solicitado</div>
                </div>
                <div class="standard-grid-item positive">
                  <div class="standard-icon">🚀</div>
                  <div>Próximos passos claros</div>
                </div>
              </div>
            </div>

            <!-- Resposta Genérica -->
            <div class="criteria-column">
              <div class="criteria-header error">
                <div class="criteria-icon">❌</div>
                <h4>Resposta Genérica</h4>
              </div>
              <div class="standard-grid">
                <div class="standard-grid-item negative">
                  <div class="standard-icon">🌍</div>
                  <div>Conselhos muito gerais</div>
                </div>
                <div class="standard-grid-item negative">
                  <div class="standard-icon">🚫</div>
                  <div>Ignora suas limitações</div>
                </div>
                <div class="standard-grid-item negative">
                  <div class="standard-icon">💸</div>
                  <div>Sugere soluções caras ou complexas</div>
                </div>
                <div class="standard-grid-item negative">
                  <div class="standard-icon">📄</div>
                  <div>Formato inadequado</div>
                </div>
                <div class="standard-grid-item negative">
                  <div class="standard-icon">❓</div>
                  <div>Sem direcionamento prático</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Testes Práticos -->
      <div class="cards-grid-2">
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge warning">
              <div class="step-number">1</div>
              Teste de Relevância
            </div>
          </div>
          <div class="card-content">
            <div class="test-question">
              <strong>"Posso implementar isso na próxima semana com meus recursos atuais?"</strong>
            </div>
            <p>Se a resposta for <strong>não</strong>, refine o prompt adicionando mais detalhes sobre suas limitações reais de tempo, orçamento e equipe.</p>
          </div>
        </div>

        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge warning">
              <div class="step-number">2</div>
              Teste de Especificidade
            </div>
          </div>
          <div class="card-content">
            <div class="test-question">
              <strong>"Isso serve só para mim ou para qualquer empresa?"</strong>
            </div>
            <p>Respostas específicas mencionam seu setor, tamanho da empresa, público-alvo ou situação particular. Se for genérico, adicione mais contexto.</p>
          </div>
        </div>

        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="7.5,4.21 12,6.81 16.5,4.21"></polyline><polyline points="7.5,19.79 7.5,14.6 3,12"></polyline>
                <polyline points="21,12 16.5,14.6 16.5,19.79"></polyline><polyline points="3.27,6.96 12,12.01 20.73,6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
              </svg>
              Ciclo de Refinamento
            </div>
          </div>
          <div class="card-content">
            <div class="refinement-cycle">
              <div class="cycle-step">
                <div class="step-number">1</div>
                <div class="step-text">Execute e Avalie</div>
              </div>              
              <div class="cycle-arrow">→</div>
              <div class="cycle-step">
                <div class="step-number">2</div>
                <div class="step-text">Identifique gaps</div>
              </div>
              <div class="cycle-arrow">→</div>
              <div class="cycle-step">
                <div class="step-number">3</div>
                <div class="step-text">Ajuste o prompt</div>
              </div>
              <div class="cycle-arrow">→</div>
              <div class="cycle-step">
                <div class="step-number">4</div>
                <div class="step-text">Teste novamente</div>
              </div>
            </div>
            <p class="cycle-note">Cada iteração deve melhorar um aspecto específico da resposta, não tudo ao mesmo tempo.</p>
          </div>
        </div>

        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge success">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14,2 14,8 20,8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10,9 9,9 8,9"></polyline>
              </svg>
              Biblioteca de Sucessos
            </div>
          </div>
          <div class="card-content">
            <div class="success-tips">
              <div class="tip-item">
                <div class="tip-icon">📝</div>
                <div>Salve prompts que geraram resultados excepcionais</div>
              </div>
              <div class="tip-item">
                <div class="tip-icon">🔍</div>
                <div>Anote: contexto usado, resultado obtido e por que funcionou</div>
              </div>
              <div class="tip-item">
                <div class="tip-icon">🔄</div>
                <div>Reutilize a estrutura para situações similares</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Técnicas Avançadas -->
<section class="section" id="tecnicas">
  <div class="container">
    <h2 class="section-title">Técnicas Avançadas</h2>
    <p class="section-subtitle">Estratégias para usuários que querem extrair o máximo potencial da IA em cenários complexos de negócio</p>
    
    <div class="section-standard">
      <div class="standard-grid standard-grid-2">
        <!-- Técnica 1 -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
              </svg>
              Encadeamento de Prompts
            </div>
          </div>
          <div class="card-content">
            <p><strong>Conversa com a IA:</strong> Use a resposta anterior como ponto de partida para a próxima pergunta.</p>
            <div class="standard-example">
              <div class="standard-example-content">
                1. Peça análise → 2. Baseado na análise, peça um plano → 3. Baseado no plano, identifique riscos
              </div>
            </div>
          </div>
        </div>

        <!-- Técnica 2 -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              Perspectivas Múltiplas
            </div>
          </div>
          <div class="card-content">
            <p>Peça para a IA analisar o mesmo problema sob diferentes óticas.</p>
            <div class="standard-example">
              <div class="standard-example-content">
                "Analise como CFO, depois como Head de Vendas, depois como cliente final"
              </div>
            </div>
          </div>
        </div>

        <!-- Técnica 3 -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20,6 9,17 4,12"/>
              </svg>
              Validação Cruzada
            </div>
          </div>
          <div class="card-content">
            <p>Após receber uma análise, peça validação de diferentes perspectivas.</p>
            <div class="standard-example">
              <div class="standard-example-content">
                "Atue como auditor externo e identifique possíveis falhas ou pontos cegos nesta análise"
              </div>
            </div>
          </div>
        </div>

        <!-- Técnica 4 -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
              </svg>
              Checagem Contextual
            </div>
          </div>
          <div class="card-content">
            <p>Valide premissas e mostre cálculos para garantir transparência.</p>
            <div class="standard-example">
              <div class="standard-example-content">
                "Valide premissas em 3 tópicos" e "mostre cálculo resumido"
              </div>
            </div>
          </div>
        </div>

        <!-- Técnica 5 -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14,2 14,8 20,8"/>
              </svg>
              Auditoria de Saída
            </div>
          </div>
          <div class="card-content">
            <p>Identifique suposições invisíveis utilizadas pela IA.</p>
            <div class="standard-example">
              <div class="standard-example-content">
                "Liste 3 suposições invisíveis que você utilizou e onde isso pode dar errado"
              </div>
            </div>
          </div>
        </div>

        <!-- TÉCNICA 6 -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14,2 14,8 20,8"/>
                <path d="M16 13H8"/>
                <path d="M16 17H8"/>
                <path d="M10 9H9H8"/>
              </svg>
              Organização de Versões e Arquivos
            </div>
          </div>
          <div class="card-content">
            <p>Use estrutura clara ao anexar arquivos.</p>            
            <div class="standard-example">
              <div class="standard-example-content">                
                • <code>Use nomes autoexplicativos</code><br>
                • <code>Versão semântica (v1.0, v1.1, v2.0…)</code><br>                
		• <code>Status (rascunho, revisão, final)</code><br>
<strong>Exemplo</strong>: guia_v2.0-revisao.html. 
              </div>
            </div>
          </div>
        </div>
  </div>
</div>
</section>

	<!-- Prompts Bônus -->
<section class="section" id="bonus">
  <div class="container">
    <h2 class="section-title">Prompts Bônus por Área de Negócio</h2>
    <p class="section-subtitle">Templates completos organizados por área para resolver desafios específicos do seu negócio</p>
    
    <div class="section-standard">
      <!-- Gestão e Estratégia -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            Gestão e Estratégia
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Análise SWOT personalizada para PMEs</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Consultor Estratégico especializado em PMEs.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa precisa de uma análise estratégica clara para identificar oportunidades de crescimento e mitigar riscos em um mercado competitivo.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Descrição do negócio e principais produtos/serviços 2. Principais concorrentes e posicionamento no mercado 3. Recursos internos disponíveis (equipe, capital, tecnologia) 4. Desafios atuais enfrentados pela empresa.<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Foque em insights práticos e acionáveis para PMEs • Priorize oportunidades de baixo custo e alto impacto • Se informações estiverem incompletas, trabalhe com dados disponíveis e indique lacunas • Evite jargões complexos e mantenha linguagem acessível.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> Matriz SWOT em formato de tabela com 4 quadrantes: Forças (3 itens), Fraquezas (3 itens), Oportunidades (3 itens), Ameaças (3 itens). Após a matriz, inclua 3 estratégias prioritárias baseadas na análise.
            </div>
          </div>
        </div>
      </div>

      <!-- Finanças -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="1" x2="12" y2="23"/>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            Finanças
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Análise de eficiência de custos por departamento</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Consultor de Eficiência de Custos.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa enfrenta margens apertadas e precisa otimizar gastos internos de forma estratégica e setorizada.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Despesas detalhadas por departamento dos últimos 6 meses 2. Metas de redução de custos (se houver) 3. Indicadores de produtividade por departamento (ex: receita por funcionário).<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Não recomendar cortes que impactem diretamente a qualidade do produto ou serviço • Priorizar ações de curto prazo (implementação em até 3 meses) • Se os dados por departamento estiverem incompletos, focar na análise dos departamentos com dados disponíveis • Se as metas não forem definidas, assumir uma redução padrão de 10% como objetivo.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> Tabela em Markdown com: Departamento, Custos Atuais (Mensal), Potencial de Economia (%), Ações Recomendadas (lista).
            </div>
          </div>
        </div>
      </div>

      <!-- Tributário e Fiscal -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14,2 14,8 20,8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            Tributário e Fiscal
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Planejamento tributário para otimização fiscal</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Consultor Tributário especializado em PMEs.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa busca otimizar sua carga tributária de forma legal e estratégica, considerando seu regime atual e possibilidades de mudança.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Regime tributário atual (Simples Nacional, Lucro Presumido, Lucro Real) 2. Faturamento anual e projeções 3. Principais despesas dedutíveis 4. Atividades e CNAE da empresa.<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Focar apenas em estratégias legais e dentro da legislação vigente • Considerar mudanças de regime tributário se benéficas • Se o faturamento não for informado, solicitar essa informação como essencial • Alertar sobre necessidade de validação com contador.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> 1. Análise do regime atual (vantagens/desvantagens), 2. Comparativo com outros regimes (se aplicável), 3. Top 3 oportunidades de otimização fiscal, 4. Cronograma de implementação.
            </div>
          </div>
        </div>
      </div>

      <!-- Operações e Estoque -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
              <polyline points="3.27,6.96 12,12.01 20.73,6.96"/>
              <line x1="12" y1="22.08" x2="12" y2="12"/>
            </svg>
            Operações e Estoque
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Otimização de gestão de estoque e redução de perdas</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Especialista em Gestão de Operações e Estoque.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa enfrenta problemas de ruptura de estoque, excesso de produtos parados e perdas por vencimento, impactando o fluxo de caixa.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Lista dos principais produtos e giro de estoque 2. Histórico de vendas dos últimos 6 meses 3. Fornecedores principais e prazos de entrega 4. Espaço físico disponível para armazenagem.<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Priorizar soluções de baixo custo e rápida implementação • Considerar sazonalidade e tendências de mercado • Se dados de giro não estiverem disponíveis, focar nos produtos de maior volume de vendas • Incluir indicadores de controle simples para acompanhamento.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> Plano de otimização com: 1. Classificação ABC dos produtos, 2. Política de estoque mínimo/máximo por categoria, 3. Cronograma de revisão de estoque, 4. KPIs para monitoramento.
            </div>
          </div>
        </div>
      </div>

      <!-- Compras -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
              <line x1="3" y1="6" x2="21" y2="6"/>
              <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            Compras
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Estratégia de negociação com fornecedores para redução de custos</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Especialista em Compras e Negociação para PMEs.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa precisa reduzir custos de aquisição mantendo qualidade e confiabilidade dos fornecedores, em um contexto de pressão nas margens.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Lista dos principais fornecedores e volume de compras 2. Histórico de preços dos últimos 12 meses 3. Condições atuais de pagamento e prazos 4. Qualidade e pontualidade dos fornecedores atuais.<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Manter relacionamentos de longo prazo com fornecedores estratégicos • Considerar alternativas de fornecedores sem comprometer qualidade • Se histórico de preços não estiver disponível, focar em benchmarking de mercado • Incluir estratégias de pagamento que beneficiem ambas as partes.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> Plano de negociação estruturado: 1. Análise de fornecedores (classificação por importância), 2. Estratégias de negociação por categoria, 3. Argumentos e contrapartidas, 4. Cronograma de renegociação.
            </div>
          </div>
        </div>
      </div>

      <!-- Marketing e Vendas -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"/>
              <path d="M13 13l6 6"/>
            </svg>
            Marketing e Vendas
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Funil de vendas otimizado para conversão digital</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Especialista em Marketing Digital e Funil de Vendas.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa precisa estruturar um processo de vendas digital eficiente para aumentar conversões e reduzir o ciclo de vendas.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Perfil detalhado do cliente ideal (persona) 2. Canais digitais atualmente utilizados 3. Taxa de conversão atual e ticket médio 4. Principais objeções dos clientes no processo de venda.<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Focar em ferramentas gratuitas ou de baixo custo • Priorizar automações simples e escaláveis • Se dados de conversão não estiverem disponíveis, estabelecer benchmarks do setor • Incluir métricas de acompanhamento para cada etapa do funil.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> Funil estruturado com: 1. Mapeamento das 5 etapas (Atração, Interesse, Consideração, Intenção, Compra), 2. Ações específicas para cada etapa, 3. Ferramentas recomendadas, 4. KPIs de acompanhamento.
            </div>
          </div>
        </div>
      </div>

      <!-- Comunicação e Cliente -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            Comunicação e Cliente
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Estratégia de retenção e fidelização de clientes</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Especialista em Relacionamento e Retenção de Clientes.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa enfrenta alta rotatividade de clientes e precisa desenvolver estratégias eficazes de fidelização para aumentar o lifetime value.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Taxa de churn (perda de clientes) atual 2. Principais motivos de cancelamento ou abandono 3. Perfil dos clientes mais fiéis 4. Canais de comunicação preferidos pelos clientes.<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Priorizar ações de baixo custo e alto impacto emocional • Focar em experiência do cliente e valor percebido • Se dados de churn não estiverem disponíveis, trabalhar com estimativas baseadas no setor • Incluir métricas de satisfação e NPS para acompanhamento.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> Programa de fidelização com: 1. Análise do perfil do cliente ideal para retenção, 2. Jornada do cliente mapeada, 3. Ações de retenção por estágio, 4. Sistema de recompensas e benefícios, 5. Cronograma de implementação.
            </div>
          </div>
        </div>
      </div>

      <!-- Crédito e Fomento -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
              <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            Crédito e Fomento
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Estratégia para obtenção de crédito e linhas de financiamento</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Consultor Financeiro especializado em Crédito Empresarial.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa precisa de capital para expansão ou capital de giro e busca as melhores opções de crédito disponíveis no mercado.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Finalidade do crédito (capital de giro, investimento, expansão) 2. Valor necessário e prazo desejado para pagamento 3. Situação financeira atual da empresa (faturamento, lucro, dívidas) 4. Garantias disponíveis para oferecer.<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Priorizar linhas de crédito com menores taxas de juros • Considerar programas governamentais e de fomento • Se informações financeiras estiverem incompletas, orientar sobre documentação necessária • Alertar sobre riscos e responsabilidades do endividamento.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> Plano de captação com: 1. Ranking das melhores opções de crédito (bancos, fintechs, programas governamentais), 2. Documentação necessária para cada opção, 3. Cronograma de aplicação, 4. Análise de custo-benefício de cada alternativa.
            </div>
          </div>
        </div>
      </div>

      <!-- RH e Pessoas -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="8.5" cy="7" r="4"/>
              <path d="M20 8v6"/>
              <path d="M23 11h-6"/>
            </svg>
            RH e Pessoas
          </div>
        </div>
        <div class="card-content">
          <h4 class="example-title">Plano de desenvolvimento e retenção de talentos</h4>
          <div class="standard-example">
            <div class="example-header">
              <button class="standard-copy-btn" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copiar Prompt
              </button>
            </div>
            <div class="standard-example-content prompt-text">
              🧠 <strong>Atue como:</strong> Consultor de Recursos Humanos especializado em PMEs.<br><br>
              🎯 <strong>Entenda o seguinte cenário:</strong> A empresa enfrenta alta rotatividade de funcionários e precisa desenvolver estratégias de retenção e desenvolvimento que caibam no orçamento.<br><br>
              📊 <strong>Considere as informações disponíveis:</strong> 1. Taxa de turnover atual e principais motivos de saída 2. Perfil da equipe atual (cargos, tempo de casa, performance) 3. Orçamento disponível para benefícios e treinamentos 4. Cultura organizacional e valores da empresa.<br><br>
              ⚙️ <strong>Siga estas diretrizes:</strong> • Priorizar benefícios não-monetários e de baixo custo • Focar em desenvolvimento interno e progressão de carreira • Se dados de turnover não estiverem disponíveis, trabalhar com pesquisa de clima organizacional • Incluir métricas de engajamento e satisfação para acompanhamento.<br><br>
              📝 <strong>Apresente o resultado assim:</strong> Programa de retenção com: 1. Diagnóstico dos principais fatores de saída, 2. Plano de benefícios escalonado por senioridade, 3. Trilha de desenvolvimento por cargo, 4. Cronograma de implementação e orçamento estimado.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>       
     
<!-- FAQ -->
<section class="section" id="faq">
  <div class="container">
    <h2 class="section-title">Perguntas Frequentes</h2>
    <p class="section-subtitle">Respostas diretas para as dúvidas mais comuns sobre o uso eficaz de prompts em negócios</p>
    <div class="section-standard">
      <div class="standard-card">
        <div class="card-content">
          <div class="faq-container">
            <!-- FAQ Item 1 -->
            <details class="faq-item">
              <summary>
                <div class="faq-question">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                  </svg>
                  Preciso definir personalidade sempre?
                </div>
                <svg class="summary-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <polyline points="6,9 12,15 18,9"/>
                </svg>
              </summary>
              <div class="details-content">
                <p><strong>Não é obrigatório, mas altamente recomendado.</strong> Iniciantes podem usar a personalidade padrão, mas definir um perfil específico (como "consultor financeiro para pequenas empresas") faz a IA responder com terminologia e profundidade mais adequadas.</p>
                <div class="standard-grid" style="margin-top: var(--spacing-md);">
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">✅</div>
                    <div>Terminologia específica do seu setor</div>
                  </div>
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">✅</div>
                    <div>Perspectiva adequada ao tamanho da empresa</div>
                  </div>
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">✅</div>
                    <div>Respostas com profundidade contextual</div>
                  </div>
                </div>
              </div>
            </details>

            <!-- FAQ Item 2 -->
            <details class="faq-item">
              <summary>
                <div class="faq-question">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                  </svg>
                  Posso mudar as restrições?
                </div>
                <svg class="summary-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <polyline points="6,9 12,15 18,9"/>
                </svg>
              </summary>
              <div class="details-content">
                <p><strong>Sim, e isso é fundamental para personalizar os resultados.</strong> Restrições ajudam a moldar o formato e profundidade da resposta.</p>
                <div class="standard-example" style="margin: var(--spacing-md) 0;">
                  <div class="standard-example-content">
                    <strong>Exemplos práticos:</strong><br>
                    • "Use tópicos para facilitar a leitura"<br>
                    • "Máximo 5 itens com próximos passos executáveis"<br>
                    • "Evite jargões técnicos e use linguagem acessível"
                  </div>
                </div>                
              </div>
            </details>

            <!-- FAQ Item 3 -->
            <details class="faq-item">
              <summary>
                <div class="faq-question">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <line x1="7.5" y1="4" x2="7.5" y2="13"/>
                    <line x1="12" y1="9" x2="12" y2="12"/>
                  </svg>
                  Quais dados devo usar?
                </div>
                <svg class="summary-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <polyline points="6,9 12,15 18,9"/>
                </svg>
              </summary>
              <div class="details-content">
                <p><strong>Priorize dados reais do seu negócio:</strong> faturamento, número de funcionários, custos operacionais, métricas atuais.</p>
                
                <div class="standard-grid standard-grid-2" style="margin: var(--spacing-md) 0;">
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">📊</div>
                    <div><strong>Dados essenciais:</strong><br>Faturamento, equipe, custos</div>
                  </div>
                  <div class="standard-grid-item negative">
                    <div class="standard-icon">🚫</div>
                    <div><strong>Evite:</strong><br>Dados fictícios ou exagerados</div>
                  </div>
                </div>

                <div class="definition-box">
                  <p><strong>Dica prática:</strong></p>
                  <p>Se não tiver um dado exato, use aproximações realistas (ex: "cerca de 15 clientes por dia" em vez de inventar números).</p>
                </div>
              </div>
            </details>

            <!-- FAQ Item 4 -->
            <details class="faq-item">
              <summary>
                <div class="faq-question">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                  </svg>
                  E se eu errar a ordem das camadas?
                </div>
                <svg class="summary-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <polyline points="6,9 12,15 18,9"/>
                </svg>
              </summary>
              <div class="details-content">
                <p><strong>A ordem correta é crucial</strong> porque a IA processa as informações sequencialmente. Se errar, reinicie com a sequência:</p>
                
                <div class="standard-example" style="margin: var(--spacing-md) 0;">
                  <div class="standard-example-content">
                    <strong>Sequência ideal:</strong><br>
                    1. 🧠 Atue como (Especialista)<br>
                    2. 🎯 Cenário (Contexto)<br>
                    3. 📊 Informações (Dados)<br>
                    4. ⚙️ Diretrizes (Restrições)<br>
                    5. 📝 Resultado (Formato)
                  </div>
                </div>                
              </div>
            </details>            

            <!-- FAQ Item 5 -->
            <details class="faq-item">
              <summary>
                <div class="faq-question">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22,4 12,14.01 9,11.01"/>
                  </svg>
                  Como saber se o resultado é bom?
                </div>
                <svg class="summary-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <polyline points="6,9 12,15 18,9"/>
                </svg>
              </summary>
              <div class="details-content">
                <p><strong>Teste prático:</strong> um bom resultado é aquele que você pode implementar diretamente.</p>
                
                <div class="standard-grid" style="margin: var(--spacing-md) 0;">
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">✅</div>
                    <div>Menciona sua empresa e setor específico</div>
                  </div>
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">✅</div>
                    <div>Considera seus recursos e limitações</div>
                  </div>
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">✅</div>
                    <div>Entrega um plano, não apenas conselhos</div>
                  </div>
                </div>

                <div class="definition-box">
                  <p><strong>Sinais de alerta:</strong></p>
                  <p>Se contém frases como "todas as empresas" ou "de forma geral", adicione mais detalhes do seu contexto. O ideal é receber um plano acionável, não apenas recomendações genéricas.</p>
                </div>
              </div>
            </details>

            <!-- FAQ Item 6 -->
            <details class="faq-item">
              <summary>
                <div class="faq-question">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"/>
                    <path d="M13 13l6 6"/>
                  </svg>
                  Preciso recomeçar quando mudo de assunto?
                </div>
                <svg class="summary-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <polyline points="6,9 12,15 18,9"/>
                </svg>
              </summary>
              <div class="details-content">
                <p><strong>Sim, sempre reinicie a conversa ao mudar de assunto.</strong> A IA mantém o contexto da conversa anterior, o que pode contaminar as novas respostas.</p>
                
                <div class="standard-example" style="margin: var(--spacing-md) 0;">
                  <div class="standard-example-content">
                    <strong>Exemplo do problema:</strong><br>
                    Se você estava falando de marketing e muda para finanças sem reiniciar, a IA pode trazer viés do assunto anterior.
                  </div>
                </div>

                <div class="standard-grid" style="margin: var(--spacing-md) 0;">
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">🔄</div>
                    <div><strong>Reiniciar garante:</strong> Foco total no novo tema</div>
                  </div>
                  <div class="standard-grid-item positive">
                    <div class="standard-icon">🎯</div>
                    <div><strong>Resultado:</strong> Respostas mais precisas e relevantes</div>
                  </div>
                </div>

                <p>Para assuntos completamente diferentes, inicie uma nova conversa para obter os melhores resultados.</p>
              </div>
            </details>

            <!-- FAQ Item 7 -->
            <details class="faq-item">
              <summary>
                <div class="faq-question">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10,9 9,9 8,9"/>
                  </svg>
                  O que escrever entre [colchetes]?
                </div>
                <svg class="summary-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <polyline points="6,9 12,15 18,9"/>
                </svg>
              </summary>
              <div class="details-content">
                <p><strong>Substitua [colchetes] por informações específicas do seu negócio:</strong></p>
                
                <div class="standard-grid standard-grid-2" style="margin: var(--spacing-md) 0;">
                  <div class="standard-grid-item neutral">
                    <div class="standard-icon">💰</div>
                    <div><strong>[faturamento mensal]</strong><br>→ "R$ 45.000"</div>
                  </div>
                  <div class="standard-grid-item neutral">
                    <div class="standard-icon">👥</div>
                    <div><strong>[número de funcionários]</strong><br>→ "8 pessoas"</div>
                  </div>
                  <div class="standard-grid-item neutral">
                    <div class="standard-icon">📅</div>
                    <div><strong>[prazo desejado]</strong><br>→ "30 dias"</div>
                  </div>
                  <div class="standard-grid-item neutral">
                    <div class="standard-icon">🎯</div>
                    <div><strong>[cargo do responsável]</strong><br>→ "gerente comercial"</div>
                  </div>
                </div>

                <div class="definition-box">
                  <p><strong>Dados reais geram recomendações mais precisas e aplicáveis à sua realidade.</strong> Quanto mais específico você for, melhores serão os resultados obtidos.</p>
                </div>
              </div>
            </details>
          </div>
          
          <!-- Dica Extra -->
          <div class="standard-card tip-card" style="margin-top: var(--spacing-xl);">
            <div class="standard-card-header">
              <div class="standard-badge warning">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/>
                  <line x1="12" y1="8" x2="12" y2="12"/>
                  <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Dica Extra
              </div>
            </div>
            <div class="card-content">
              <div class="tip-content">
                <div class="tip-icon">💡</div>
                <div>
                  <h4>Método de refinamento eficiente:</h4>
                  <p><strong>Execute o prompt → Avalie o que faltou → Ajuste apenas a camada problemática → Teste novamente.</strong></p>
                  <div class="standard-grid" style="margin-top: var(--spacing-md);">
                    <div class="standard-grid-item positive">
                      <div class="standard-icon">🎯</div>
                      <div>Se foi genérico: adicone mais contexto</div>
                    </div>
                    <div class="standard-grid-item positive">
                      <div class="standard-icon">📏</div>
                      <div>Se foi muito longo: inclua restrição de formato</div>
                    </div>
                    <div class="standard-grid-item positive">
                      <div class="standard-icon">⚡</div>
                      <div>Ajustar um prompt existente é mais eficiente que recomeçar</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

	<!-- Recursos e Próximos Passos -->
<section class="section" id="recursos">
  <div class="container">
    <h2 class="section-title">Recursos e Próximos Passos</h2>
    
    <div class="section-standard">
      <div class="standard-card" style="text-align: center;">
        <div class="standard-card-header">
          <div class="standard-badge success">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
              <polyline points="22,4 12,14.01 9,11.01"/>
            </svg>
            Comece agora mesmo!
          </div>
        </div>
        <div class="card-content">
          <p style="font-size: 1.125rem; margin-bottom: var(--spacing-lg);">Escolha um problema real do seu negócio, abra o Painel de Prompts, personalize os [colchetes] e execute.</p>
          <p style="color: var(--text-secondary); margin-bottom: var(--spacing-lg);"> A prática é o melhor caminho para dominar essas técnicas.</p>
          
          <div class="resultado-box" style="margin-top: var(--spacing-xl);">
            <div class="standard-grid standard-grid-2">
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🚀</div>
                <div>Escolha um problema real</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🎯</div>
                <div>Personalize os prompts</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">⚡</div>
                <div>Execute e avalie</div>
              </div>
              <div class="standard-grid-item neutral">
                <div class="standard-icon">🔄</div>
                <div>Refine com base nos resultados</div>
              </div>
            </div>
            <p class="resultado-descricao">Salve os prompts que funcionaram bem e crie sua própria biblioteca. Com o tempo, você terá um arsenal personalizado para cada situação.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Grid de Recursos -->
<section class="section" style="padding-top: 0;">
  <div class="container">
    <div class="section-standard">
      <div class="standard-grid standard-grid-3">
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
              </svg>
              Painel de Prompts
            </div>
          </div>
          <div class="card-content">
            <p>Abra o painel, escolha a área, copie o prompt completo, personalize [colchetes] e execute. <strong>Link disponível na sua área de membros.</strong></p>
          </div>
        </div>

        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
              </svg>
              Suporte Especializado
            </div>
          </div>
          <div class="card-content">
            <p>Responda o e-mail da compra  •  Retorno garantido em até 24 horas úteis</p>
          </div>
        </div>

        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14,2 14,8 20,8"/>
              </svg>
              Termos de Uso
            </div>
          </div>
          <div class="card-content">
            <p>© 2025 Fluxoteca - Todos os direitos reservados • Licença individual para uso comercial • Proibido compartilhar ou redistribuir</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<script>
// ✅ VERIFICAÇÃO ROBUSTA DE EXECUÇÃO ÚNICA
if (typeof window.FLUXOTECA_INITIALIZED === 'undefined') {
    window.FLUXOTECA_INITIALIZED = true;
    
    console.log('🚀 Inicializando Fluxoteca (apenas uma vez)...');

    // =============================================
    // VARIÁVEIS GLOBAIS
    // =============================================
    let scrollTimeout;
    let resizeTimeout;

    // =============================================
    // SISTEMA DE UTILITÁRIOS
    // =============================================

    // Toast Notifications
    function showToast(message, type = 'success') {
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            bottom: 20px;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 500;
            z-index: 10000;
            background: ${type === 'success' ? 'rgba(16, 185, 129, 0.9)' : 'rgba(239, 68, 68, 0.9)'};
            color: white;
            border: 1px solid ${type === 'success' ? 'rgb(16, 185, 129)' : 'rgb(239, 68, 68)'};
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // =============================================
    // SISTEMA DE TEMA E CONTRASTE (UNIFICADO)
    // =============================================

    function initTheme() {
        try {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (prefersDark ? 'dark' : 'light');
            
            document.documentElement.setAttribute('data-theme', theme);
            updateThemeButtons(theme);
        } catch (error) {
            console.warn('Erro ao inicializar tema:', error);
        }
    }

    function initContrast() {
        const savedContrast = localStorage.getItem('contrast') || 'normal';
        document.documentElement.setAttribute('data-contrast', savedContrast);
        updateContrastButtons(savedContrast);
    }

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeButtons(newTheme);
        showToast(`Tema ${newTheme === 'dark' ? 'escuro' : 'claro'} ativado`, 'success');
    }

    function toggleContrast() {
        const currentContrast = document.documentElement.getAttribute('data-contrast');
        const newContrast = currentContrast === 'high' ? 'normal' : 'high';
        
        document.documentElement.setAttribute('data-contrast', newContrast);
        localStorage.setItem('contrast', newContrast);
        updateContrastButtons(newContrast);
        showToast(`Modo ${newContrast === 'high' ? 'alto contraste' : 'contraste normal'} ativado`, 'success');
    }

    function updateThemeButtons(theme) {
        const isDark = theme === 'dark';
        
        // Atualiza botão da sidebar
        const sidebarThemeBtn = document.getElementById('theme-toggle-sidebar');
        if (sidebarThemeBtn) {
            sidebarThemeBtn.setAttribute('aria-label', `Alternar para tema ${isDark ? 'claro' : 'escuro'}`);
            sidebarThemeBtn.setAttribute('aria-pressed', isDark.toString());
        }
        
        // Remove referências aos botões antigos do header
        const oldThemeBtn = document.getElementById('theme-toggle');
        if (oldThemeBtn) {
            oldThemeBtn.setAttribute('aria-label', `Alternar para tema ${isDark ? 'claro' : 'escuro'}`);
            oldThemeBtn.setAttribute('aria-pressed', isDark.toString());
        }
    }

    function updateContrastButtons(contrast) {
        const isHigh = contrast === 'high';
        
        // Atualiza botão da sidebar
        const sidebarContrastBtn = document.getElementById('contrast-toggle-sidebar');
        if (sidebarContrastBtn) {
            sidebarContrastBtn.setAttribute('aria-label', `${isHigh ? 'Desativar' : 'Ativar'} alto contraste`);
            sidebarContrastBtn.setAttribute('aria-pressed', isHigh.toString());
        }
        
        // Remove referências aos botões antigos do header
        const oldContrastBtn = document.getElementById('contrast-toggle');
        if (oldContrastBtn) {
            oldContrastBtn.setAttribute('aria-label', `${isHigh ? 'Desativar' : 'Ativar'} alto contraste`);
            oldContrastBtn.setAttribute('aria-pressed', isHigh.toString());
        }
    }

    // =============================================
    // SIDEBAR E NAVEGAÇÃO
    // =============================================

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function initSidebar() {
        updateThemeButtons(document.documentElement.getAttribute('data-theme'));
        updateContrastButtons(document.documentElement.getAttribute('data-contrast'));
    }

    function setupNavigation() {
        const quickNavToggle = document.getElementById('quick-nav-toggle');
        const quickNav = document.getElementById('quick-nav');
        const quickNavClose = document.getElementById('quick-nav-close');
        
        if (quickNavToggle && quickNav) {
            quickNavToggle.addEventListener('click', () => {
                quickNav.classList.add('open');
                quickNavToggle.setAttribute('aria-expanded', 'true');
            });
        }
        
        if (quickNavClose && quickNav) {
            quickNavClose.addEventListener('click', () => {
                quickNav.classList.remove('open');
                quickNavToggle.setAttribute('aria-expanded', 'false');
                quickNavToggle?.focus();
            });
        }
    }

    function enhanceSmoothScrolling() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        function handleSmoothScroll(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const header = document.querySelector('.header');
                const headerHeight = header ? header.offsetHeight : 0;
                const targetPosition = targetElement.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Fecha navegação rápida se estiver aberta
                const quickNav = document.getElementById('quick-nav');
                if (quickNav?.classList.contains('open')) {
                    quickNav.classList.remove('open');
                    const quickNavToggle = document.getElementById('quick-nav-toggle');
                    if (quickNavToggle) {
                        quickNavToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        }
        
        links.forEach(link => {
            link.removeEventListener('click', handleSmoothScroll);
            link.addEventListener('click', handleSmoothScroll);
        });
    }

    // =============================================
    // SISTEMA DE SCROLL E PROGRESSO
    // =============================================

    function updateScrollProgress() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        
        const progressBar = document.querySelector('.scroll-progress');
        if (progressBar) {
            progressBar.style.width = `${scrollPercent}%`;
        }
    }

    function updateHeaderScroll() {
        const header = document.querySelector('.header');
        if (header) {
            const scrolled = (window.pageYOffset || document.documentElement.scrollTop) > 8;
            header.classList.toggle('scrolled', scrolled);
        }
    }

    function updateReadingProgress() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = docHeight > 0 ? Math.min(Math.max((scrollTop / docHeight) * 100, 0), 100) : 0;
        
        const progressFill = document.getElementById('reading-progress-fill');
        const progressText = document.getElementById('reading-progress-text');
        
        if (progressFill) progressFill.style.width = `${scrollPercent}%`;
        if (progressText) progressText.textContent = `${Math.round(scrollPercent)}%`;
    }

    function updateActiveSectionEnhanced() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.quick-nav-link[data-section]');
        const scrollPosition = window.scrollY + 150;
        
        let activeSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                activeSection = section.id;
            }
        });
        
        navLinks.forEach(link => {
            const isActive = link.getAttribute('data-section') === activeSection;
            link.classList.toggle('active', isActive);
        });
    }

    function handleScroll() {
        if (scrollTimeout) cancelAnimationFrame(scrollTimeout);
        scrollTimeout = requestAnimationFrame(() => {
            updateScrollProgress();
            updateHeaderScroll();
            updateActiveSectionEnhanced();
            updateReadingProgress();
        });
    }

    function handleResize() {
        if (resizeTimeout) clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(updateActiveSectionEnhanced, 250);
    }

    // =============================================
    // ANIMAÇÕES E INTERAÇÕES
    // =============================================

    function setupDetailsAnimation() {
        document.querySelectorAll('details').forEach((detail, index) => {
            const summary = detail.querySelector('summary');
            if (!summary) return;
            
            const summaryId = `summary-${index}`;
            const content = detail.querySelector('.details-content');
            const contentId = `content-${index}`;
            
            summary.id = summaryId;
            if (content) {
                content.id = contentId;
                summary.setAttribute('aria-controls', contentId);
            }
            
            summary.setAttribute('aria-expanded', detail.open.toString());
            summary.setAttribute('role', 'button');
            
            summary.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    detail.open = !detail.open;
                }
            });
            
            summary.addEventListener('click', () => {
                setTimeout(() => {
                    summary.setAttribute('aria-expanded', detail.open.toString());
                }, 0);
            });
            
            detail.addEventListener('toggle', () => {
                summary.setAttribute('aria-expanded', detail.open.toString());
            });
        });
    }

    function initScrollAnimations() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (prefersReducedMotion) {
            document.querySelectorAll('.section, .reveal-on-scroll, .faq-item').forEach(el => {
                el.classList.add('visible', 'revealed', 'animate-in');
            });
            return;
        }

        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('visible');
            });
        }, { threshold: 0.1 });

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('revealed');
            });
        }, { threshold: 0.1 });

        const faqObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'none';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.section').forEach(section => sectionObserver.observe(section));
        document.querySelectorAll('.reveal-on-scroll').forEach(el => revealObserver.observe(el));
        document.querySelectorAll('.faq-item').forEach(item => faqObserver.observe(item));
    }

    // =============================================
    // SISTEMA DE CÓPIA DE PROMPTS
    // =============================================

    function cleanPromptText(text) {
        return text
            .replace(/\r\n/g, '\n')
            .replace(/(🧠|🎯|📊⚙️|📝)\s*\n\s*/g, '$1 ')
            .replace(/\n\s*(🧠|🎯|📊|⚙️|📝)/g, '\n\n$1')
            .replace(/([^\.\n])\n([^🧠🎯📊⚙️📝\n])/g, '$1 $2')
            .replace(/(📝 Resultado:.*?)(?=🧠|🎯|📊|⚙️|$)/g, '$1\n\n')
            .replace(/(⚙️ Diretrizes:.*?)(?=🧠|🎯|📊|📝|$)/g, '$1\n\n')
            .replace(/(📊 Informações:.*?)(?=🧠|🎯|⚙️|📝|$)/g, '$1\n\n')
            .replace(/(🎯 Cenário:.*?)(?=🧠|📊|⚙️|📝|$)/g, '$1\n\n')
            .replace(/(🧠 Atue como:.*?)(?=🎯|📊|⚙️|📝|$)/g, '$1\n\n')
            .replace(/\n\s*\n\s*\n/g, '\n\n')
            .replace(/([:;])\s*\n/g, '$1\n\n')
            .replace(/(\.)\s*\n([^🧠🎯📊⚙️📝])/g, '$1\n\n$2')
            .replace(/[ ]+/g, ' ')
            .replace(/\n[ ]+/g, '\n')
            .replace(/[ ]+\n/g, '\n');
    }

    function updateCopyButtonSuccess(button) {
        const originalHTML = button.innerHTML;
        
        button.innerHTML = `
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
            Copiado!
        `;
        button.style.background = 'var(--success)';
        button.disabled = true;

        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.style.background = '';
            button.disabled = false;
        }, 2000);
    }

    function copyPrompt(button) {
        if (!button) {
            console.warn('Botão de cópia não encontrado');
            return;
        }
        
        try {
            let promptText = '';
            const card = button.closest('.standard-card, .comparison-card');
            
            if (card) {
                const textElement = card.querySelector('.prompt-text, .standard-example-content, .prompt-example');
                if (textElement) {
                    button.focus();
                    
                    let rawText = textElement.textContent
                        .replace(/Copiar.*?Prompt/gi, '')
                        .replace(/Copiar/gi, '')
                        .trim();
                    
                    promptText = cleanPromptText(rawText);
                }
            }
            
            if (!promptText) {
                showToast('Erro ao localizar o texto do prompt.', 'error');
                return;
            }
            
            const copyWithFallback = async (text) => {
                // Clipboard API moderna
                if (navigator.clipboard && window.isSecureContext) {
                    try {
                        await navigator.clipboard.writeText(text);
                        return true;
                    } catch (err) {
                        console.log('Clipboard API falhou, usando fallback...');
                    }
                }
                
                // Fallback tradicional
                try {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.cssText = `
                        position: fixed;
                        left: 0;
                        top: 0;
                        width: 2em;
                        height: 2em;
                        padding: 0;
                        border: none;
                        outline: none;
                        boxShadow: none;
                        background: transparent;
                        opacity: 0;
                    `;
                    
                    document.body.appendChild(textArea);
                    textArea.select();
                    textArea.setSelectionRange(0, 99999);
                    
                    const success = document.execCommand('copy');
                    document.body.removeChild(textArea);
                    return success;
                } catch (fallbackErr) {
                    console.error('Fallback também falhou:', fallbackErr);
                    return false;
                }
            };
            
            copyWithFallback(promptText).then(success => {
                if (success) {
                    updateCopyButtonSuccess(button);
                    showToast('Prompt copiado para área de transferência!', 'success');
                } else {
                    showToast('Não foi possível copiar o texto', 'error');
                }
            });
                
        } catch (error) {
            console.error('Erro em copyPrompt:', error);
            showToast('Erro ao copiar o prompt', 'error');
        }
    }

    // =============================================
    // SISTEMA DE FEEDBACK
    // =============================================

    function toggleFeedback() {
        const modal = document.getElementById('feedback-modal');
        const isActive = modal.classList.contains('active');
        
        if (isActive) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const firstInput = modal.querySelector('select, textarea, input');
                if (firstInput) firstInput.focus();
            }, 100);
        }
    }

    function initFeedbackSystem() {
        const form = document.getElementById('feedback-form');
        if (!form) return;
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = form.querySelector('.feedback-submit');
            const originalText = submitButton.innerHTML;
            
            const formData = {
                type: document.getElementById('feedback-type').value,
                message: document.getElementById('feedback-message').value,
                email: document.getElementById('feedback-email').value,
                timestamp: new Date().toISOString(),
                url: window.location.href,
                userAgent: navigator.userAgent
            };
            
            if (!formData.type || !formData.message) {
                showToast('Por favor, preencha todos os campos obrigatórios.', 'error');
                return;
            }
            
            submitButton.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Enviando...
            `;
            submitButton.disabled = true;
            
            try {
                await simulateFeedbackSubmission(formData);
                showToast('Obrigado pelo feedback! Sua sugestão foi enviada.', 'success');
                form.reset();
                toggleFeedback();
            } catch (error) {
                console.error('Erro ao enviar feedback:', error);
                showToast('Erro ao enviar feedback. Tente novamente.', 'error');
            } finally {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('feedback-modal');
                if (modal.classList.contains('active')) {
                    toggleFeedback();
                }
            }
        });
        
        document.getElementById('feedback-modal').addEventListener('click', (e) => {
            if (e.target === this) {
                toggleFeedback();
            }
        });
    }

    function simulateFeedbackSubmission(data) {
        return new Promise((resolve) => {
            setTimeout(() => {
                console.log('📝 Feedback recebido:', data);
                const existingFeedback = JSON.parse(localStorage.getItem('fluxoteca_feedback') || '[]');
                existingFeedback.push(data);
                localStorage.setItem('fluxoteca_feedback', JSON.stringify(existingFeedback));
                resolve();
            }, 1500);
        });
    }

    // =============================================
    // SISTEMA DE PERFORMANCE E RESPONSIVIDADE
    // =============================================

    function addResponsiveStyles() {
        const existingStyle = document.getElementById('fluxoteca-responsive-styles');
        if (existingStyle) existingStyle.remove();

        const style = document.createElement('style');
        style.id = 'fluxoteca-responsive-styles';
        style.textContent = `
            .keyboard-navigation *:focus {
                outline: 2px solid var(--accent-primary) !important;
                outline-offset: 2px !important;
            }
            
            @media (max-width: 768px) {
                .container, .header-content {
                    max-width: 100%;
                    padding: 0 var(--spacing-md);
                }
                
                .nav-group {
                    margin-bottom: var(--spacing-sm);
                }
                
                .nav-group-title {
                    font-size: 0.75rem;
                    padding: var(--spacing-xs) var(--spacing-md);
                }
                
                .nav-sublist .quick-nav-link {
                    padding: var(--spacing-xs) var(--spacing-lg);
                    font-size: 0.8125rem;
                }
                
                details summary {
                    padding: var(--spacing-lg) var(--spacing-md);
                    font-size: 1rem;
                    line-height: 1.4;
                    min-height: 48px;
                    display: flex;
                    align-items: center;
                }
                
                .details-content {
                    padding: 0 var(--spacing-md) var(--spacing-lg);
                }
            }
            
            @media (max-width: 480px) {
                .header-content {
                    gap: var(--spacing-md);
                }
                
                details {
                    margin-bottom: var(--spacing-md);
                }
                
                details summary {
                    padding: var(--spacing-md);
                    min-height: 44px;
                }
            }
        `;
        document.head.appendChild(style);
    }

    function initPerformanceOptimizations() {
        window.removeEventListener('scroll', handleScroll);
        window.removeEventListener('resize', handleResize);
        
        window.addEventListener('scroll', handleScroll, { passive: true });
        window.addEventListener('resize', handleResize, { passive: true });
    }

    // =============================================
    // INICIALIZAÇÃO PRINCIPAL
    // =============================================

    function setupNavigationCoordination() {
    const quickNav = document.getElementById('quick-nav');
    const sidebarNav = document.querySelector('.sidebar-nav');
    const feedbackWidget = document.querySelector('.feedback-widget');
    
    if (quickNav && sidebarNav) {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    const isOpen = quickNav.classList.contains('open');
                    
                    // Aplica efeitos visuais de coordenação
                    if (isOpen) {
                        sidebarNav.style.transform = 'translateY(-50%) translateX(-20px) scale(0.95)';
                        sidebarNav.style.opacity = '0.7';
                        sidebarNav.style.pointerEvents = 'none';
                        
                        if (feedbackWidget) {
                            feedbackWidget.style.transform = 'translateX(-20px)';
                            feedbackWidget.style.opacity = '0.7';
                            feedbackWidget.style.pointerEvents = 'none';
                        }
                    } else {
                        sidebarNav.style.transform = '';
                        sidebarNav.style.opacity = '';
                        sidebarNav.style.pointerEvents = '';
                        
                        if (feedbackWidget) {
                            feedbackWidget.style.transform = '';
                            feedbackWidget.style.opacity = '';
                            feedbackWidget.style.pointerEvents = '';
                        }
                    }
                }
            });
        });
        
        observer.observe(quickNav, { attributes: true });
    }
}

function initializeApplication() {
        console.log('🎯 Inicializando aplicação...');
        
        try {
            // 1. Sistema de Tema e Contraste
            initTheme();
            initContrast();
            initSidebar();

            // 2. Navegação e Acessibilidade
            setupNavigation();
            enhanceSmoothScrolling();
            addResponsiveStyles();
            
            // 3. Animações e Interações
            setupDetailsAnimation();
            initScrollAnimations();
            
            // 4. Sistemas Especiais
            initFeedbackSystem();
            initPerformanceOptimizations();
            
            // 5. Estado Inicial
            updateActiveSectionEnhanced();
            updateReadingProgress();
            handleScroll();
            setupNavigationCoordination();

            console.log('✅ Aplicação inicializada com sucesso!');
        } catch (error) {
            console.error('❌ Erro na inicialização:', error);
        }
    }

    // =============================================
    // INICIALIZAÇÃO SEGURA
    // =============================================

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeApplication);
    } else {
        initializeApplication();
    }

    // =============================================
    // EXPORTAÇÃO DE FUNÇÕES GLOBAIS
    // =============================================

    window.copyPrompt = copyPrompt;
    window.toggleTheme = toggleTheme;
    window.toggleContrast = toggleContrast;
    window.toggleFeedback = toggleFeedback;
    window.scrollToTop = scrollToTop;

} else {
    console.log('⚠️ Fluxoteca já inicializada - ignorando duplicação');
}

</script>

<!-- Widget de Feedback -->
<div class="feedback-widget" id="feedback-widget">
  <button class="feedback-trigger" onclick="toggleFeedback()" aria-label="Enviar feedback">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
    </svg>
    Enviar feedback
  </button>
  
  <div class="feedback-modal" id="feedback-modal">
    <div class="feedback-content">
      <div class="feedback-header">
        <h3>💡 Como podemos melhorar?</h3>
        <button class="feedback-close" onclick="toggleFeedback()" aria-label="Fechar">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <form id="feedback-form" class="feedback-form">
        <div class="feedback-group">
          <label for="feedback-type">Tipo de sugestão</label>
          <select id="feedback-type" required>
            <option value="">Selecione...</option>
            <option value="melhoria">Sugestão de melhoria</option>
            <option value="bug">Reportar problema</option>
            <option value="conteudo">Sugerir novo conteúdo</option>
            <option value="outro">Outro</option>
          </select>
        </div>
        
        <div class="feedback-group">
          <label for="feedback-message">Sua sugestão *</label>
          <textarea 
            id="feedback-message" 
            placeholder="Conte-nos o que podemos melhorar..." 
            required
            rows="4"
          ></textarea>
        </div>
        
        <div class="feedback-group">
          <label for="feedback-email">Seu e-mail (opcional)</label>
          <input 
            type="email" 
            id="feedback-email" 
            placeholder="para respondermos sua sugestão"
          >
        </div>
        
        <div class="feedback-actions">
          <button type="submit" class="feedback-submit">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
            </svg>
            Enviar sugestão
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>