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

/* Header */
header {
    position: sticky;
    top: 0;
    z-index: 50;
    padding: var(--spacing-lg);
    background: rgba(10, 10, 15, 0.85);
    backdrop-filter: var(--blur-xl);
    border-bottom: 1px solid var(--border-subtle);
    transition: all var(--transition-normal);
    will-change: transform;
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

.hero-logo-container {
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
    animation: fadeInDown 0.8s ease-out;
}

.hero-logo {
    height: 80px;
    width: auto;
    max-width: 360px;
    transition: all 0.3s ease;
}

.hero-logo-header {
    display: flex;
    align-items: center;
    justify-content: flex-start; 
    margin: 0;  
    padding: 8px 0;  
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .hero-logo {
        height: 60px;
        max-width: 280px;
    }

    .hero-logo-container {
        margin-bottom: 30px;
    }
}

/* Empilha grades em telas menores */
@media (max-width: 640px) {
  .standard-grid-2,
  .standard-grid-3,
  .comparison-grid {
    grid-template-columns: 1fr;
  }

  .standard-card,
  .comparison-card {
    padding: var(--spacing-lg);
  }
}

@media (max-width: 480px) {
    .hero-logo {
        height: 50px;
        max-width: 240px;
    }

    .hero-logo-container {
        margin-bottom: 25px;
    }
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
    left: 16px;
    bottom: 10%; 
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
    /* transição só no que realmente queremos animar */
    transition: box-shadow var(--transition-normal), background var(--transition-normal), border-color var(--transition-normal);
}

.sidebar-nav:hover {
    box-shadow: var(--shadow-xl), var(--shadow-glow-strong);
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
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    margin-left: 12px;
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
    transform: translateY(-50%) translateX(-2px);
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

/* Some em telas muito pequenas */
@media (max-width: 360px) {
    .sidebar-nav {
        display: none;
    }
}

/* =============================================
   COMPONENTES DE MARCA E CONTROLES
   ============================================= */
/* Performance optimizations */
.hero::before {
  animation: none !important;
}

.btn::before {
  animation: none !important;
}

/* Controles de Acessibilidade */
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

/* ====== Toggle dos Prompts Bônus ====== */

.standard-card.is-open .standard-example-content {
  /* Quando estiver aberto, aparece normal */
  display: block;
}

.standard-example-content[hidden] {
  display: none;
}

.example-header {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: var(--spacing-xs);
}

/* Botão de abrir/fechar prompt */
.prompt-toggle-btn {
  border: none;
  background: transparent;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 999px;
  color: var(--text-soft);
  transition: background var(--transition-fast), 
              color var(--transition-fast), 
              transform var(--transition-fast);
}

.prompt-toggle-btn:hover {
  background: var(--surface-hover);
  color: var(--text-primary);
}

.prompt-toggle-btn svg {
  transition: transform var(--transition-fast);
}

/* Quando o card estiver aberto, gira o ícone */
.standard-card.is-open .prompt-toggle-btn svg {
  transform: rotate(180deg);
}

.prompt-preview {
  padding: var(--spacing-md);
  border-radius: var(--radius-lg);
  background: var(--surface);
  color: var(--text-soft);
  font-size: 0.95rem;
}

/* quando abrir, você pode reforçar visualmente que há mais conteúdo */
.standard-card.is-open .prompt-preview {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  border-bottom: 1px solid var(--border-subtle);
}

.prompt-full {
  padding: var(--spacing-md);
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  background: var(--surface);
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
    background: var(--surface-hover);
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

/* =============================================
   LAYOUTS ESPECÍFICOS
   ============================================= */

.vertical-comparison {
    margin-bottom: var(--spacing-3xl);
    position: relative;
}

.vertical-comparison .transition-arrow {
    margin-bottom: var(--spacing-md);
}
.vertical-comparison .card-intro {
    margin-bottom: var(--spacing-lg);
}

/* Alinhamento do bloco de Técnicas Avançadas */
.vertical-comparison .card-intro,
.vertical-comparison .techniques-grid {
    max-width: 780px;
    margin-left: auto;
    margin-right: auto;
}

.comparison-title {
    font-size: 1.5rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: var(--spacing-xl);
    color: var(--text-primary);
    position: relative;
}

.comparison-title::after {
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
   GRID ESPECÍFICO - TÉCNICAS AVANÇADAS
   ============================================= */

.techniques-grid {
    display: grid;
    grid-template-columns: 1fr;    
    gap: var(--spacing-lg);
    margin-top: var(--spacing-lg);
}

/* Em telas maiores, só centraliza e controla largura máx. */
@media (min-width: 1024px) {
    .techniques-grid {
        max-width: 780px;
        margin: var(--spacing-xl) auto 0;    
    }
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
}

@media (max-width: 480px) {
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
    
    .comparison-title {
        font-size: 1.25rem;
    }
    
    .prompt-element {
        flex-direction: column;
        gap: var(--spacing-xs);
    }
    
    .quick-nav-toggle {
        right: 12px;
        width: 40px;
        height: 40px;
	top: 22%;
    }

    .sidebar-nav {
        left: 12px;
        bottom: 8%;
        padding: 10px 6px;
        transform: scale(0.85); 
        transform-origin: center;
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

/* ============================================
   LOGO DA FLUXOTECA (DARK MODE + BRILHO MÉDIO)
   ============================================ */
:root[data-theme="dark"] .hero-logo text:first-of-type {
  fill: #c7baff; /* lavanda clara */
  filter: drop-shadow(0 0 4px rgba(199, 186, 255, 0.55))
          drop-shadow(0 0 10px rgba(150, 120, 250, 0.25));
}

/* Caso use <tspan> dentro do <text> */
:root[data-theme="dark"] .hero-logo text:first-of-type tspan {
  fill: inherit;
}

</style>

</head>

<body>
  <div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>

<header id="header">
  <div class="header-content">
    <a href="#hero" class="brand hero-logo-header" aria-label="Fluxoteca">
      <svg class="hero-logo" viewBox="0 0 360 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
        <defs>
          <linearGradient id="heroPrimaryGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#764ba2;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#4facfe;stop-opacity:1" />
          </linearGradient>
          
          <linearGradient id="heroTextGradient" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#1a1a1a;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#667eea;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#1a1a1a;stop-opacity:1" />
          </linearGradient>
          
          <filter id="heroShadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="2" dy="3" stdDeviation="6" flood-color="#667eea" flood-opacity="0.1"/>
          </filter>
        </defs>
        
        <g transform="translate(25, 20)" filter="url(#heroShadow)">
          <circle cx="40" cy="30" r="25" fill="url(#heroPrimaryGradient)" opacity="0.08"/>
          <circle cx="40" cy="30" r="14" fill="url(#heroPrimaryGradient)" opacity="0.95" />
          <circle cx="40" cy="30" r="9" fill="white" opacity="0.95" />
          <circle cx="40" cy="30" r="5" fill="url(#heroPrimaryGradient)" opacity="0.8" />                        
        </g>
        
        <g transform="translate(120, 20)">
          <text x="0" y="35" font-family="Inter, -apple-system, BlinkMacSystemFont, sans-serif" 
                font-size="40" font-weight="700" fill="url(#heroTextGradient)" 
                letter-spacing="-0.25px" text-anchor="start">
            Fluxoteca
          </text>
          <text x="0" y="60" font-family="Inter, -apple-system, BlinkMacSystemFont, sans-serif" 
                font-size="18" font-weight="500" fill="#667eea" 
                letter-spacing="0.4px" text-anchor="start" opacity="0.8">
            Ferramentas para PMEs
          </text>
        </g>
      </svg>
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

 
<main class="main">

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
      Esta não é apenas uma leitura rápida, ele funciona como o “sistema operacional” que prepara seu raciocínio para extrair o máximo do <strong>Painel Interativo de Prompts</strong>, onde cada prompt foi desenhado para transformar caos em clareza e tirar peso da sua rotina.  
    </p>
    <p class="intro-text">
      O Painel te entrega <strong>todos os prompts prontos para uso</strong> em cada área, com estrutura completa, pronto para copiar e colar na IA.  
      Já o Guia te mostra <strong>como pensar antes de pedir</strong>, como montar cenários válidos, como transformar dúvidas vagas em entregas profissionais, e ainda traz exemplos bônus para cada área, seguindo a mesma lógica interna do Painel.
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
    <h2 class="section-title">Do genérico ao específico, a evolução do seu comando</h2>
<div class="educational-intro" style="margin-top: var(--spacing-lg); margin-bottom: var(--spacing-xl);">
    <p class="section-subtitle">
    Para criar prompts realmente úteis para sua PME, troque o “me ajuda com isso?” por “aqui está minha situação, minhas limitações e o que eu preciso no final”. A IA pensa melhor quando você deixa o cenário claro, como se estivesse explicando para alguém que vai entrar na sua empresa agora.
  </p>
</div>
    
    <!-- Primeiro Exemplo, Marketing -->
    <div class="vertical-comparison">
      <h3 class="comparison-title">Exemplo, Estratégia de Marketing</h3>
      
      <!-- Card Antes -->
      <div class="comparison-column">
        <div class="step-indicator">          
          <div class="step-label">Abordagem Genérica</div>
        </div>
        <div class="comparison-card before-card">
          <div class="card-header">
            <div class="card-badge error">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
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
              <h4>Por que isso não funciona bem</h4>
              <div class="problem-grid">
                <div class="problem-item"><div class="problem-icon">❌</div>Sem contexto do negócio</div>
                <div class="problem-item"><div class="problem-icon">❌</div>Nenhuma informação sobre recursos</div>
                <div class="problem-item"><div class="problem-icon">❌</div>Formato de resposta indefinido</div>
                <div class="problem-item"><div class="problem-icon">❌</div>Foco genérico, não aplicável</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Seta -->
      <div class="transition-arrow">
        <div class="arrow-line"></div>
        <div class="arrow-head">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14m0 0l-7-7m7 7l7-7"/>
          </svg>
        </div>
        <div class="arrow-text">Transforme em</div>
      </div>

      <!-- Card Depois -->
      <div class="comparison-column">
        <div class="step-indicator">
          <div class="step-label">Prompt Inteligente</div>
        </div>

        <div class="comparison-card after-card">
          <div class="card-header">
            <div class="card-badge success">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22,4 12,14.01 9,11.01"></polyline>
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
                    <strong>Contexto:</strong> Sou dono de uma padaria artesanal em um bairro residencial e quero atrair clientes de 25 a 45 anos sem investir pesado em anúncios.
                  </div>

                  <div class="prompt-element">
                    <span class="element-icon">📊</span>
                    <strong>Considere as informações disponíveis:</strong><br>
                    1. Orçamento mensal de R$ 800.<br>
                    2. Equipe pequena sem experiência em redes sociais.<br>
                    3. Produtos principais, pães artesanais e doces caseiros.<br>
                    4. Canais já usados e resultados anteriores (se houver).
                  </div>

                  <div class="prompt-element">
                    <span class="element-icon">⚙️</span>
                    <strong>Siga estas diretrizes:</strong><br>
                    • Estratégias simples para executar no dia a dia.<br>
                    • Foco em redes sociais orgânicas.<br>
                    • Tom acolhedor e próximo da comunidade do bairro.<br>
                    • Evitar jargões e ações complexas demais para uma PME.
                  </div>

                  <div class="prompt-element">
                    <span class="element-icon">📝</span>
                    <strong>Apresente o resultado assim:</strong><br>
                    Plano de 90 dias com 3 ações específicas por mês, tipos de conteúdo, frequência ideal e métricas simples para acompanhar.
                  </div>

                </div>
              </div>
            </div>

            <div class="analysis-section">
              <h4>Por que esta abordagem funciona</h4>
              <div class="benefit-grid">
                <div class="benefit-item"><div class="benefit-icon">✅</div>Contexto claro do negócio</div>
                <div class="benefit-item"><div class="benefit-icon">✅</div>Recursos bem definidos</div>
                <div class="benefit-item"><div class="benefit-icon">✅</div>Formato de entrega especificado</div>
                <div class="benefit-item"><div class="benefit-icon">✅</div>Estratégias realistas e aplicáveis</div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Segundo Exemplo, Vendas -->
    <div class="vertical-comparison" style="margin-top: var(--spacing-3xl);">
      <h3 class="comparison-title">Exemplo, Otimização de Vendas</h3>
      
      <!-- Card Antes -->
      <div class="comparison-column">
        <div class="step-indicator">          
          <div class="step-label">Abordagem Genérica</div>
        </div>
        <div class="comparison-card before-card">
          <div class="card-header">
            <div class="card-badge error">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
              </svg>
              O que não fazer
            </div>
          </div>

          <div class="card-content">
            <div class="prompt-example generic">
              <div class="example-text">
             "Como melhorar as vendas da minha loja?"</div>
            </div>

            <div class="analysis-section">
              <h4>Por que isso não funciona bem</h4>
              <div class="problem-grid">
                <div class="problem-item"><div class="problem-icon">❌</div>Nenhum dado sobre a situação atual</div>
                <div class="problem-item"><div class="problem-icon">❌</div>Sem análise da concorrência</div>
                <div class="problem-item"><div class="problem-icon">❌</div>Público-alvo indefinido</div>
                <div class="problem-item"><div class="problem-icon">❌</div>Resultado esperado vago</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Seta -->
      <div class="transition-arrow">
        <div class="arrow-line"></div>
        <div class="arrow-head">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14m0 0l-7-7m7 7l7-7"/>
          </svg>
        </div>
        <div class="arrow-text">Transforme em</div>
      </div>

      <!-- Card Depois -->
      <div class="comparison-column">
        <div class="step-indicator">
          <div class="step-label">Prompt Inteligente</div>
        </div>

        <div class="comparison-card after-card">
          <div class="card-header">
            <div class="card-badge success">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22,4 12,14.01 9,11.01"></polyline>
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
                    <strong>Atue como:</strong> Consultor de vendas especializado em varejo de moda feminina.
                  </div>

                  <div class="prompt-element">
                    <span class="element-icon">🎯</span>
                    <strong>Contexto:</strong> Tenho uma boutique de roupas femininas com queda de 30 por cento nas vendas nos últimos 6 meses devido ao aumento da concorrência online.
                  </div>

                  <div class="prompt-element">
                    <span class="element-icon">📊</span>
                    <strong>Considere as informações disponíveis:</strong><br>
                    1. Ticket médio atual de R$ 120.<br>
                    2. Público-alvo, mulheres de 25 a 50 anos, classes B e C.<br>
                    3. Concorrentes diretos em um raio de 500 metros.<br>
                    4. Localização em rua comercial com alto fluxo de pessoas.
                  </div>

                  <div class="prompt-element">
                    <span class="element-icon">⚙️</span>
                    <strong>Siga estas diretrizes:</strong><br>
                    • Ações com baixo investimento financeiro.<br>
                    • Diferenciação da concorrência local.<br>
                    • Foco em melhorias rápidas, aplicáveis em 30 dias.<br>
                    • Aproveitar a loja física como vantagem competitiva.
                  </div>

                  <div class="prompt-element">
                    <span class="element-icon">📝</span>
                    <strong>Apresente o resultado assim:</strong><br>
                    Lista de 5 estratégias priorizadas por impacto e esforço, com cronograma de implementação e investimentos necessários para cada ação.
                  </div>
                </div>

              </div>
            </div>

            <div class="analysis-section">
              <h4>Por que esta abordagem funciona</h4>
              <div class="benefit-grid">
                <div class="benefit-item"><div class="benefit-icon">✅</div>Dados concretos da situação</div>
                <div class="benefit-item"><div class="benefit-icon">✅</div>Análise competitiva considerada</div>
                <div class="benefit-item"><div class="benefit-icon">✅</div>Público bem definido</div>
                <div class="benefit-item"><div class="benefit-icon">✅</div>Resultado estruturado e priorizado</div>
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
<div class="educational-intro" style="margin-top: var(--spacing-lg); margin-bottom: var(--spacing-xl);">
    <p class="section-subtitle">
            Estes exemplos completos funcionam como mapas que você pode adaptar para qualquer área da sua PME. 
            Eles mostram como as cinco camadas se encaixam, como a IA raciocina quando recebe um cenário bem descrito 
            e como você pode replicar a mesma lógica para problemas totalmente diferentes. 
            Quanto mais você dominar essa estrutura, mais autonomia terá para criar prompts inteligentes sob medida 
            para o seu negócio.
          </p>
    </div>
    
    <div class="section-standard">
      
      <!-- Exemplo 1, TI para PMEs -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 11l3-3-3-3"></path>
              <path d="M12 4h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-7"></path>
              <path d="M3 12h11"></path>
            </svg>
            Cenário: Organização da TI em uma PME
          </div>
        </div>
        
        <div class="card-content">
          <!-- Prompt Completo -->
          <div class="example-section">
            <h4 class="example-title">🧩 Prompt completo, TI enxuta e organizada</h4>
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
                🧠 <strong>Atue como:</strong> Especialista em Infraestrutura de TI para PMEs brasileiras (sem jargões técnicos, com foco em impacto no negócio).<br><br>
                
                🎯 <strong>Contexto:</strong> Tenho uma empresa com processos críticos em ERP e planilhas, servidores locais antigos, parte da equipe remota e histórico de quedas, lentidão e risco de perda de dados. Quero entender onde estão os maiores riscos e o que posso fazer em fases para organizar a infraestrutura sem parar a operação.<br><br>
                
                📊 <strong>Considere as informações disponíveis:</strong><br>
                1. Módulos críticos do ERP e qual deles não pode parar de jeito nenhum.<br>
                2. Sistemas, integrações e planilhas que se conectam ao ERP.<br>
                3. Como a equipe acessa o sistema hoje (local, remoto, VPN, desktop remoto etc.).<br>
                4. Quantidade aproximada de computadores, servidores e dispositivos usados na operação.<br>
                5. Rotina de backup atual (se existe, onde é feito e quem é responsável).<br>
                6. Principais ocorrências recentes de falhas, quedas ou perda de dados.<br>
                7. Limitações de orçamento, equipe interna de TI e suporte terceirizado.<br><br>
                
                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Objetivo principal: mapear riscos críticos e organizar um plano de melhoria de infraestrutura em fases, sem travar o dia a dia.<br>
                • Traduza qualquer termo técnico em impacto prático para o negócio (tempo parado, risco fiscal, retrabalho etc.).<br>
                • Preserve o que já funciona bem e sinalize o que pode ser ajustado, em vez de propor “revolução de tudo”.<br>
                • Priorize primeiro o que reduz risco de parada, perda de dados e problemas fiscais/contábeis.<br>
                • Proponha melhorias em 2 ou 3 fases, com ganhos progressivos e investimentos proporcionais.<br>
                • Considere que a empresa tem equipe enxuta e pouco tempo para projetos longos.<br><br>

                ❗ <strong>Quando faltarem dados:</strong> peça informações em blocos curtos e objetivos, priorizando riscos (backup, acesso remoto, quedas de sistema) antes de detalhes secundários. Não faça mais de 5 perguntas por vez.<br><br>
                
                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Um resumo executivo de até 10 linhas explicando a situação atual e os principais riscos.<br>
                2. Uma lista de riscos críticos, em ordem de prioridade, com 1 ou 2 linhas de explicação cada.<br>
                3. Um plano em 2 ou 3 fases com:<br>
                &nbsp;&nbsp;• foco de cada fase<br>
                &nbsp;&nbsp;• ações sugeridas<br>
                &nbsp;&nbsp;• impacto esperado (risco reduzido, ganho de estabilidade, redução de retrabalho)<br>
                4. Uma lista curta de próximos passos práticos para o gestor decidir o que começar primeiro.
              </div>
            </div>
          </div>

          <!-- Análise das Camadas Aplicadas -->
          <div class="example-section">
            <h4 class="example-title">🔍 Análise das camadas aplicadas</h4>
            <div class="standard-grid">
              <div class="standard-grid-item positive">
                <div class="standard-icon">1</div>
                <div><strong>Atue como:</strong> chama a expertise exata de suporte e infraestrutura para PME, nada genérico.</div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">2</div>
                <div><strong>Contexto:</strong> descreve o tamanho da empresa, o tipo de dor e o cenário de riscos concretos.</div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">3</div>
                <div><strong>Considere as informações disponíveis:</strong> define quais dados mínimos a IA precisa para não trabalhar no escuro.</div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">4</div>
                <div><strong>Siga estas diretrizes:</strong> limita soluções fora da realidade, como grandes investimentos ou projetos complexos.</div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">5</div>
                <div><strong>Apresente o resultado assim:</strong> orienta a IA a entregar um plano concreto, com fases claras, prioridades e próximos passos.</div>
              </div>
            </div>
          </div>

          <!-- Resultado Esperado -->
          <div class="example-section">
            <h4 class="example-title">🎯 Resultado esperado</h4>
            <div class="resultado-box">
              <div class="standard-grid standard-grid-2">
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">📂</div>
                  <div>Mapa simples de categorias de chamados e riscos críticos</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">🛠️</div>
                  <div>Sugestão de ferramenta leve de registro e acompanhamento da TI</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">📅</div>
                  <div>Plano em fases com rotina semanal de manutenção e checklist pronto</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">⚡</div>
                  <div>Lista de melhorias rápidas com alto impacto na estabilidade da operação</div>
                </div>
              </div>
              <p class="resultado-descricao">
                A ideia é transformar a TI da empresa de um setor que só apaga incêndios em um suporte previsível, 
                com fila organizada, manutenção básica e decisões mais conscientes sobre prioridades e investimentos.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Exemplo 2, Organização do Tempo e Fluxos de Trabalho -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 8v4l3 3"></path>
              <circle cx="12" cy="12" r="10"></circle>
            </svg>
            Cenário: Organização do Tempo e Fluxos de Trabalho
          </div>
        </div>
        
        <div class="card-content">
          <!-- Prompt Completo -->
          <div class="example-section">
            <h4 class="example-title">🧩 Prompt completo, rotina enxuta para PME</h4>
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
                🧠 <strong>Atue como:</strong> Consultor(a) em Organização do Trabalho para PMEs, com foco em fluxo de tarefas e priorização realista.<br><br>
                
                🎯 <strong>Contexto:</strong> Tenho uma empresa em que quase tudo chega por mensagem direta, e-mail ou grupos de WhatsApp. Demandas urgentes se misturam com tarefas estratégicas, não temos um fluxo claro de priorização e eu vivo apagando incêndio. Quero organizar o fluxo de trabalho em etapas simples, com uma rotina que eu realmente consiga seguir.<br><br>
                
                📊 <strong>Considere as informações disponíveis:</strong><br>
                1. Canais pelos quais as demandas chegam hoje (WhatsApp, e-mail, reuniões, ligações etc.).<br>
                2. Tipos de demanda mais comuns (operacionais, estratégicas, urgentes, “favorzinhos” etc.).<br>
                3. Quem pode decidir prioridades (somente o dono, líderes de área, todos etc.).<br>
                4. Ferramentas já usadas (planilhas, Trello, Notion, agenda de papel etc.).<br>
                5. Horas disponíveis por dia para trabalho profundo (sem interrupções).<br>
                6. Tamanho da equipe e quem ajuda a executar tarefas.<br>
                7. Principais sintomas atuais (atrasos, retrabalho, perda de prazos, exaustão etc.).<br><br>
                
                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Objetivo principal: transformar o caos de tarefas soltas em um fluxo visível de entrada, triagem, priorização e execução.<br>
                • Traga soluções simples, que funcionem mesmo com a rotina cheia, evitando sistemas complexos demais.<br>
                • Use linguagem clara, sem termos motivacionais vazios ou jargões corporativos.<br>
                • Considere que a pessoa ainda está no dia a dia da operação, não consegue “parar tudo” para mudar.<br>
                • Sugira blocos de tempo e rituais semanais curtos (30 a 60 minutos) para revisão e ajuste do fluxo.<br>
                • Foque em criar um sistema que funcione “bom o suficiente”, não perfeito.<br><br>

                ❗ <strong>Quando faltarem dados:</strong> peça exemplos concretos de um ou dois dias típicos de trabalho, em vez de perguntas abstratas. Use perguntas que ajudem a visualizar o fluxo, como “o que acontece depois que alguém te manda uma mensagem com uma demanda?”.<br><br>
                
                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Um resumo simples da situação atual, em até 8 linhas.<br>
                2. Um fluxograma textual com 4 a 6 etapas (entrada → triagem → priorização → execução → acompanhamento).<br>
                3. Sugestão de quadro ou lista de tarefas (colunas ou categorias) com exemplo preenchido.<br>
                4. Proposta de rotina mínima semanal (em minutos) para revisar prioridades e ajustar o fluxo.<br>
                5. Lista curta de primeiros passos para testar o modelo na prática nos próximos 7 dias.
              </div>
            </div>
          </div>

          <!-- Análise das Camadas Aplicadas -->
          <div class="example-section">
            <h4 class="example-title">🔍 Análise das camadas aplicadas</h4>
            <div class="standard-grid">
              <div class="standard-grid-item positive">
                <div class="standard-icon">1</div>
                <div><strong>Atue como:</strong> chama um perfil focado em produtividade e operação, que entende rotina de PME.</div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">2</div>
                <div><strong>Contexto:</strong> descreve a realidade do dia a dia, com demandas espalhadas e sensação constante de incêndio.</div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">3</div>
                <div><strong>Considere as informações disponíveis:</strong> orienta quais informações a empresa precisa levantar para o plano fazer sentido.</div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">4</div>
                <div><strong>Siga estas diretrizes:</strong> protege a empresa de soluções engessadas ou cheias de burocracia que não vão ser usadas.</div>
              </div>
              <div class="standard-grid-item positive">
                <div class="standard-icon">5</div>
                <div><strong>Apresente o resultado assim:</strong> define que o resultado final tem que ser um mapa prático de uso diário, não um relatório teórico.</div>
              </div>
            </div>
          </div>

          <!-- Resultado Esperado -->
          <div class="example-section">
            <h4 class="example-title">🎯 Resultado esperado</h4>
            <div class="resultado-box">
              <div class="standard-grid standard-grid-2">
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">📋</div>
                  <div>Quadro de prioridades semanais com o que realmente não pode ser adiado</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">🔄</div>
                  <div>Fluxo simples de entrada, triagem, priorização e execução das tarefas</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">👥</div>
                  <div>Responsáveis por área definidos de forma clara e realista</div>
                </div>
                <div class="standard-grid-item neutral">
                  <div class="standard-icon">✅</div>
                  <div>Checklist semanal para manter a rotina organizada mesmo em dias caóticos</div>
                </div>
              </div>
              <p class="resultado-descricao">
                O objetivo é tirar a equipe do modo incêndio constante e criar uma rotina mínima, visual e possível, 
                que reduz esquecimento, retrabalho e sensação de sobrecarga.
              </p>
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
    <p class="section-subtitle">
      Estratégias para quem já domina o básico e quer transformar a IA em um braço analítico capaz de trabalhar com decisões, riscos e cenários reais.
    </p>

    <div class="section-standard">

      <!-- Caixa de enquadramento -->
      <div class="definition-box">
        <p>Técnicas avançadas não são “firulas de prompt”.</p>
        <p>
          Elas conectam <strong>como você pensa</strong>, <strong>como a IA responde</strong> e <strong>como a sua empresa decide</strong>.  
          A ideia aqui é sair do uso reativo (“me dá uma resposta”) e ir para um uso estrutural:  
          construir raciocínios, testar hipóteses e reduzir risco de decisão.
        </p>
      </div>

      <!-- BLOCO 1: ARQUITETURA DE RACIOCÍNIO -->
      <div class="vertical-comparison">
      
<!-- Seta de transição entre blocos -->
      <div class="transition-arrow">
        <div class="arrow-line"></div>
        <div class="arrow-head">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14m0 0l-7-7m7 7l7-7"/>
          </svg>
        </div>
        <div class="arrow-text">
          Primeiro você cria a Arquitetura de raciocínio
        </div>
      </div>
        <p class="card-intro">
          Neste bloco, você usa a IA para montar um <strong>raciocínio em camadas</strong>, em vez de respostas soltas.  
          Primeiro organiza a análise, depois muda de lente, depois testa a robustez.
        </p>

<div class="techniques-grid">

          <!-- Técnica 1 -->
          <div class="standard-card">
            <div class="standard-card-header">
              <div class="standard-badge primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 18l6-6-6-6"/>
                </svg>
                Encadeamento de Prompts (raciocínio progressivo)
              </div>
            </div>
            <div class="card-content">
              <p>
                Use a IA como se estivesse montando um <strong>relatório vivo</strong>.  
                Cada resposta vira insumo para a próxima etapa.  
                Isso reduz saltos de lógica e obriga o modelo a construir a análise em camadas.
              </p>
              <div class="standard-example">
                <div class="standard-example-content">
                  1. “Analise o cenário X e aponte os 5 pontos críticos.”<br>
                  2. “Com base nesses pontos críticos, estruture um plano de ação em fases.”<br>
                  3. “Agora identifique riscos, dependências e decisões críticas para cada fase.”
                </div>
              </div>
<p>
  <strong>Ideia central:</strong> em vez de tentar resolver tudo em um único prompt,
  você obriga o modelo a pensar em etapas, como um analista montando um dossiê.
</p>

            </div>
          </div>

          <!-- Técnica 2 -->
          <div class="standard-card">
            <div class="standard-card-header">
              <div class="standard-badge primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/>
                  <line x1="12" y1="8" x2="12" y2="12"/>
                  <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Perspectivas Múltiplas (mudança de lentes)
              </div>
            </div>
            <div class="card-content">
              <p>
                Quando você força a IA a mudar de papel, ela é obrigada a revisar o raciocínio.  
                Isso diminui viés e evita respostas “bonitinhas, porém rasas”.
              </p>
              <div class="standard-example">
                <div class="standard-example-content">
                  “Explique essa decisão primeiro como <strong>CFO</strong>, depois como <strong>Head de Vendas</strong>  
                  e depois como <strong>cliente final</strong>. Mostre o que preocupa cada um.”
                </div>
              </div>
<p>
  <strong>Ideia central:</strong> mudar de papel força a IA a revisar o próprio raciocínio
  e evita respostas bonitas, porém desalinhadas com a realidade do negócio.
</p>
            </div>
          </div>

          <!-- Técnica 3 -->
          <div class="standard-card">
            <div class="standard-card-header">
              <div class="standard-badge primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="20,6 9,17 4,12"/>
                </svg>
                Validação Cruzada (testes de robustez)
              </div>
            </div>
            <div class="card-content">
              <p>
                Aqui você faz o papel de auditoria: não aceita a primeira resposta como verdade,  
                pede para o próprio modelo tentar derrubar o que ele acabou de propor.
              </p>
              <div class="standard-example">
                <div class="standard-example-content">
                  “Agora atue como <strong>auditor externo</strong> e aponte falhas, riscos e pontos cegos  
                  na análise que você mesmo acabou de construir.”
                </div>
              </div>
<p>
  <strong>Ideia central:</strong> você trata a resposta da IA como uma hipótese a ser testada,
  não como verdade pronta. Isso reduz o risco de seguir uma análise frágil.
</p>
            </div>
          </div>

        </div>
      </div>

      <!-- BLOCO 2: CONTROLE, RISCO E AMBIENTE -->
      <div class="vertical-comparison">

      <!-- Seta de transição entre blocos -->
      <div class="transition-arrow">
        <div class="arrow-line"></div>
        <div class="arrow-head">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14m0 0l-7-7m7 7l7-7"/>
          </svg>
        </div>
        <div class="arrow-text">
          Depois de estruturar o raciocínio, você passa a controlar riscos e ambiente.
        </div>
      </div>

        <p class="card-intro">
          Agora o foco deixa de ser “apenas raciocinar bem” e passa a ser <strong>decidir com menos surpresa</strong>.  
          Você valida premissas, expõe suposições, organiza arquivos e ajusta o próprio ambiente do ChatGPT.
        </p>

<div class="techniques-grid">

          <!-- Técnica 4 -->
          <div class="standard-card">
            <div class="standard-card-header">
              <div class="standard-badge primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
                Checagem Contextual (coerência e premissas)
              </div>
            </div>
            <div class="card-content">
              <p>
                A IA pode estar logicamente coerente e, ao mesmo tempo,  
                completamente deslocada da realidade da sua empresa.  
                Por isso, você pede para ela abrir as premissas.
              </p>
              <div class="standard-example">
                <div class="standard-example-content">
                  “Liste as premissas que você usou (em até 4 itens) e valide se cada uma realmente se aplica  
                  ao contexto de uma PME brasileira com equipe enxuta. Aponte o que não encaixa.”
                </div>
              </div>
<p>
  <strong>Ideia central:</strong> a IA pode estar certa em outro contexto,
  mas errada na sua operação. Abrir premissas é o que faz a resposta “encaixar” na sua PME.
</p>
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
                Auditoria de Saída (risco de decisão)
              </div>
            </div>
            <div class="card-content">
              <p>
                Aqui você pede, explicitamente, para a IA mostrar onde a própria recomendação pode dar errado.  
                Isso é especialmente útil antes de levar algo para diretoria ou cliente.
              </p>
              <div class="standard-example">
                <div class="standard-example-content">
                  “Liste 3 suposições invisíveis que você está fazendo nesta recomendação  
                  e descreva em que cenário cada uma poderia gerar um erro de decisão grave.”
                </div>
              </div>
<p>
  <strong>Ideia central:</strong> antes de levar algo para diretoria ou cliente,
  você obriga o modelo a mostrar onde a recomendação pode falhar. Isso cria um “cinto de segurança” de decisão.
</p>
            </div>
          </div>

          <!-- Técnica 6 -->
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
              <p>
                Se seus arquivos estão confusos, o raciocínio da IA também fica.  
                Você trata cada versão como um “estado” claro do documento.
              </p>
              <div class="standard-example">
                <div class="standard-example-content">
                  • <code>Nome autoexplicativo</code><br>
                  • <code>Versão semântica (v1.0, v1.1, v2.0…)</code><br>
                  • <code>Status (rascunho, revisão, final)</code><br>
                  <strong>Exemplo</strong>: <code>guia_prompts_v2.0-revisao.html</code>
                </div>
              </div>
<p>
  <strong>Ideia central:</strong> quando os arquivos contam uma história clara (nome, versão, status),
  a IA entende em que “momento do filme” ela está e responde com muito menos ruído.
</p>

            </div>
          </div>

          <!-- Técnica 7 -->
          <div class="standard-card">
            <div class="standard-card-header">
              <div class="standard-badge primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 6v6l4 2"/>
                  <circle cx="12" cy="12" r="10"/>
                </svg>
                Uso Estratégico de Agentes (GPTs específicos)
              </div>
            </div>
            <div class="card-content">
              <p>
                Pense nos agentes como “funções externas” altamente especializadas.  
                Você terceiriza microtarefas para eles e mantém a conversa principal focada na decisão.
              </p>
              <div class="standard-example">
                <div class="standard-example-content">
                  “Envie este texto para o <strong>Agente de Revisão Técnica</strong>, corrija termos incorretos  
                  e devolva apenas o texto limpo.”<br><br>
                  “Use o <strong>Agente Financeiro</strong> para montar a projeção numérica.  
                  Depois, traga os resultados para cá e foque apenas na análise estratégica.”<br><br>
                  “Peça ao <strong>Agente Analista de Dados</strong> que explique discrepâncias  
                  antes de montar o relatório que será apresentado ao diretor.”
                </div>
              </div>
              <p>
<p>
  <strong>Ideia central:</strong> cada agente resolve uma microparte do trabalho cognitivo;
  você concentra energia em integrar os resultados e decidir.
</p>
              </p>
            </div>
          </div>

          <!-- Técnica 8 -->
          <div class="standard-card">
            <div class="standard-card-header">
              <div class="standard-badge primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 12h18"/>
                  <path d="M3 6h18"/>
                  <path d="M3 18h18"/>
                </svg>
                Personalização do Ambiente (configurações do ChatGPT)
              </div>
            </div>
            <div class="card-content">
              <p>
                Usuário avançado não depende só de bons prompts, ele configura o ambiente.  
                Isso faz a IA “pensar mais parecido” com o seu jeito de trabalhar.
              </p>

              <div class="standard-example">
                <div class="standard-example-content">
                  <strong>Ajustes que valem ouro:</strong><br><br>
                  • <strong>Memória ativa</strong>: deixe a IA aprender seu contexto, público e produtos.<br>
                  • <strong>Instruções personalizadas</strong>: defina como quer as respostas, sempre.<br>
                  • <strong>Preferência de modelos</strong>: escolha modelos diferentes para criação, análise ou código.<br>
                  • <strong>Organização interna</strong>: mantenha arquivos recorrentes em uma mesma conversa ou pasta lógica.<br><br>
 <p>
                <strong>Exemplo prático de instrução personalizada:</strong><br>
                “Sempre peça premissas antes de gerar um diagnóstico.  
                Prefiro respostas estruturadas, em tópicos, e que expliquem o porquê antes do como.”
              </p>
                </div>
              </div>
<p>
  <strong>Ideia central:</strong> com o ambiente configurado, você transforma o ChatGPT
  de “caixa de respostas” em um assistente que pensa mais parecido com você.
</p>            
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
    <h2 class="section-title">Prompts Bônus Exclusivos</h2>
    <p class="section-subtitle">
      Estes prompts extras expandem o método para áreas que influenciam diretamente a rotina do dono da PME,
      como tomada de decisão, foco, energia, criatividade e liderança humana.
      São exemplos avançados que mostram como a IA pode organizar não só processos,
      mas também o próprio raciocínio.
    </p>
    
    <div class="section-standard">

      <!-- ========================================================= -->
      <!-- CARD 1 – Reset mental rápido -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Reset mental rápido antes de uma decisão importante</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-1"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Mini-reset para organizar o pensamento quando surge uma decisão importante e a mente está com ruído.
              </p>

              <div
                id="prompt-bonus-1"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Mentor(a) de tomada de decisão para empreendedores, especializado(a) em clareza emocional e raciocínio objetivo.<br><br>

                🎯 <strong>Contexto:</strong> Estou diante de uma decisão importante e sinto ansiedade leve, ruído mental e dificuldade de enxergar as opções com lógica. Quero reorganizar o pensamento e chegar a uma decisão mais calma e estruturada.<br><br>

                📊 <strong>Considere:</strong><br>
                1. Qual é a decisão e o prazo real.<br>
                2. Sintomas emocionais ou físicos presentes.<br>
                3. Alternativas que já considerei.<br>
                4. Consequências de curto e longo prazo.<br>
                5. Melhor e pior cenário que visualizei até agora.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Comece com um diagnóstico em 5 linhas, traduzindo a situação em linguagem simples.<br>
                • Analise a decisão em 3 lentes: racional, emocional e prática (logística/recursos).<br>
                • Reduza tudo a 2 ou 3 caminhos possíveis, com impactos claros e honestos.<br>
                • Use linguagem acolhedora, sem jargões psicológicos complicados.<br>
                • Feche com uma recomendação objetiva e um micro ritual de 60 segundos para estabilizar foco.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu descreva, em até 3 linhas cada: a decisão, como me sinto e o que temo que aconteça.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Mini diagnóstico em até 8 linhas.<br>
                2. Análise em 3 lentes (racional, emocional, prática).<br>
                3. Lista de 2 ou 3 caminhos possíveis com impactos.<br>
                4. Recomendação final objetiva.<br>
                5. Micro ritual rápido de 60 segundos para clarear a mente.
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 2 – Redução da sobrecarga mental -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Redução da sobrecarga mental em dias caóticos</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-2"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Uma limpeza mental guiada para reorganizar prioridades quando tudo parece urgente ao mesmo tempo.
              </p>

              <div
                id="prompt-bonus-2"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Facilitador(a) de redução de sobrecarga mental para profissionais multitarefas.<br><br>

                🎯 <strong>Contexto:</strong> Estou em um dia cheio, com demandas simultâneas, interrupções e sensação de descontrole. Quero reduzir a carga mental e reorganizar o que realmente importa hoje.<br><br>

                📊 <strong>Considere:</strong><br>
                1. Quais tarefas estão me pressionando agora.<br>
                2. O que depende de mim e o que depende de terceiros.<br>
                3. Prazos reais versus prazos que eu interpretei como urgentes.<br>
                4. Meu nível de energia atual (baixo, médio, alto).<br>
                5. Impacto real se cada tarefa atrasar 1 ou 2 dias.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Comece com uma “limpeza mental” em até 6 linhas, organizando o que está na minha cabeça.<br>
                • Reorganize as demandas em quatro grupos: fazer hoje, delegar, reagendar, cancelar.<br>
                • Mostre com clareza quais riscos são reais e quais são exageros da minha percepção.<br>
                • Sugira 1 ação simples, de baixa energia, para aliviar a sensação de peso imediato.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu liste, sem filtro, tudo o que “está na minha cabeça agora” em forma de bullets.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Limpeza mental organizada em tópicos.<br>
                2. Quadro com as quatro categorias (fazer, delegar, reagendar, cancelar).<br>
                3. Top 3 ações prioritárias para hoje.<br>
                4. Uma ação rápida de alívio imediato (de 5 a 10 minutos).
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 3 – Planejamento da semana -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Planejamento da semana em 10 minutos</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-3"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Um planejamento semanal mínimo, simples e realista, com foco nas três prioridades que realmente movem o ponteiro.
              </p>

              <div
                id="prompt-bonus-3"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Facilitador(a) de clareza pessoal especializado(a) em foco leve e produtivo.<br><br>

                🎯 <strong>Contexto:</strong> Quero iniciar a semana entendendo o essencial, sem criar listas gigantes ou planos impossíveis de cumprir.<br><br>

                📊 <strong>Considere:</strong><br>
                1. Projetos ativos que não posso abandonar.<br>
                2. Obrigações fixas (reuniões, fechamentos, compromissos pessoais).<br>
                3. Limite real de energia ao longo da semana.<br>
                4. Prazos inegociáveis que já existem.<br>
                5. Coisas que quero avançar “pouco a pouco” sem pressão.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Traga no máximo 3 prioridades reais para a semana.<br>
                • Use blocos de intenção (períodos focados) em vez de listas de 30 tarefas.<br>
                • Considere um “plano B” caso um dia saia do controle.<br>
                • Feche com um resumo simples que caiba em 8 linhas.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu liste rapidamente compromissos fixos e projetos ativos desta semana.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Três prioridades da semana, com justificativa curta.<br>
                2. Sugestão de blocos de tempo (manhã/tarde/noite) para cada prioridade.<br>
                3. Mini plano de contingência (“se X travar, faço Y”).<br>
                4. Resumo final em até 8 linhas.
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 4 – Ajuste de vida -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Ajuste de vida quando algo saiu do eixo</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-4"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Um diagnóstico rápido para entender o que saiu do eixo e quais pequenos ajustes trazem de volta uma sensação de controle.
              </p>

              <div
                id="prompt-bonus-4"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Mentor(a) de realinhamento pessoal para momentos de sobrecarga ou desorganização interna.<br><br>

                🎯 <strong>Contexto:</strong> Sinto que saí do meu eixo nos últimos dias e quero entender o que mudou, o que estou carregando a mais e qual o próximo passo mais inteligente.<br><br>

                📊 <strong>Considere:</strong><br>
                1. Mudanças recentes no humor, na rotina ou no sono.<br>
                2. Aumento de carga emocional (preocupações, conflitos, culpas).<br>
                3. Hábitos que eu fazia e deixei de fazer.<br>
                4. Coisas que estou evitando encarar ou decidir.<br>
                5. Sinais físicos ou mentais de exaustão.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Comece com um diagnóstico curto que conecte fatos, emoções e contexto.<br>
                • Aponte padrões e pontos cegos, sem julgamento moral.<br>
                • Traga 1 ajuste imediato para hoje e 1 ajuste para as próximas 48 horas.<br>
                • Evite planos grandiosos; foque em pequenos movimentos consistentes.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu descreva: meu último “dia bom”, meu último “dia difícil” e o que mudou entre eles.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Diagnóstico em até 10 linhas.<br>
                2. Padrões identificados (em bullets).<br>
                3. Ajuste imediato para hoje.<br>
                4. Ajustes para as próximas 48 horas.<br>
                5. Sinal de progresso para eu observar em mim.
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 5 – Brainstorm rápido -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Brainstorm rápido para destravar ideias</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-5"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Um disparo criativo para gerar várias alternativas e depois refinar o que é viável de verdade.
              </p>

              <div
                id="prompt-bonus-5"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Facilitador(a) criativo(a) para PMEs que precisam gerar alternativas práticas.<br><br>

                🎯 <strong>Contexto:</strong> Preciso de novas ideias para resolver um problema específico, mas estou travado(a) e repetindo sempre as mesmas soluções.<br><br>

                📊 <strong>Considere:</strong><br>
                1. O problema central que quero resolver.<br>
                2. Soluções que já tentei e fracassaram.<br>
                3. Restrições de orçamento, tempo e equipe.<br>
                4. Impacto se nada mudar.<br>
                5. Como seria uma solução ideal, sem limites, apenas como referência.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Traga primeiro 7 ideias rápidas, sem julgamento, em modo “chuva de ideias”.<br>
                • Depois refine 3 ideias viáveis levando em conta os limites reais da PME.<br>
                • Apresente 1 caminho mais seguro e 1 caminho mais ousado, e explique a diferença.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu descreva o contexto em no máximo 5 linhas, com foco em “o que está pegando agora”.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Lista com 7 ideias soltas.<br>
                2. Tabela com 3 ideias refinadas (vantagens, riscos, esforço).<br>
                3. Um caminho seguro e um caminho ousado destacados.<br>
                4. Primeiro passo imediato para começar a testar.
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 6 – Criar nomes -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Criar nomes de projetos, ações ou produtos</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-6"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Um gerador estruturado com variações de nomes em estilos diferentes, já com indicação dos mais promissores.
              </p>

              <div
                id="prompt-bonus-6"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Especialista em criação de nomes marcantes e memoráveis para PMEs.<br><br>

                🎯 <strong>Contexto:</strong> Preciso criar um nome forte, simples e fácil de lembrar para um projeto, ação interna ou produto, sem cair em clichês.<br><br>

                📊 <strong>Considere:</strong><br>
                1. Objetivo principal do projeto ou produto.<br>
                2. Público que vai usar ou comprar.<br>
                3. Tom desejado (leve, técnico, aspiracional, popular etc.).<br>
                4. Benefício central que quero comunicar.<br>
                5. Palavras proibidas ou cansadas do mercado.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Traga 12 opções de nomes divididas em 3 estilos (ex.: direto, criativo, conceitual).<br>
                • Explique em 1 linha o raciocínio de cada estilo.<br>
                • Aponte quais 3 nomes têm maior potencial de uso prático no contexto de PME.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu informe 3 palavras que representam o projeto/produto e 3 concorrentes de referência.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Explicação rápida dos 3 estilos de nome.<br>
                2. Lista de nomes por estilo, em bullet points.<br>
                3. Destaque de 3 nomes recomendados, com justificativa curta de cada um.
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 7 – Feedback difícil, porém humano -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Construir um feedback difícil, porém humano</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-7"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Um roteiro de conversa que fala o que precisa ser dito sem humilhar, sem rodeios e sem criar clima de guerra.
              </p>

              <div
                id="prompt-bonus-7"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Mentor(a) de liderança humana para pequenas equipes.<br><br>

                🎯 <strong>Contexto:</strong> Preciso dar um feedback delicado para alguém da equipe sem causar defensividade ou desconforto desnecessário.<br><br>

                📊 <strong>Considere:</strong><br>
                1. O comportamento específico que preciso abordar.<br>
                2. Consequências reais desse comportamento para o time, clientes ou resultados.<br>
                3. Histórico da pessoa (recém-chegada, experiente, em desenvolvimento etc.).<br>
                4. Tom ideal da conversa (mais direto, mais cuidadoso, mais pedagógico).<br>
                5. Resultado que eu gostaria de ver depois da conversa.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Construa um roteiro com início acolhedor, contexto, fato, impacto e acordo.<br>
                • Traga exemplos de frases que evitam ataque pessoal e generalizações.<br>
                • Inclua 2 ou 3 perguntas que ajudem a pessoa a refletir, não se defender.<br>
                • Feche com um acordo claro, simples e verificável no dia a dia.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu descreva em 3 frases o que aconteceu, qual impacto teve e por que isso me incomodou.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Mini diagnóstico da situação.<br>
                2. Roteiro completo da conversa, em blocos.<br>
                3. Sugestão de frases-chave que evitam ataque e sarcasmo.<br>
                4. Proposta de acordo final e forma de acompanhar.
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 8 – Reforço positivo -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Reforço positivo para desenvolver alguém da equipe</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-8"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Um modelo de reconhecimento específico, com impacto e próximos passos, para fortalecer quem já está evoluindo.
              </p>

              <div
                id="prompt-bonus-8"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Coach de desenvolvimento humano para PMEs.<br><br>

                🎯 <strong>Contexto:</strong> Quero incentivar alguém que está evoluindo, reforçar o bom desempenho e definir próximos passos de crescimento sem parecer cobrança disfarçada.<br><br>

                📊 <strong>Considere:</strong><br>
                1. Exemplos concretos de progresso que observei.<br>
                2. Padrões positivos de comportamento ou postura.<br>
                3. Impacto desse avanço na equipe, nos clientes ou nos resultados.<br>
                4. Possíveis próximos passos de desenvolvimento.<br>
                5. Estilo de comunicação da pessoa (mais direta, mais sensível, mais tímida etc.).<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Evite elogios genéricos, como “você é ótimo”.<br>
                • Conecte sempre comportamento → impacto → reconhecimento.<br>
                • Sugira novos desafios ou responsabilidades como convite, não como obrigação.<br>
                • Mantenha tom humano e realista, sem frases motivacionais vazias.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu descreva um episódio concreto que mostra essa evolução da pessoa.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Reconhecimento claro e específico.<br>
                2. Explicação do impacto positivo gerado.<br>
                3. Sugestão de próximos passos de desenvolvimento.<br>
                4. Mensagem final motivadora, porém pé no chão.
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 9 – Diagnóstico de gargalos -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Diagnóstico rápido de gargalos operacionais</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-9"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Um scanner simples para localizar pontos que geram fila, retrabalho, atraso e dependência excessiva de uma única pessoa.
              </p>

              <div
                id="prompt-bonus-9"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Analista de melhoria contínua especializado(a) em identificar gargalos simples e de alto impacto.<br><br>

                🎯 <strong>Contexto:</strong> Quero identificar rapidamente onde estão os gargalos que mais geram retrabalho, atrasos ou perda de eficiência na operação.<br><br>

                📊 <strong>Considere:</strong><br>
                1. Pontos do fluxo onde há filas, esperas e acúmulo de tarefas.<br>
                2. Atividades que não têm padrão definido e variam conforme a pessoa.<br>
                3. Processos totalmente dependentes de uma única pessoa-chave.<br>
                4. Erros repetitivos que geram retrabalho.<br>
                5. Atrasos previsíveis que “sempre” acontecem nas mesmas etapas.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Faça um diagnóstico inicial em até 10 linhas, destacando sintomas principais.<br>
                • Aponte os 3 gargalos de maior impacto, explicando por que eles são críticos.<br>
                • Sugira microcorreções que possam ser testadas em pouco tempo, com esforço baixo.<br>
                • Evite propostas de redesenho total da empresa; foque em melhorias incrementais.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu cite 3 pontos que “sempre dão problema” no dia a dia.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Diagnóstico geral em texto curto.<br>
                2. Lista dos 3 principais gargalos, em ordem de prioridade.<br>
                3. Sugestões de microcorreções para cada gargalo.<br>
                4. Indicação de qual gargalo atacar primeiro e por quê.
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- ========================================================= -->
      <!-- CARD 10 – Mini experimento 7 dias -->
      <!-- ========================================================= -->
      <div class="standard-card">
        <div class="standard-card-header">
          <div class="standard-badge primary">
            ⭐ Prompt Bônus
          </div>
        </div>

        <div class="card-content">
          <h4 class="example-title">Criar um mini experimento de melhoria em 7 dias</h4>

          <div class="standard-example">
            <div class="example-header">
              <button
                class="prompt-toggle-btn"
                type="button"
                aria-expanded="false"
                aria-controls="prompt-bonus-10"
                onclick="togglePrompt(this)"
                aria-label="Mostrar ou ocultar o texto completo deste prompt"
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"></path>
                </svg>
              </button>

              <button class="standard-copy-btn" type="button" onclick="copyPrompt(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Copiar Prompt</span>
              </button>
            </div>

            <div class="standard-example-content">
              <p class="prompt-preview">
                Um teste rápido, de baixo risco e com começo, meio e fim claros, para melhorar um processo real em apenas 7 dias.
              </p>

              <div
                id="prompt-bonus-10"
                class="prompt-full prompt-text"
                hidden
              >
                🧠 <strong>Atue como:</strong> Estrategista de inovação leve para PMEs.<br><br>

                🎯 <strong>Contexto:</strong> Quero testar uma melhoria pequena, rápida e simples em um processo da empresa, sem grandes riscos ou investimentos, em um período de 7 dias.<br><br>

                📊 <strong>Considere:</strong><br>
                1. O processo alvo que desejo melhorar.<br>
                2. O sintoma principal que quero reduzir (atraso, erro, retrabalho, reclamação etc.).<br>
                3. Resultado mínimo que eu consideraria um sucesso.<br>
                4. Recursos disponíveis (pessoas, tempo, ferramentas).<br>
                5. O que é possível medir em apenas 7 dias sem complicar demais.<br><br>

                ⚙️ <strong>Siga estas diretrizes:</strong><br>
                • Simplifique ao máximo a ideia de experimento; nada de projeto gigante.<br>
                • Estruture o experimento em 4 passos: hipótese, ação, medição, decisão.<br>
                • Traga exemplos de indicadores simples (contar ocorrências, medir tempo, comparar antes/depois).<br>
                • Evite qualquer solução que exija implantação longa ou grande investimento logo de início.<br><br>

                ❗ <strong>Quando faltarem dados:</strong><br>
                Peça que eu descreva o processo escolhido em até 5 linhas e diga o que mais me irrita nele.<br><br>

                📝 <strong>Apresente o resultado assim:</strong><br>
                1. Hipótese do experimento, em 2 ou 3 frases.<br>
                2. Ação que será testada durante 7 dias, com passos claros.<br>
                3. Métricas simples para comparar antes e depois.<br>
                4. Critério de decisão (o que define se vale a pena manter ou ajustar).<br>
                5. Próximo passo sugerido após o experimento.
              </div>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- /.section-standard -->
  </div> <!-- /.container -->
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
                    2. 🎯 Contexto (Cenário)<br>
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

<section class="section" style="padding-top: 0;">
  <div class="container">
    <div class="section-standard">
      <div class="standard-grid standard-grid-3">
        <!-- Card 1 - Guia Inteligente -->
        <div class="standard-card">
          <div class="standard-card-header">
            <div class="standard-badge primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
              </svg>
              Guia Inteligente
            </div>
          </div>
          <div class="card-content">
            <p>
              Use o Guia como mapa de bolso: entenda a estrutura dos prompts, siga os exemplos
              práticos e volte nele sempre que for criar ou ajustar novos comandos de IA.
              <strong>Arquivo disponível na sua área de membros.</strong>
            </p>
          </div>
        </div>

        <!-- Card 2 - Suporte -->
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
            <p>
              Ficou com dúvida sobre o Guia ou sobre como adaptar um prompt?
              Responda o e-mail de confirmação da compra.  
              <strong>Retorno em até 24 horas úteis.</strong>
            </p>
          </div>
        </div>

<!-- Card 3 - Termos e Privacidade -->
<div class="standard-card">
  <div class="standard-card-header">
    <div class="standard-badge primary">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14,2 14,8 20,8"/>
      </svg>
      Termos e Privacidade
    </div>
  </div>

  <div class="card-content">
    <p>
      © 2025 Fluxoteca · Licença pessoal, individual e intransferível para uso profissional.<br>
      Proibido compartilhar arquivos brutos, links protegidos, login ou redistribuir conteúdos premium.<br><br>

      <a href="https://fluxoteca.com.br/termos-de-uso/" target="_blank" rel="noopener noreferrer" class="standard-link">
        Termos de Uso →
      </a>
      <br>
      <a href="https://fluxoteca.com.br/politica-de-privacidade/" target="_blank" rel="noopener noreferrer" class="standard-link">
        Política de Privacidade →
      </a>
    </p>
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

function togglePrompt(button) {
  if (!button) return;

  const card = button.closest('.standard-card');
  if (!card) return;

  const contentId = button.getAttribute('aria-controls');
  const content = contentId 
    ? document.getElementById(contentId) 
    : card.querySelector('.standard-example-content');

  if (!content) return;

  const isExpanded = button.getAttribute('aria-expanded') === 'true';

  button.setAttribute('aria-expanded', String(!isExpanded));
  card.classList.toggle('is-open', !isExpanded);

  if (isExpanded) {
    content.setAttribute('hidden', 'hidden');
  } else {
    content.removeAttribute('hidden');
  }
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

function initializeApplication() {
        
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
            initPerformanceOptimizations();
            
            // 5. Estado Inicial
            updateActiveSectionEnhanced();
            updateReadingProgress();
            handleScroll();

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
    window.scrollToTop = scrollToTop;

}

</script>
</body>
</html>
