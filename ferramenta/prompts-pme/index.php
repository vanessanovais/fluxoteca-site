<?php
// Guarda de acesso por token para o Painel

$tokensFile = __DIR__ . '/../../secure/kpass_tokens.json';

// Página amigável quando o acesso é inválido ou expirou
function kpass_deny_access() {
    http_response_code(403);
    echo "
    <!DOCTYPE html>
    <html lang='pt-br'>
    <head>
      <meta charset='UTF-8'>
      <title>Acesso expirado</title>
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
        <h1>Link de acesso expirado</h1>
        <p>Por segurança, este link não é mais válido.</p>
        <p>Para abrir novamente o <strong>Painel de Prompts</strong>, volte à
        <strong>Área de Membros da Kiwify</strong> e clique no botão de acesso.</p>
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
$tokenId = preg_replace('/[^a-f0-9]/', '', $tokenId); // só caracteres hexadecimais

// Sem token já bloqueia
if ($tokenId === '') {
    kpass_deny_access();
}

// Arquivo de tokens não encontrado
if (!file_exists($tokensFile)) {
    kpass_deny_access();
}

// Lê tokens
$json   = file_get_contents($tokensFile);
$tokens = $json ? json_decode($json, true) : [];

if (!is_array($tokens) || !isset($tokens[$tokenId])) {
    kpass_deny_access();
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
    kpass_deny_access();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<!-- Meta Tags Otimizadas para Mobile Embed -->
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="format-detection" content="telephone=no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<!-- Segurança e Privacidade -->
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
<meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
<meta name="bingbot" content="noindex, nofollow">

<title>Prompts Inteligentes para PMEs | Painel de Aplicação</title>

<!-- Canonical / OG -->
<link rel="canonical" href="https://fluxoteca.com.br/ferramenta/prompts-pme/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Fluxoteca">
<meta property="og:title" content="Prompts Inteligentes para PMEs — Fluxoteca">
<meta property="og:description" content="Sistema de prompts inteligentes com contextos profissionais para PMEs.">
<meta property="og:url" content="https://fluxoteca.com.br/ferramenta/prompts-pme/">
<meta property="og:image" content="https://fluxoteca.com.br/images/og-prompts.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="https://fluxoteca.com.br/images/og-prompts.jpg">

<!-- Referrer moderno -->
<meta name="referrer" content="strict-origin-when-cross-origin">

<!-- Ícones (opcional, para PWA/atalhos) -->
<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon-dark.png" media="(prefers-color-scheme: dark)">

<!-- Fontes -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

<meta name="theme-color" content="#0a0a0f">
<style>
/* =============================
   1) VARIÁVEIS E TEMAS
   ============================= */
:root{
  /* Cores base, dark padrão */
  --bg-primary:#0a0a0f; --bg-secondary:#111118; --bg-tertiary:#1a1a24; --bg-quaternary:#252530;
  --surface:rgba(255,255,255,.03); --surface-hover:rgba(255,255,255,.06);
  --surface-elevated:rgba(255,255,255,.08); --surface-glass:rgba(255,255,255,.05);
  --text-primary:#fff; --text-secondary:#a1a1aa; --text-tertiary:#71717a; --text-muted:#52525b;
  --accent-primary:#0ea5e9; --accent-secondary:#0284c7; --accent-tertiary:#0369a1;
  --accent-gradient:linear-gradient(135deg,#0ea5e9 0%,#0284c7 50%,#0369a1 100%);
  --accent-gradient-alt:linear-gradient(135deg,#06b6d4 0%,#0ea5e9 50%,#0284c7 100%);
  --success:#10b981; --error:#ef4444; --warning:#f59e0b; --info:#3b82f6;

  /* Bordas, sombras, efeitos */
  --border-subtle:rgba(255,255,255,.08); --border-default:rgba(255,255,255,.12);
  --border-strong:rgba(255,255,255,.18); --border-accent:rgba(14,165,233,.3);
  --shadow-sm:0 1px 2px rgba(0,0,0,.05);
  --shadow-md:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06);
  --shadow-lg:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05);
  --shadow-xl:0 20px 25px -5px rgba(0,0,0,.1),0 10px 10px -5px rgba(0,0,0,.04);
  --shadow-2xl:0 25px 50px -12px rgba(0,0,0,.25);
  --shadow-glow:0 0 20px rgba(14,165,233,.15); --shadow-glow-strong:0 0 40px rgba(14,165,233,.25);

  /* Blur, espaçamento, raio, transições */
  --blur-sm:blur(4px); --blur-md:blur(8px); --blur-lg:blur(16px); --blur-xl:blur(24px); --blur-2xl:blur(40px);
  --spacing-xs:4px; --spacing-sm:8px; --spacing-md:16px; --spacing-lg:24px; --spacing-xl:32px; --spacing-2xl:48px; --spacing-3xl:64px;
  --radius-sm:6px; --radius-md:12px; --radius-lg:16px; --radius-xl:20px; --radius-2xl:24px;
  --transition-fast:.15s cubic-bezier(.4,0,.2,1);
  --transition-normal:.2s cubic-bezier(.4,0,.2,1);
  --transition-slow:.3s cubic-bezier(.4,0,.2,1);
  --transition-bounce:.4s cubic-bezier(.68,-.55,.265,1.55);
}

/* Tema via preferências do SO */
@media (prefers-color-scheme: light){
  :root{
    --bg-primary:#fff; --bg-secondary:#f8fafc; --bg-tertiary:#f1f5f9; --bg-quaternary:#e2e8f0;
    --surface:rgba(0,0,0,.02); --surface-hover:rgba(0,0,0,.04); --surface-elevated:rgba(0,0,0,.06);
    --surface-glass:rgba(255,255,255,.8);
    --text-primary:#0f172a; --text-secondary:#475569; --text-tertiary:#64748b; --text-muted:#94a3b8;
    --accent-primary:#0284c7; --accent-secondary:#0ea5e9; --accent-tertiary:#0369a1;
    --accent-gradient:linear-gradient(135deg,#0284c7 0%,#0ea5e9 50%,#0369a1 100%);
    --accent-gradient-alt:linear-gradient(135deg,#0891b2 0%,#0284c7 50%,#0ea5e9 100%);
    --border-subtle:rgba(0,0,0,.06); --border-default:rgba(0,0,0,.10); --border-strong:rgba(0,0,0,.15); --border-accent:rgba(2,132,199,.2);
    --shadow-glow:0 0 20px rgba(2,132,199,.1); --shadow-glow-strong:0 0 40px rgba(2,132,199,.2);
  }
}

/* Força tema manual por data-attribute */
:root[data-theme="light"]{
  --bg-primary:#fff; --bg-secondary:#f8fafc; --bg-tertiary:#f1f5f9; --bg-quaternary:#e2e8f0;
  --surface:rgba(0,0,0,.03); --surface-hover:rgba(0,0,0,.06); --surface-elevated:rgba(0,0,0,.08);
  --surface-glass:rgba(255,255,255,.9);
  --text-primary:#0f172a; --text-secondary:#334155; --text-tertiary:#475569; --text-muted:#64748b;
  --border-subtle:rgba(0,0,0,.08); --border-default:rgba(0,0,0,.15); --border-strong:rgba(0,0,0,.25); --border-accent:rgba(2,132,199,.3);
  --shadow-glow:0 0 20px rgba(2,132,199,.15); --shadow-glow-strong:0 0 40px rgba(2,132,199,.25);
}
:root[data-theme="dark"]{
  --bg-primary:#000; --bg-secondary:#0a0a0a; --bg-tertiary:#111; --bg-quaternary:#1a1a1a;
  --surface:rgba(255,255,255,.03); --surface-hover:rgba(255,255,255,.06); --surface-elevated:rgba(255,255,255,.08);
  --surface-glass:rgba(255,255,255,.05);
  --text-primary:#fff; --text-secondary:#a1a1aa; --text-tertiary:#71717a; --text-muted:#52525b;
  --border-subtle:rgba(255,255,255,.08); --border-default:rgba(255,255,255,.12); --border-strong:rgba(255,255,255,.18);
  --border-accent:rgba(14,165,233,.3); --shadow-glow:0 0 20px rgba(14,165,233,.15); --shadow-glow-strong:0 0 40px rgba(14,165,233,.25);
}

/* =============================
   2) BASE / RESET / ACESSO
   ============================= */
*{box-sizing:border-box;margin:0;padding:0}
html{
  scroll-behavior:smooth;
  font-size:16px;
  overflow-x:hidden;
  -webkit-text-size-adjust:100%;
  touch-action:manipulation;
}
body{
  font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
  background:var(--bg-primary); color:var(--text-primary); line-height:1.6;
  font-feature-settings:'cv02','cv03','cv04','cv11';
  -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
  overflow-x:hidden; transition:background-color .3s ease,color .3s ease;
}
h1,h2,h3,h4,h5,h6{font-weight:700;letter-spacing:-.025em;line-height:1.2}
p{color:var(--text-secondary)}
a{color:var(--accent-primary);text-decoration:none;transition:all var(--transition-normal);font-weight:500;position:relative}
a:hover{color:var(--accent-secondary);transform:translateY(-1px)}
a:focus-visible,button:focus-visible{outline:2px solid var(--accent-primary);outline-offset:2px;border-radius:var(--radius-sm)}
@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important;scroll-behavior:auto !important}
}

/* =============================
   3) ANIMAÇÕES (centralizadas)
   ============================= */
@keyframes float{0%{transform:translateY(0) translateX(0)}33%{transform:translateY(-10px) translateX(5px)}66%{transform:translateY(5px) translateX(-5px)}100%{transform:translateY(0) translateX(0)}}
@keyframes shimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
@keyframes pulse{0%,100%{transform:translate(-50%,-50%) scale(1);opacity:.5}50%{transform:translate(-50%,-50%) scale(1.1);opacity:.8}}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.7;transform:scale(1.2)}}
@keyframes fadeInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideInFromLeft{from{opacity:0;transform:translateX(-50px)}to{opacity:1;transform:translateX(0)}}
@keyframes slideInFromRight{from{opacity:0;transform:translateX(50px)}to{opacity:1;transform:translateX(0)}}
@keyframes scaleIn{from{opacity:0;transform:scale(.9)}to{opacity:1;transform:scale(1)}}
@keyframes bounce{0%,20%,50%,80%,100%{transform:translateY(0)}40%{transform:translateY(-10px)}60%{transform:translateY(-5px)}}
@keyframes expandWidth{from{width:0}to{width:100px}}
@keyframes toastSlideUp{from{transform:translateX(-50%) translateY(100%) scale(.8);opacity:0}to{transform:translateX(-50%) translateY(0) scale(1);opacity:1}}
@keyframes slideInModal{from{opacity:0;transform:translate(-50%,-48%) scale(.96)}to{opacity:1;transform:translate(-50%,-50%) scale(1)}}

/* =============================
   4) LAYOUT
   ============================= */
.container{max-width:1280px;margin:0 auto;padding:0 var(--spacing-lg)}

/* Header */
header{
  position:sticky; top:0; z-index:50; padding:var(--spacing-lg);
  background:var(--surface-glass); backdrop-filter:var(--blur-xl);
  border-bottom:1px solid var(--border-subtle); transition:all var(--transition-normal); will-change:transform;
}
header.scrolled{background:var(--bg-primary); box-shadow:var(--shadow-lg); border-bottom-color:var(--border-default)}
.header-content{max-width:1280px;margin:0 auto;display:flex;align-items:center;justify-content:center}
.brand{display:flex;align-items:center;gap:var(--spacing-md);font-weight:800;font-size:20px;letter-spacing:-.025em;color:var(--text-primary);transition:all var(--transition-normal)}
.brand:hover{transform:scale(1.02)}

/* Logo Fluxoteca no header do Painel */
.hero-logo-header {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0;
  padding: 4px 0;
}

.hero-logo {
  height: 80px;
  width: auto;
  max-width: 360px;
  display: block;
}

/* Foco acessível no link da marca */
.hero-logo-header:focus-visible {
  outline: 2px solid var(--accent-secondary);
  outline-offset: 2px;
  border-radius: 6px;
}

/* Sidebar */
.sidebar-nav{
  position:fixed; right:var(--spacing-lg); top:50%; transform:translateY(-50%); z-index:60;
  display:flex; flex-direction:column; gap:var(--spacing-sm);
  background:var(--surface-glass); backdrop-filter:var(--blur-xl);
  border:1px solid var(--border-subtle); border-radius:var(--radius-xl); padding:var(--spacing-md);
  box-shadow:var(--shadow-lg),var(--shadow-glow); transition:all var(--transition-normal)
}
.sidebar-nav:hover{background:var(--surface-hover);border-color:var(--border-default);box-shadow:var(--shadow-xl),var(--shadow-glow-strong)}
.nav-item,.theme-toggle{
  display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:var(--radius-md);
  color:var(--text-tertiary);cursor:pointer;transition:all var(--transition-normal);position:relative;overflow:hidden;background:transparent;border:1px solid var(--border-subtle)
}
.nav-item{border:none}
.nav-item::before,.theme-toggle::before{content:'';position:absolute;inset:0;background:var(--accent-gradient);opacity:0;transition:opacity var(--transition-normal);border-radius:var(--radius-md)}
.nav-item:hover::before,.theme-toggle:hover::before{opacity:.1}
.nav-item:hover,.theme-toggle:hover{color:var(--accent-primary);transform:scale(1.1);box-shadow:var(--shadow-md);border-color:var(--border-default)}
.nav-item:active,.theme-toggle:active{transform:scale(.95)}
.nav-item svg,.theme-toggle svg{position:relative;z-index:1;transition:all var(--transition-normal)}
.nav-item:hover svg{transform:scale(1.1)}
.nav-item::after,.theme-toggle::after{
  content:attr(data-tooltip);position:absolute;right:calc(100% + var(--spacing-md));top:50%;
  transform:translateY(-50%) translateX(8px);background:var(--surface-glass);backdrop-filter:var(--blur-lg);color:var(--text-primary);
  padding:var(--spacing-sm) var(--spacing-md);border-radius:var(--radius-md);font-size:13px;font-weight:500;white-space:nowrap;opacity:0;pointer-events:none;
  transition:all var(--transition-normal);border:1px solid var(--border-subtle);box-shadow:var(--shadow-lg),var(--shadow-glow);z-index:1000;letter-spacing:-.01em
}
.nav-item:hover::after,.theme-toggle:hover::after{opacity:1;transform:translateY(-50%) translateX(-4px)}
.theme-toggle svg{position:absolute;transition:all var(--transition-normal);z-index:1}
.theme-toggle .sun-icon{opacity:1;transform:rotate(0) scale(1)}
.theme-toggle .moon-icon{opacity:0;transform:rotate(180deg) scale(.8)}
[data-theme="dark"] .theme-toggle .sun-icon{opacity:0;transform:rotate(-180deg) scale(.8)}
[data-theme="dark"] .theme-toggle .moon-icon{opacity:1;transform:rotate(0) scale(1)}
.nav-divider{height:1px;background:var(--border-subtle);margin:var(--spacing-xs) 0}

/* Seções */
.hero{padding:var(--spacing-3xl) 0;text-align:center;position:relative;overflow:hidden}
.hero::before{
  content:'';position:absolute;top:50%;left:50%;width:800px;height:800px;
  background:radial-gradient(circle,rgba(14,165,233,.1) 0%,transparent 70%);
  transform:translate(-50%,-50%);animation:pulse 4s ease-in-out infinite;pointer-events:none
}
.hero-content{position:relative;z-index:1}
.hero-badge{
  display:inline-flex;align-items:center;gap:var(--spacing-sm);padding:var(--spacing-sm) var(--spacing-lg);
  background:var(--surface-glass);border:1px solid var(--border-accent);border-radius:999px;font-size:13px;font-weight:600;color:var(--accent-primary);
  margin-bottom:var(--spacing-lg);backdrop-filter:var(--blur-md);box-shadow:var(--shadow-md),var(--shadow-glow);transition:all var(--transition-normal);animation:fadeInUp .6s ease-out
}
.hero-badge:hover{transform:translateY(-2px) scale(1.05);box-shadow:var(--shadow-lg),var(--shadow-glow-strong)}
.hero-badge::before{content:'';width:8px;height:8px;border-radius:50%;background:var(--accent-primary);animation:pulse-dot 2s infinite;box-shadow:0 0 10px var(--accent-primary)}
.hero h1{
  font-size:clamp(36px,5vw,72px);font-weight:900;margin-bottom:var(--spacing-lg);
  background:linear-gradient(135deg,var(--text-primary) 0%,var(--accent-primary) 50%,var(--accent-tertiary) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;animation:fadeInUp .8s ease-out .2s both;position:relative
}
.hero h1::after{
  content:'';position:absolute;bottom:-10px;left:50%;width:100px;height:4px;background:var(--accent-gradient);
  transform:translateX(-50%);border-radius:2px;animation:expandWidth 1s ease-out 1s both
}
.hero-description{
  font-size:clamp(18px,2.5vw,24px);color:var(--text-secondary);max-width:800px;margin:0 auto var(--spacing-2xl);
  line-height:1.2;animation:fadeInUp 1s ease-out .4s both
}
.hero-actions{display:flex;gap:var(--spacing-md);justify-content:center;flex-wrap:wrap;animation:fadeInUp 1.2s ease-out .6s both;margin-bottom:48px}

.chapters{padding:var(--spacing-3xl) 0;position:relative}
.section-header{text-align:center;margin-bottom:var(--spacing-3xl)}
.section-title{font-size:clamp(28px,4vw,48px);font-weight:900;margin-bottom:var(--spacing-md);color:var(--text-primary);position:relative}
.section-title::after{content:'';position:absolute;bottom:-8px;left:50%;width:80px;height:3px;background:var(--accent-gradient);transform:translateX(-50%);border-radius:2px}
.section-description{font-size:18px;color:var(--text-secondary);max-width:600px;margin:0 auto}
.chapters-grid{
  display:grid;
  gap:var(--spacing-xl);
}
.chapter-card{
  background:var(--surface-glass);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);
  padding:var(--spacing-xl);backdrop-filter:var(--blur-md);transition:all var(--transition-slow);
  position:relative;overflow:hidden;cursor:pointer;opacity:1 !important
}
.chapter-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:var(--accent-gradient);transform:scaleX(0);transition:transform var(--transition-slow);transform-origin:left}
.chapter-card::after{content:'';position:absolute;inset:0;background:var(--accent-gradient);opacity:0;transition:opacity var(--transition-slow)}
.chapter-card:hover::before{transform:scaleX(1)}
.chapter-card:hover::after{opacity:.03}
.chapter-card:hover{transform:translateY(-10px) scale(1.02);background:var(--surface-hover);border-color:var(--border-accent);box-shadow:var(--shadow-2xl),var(--shadow-glow-strong)}
.chapter-card.active{border-color:var(--accent-primary);transform:translateY(-5px);box-shadow:var(--shadow-xl),var(--shadow-glow)}
.chapter-card.active::before{transform:scaleX(1)}
.chapter-number{
  display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background:var(--accent-gradient);color:#fff;border-radius:var(--radius-md);
  font-weight:800;font-size:18px;margin-bottom:var(--spacing-md);box-shadow:var(--shadow-md),var(--shadow-glow);transition:all var(--transition-normal);position:relative;z-index:1
}
.chapter-card:hover .chapter-number{transform:scale(1.1) rotate(-5deg);box-shadow:var(--shadow-lg),var(--shadow-glow-strong)}
.chapter-title{font-size:24px;font-weight:800;color:var(--text-primary);margin-bottom:var(--spacing-md);position:relative;z-index:1}
.chapter-description{font-size:15px;color:var(--text-secondary);line-height:1.6;margin-bottom:var(--spacing-xl);position:relative;z-index:1}
.chapter-actions{display:flex;gap:var(--spacing-md);flex-wrap:wrap;position:relative;z-index:1}

/* Indicador de clique nos cards */
.card-click-indicator{
  position:absolute;bottom:var(--spacing-md);right:var(--spacing-md);display:flex;align-items:center;gap:var(--spacing-xs);
  padding:var(--spacing-xs) var(--spacing-sm);background:var(--surface-glass);border:1px solid var(--border-subtle);border-radius:var(--radius-md);
  color:var(--text-tertiary);font-size:11px;font-weight:600;opacity:.6;transform:translateY(0);transition:all var(--transition-normal);
  backdrop-filter:var(--blur-md);pointer-events:none;z-index:2
}
.chapter-card:hover .card-click-indicator{opacity:1;background:var(--accent-primary);color:#fff;border-color:var(--accent-primary);transform:scale(1.05);box-shadow:var(--shadow-sm)}
.chapter-card:hover .card-click-indicator svg{transform:translateX(3px)}

/* Footer em cards */
.footer{
  padding:var(--spacing-3xl) 0;
  background:var(--bg-secondary);
  border-top:1px solid var(--border-subtle);
  position:relative;
  color:var(--text-tertiary);
  font-size:14px;
}

.footer::before{
  content:'';
  position:absolute;
  top:0;
  left:50%;
  width:120px;
  height:2px;
  background:var(--accent-gradient);
  transform:translateX(-50%);
}

.footer-cards{
  display:grid;
  grid-template-columns:repeat(3,minmax(0,1fr));
  gap:var(--spacing-xl);
  align-items:stretch;
}

.footer-card{
  background:var(--surface-glass);
  border:1px solid var(--border-subtle);
  border-radius:var(--radius-xl);
  padding:var(--spacing-xl);
  box-shadow:var(--shadow-md);
  display:flex;
  flex-direction:column;
  gap:var(--spacing-md);
  text-align:left;
  position:relative;
  overflow:hidden;
  transition:all var(--transition-normal);
}

.footer-card::after{
  content:'';
  position:absolute;
  inset:0;
  background:var(--accent-gradient);
  opacity:0;
  pointer-events:none;
  transition:opacity var(--transition-normal);
}

.footer-card:hover{
  transform:translateY(-4px);
  box-shadow:var(--shadow-xl),var(--shadow-glow);
  border-color:var(--border-accent);
}

.footer-card:hover::after{
  opacity:.03;
}

.footer-card-header{
  display:flex;
  align-items:center;
  gap:var(--spacing-md);
}

.footer-card-icon{
  width:36px;
  height:36px;
  border-radius:999px;
  background:var(--accent-gradient);
  display:flex;
  align-items:center;
  justify-content:center;
  color:#fff;
  box-shadow:var(--shadow-lg),var(--shadow-glow);
  flex-shrink:0;
}

.footer-card-title{
  font-size:16px;
  font-weight:700;
  color:var(--text-primary);
}

.footer-card-title a{
  color:inherit;
  text-decoration:none;
}

.footer-card-title a:hover{
  text-decoration:underline;
  color:var(--accent-primary);
}

.footer-card-text{
  font-size:14px;
  color:var(--text-secondary);
  line-height:1.6;
}

/* =============================
   5) COMPONENTES
   ============================= */
/* Botões */
.btn{
  display:inline-flex;align-items:center;gap:var(--spacing-sm);padding:var(--spacing-md) var(--spacing-xl);
  border:none;border-radius:var(--radius-md);font-weight:600;font-size:16px;cursor:pointer;transition:all var(--transition-normal);
  position:relative;overflow:hidden;text-decoration:none;white-space:nowrap;min-height:44px
}
.btn::before{content:'';position:absolute;inset:0;background:linear-gradient(45deg,transparent,rgba(255,255,255,.1),transparent);transform:translateX(-100%);transition:transform .6s}
.btn:hover::before{transform:translateX(100%)}
.btn-primary{background:var(--accent-gradient);color:#fff;box-shadow:var(--shadow-lg),var(--shadow-glow);border:1px solid transparent}
.btn-primary:hover{transform:translateY(-3px) scale(1.02);box-shadow:var(--shadow-xl),var(--shadow-glow-strong)}
.btn-primary:active{transform:translateY(-1px) scale(.98)}
.btn-secondary{background:var(--surface-glass);color:var(--text-primary);border:1px solid var(--border-default);backdrop-filter:var(--blur-md)}
.btn-secondary:hover{background:var(--surface-hover);border-color:var(--border-strong);transform:translateY(-2px);box-shadow:var(--shadow-md)}
.btn-ghost{background:transparent;color:var(--text-secondary);border:1px solid var(--border-subtle);font-size:14px;padding:var(--spacing-sm) var(--spacing-md);transition:all var(--transition-normal);position:relative;overflow:hidden}
.btn-ghost::before{content:'';position:absolute;inset:0;background:var(--accent-gradient);opacity:0;transition:opacity var(--transition-normal);border-radius:inherit}
.btn-ghost:hover::before{opacity:.1}
.btn-ghost:hover{background:var(--surface-hover);color:var(--text-primary);border-color:var(--border-default);transform:translateY(-1px)}
.btn svg{transition:transform var(--transition-normal)}
.btn:hover svg{transform:scale(1.1)}
.btn-link{color:var(--accent-primary);text-decoration:none;font-weight:600;transition:all var(--transition-normal)}
.btn-link:hover{color:var(--accent-secondary);text-decoration:underline}

/* Modal */
.modal-backdrop{
  position:fixed;inset:0;background:rgba(0,0,0,.8);backdrop-filter:var(--blur-xl);
  z-index:1000;display:none;align-items:center;justify-content:center;padding:clamp(12px,4vw,24px)
}
.modal-backdrop.active{display:flex}
.modal{
  position:fixed;top:50%;left:50%;transform:translate(-50%,-50%) scale(.98);width:min(960px,95vw);max-height:92svh;background:var(--bg-primary);
  border:1px solid var(--border-default);border-radius:var(--radius-xl);box-shadow:var(--shadow-2xl),var(--shadow-glow);
  opacity:0;pointer-events:none;transition:all .3s ease;z-index:1001;overflow:hidden;display:flex;flex-direction:column
}
.modal.active{opacity:1;pointer-events:auto;transform:translate(-50%,-50%) scale(1);animation:slideInModal .3s cubic-bezier(.34,1.56,.64,1)}
.modal-header{
  flex:0 0 auto;padding:var(--spacing-xl);border-bottom:1px solid var(--border-subtle);
  background:var(--bg-secondary);position:sticky;z-index:1;top:0
}
.modal-header::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--accent-gradient)}
.modal-header::after{content:'';position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent 0%,var(--accent-primary) 50%,transparent 100%);opacity:.3}
.modal-header-content{display:flex;align-items:center;justify-content:space-between;position:relative;min-height:60px}
.modal-title-section{flex:1;padding-right:80px}
.modal-title{font-size:22px;font-weight:800;color:var(--text-primary);margin:0;display:flex;align-items:center;gap:var(--spacing-sm)}
.modal-title::before{content:'⚡';font-size:24px}
.modal-subtitle{color:var(--text-secondary);margin:4px 0 0 32px;font-size:14px;line-height:1.4}
.modal-progress{
  position:absolute;top:50%;right:var(--spacing-xl);transform:translateY(-50%);background:var(--surface-glass);color:var(--accent-primary);
  padding:6px 10px;border-radius:var(--radius-md);font-size:12px;font-weight:700;border:1px solid var(--border-accent);
  backdrop-filter:var(--blur-md);box-shadow:var(--shadow-sm);min-width:50px;text-align:center;z-index:2
}
.modal-content{
  flex:1 1 auto;min-height:0;overflow:auto;-webkit-overflow-scrolling:touch;
  max-height:calc(min(80svh,640px) - 120px);margin:var(--spacing-md);padding:var(--spacing-md);
  background:var(--bg-tertiary);border:1px solid var(--border-subtle);border-radius:var(--radius-md);
  font-family:'JetBrains Mono','Fira Code',Consolas,monospace;font-size:14px;line-height:1.6;color:var(--text-primary);
  position:relative;z-index:1;display:flex;flex-direction:column;gap:var(--spacing-xl)
}
.modal-content::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent-gradient)}
.modal-content::-webkit-scrollbar{width:8px}
.modal-content::-webkit-scrollbar-track{background:transparent}
.modal-content::-webkit-scrollbar-thumb{background:var(--accent-primary);border-radius:4px}
.modal-actions{
  display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;padding:var(--spacing-md) var(--spacing-xl);
  background:var(--surface-glass);border-top:1px solid var(--border-accent);border-radius:0 0 var(--radius-lg) var(--radius-lg);
  gap:16px;width:100%;box-sizing:border-box
}
.modal-nav{display:flex;gap:var(--spacing-md)}
.modal-meta{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;align-items:center}
.modal-meta button.btn{white-space:nowrap;padding:8px 12px;height:auto;display:inline-flex;align-items:center;gap:4px}
.modal-meta button.btn svg{flex-shrink:0}
.modal-nav .btn-ghost{position:relative;border:1px solid var(--border-subtle);font-weight:600}
.modal-nav .btn-ghost:disabled{opacity:.5;cursor:not-allowed;transform:none !important}
.modal-nav .btn-ghost:not(:disabled):hover{border-color:var(--accent-primary);color:var(--accent-primary)}
.modal-meta .btn{position:relative}
.modal-meta .btn::after{
  content:attr(aria-label);position:absolute;bottom:-40px;left:50%;transform:translateX(-50%);
  background:var(--surface-glass);backdrop-filter:var(--blur-lg);color:var(--text-primary);padding:6px 10px;border-radius:var(--radius-sm);
  font-size:12px;font-weight:500;white-space:nowrap;opacity:0;pointer-events:none;transition:all var(--transition-normal);border:1px solid var(--border-subtle);z-index:1000
}
.modal-meta .btn:hover::after{opacity:1;bottom:-35px}

/* Conteúdo do Modal (tips, prompt) */
.modal-content .tip{
  background:var(--surface-glass);border:1px solid var(--border-accent);border-radius:var(--radius-md);
  padding:var(--spacing-lg);margin-bottom:var(--spacing-md);transition:all var(--transition-normal);cursor:pointer
}
.modal-content .tip:hover{border-color:var(--accent-primary);transform:translateY(-1px);box-shadow:var(--shadow-sm)}
.modal-content .tip.open{background:var(--surface-hover);border-color:var(--accent-primary)}
.modal-content .tip-title{
  color:var(--accent-primary);font-weight:700;margin:0;padding:0;display:flex;align-items:center;justify-content:space-between;cursor:pointer;font-size:15px;user-select:none
}
.modal-content .tip-title .chev{transition:transform .3s cubic-bezier(.4,0,.2,1);font-size:14px;opacity:.8;display:inline-block}
.modal-content .tip-content{color:var(--text-secondary);font-size:14px;line-height:1.6;display:none;padding-top:12px;animation:fadeInUp .3s ease;transition:all .3s ease}
.modal-content .tip.open .tip-content{display:block}
.modal-content .tip.open .tip-title .chev{transform:rotate(180deg)}

.prompt-section{
  background:var(--bg-tertiary);border:1px solid var(--border-default);border-radius:var(--radius-lg);padding:var(--spacing-xl);
  transition:all var(--transition-normal);position:relative
}
.prompt-section::after{content:'';position:absolute;inset:0;border-radius:inherit;background:var(--accent-gradient);opacity:0;transition:opacity var(--transition-normal);pointer-events:none}
.prompt-section:hover::after{opacity:.03}
.prompt-section:hover{border-color:var(--border-accent);box-shadow:var(--shadow-md)}
.prompt-section::before{
  content:'⚡ Prompt Completo';position:absolute;top:-10px;left:var(--spacing-lg);background:var(--accent-primary);color:#fff;
  padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;box-shadow:var(--shadow-sm)
}
.prompt-content{font-family:'JetBrains Mono',monospace;font-size:14px;line-height:1.6;color:var(--text-primary);white-space:pre-wrap;margin:0;padding-top:var(--spacing-sm)}

/* Toast */
.toast{
  position:fixed;bottom:var(--spacing-lg);left:50%;transform:translateX(-50%);
  background:var(--bg-primary);border:1px solid var(--border-default);color:var(--text-primary);
  padding:var(--spacing-md) var(--spacing-lg);border-radius:var(--radius-md);box-shadow:var(--shadow-xl),var(--shadow-glow);
  backdrop-filter:var(--blur-lg);font-weight:600;font-size:14px;display:none;z-index:1002;min-width:200px;text-align:center
}
.toast.show{display:block;animation:toastSlideUp .4s cubic-bezier(.68,-.55,.265,1.55)}
.toast.success{border-color:var(--success);background:rgba(16,185,129,.1);color:var(--success)}
.toast.error{border-color:var(--error);background:rgba(239,68,68,.1);color:var(--error)}

/* Busca */
.search-container{max-width:1280px;margin:0 auto var(--spacing-2xl);padding:0 var(--spacing-lg);position:relative}
.search-box{position:relative;max-width:500px;margin:0 auto}
.search-box svg{position:absolute;left:16px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:var(--text-tertiary);z-index:2;pointer-events:none}
.search-box input{
  width:100%;padding:var(--spacing-md) var(--spacing-md) var(--spacing-md) 48px;background:var(--surface-glass);
  border:2px solid var(--border-subtle);border-radius:var(--radius-lg);color:var(--text-primary);font-size:16px;transition:all var(--transition-normal)
}
.search-box input:focus{outline:none;border-color:var(--accent-primary);box-shadow:0 0 0 3px rgba(14,165,233,.1)}
.search-counter{
  position:absolute;top:12px;right:12px;background:var(--accent-primary);color:#fff;padding:4px 8px;border-radius:12px;font-size:11px;font-weight:700;z-index:3
}
.search-suggestions{
  position:absolute;top:100%;left:0;right:0;background:var(--bg-primary);border:1px solid var(--border-default);border-radius:var(--radius-md);
  margin-top:4px;box-shadow:var(--shadow-xl);z-index:1000;display:none
}
.search-suggestion{
  padding:var(--spacing-md);cursor:pointer;border-bottom:1px solid var(--border-subtle);transition:background-color var(--transition-fast);min-height:44px;display:flex;align-items:center
}
.search-suggestion:hover{background:var(--surface-hover)}
.chapter-card.search-highlight{
  border:2px solid var(--accent-primary) !important;background:var(--surface-hover) !important;
  transform:translateY(-2px);box-shadow:var(--shadow-lg),var(--shadow-glow);transition:all var(--transition-normal)
}
.chapter-badge{
  position:absolute;
  top:var(--spacing-lg);
  right:var(--spacing-lg);
  padding:4px 10px;
  background:#10B981;
  color:#fff;
  border-radius:999px;
  font-size:11px;
  font-weight:700;
  letter-spacing:.04em;
  text-transform:uppercase;
  box-shadow:var(--shadow-md);
}

.chapter-card.search-no-match{opacity:.4;transition:opacity .3s ease}
.chapter-card.search-match{opacity:1;transform:translateY(-2px);box-shadow:var(--shadow-lg),var(--shadow-glow);transition:all .3s ease}
.show-all-results-btn{
  display:flex;align-items:center;justify-content:space-between;width:100%;max-width:500px;margin:var(--spacing-md) auto 0;
  padding:var(--spacing-md) var(--spacing-lg);background:var(--surface-glass);border:2px solid var(--accent-primary);border-radius:var(--radius-lg);
  color:var(--accent-primary);font-weight:600;font-size:14px;cursor:pointer;transition:all var(--transition-normal);backdrop-filter:var(--blur-md)
}
.show-all-results-btn:hover{background:var(--accent-primary);color:#fff;transform:translateY(-2px);box-shadow:var(--shadow-lg),var(--shadow-glow)}
.show-all-results-btn:active{transform:translateY(0)}
.search-results-header{text-align:center;margin-bottom:var(--spacing-xl);padding-bottom:var(--spacing-lg);border-bottom:1px solid var(--border-subtle)}
.search-results-header h3{color:var(--text-primary);margin-bottom:var(--spacing-sm)}
.search-results-header p{color:var(--text-secondary);font-size:14px}
.search-results-list{
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
  flex: 1 1 auto;
  max-height: none;  
  min-height: 260px;  
  overflow-y: auto;
  padding-right: var(--spacing-sm);
}
.search-result-item{
  background:var(--surface-glass);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);
  padding:var(--spacing-lg);cursor:pointer;transition:all var(--transition-normal);position:relative
}
.search-result-item:hover{border-color:var(--accent-primary);transform:translateY(-2px);box-shadow:var(--shadow-md);background:var(--surface-hover)}
.result-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--spacing-sm)}
.result-badge{background:var(--accent-gradient);color:#fff;padding:4px 8px;border-radius:var(--radius-sm);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em}
.result-number{background:var(--surface);color:var(--text-tertiary);padding:2px 6px;border-radius:var(--radius-sm);font-size:11px;font-weight:700}
.result-title{color:var(--text-primary);font-size:16px;font-weight:700;margin-bottom:var(--spacing-xs);line-height:1.3}
.result-role{color:var(--accent-primary);font-size:13px;font-weight:600;margin-bottom:var(--spacing-xs)}
.result-objective{color:var(--text-secondary);font-size:13px;line-height:1.4}
.search-results-actions{
  text-align:center;
  margin-top: var(--spacing-lg);  
  padding-top: var(--spacing-md); 
  border-top:1px solid var(--border-subtle);
}

/* =============================
   6) RESPONSIVIDADE
   ============================= */
@media (max-width:1024px){
  .container{padding:0 var(--spacing-md)}
  .hero{padding:var(--spacing-2xl) 0}
  .hero h1{font-size:clamp(32px,6vw,56px)}
  .chapters-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:var(--spacing-lg)}
  .chapter-card{padding:var(--spacing-lg)}
  .sidebar-nav{right:var(--spacing-md);padding:var(--spacing-sm)}
  .modal{width:min(90vw,800px)}
}

@media (max-width:768px){
  html{font-size:14px}
  .container{padding:0 var(--spacing-md)}
  header{padding:var(--spacing-md)}
  .brand{font-size:18px}

  .hero{padding:var(--spacing-2xl) 0 var(--spacing-xl)}
  .hero h1{font-size:clamp(28px,8vw,42px);margin-bottom:var(--spacing-md)}
  .hero-description{font-size:clamp(16px,4vw,20px);margin-bottom:var(--spacing-xl)}
  .hero-actions{flex-direction:column;align-items:center;gap:var(--spacing-sm);margin-bottom:var(--spacing-xl)}
  .hero-actions .btn{width:100%;max-width:280px;justify-content:center}

  .hero-logo {
    height: 60px;
    width: auto;
    max-width: 280px;
  }

  .hero-logo-header {
    justify-content: center;
  }

  .hero-logo-container {
    margin-bottom: 30px;
  }

  .chapters{padding:var(--spacing-2xl) 0}
  .chapters-grid{grid-template-columns:repeat(1,minmax(0,1fr));gap:var(--spacing-md)}
  .chapter-card{padding:var(--spacing-lg);margin:0 var(--spacing-xs)}
  .chapter-title{font-size:20px}
  .chapter-description{font-size:14px}
  .card-click-indicator{opacity:.9;background:var(--surface-elevated)}

  .search-container{padding:0 var(--spacing-md);margin-bottom:var(--spacing-xl)}
  .search-box{max-width:100%}
  .search-box input{font-size:16px;padding:var(--spacing-md) var(--spacing-md) var(--spacing-md) 44px}

  /* Sidebar vai para o topo em layout compacto */
  .sidebar-nav{
    position:fixed; top:var(--spacing-md); right:var(--spacing-md); transform:none; z-index:60;
    display:flex; flex-direction:row; gap:0; width:auto; padding:0; background:transparent; backdrop-filter:none;
    border:none; border-radius:0; box-shadow:none
  }
  .sidebar-nav .nav-item:first-child,.sidebar-nav .nav-divider{display:none}
  .theme-toggle{width:44px;height:44px;background:var(--surface-glass);backdrop-filter:var(--blur-xl);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);box-shadow:var(--shadow-md),var(--shadow-glow)}
  .theme-toggle svg{width:18px;height:18px}
  .nav-item::after,.theme-toggle::after{display:none}

  /* Modal full mobile */
  .modal{width:100vw;height:100svh;max-height:100svh;border-radius:0;margin:0;-webkit-overflow-scrolling:touch;overflow-y:auto}
  .modal-header{padding:var(--spacing-lg)}
  .modal-title{font-size:18px}
  .modal-progress{right:var(--spacing-lg);padding:4px 8px;font-size:11px}
  .modal-content{
    margin:var(--spacing-sm);padding:var(--spacing-md);font-size:13px;gap:var(--spacing-md);
    max-height:calc(100svh - 180px);-webkit-overflow-scrolling:touch !important;overflow-y:auto !important
  }
  .modal-actions{
    flex-direction:column;align-items:stretch;gap:var(--spacing-sm);padding:var(--spacing-md)
  }
  .modal-actions .modal-nav,.modal-actions .modal-meta{width:100%;justify-content:center}
  .modal-nav{gap:var(--spacing-sm)}
  .modal-meta{gap:var(--spacing-sm)}
  .modal-meta button.btn{flex:1;justify-content:center}

  .prompt-section{padding:var(--spacing-md)}
  .prompt-section::before{left:var(--spacing-md);font-size:10px;padding:3px 10px}
  .prompt-content{font-size:13px;line-height:1.5}
  .modal-content .tip{padding:var(--spacing-md)}
  .modal-content .tip-title{font-size:14px}
  .footer{padding:var(--spacing-xl) 0;font-size:12px}

  /* Mobile geral */
  body{overflow-y:auto !important;-webkit-overflow-scrolling:touch !important;min-height:100dvh !important;height:auto !important}
}

@media (max-width:480px){
  .container{padding:0 var(--spacing-sm)}
  .hero{padding:var(--spacing-xl) 0}
  .hero h1{font-size:clamp(24px,10vw,32px)}
  .hero-badge{font-size:12px;padding:var(--spacing-xs) var(--spacing-md)}
  .hero-logo {
    height: 50px;
    width: auto;
    max-width: 240px;
  }

  .hero-logo-container {
    margin-bottom: 25px;
  }
  .chapters-grid{gap:var(--spacing-sm)}
  .chapter-card{padding:var(--spacing-md);margin:0}
  .chapter-number{width:40px;height:40px;font-size:16px}
  .chapter-title{font-size:18px}
  .chapter-actions{flex-direction:column;gap:var(--spacing-sm)}
  .chapter-actions .btn{width:100%;justify-content:center}
  .modal-header{padding:var(--spacing-md)}
  .modal-title{font-size:16px}
  .modal-subtitle{font-size:12px;margin-left:28px}
  .modal-content{margin:var(--spacing-xs);padding:var(--spacing-sm);gap:var(--spacing-sm)}
  .prompt-section{padding:var(--spacing-sm)}
  .prompt-content{font-size:12px}
  .btn{padding:var(--spacing-sm) var(--spacing-md);font-size:14px}
  .modal-meta button.btn{padding:8px 12px;font-size:13px}
}

@media (max-height:600px){
  .modal{max-height:98svh}
  .modal-content{max-height:calc(98svh - 140px)}
  .hero{padding:var(--spacing-xl) 0;min-height:auto}
}

@media (min-width:1440px){
  .container{max-width:1400px}
  .chapters-grid{grid-template-columns:repeat(auto-fit,minmax(380px,1fr))}
}

@media (max-width:768px) and (orientation:landscape){
  .modal{max-height:90svh}
  .modal-content{max-height:calc(90svh - 140px)}
  .hero{padding:var(--spacing-lg) 0}
  .chapters-grid{grid-template-columns:repeat(2,1fr);gap:var(--spacing-sm)}
}

/* Hover em telas de toque */
@media (hover:none) and (pointer:coarse){
  .chapter-card:hover,.btn:hover,.sidebar-nav:hover{transform:none}
}

.chapter-card .chapter-badge-soon{
  background:#F59E0B !important; /* amber */
  color:#fff !important;
}

/* Responsividade footer */
@media (max-width:1024px){
  .footer-cards{
    grid-template-columns:repeat(2,minmax(0,1fr));
  }
}

@media (max-width:768px){
  .footer{
    padding:var(--spacing-2xl) 0;
    font-size:12px;
  }
  .footer-cards{
    grid-template-columns:1fr;
    gap:var(--spacing-lg);
  }
  .footer-card{
    padding:var(--spacing-lg);
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

<header id="header">
  <div class="header-content">
    <a href="#secoes" class="brand hero-logo-header" aria-label="Fluxoteca - Ir para seção principal">
      <svg class="hero-logo" viewBox="0 0 360 100" xmlns="http://www.w3.org/2000/svg">
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
          <text x="0" y="35"
                font-family="Inter, -apple-system, BlinkMacSystemFont, sans-serif"
                font-size="40" font-weight="700"
                fill="url(#heroTextGradient)"
                letter-spacing="-0.25px" text-anchor="start">
            Fluxoteca
          </text>
          <text x="0" y="60"
                font-family="Inter, -apple-system, BlinkMacSystemFont, sans-serif"
                font-size="18" font-weight="500"
                fill="#667eea"
                letter-spacing="0.4px" text-anchor="start"
                opacity="0.8">
            Ferramentas para PMEs
          </text>
        </g>
      </svg>
    </a>
  </div>
</header>

<!-- Modern Sidebar Navigation -->
<nav class="sidebar-nav" aria-label="Navegação rápida">      
  <button id="theme-toggle" class="theme-toggle" onclick="toggleTheme()" aria-label="Alternar tema" data-tooltip="Alternar tema" type="button">
    <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
      <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/>
    </svg>
    <svg class="moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
      <path d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z"/>
    </svg>
  </button>
</nav>

<main>
 <!-- Enhanced Hero Section -->
<section class="hero" aria-labelledby="hero-title">
  <div class="container">
    <div class="hero-content">
      <div class="hero-badge" aria-label="Ferramenta interativa para PMEs">
        Ferramenta Interativa
      </div>

      <h1 id="hero-title">Painel de Prompts Inteligentes para Gestão de PMEs</h1>

      <p class="hero-description">
        Pare de receber respostas genéricas da IA e passe a gerar <strong>planos, análises e decisões</strong>
        realmente aplicáveis à <strong>gestão diária da empresa</strong>.
      </p>
    </div>

    <div class="hero-actions">
      <p class="context-description hero-note">
        Quer entender a lógica completa, os hacks de refinamento e ver exemplos avançados por área?  
        Use o <strong>Guia Inteligente</strong> disponível na sua área de membros.
      </p>
    </div>
  </div>
</section>

  <!-- Enhanced Chapters Section -->
  <section id="secoes" class="chapters" aria-labelledby="chapters-title">
    <div class="container">
      <div class="context-content">
        <div class="context-header">                 
          <div class="section-header">
            <h2 id="chapters-title" class="section-title">Prompts Inteligentes</h2>          
<p class="context-description">
  <strong>Clique na área de negócios</strong> ou use a busca por palavra-chave para encontrar o Prompt ideal para o seu cenário.
</p>
          </div>
        </div>
      </div>

      <div class="search-container">
        <div class="search-box">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
            <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
          <input type="text" id="search-input" placeholder="Buscar prompts..." aria-label="Buscar prompts">
        </div>
      </div>

      <div class="chapters-grid" role="list" aria-label="Categorias de prompts">
        
        <!-- 1. Gestão & Estratégia -->
        <div class="chapter-card" role="listitem" data-category="gestao" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12A2.25 2.25 0 0020.25 14.25V3.75M16.5 18.75h.008v.008h-.008v-.008zM12 18.75h.008v.008h-.008v-.008zM7.5 18.75h.008v.008h-.008v-.008z"/>
            </svg>
          </div>    
          <h3 class="chapter-title">Gestão & Estratégia</h3>
<p class="chapter-description">
  Conduza SWOT aplicada, construa OKRs, Canvas, análises de viabilidade, planos de crise e sucessão para tomar decisões estratégicas com base em dados reais.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

<!--2. Produtividade & Tempo (NOVO) -->
<div class="chapter-card" role="listitem" data-category="produtividade" tabindex="0">
  
  <span class="chapter-badge" aria-label="Seção nova">Novo</span>

  <div class="chapter-number" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
         stroke-width="1.5" stroke="currentColor" width="24" height="24">
      <path stroke-linecap="round" stroke-linejoin="round" 
            d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
  </div>

  <h3 class="chapter-title">Produtividade & Tempo</h3>
<p class="chapter-description">
  Estruture planejamento semanal, blocos de foco, delegação inteligente, gestão de energia, revisões semanais e automações simples para ganhar horas livres sem perder o controle do negócio.
</p>

  <div class="card-click-indicator">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
      <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
    </svg>
    <span>Ver prompts</span>
  </div>
</div>
     
        <!-- 3. Finanças -->
        <div class="chapter-card" role="listitem" data-category="financas" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0h.75A.75.75 0 015.25 6v.75m0 0v.75a.75.75 0 01-.75.75h-.75a.75.75 0 01-.75-.75V6.75m0 0H3.75m0 0h.75m1.5-1.5v.75A.75.75 0 016 6h-.75m0 0v-.75A.75.75 0 016 4.5h.75m0 0h.75A.75.75 0 018.25 6v.75m0 0v.75a.75.75 0 01-.75.75h-.75a.75.75 0 01-.75-.75V6.75m0 0H6.75m0 0h.75M9 12.75l.75-1.036a.75.75 0 011.063-.263 60.11 60.11 0 015.374 2.105c.727.198 1.453-.342 1.453-1.096V12.75M9 12.75l-1.875-2.583a.75.75 0 00-1.063.263 60.11 60.11 0 00-5.374-2.105C.727 8.128 0 8.67 0 9.423v.327"/>
            </svg>
          </div>
          <h3 class="chapter-title">Finanças</h3>
<p class="chapter-description">
  Analise DRE e fluxo de caixa, simule cenários, interprete indicadores e transforme números em decisões financeiras concretas para a PME.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

        <!-- 4. Tributário & Fiscal -->
        <div class="chapter-card" role="listitem" data-category="tributario" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
          </div>
          <h3 class="chapter-title">Tributário & Fiscal</h3>
<p class="chapter-description">
  Compare regimes tributários, organize obrigações acessórias, identifique créditos recuperáveis, reduza riscos em fiscalizações e planeje estrutura societária com segurança.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

        <!-- 5. Operações & Estoque -->
        <div class="chapter-card" role="listitem" data-category="operacoes" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>
          </div>
          <h3 class="chapter-title">Operações & Estoque</h3>
<p class="chapter-description">
  Desenhe processos fim a fim, reduza gargalos, organize estoque e conecte operação, fiscal e contabilidade para diminuir retrabalho no ERP.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

        <!-- 6. Compras & Suprimentos -->
        <div class="chapter-card" role="listitem" data-category="compras" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
            </svg>
          </div>
          <h3 class="chapter-title">Compras & Suprimentos</h3>
<p class="chapter-description">
  Crie fluxos claros de compras, padronize cotações, negocie melhor com fornecedores e conecte pedidos, recebimento, fiscal e financeiro sem ruído.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

        <!-- 7. Crédito & Fomento -->
        <div class="chapter-card" role="listitem" data-category="credito" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0h.75A.75.75 0 015.25 6v.75m0 0v.75a.75.75 0 01-.75.75h-.75a.75.75 0 01-.75-.75V6.75m0 0H3.75m0 0h.75m1.5-1.5v.75A.75.75 0 016 6h-.75m0 0v-.75A.75.75 0 016 4.5h.75m0 0h.75A.75.75 0 018.25 6v.75m0 0v.75a.75.75 0 01-.75.75h-.75a.75.75 0 01-.75-.75V6.75m0 0H6.75m0 0h.75M9 12.75l.75-1.036a.75.75 0 011.063-.263 60.11 60.11 0 015.374 2.105c.727.198 1.453-.342 1.453-1.096V12.75M9 12.75l-1.875-2.583a.75.75 0 00-1.063.263 60.11 60.11 0 00-5.374-2.105C.727 8.128 0 8.67 0 9.423v.327"/>
            </svg>
          </div>
          <h3 class="chapter-title">Crédito & Fomento</h3>
<p class="chapter-description">
  Mapeie elegibilidade a crédito, estruture projetos para BNDES e fomento, organize garantias, use antecipação e crédito verde com critério e melhore o score da empresa.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

        <!-- 8. Marketing & Vendas -->
        <div class="chapter-card" role="listitem" data-category="marketing" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 100 15 7.5 7.5 0 000-15zM21 21l-5.197-5.197"/>
            </svg>
          </div>
          <h3 class="chapter-title">Marketing & Vendas</h3>
<p class="chapter-description">
  Estruture funil, campanhas, ofertas e argumentos comerciais, conectando marketing e vendas para gerar demanda qualificada e aumentar conversão.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

        <!-- 9. Comunicação & Cliente -->
        <div class="chapter-card" role="listitem" data-category="comunicacao" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.158 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.671.97-.086 1.944-.21 2.919-.372C20.536 15.754 21.75 14.36 21.75 12.76v-1.524c0-1.6-1.123-2.994-2.707-3.227-1.068-.158-2.148-.279-3.238-.364a1.32 1.32 0 00-1.153.671L12 9 9.348 5.022c-.26-.39-.687-.634-1.153-.671-.97-.086-1.944-.21-2.919-.372C3.464 3.754 2.25 5.14 2.25 6.74v4.524z"/>
            </svg>
          </div>
<h3 class="chapter-title">Comunicação & Relacionamento com Cliente</h3>
<p class="chapter-description">
  Crie scripts de atendimento, nutrição de leads, pós-venda, recuperação de clientes, pesquisa de satisfação e tom de voz consistente em todos os canais.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

        <!-- 10. RH & Pessoas -->
        <div class="chapter-card" role="listitem" data-category="rh" tabindex="0">
          <div class="chapter-number" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true" focusable="false">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.24.252-.473.386-.702z"/>
            </svg>
          </div>
          <h3 class="chapter-title">RH & Pessoas</h3>
<p class="chapter-description">
  Desenhe cargos, onboarding, PDI, pesquisas de clima, políticas de remuneração, programas de reconhecimento, feedbacks 1-a-1 e planos de treinamento alinhados à estratégia.
</p>
<div class="card-click-indicator">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
  </svg>
  <span>Ver prompts</span>
</div>
        </div>

<!-- 11. Inovação & Criatividade (EM BREVE) -->
<div class="chapter-card" role="listitem" data-category="inovacao" tabindex="0">

  <span class="chapter-badge chapter-badge-soon" aria-label="Seção em breve">Em Breve</span>

  <div class="chapter-number" aria-hidden="true">
    <!-- Ícone lâmpada para representar criatividade -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke-width="1.5" stroke="currentColor" width="24" height="24">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 2.25c-3.728 0-6.75 2.91-6.75 6.5 0 2.01 1.03 3.8 2.676 4.957.506.368.824.956.824 1.594V18a1.5 1.5 0 001.5 1.5h3a1.5 1.5 0 001.5-1.5v-2.699c0-.638.318-1.226.824-1.594A6.457 6.457 0 0018.75 8.75c0-3.59-3.022-6.5-6.75-6.5z" />
    </svg>
  </div>

  <h3 class="chapter-title">Inovação & Criatividade</h3>
<p class="chapter-description">
  Práticas e prompts para facilitar brainstorming, testar ideias e transformar criatividade em projetos aplicados ao dia a dia da PME. 
</p>
  <div class="card-click-indicator">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
      <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
    </svg>
    <span>Ver prompts</span>
  </div>
</div>

<!-- 12. Liderança & Gestão de Equipe (EM BREVE) -->
<div class="chapter-card" role="listitem" data-category="lideranca" tabindex="0">

  <span class="chapter-badge chapter-badge-soon" aria-label="Seção em breve">Em Breve</span>

  <div class="chapter-number" aria-hidden="true">
    <!-- Ícone de mãos levantadas / liderança / suporte -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke-width="1.5" stroke="currentColor" width="24" height="24">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M19 11V5a2 2 0 00-2-2h-3m-4 0H7a2 2 0 00-2 2v6m12 0v8a2 2 0 01-2 2h-3m-4 0H7a2 2 0 01-2-2v-8m10-4H9" />
    </svg>
  </div>

  <h3 class="chapter-title">Liderança & Gestão de Equipe</h3>
<p class="chapter-description">
  Ferramentas para conversas 1-a-1, alinhamento de equipe, rituais de liderança e cultura saudável, com foco em PMEs brasileiras.
</p>
  <div class="card-click-indicator">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
      <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
    </svg>
    <span>Ver prompts</span>
  </div>
</div>

      </div>
    </div>
  </section>
</main>

<footer class="footer" role="contentinfo">
  <div class="container">
    <div class="footer-cards" aria-label="Informações sobre o produto e suporte">
      
      <!-- Card 1: Painel de Prompts -->
      <section class="footer-card" aria-labelledby="footer-card-prompts-title">
        <div class="footer-card-header">
          <span class="footer-card-icon" aria-hidden="true">
            <!-- Ícone de chave -->
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
              <path d="M16.5 3a4.5 4.5 0 0 0-3.622 7.162L7.5 15.54V18h2.25v-1.5H12V15h1.5l1.854-1.854A4.5 4.5 0 1 0 16.5 3zm0 2.25A2.25 2.25 0 1 1 14.25 7.5 2.25 2.25 0 0 1 16.5 5.25z" />
            </svg>
          </span>
          <h2 id="footer-card-prompts-title" class="footer-card-title">Guia + Painel de Prompts</h2>
        </div>
        <p class="footer-card-text">
          Abra o painel, escolha a área, copie o prompt completo, personalize os <strong>[colchetes]</strong> 
          com a realidade da sua empresa e execute na ferramenta de IA.
          <br>
          <strong>O acesso é liberado pela sua área de membros, com token temporário de segurança.</strong>
        </p>
      </section>

      <!-- Card 2: Suporte Especializado -->
      <section class="footer-card" aria-labelledby="footer-card-support-title">
        <div class="footer-card-header">
          <span class="footer-card-icon" aria-hidden="true">
            <!-- Ícone de envelope -->
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
              <path d="M4.5 4.5h15a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18V6a1.5 1.5 0 0 1 1.5-1.5zm0 2.25v.26l7.5 4.5 7.5-4.5v-.26L12 11.25 4.5 6.75z" />
            </svg>
          </span>
          <h2 id="footer-card-support-title" class="footer-card-title">Suporte especializado</h2>
        </div>
        <p class="footer-card-text">
          Teve dúvida na aplicação dos prompts ou no acesso ao painel?
          <br>
          Basta responder o e-mail de confirmação da compra ou escrever para 
          <a href="mailto:contato@fluxoteca.com.br">contato@fluxoteca.com.br</a>.
          <br>
          <strong>Retorno em até 24 horas úteis.</strong>
        </p>
      </section>

      <!-- Card 3: Termos e Privacidade -->
      <section class="footer-card" aria-labelledby="footer-card-terms-title">
        <div class="footer-card-header">
          <span class="footer-card-icon" aria-hidden="true">
            <!-- Ícone de documento -->
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
              <path d="M7.5 3A2.5 2.5 0 0 0 5 5.5v13A2.5 2.5 0 0 0 7.5 21h9a2.5 2.5 0 0 0 2.5-2.5V9.75L14.25 3h-6.75zm6.75 1.5 3.75 3.75h-3.75V4.5zM9 11.25h6v1.5H9v-1.5zm0 3h6v1.5H9v-1.5z" />
            </svg>
          </span>
          <h2 id="footer-card-terms-title" class="footer-card-title">
            Termos, licença e privacidade
          </h2>
        </div>
        <p class="footer-card-text">
          <a href="https://fluxoteca.com.br/termos-de-uso/" target="_blank" rel="noopener noreferrer">
            Termos de Uso
          </a> · 
          <a href="https://fluxoteca.com.br/politica-de-privacidade/" target="_blank" rel="noopener noreferrer">
            Política de Privacidade
          </a>
          <br>
          Licença <strong>pessoal, individual e intransferível</strong> para uso comercial na sua rotina de trabalho.
          <br>
          <strong>Proibido compartilhar logins, copiar integralmente ou redistribuir os conteúdos premium.</strong>
          <br>
          © 2025 Fluxoteca – Todos os direitos reservados.
        </p>
      </section>

    </div>
  </div>
</footer>

<!-- Enhanced Modal -->
<div id="modal-backdrop" class="modal-backdrop" aria-hidden="true"></div>
<div id="prompt-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-description">
  <div class="modal-header">
    <div class="modal-header-content">
      <div class="modal-title-section">
        <h2 id="modal-title" class="modal-title">Prompt</h2>
        <p id="modal-description" class="modal-subtitle"></p>
      </div>
      <div class="modal-progress" id="modal-progress" aria-live="polite">1/10</div>
    </div>
  </div>
  <div id="modal-content" class="modal-content" tabindex="-1">
    <!-- Conteúdo dinâmico -->
  </div>
  <div class="modal-actions">
    <div class="modal-nav">
      <button class="btn btn-ghost" onclick="prevPrompt()" aria-label="Ver prompt anterior" type="button">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
        Anterior
      </button>
      <button class="btn btn-ghost" onclick="nextPrompt()" aria-label="Ver próximo prompt" type="button">
        Próximo
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
          <path d="M9 18l6-6-6-6"/>
        </svg>
      </button>
    </div>
    <div class="modal-meta">   
      <button class="btn btn-ghost" onclick="copyCurrentPrompt()" aria-label="Copiar prompt para área de transferência" type="button">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
          <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
        </svg>
        <span id="copy-prompt-btn-text">Copiar Prompt</span>
      </button>
      <button class="btn btn-ghost" onclick="closeModal()" aria-label="Fechar seção de prompt" type="button">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
        Fechar
      </button>
    </div>
  </div>
</div>

<div id="toast" class="toast" role="status" aria-live="polite" aria-atomic="true">Copiado!</div>

<script>
/* ===========================
   PROMPTS
   =========================== */

const PROMPTS = {
/* ---------- FINANÇAS ---------- */
financas: [
  {
    "title": "Diagnosticar a saúde financeira geral da empresa com base em índices-chave.",
    "papel": "Analista Financeiro Sênior para PMEs",
    "contexto": "A empresa precisa de um retrato claro e objetivo da sua situação financeira atual, que possa ser entendido por sócios, diretores e bancos, mesmo que não sejam da área financeira. O foco é ter uma visão sintética (2 a 3 páginas) que ajude em decisões do dia a dia e em conversas com instituições financeiras.",
    "lista_verificacao_inicial": [
      "Balanço Patrimonial dos últimos 2 anos em formato comparativo (mesmos critérios contábeis e mesma moeda)",
      "DRE dos últimos 2 anos, com pelo menos receitas, CMV/CPV, despesas operacionais, resultado financeiro e lucro líquido",
      "Se disponível, índices ou benchmarks típicos do setor (rentabilidade, endividamento, margens, giro)",
      "Informar o ramo de atuação principal da empresa (ex: comércio, indústria, serviços) e porte aproximado (micro, pequena, média)"
    ],
    "objetivo": "Calcular e interpretar os principais índices de liquidez, endividamento, rentabilidade e atividade, entregando um diagnóstico sintético com pontos fortes, alertas e próximos passos recomendados, de forma que qualquer gestor consiga entender o que precisa ser feito.",
    "restricoes": [
      "Priorizar no máximo 5 a 7 índices realmente relevantes para PMEs (evitar listas extensas que confundam o leitor)",
      "Sempre explicar o que cada índice significa em linguagem simples e aplicada ao dia a dia (ex: impacto em pagar contas em dia, endividamento, retorno sobre o negócio)",
      "Evitar jargões técnicos sem explicação e fórmulas exibidas sem contexto",
      "Apontar claramente se o índice está saudável, em atenção ou em alerta, justificando o porquê",
      "Sempre indicar para qual período e moeda os índices foram calculados",
      "Não sugerir decisões radicais (ex: fechar unidade, demitir em massa) sem reforçar que a análise é preliminar e depende de validação com contador/consultor"
    ],
    "processo_dados_faltando": "Se faltarem contas importantes no Balanço ou DRE (ex: estoques, empréstimos, despesa financeira), explicar quais índices ficam comprometidos ou distorcidos e trabalhar apenas com os índices que podem ser calculados de forma minimamente confiável. Se não houver benchmark setorial, focar na comparação da empresa consigo mesma ao longo do tempo (melhora ou piora) e deixar claro que isso limita a comparação com o mercado.",
    "formato_saida": "1) Tabela em Markdown com colunas: 'Índice', 'Fórmula (resumida)', 'Valor Calculado', 'Interpretação prática (o que isso diz sobre a empresa)'. 2) Resumo executivo em até 5 parágrafos, destacando: a) principais pontos fortes, b) principais riscos e fragilidades, c) 3 ações prioritárias de curto prazo e, se fizer sentido, d) 1 a 2 ações de médio prazo."
  },
  {
    "title": "Projetar o fluxo de caixa dos próximos 3 meses e identificar meses de atenção ao caixa.",
    "papel": "Controller Financeiro com foco em PMEs",
    "contexto": "A empresa quer antecipar possíveis faltas de caixa no curto prazo para não depender de empréstimos de última hora, cheque especial ou atraso em pagamentos. A ideia é enxergar mês a mês se o dinheiro que entra é suficiente para cobrir o que sai.",
    "lista_verificacao_inicial": [
      "Saldo de caixa e bancos atual (informar data de referência)",
      "Previsão de receitas (vendas e recebimentos) mês a mês para os próximos 3 meses, separando à vista e a prazo se possível",
      "Previsão de saídas fixas (folha, aluguel, tributos, fornecedores principais, financiamentos) para os próximos 3 meses",
      "Principais despesas variáveis ou sazonais previstas (ex: comissões, campanhas de marketing, manutenção pontual)",
      "Se existir, limites de crédito já aprovados (cheque especial, conta garantida, capital de giro), com taxas e prazos básicos"
    ],
    "objetivo": "Montar uma projeção simples e clara de entradas, saídas e saldo final de caixa mês a mês, apontando meses com risco de saldo negativo ou muito apertado, e sugerindo ações preventivas que façam sentido para a realidade da empresa.",
    "restricoes": [
      "Aplicar uma margem de segurança de pelo menos 10% a mais nas despesas variáveis ou incertas, deixando isso explícito",
      "Agrupar despesas por grandes blocos (Folha, Fornecedores, Tributos, Despesas Gerais, Financeiras) para não sobrecarregar o gestor em detalhes",
      "Destacar claramente os meses em que o saldo final projetado for negativo ou muito próximo de zero (por exemplo, abaixo de uma reserva mínima definida)",
      "Não criar projeções excessivamente complexas ou baseadas em modelos avançados de previsão estatística; manter foco prático",
      "Sempre indicar que se trata de projeção baseada em premissas e que deve ser revisada e atualizada mensalmente"
    ],
    "processo_dados_faltando": "Se não houver previsão formal de receitas ou despesas, usar a média dos últimos 3 meses como base, deixando absolutamente claro que se trata de uma aproximação e sugerindo que o gestor refine os números. Se não houver dados de limites de crédito, não assumir crédito disponível automaticamente; apenas citar que o uso de crédito pode ser uma alternativa a ser negociada.",
    "formato_saida": "Tabela em Markdown com colunas: 'Mês', 'Entradas Previstas', 'Saídas Previstas', 'Saldo Inicial', 'Saldo Final Projetado', incluindo observações textuais para meses críticos (por exemplo, 'risco de falta de caixa'). Ao final, uma lista numerada com 3 a 5 ações sugeridas para evitar problemas de caixa (por exemplo, antecipar cobranças, negociar prazos com fornecedores, postergar gastos não essenciais)."
  },
  {
    "title": "Otimizar o capital de giro pela análise dos prazos médios de pagamento, recebimento e estoque.",
    "papel": "Especialista em Gestão de Capital de Giro",
    "contexto": "A empresa sente que 'fatura bem mas vive sem caixa' e precisa entender se o problema está nos prazos de clientes, nos prazos de fornecedores, nos estoques ou em uma combinação desses fatores. A ideia é tornar visível quanto tempo o dinheiro 'fica preso' no ciclo do negócio.",
    "lista_verificacao_inicial": [
      "Valor médio mensal de contas a receber e prazo médio de recebimento (em dias), ou informações necessárias para calculá-lo",
      "Valor médio mensal de contas a pagar e prazo médio de pagamento a fornecedores (em dias), ou informações necessárias para calculá-lo",
      "Se possível, prazo médio de estocagem (em dias) ou giro de estoque, com breve explicação de como foi obtido",
      "Volume médio de vendas no período analisado e principal forma de venda (à vista, cartão, boleto, etc.)"
    ],
    "objetivo": "Calcular o Ciclo Operacional e o Ciclo Financeiro (Ciclo de Caixa), identificar qual componente (estoque, clientes, fornecedores) mais impacta o consumo de capital de giro e sugerir ações práticas para encurtar o ciclo, respeitando relações comerciais importantes.",
    "restricoes": [
      "Realizar todos os cálculos em dias, explicando cada passo de forma simples, inclusive a lógica de cada prazo médio",
      "Focar em ações de curto e médio prazo viáveis para PMEs (ex: renegociação de prazo com alguns fornecedores, ajuste de política de crédito, revisão de lote de compras, incentivo a meios de pagamento mais rápidos)",
      "Evitar recomendações agressivas que possam comprometer o relacionamento com bons clientes ou fornecedores estratégicos",
      "Lembrar que decisões sobre crédito, prazos e estoque exigem sempre validação humana, considerando aspectos comerciais e operacionais",
      "Evitar suposições excessivamente otimistas; trabalhar com cenários realistas ou ligeiramente conservadores"
    ],
    "processo_dados_faltando": "Se os prazos médios não forem fornecidos, sugerir como calculá-los a partir de relatórios contábeis, planilhas ou do ERP (explicando o passo a passo em alto nível) e trabalhar com estimativas apenas se o usuário autorizar e confirmar que os dados são representativos. Se houver grandes lacunas de informação, deixar claro que as conclusões são preliminares.",
    "formato_saida": "Texto estruturado com: 1) Cálculos do Ciclo Operacional, Ciclo Econômico (se aplicável) e Ciclo Financeiro em dias, 2) Interpretação sobre onde o dinheiro fica mais tempo 'parado', 3) Lista numerada de recomendações, indicando para cada ação o impacto estimado em dias e, se possível, em caixa liberado (mesmo que em estimativa)."
  },
  {
    "title": "Construir um orçamento anual de despesas operacionais alinhado ao plano do negócio.",
    "papel": "Gestor Orçamentário orientado a PMEs",
    "contexto": "A empresa quer controlar melhor os gastos do próximo ano e precisa de um orçamento que faça sentido para o dia a dia, não apenas uma planilha complexa que ninguém acompanha. O objetivo é ter uma visão simples para comparar o que foi gasto com o que estava previsto.",
    "lista_verificacao_inicial": [
      "Histórico de despesas por categoria dos últimos 12 meses (ex: Marketing, RH, Administrativo, Manutenção, TI, Logística)",
      "Previsão de inflação ou reajuste médio de custos para o próximo ano (ou faixa estimada)",
      "Planos de expansão, redução de custos ou mudanças relevantes que impactem despesas (ex: novas contratações, mudança de sede, investimentos em sistemas)",
      "Se houver, metas de faturamento ou crescimento para o próximo ano"
    ],
    "objetivo": "Criar um orçamento anual por categoria de despesa, usando como base o histórico recente e as premissas futuras, permitindo comparação entre 'Real' e 'Orçado' ao longo do ano e ajudando a empresa a priorizar gastos.",
    "restricoes": [
      "Reajustar valores históricos pela inflação estimada ou outro critério informado, deixando essa premissa claramente documentada",
      "Evitar granularidade excessiva que torne o orçamento impossível de ser acompanhado (ex: detalhar demais contas irrelevantes)",
      "Prever no mínimo uma coluna para 'Real' e uma para 'Orçado' em cada período (mensal, bimestral ou trimestral, conforme a realidade da empresa)",
      "Indicar claramente as premissas assumidas (ex: reajuste de aluguel, aumentos salariais, corte ou aumento de investimentos em marketing)",
      "Manter uma estrutura de categorias que qualquer gestor da empresa consiga entender sem precisar do contador"
    ],
    "processo_dados_faltando": "Se faltarem dados para alguma categoria, usar a média dos meses disponíveis ou sinalizar que a categoria ficará inicialmente sem orçamento definido, sugerindo um plano para levantar esses dados depois. Se não houver previsão de inflação, assumir uma taxa padrão (ex: 5% ao ano) deixando explícito que deve ser ajustada pelo gestor.",
    "formato_saida": "Tabela em Markdown com linhas para categorias de despesa e colunas para cada mês do ano (ou período escolhido), além de uma coluna de total anual por categoria. Ao final, um breve resumo com 3 a 5 pontos de atenção do orçamento (ex: categorias que mais cresceram, riscos de estouro de orçamento, necessidade de monitorar determinados gastos)."
  },
  {
    "title": "Avaliar a viabilidade financeira de um novo projeto ou investimento com VPL e TIR.",
    "papel": "Analista de Investimentos para PMEs",
    "contexto": "A empresa está avaliando um investimento relevante (ex: equipamento, nova unidade, novo produto, sistema) e precisa entender se o retorno compensa o risco e o esforço. O objetivo é ter uma visão financeira clara antes de assumir o compromisso.",
    "lista_verificacao_inicial": [
      "Investimento inicial previsto (incluindo impostos, instalação, treinamento, consultorias e outros custos associados)",
      "Projeção de fluxos de caixa líquidos anuais do projeto (pelo menos 3 a 5 anos, com melhor estimativa possível)",
      "Taxa mínima de atratividade ou custo de capital desejado (taxa de desconto), mesmo que em faixa aproximada",
      "Se existirem, cenários alternativos (otimista, base e conservador) para os fluxos de caixa"
    ],
    "objetivo": "Calcular o Valor Presente Líquido (VPL) e a Taxa Interna de Retorno (TIR) do projeto, interpretar os resultados de forma simples e apontar fatores de risco que podem afetar o retorno, ajudando os sócios a tomarem decisão mais consciente.",
    "restricoes": [
      "Assumir que os fluxos de caixa ocorrem no final de cada período (ano), salvo se o usuário informar outra periodicidade",
      "Apresentar fórmulas de forma simples e explicar o que significam (ex: 'VPL positivo significa que, nas premissas utilizadas, o projeto tende a gerar valor para a empresa')",
      "Esclarecer que o cálculo é uma simulação baseada em premissas e não substitui uma análise detalhada de investimentos",
      "Evitar recomendações categóricas do tipo 'faça' ou 'não faça'; sempre sugerir validação com contador/consultor de confiança",
      "Evitar transmitir falsa sensação de precisão em casos de grande incerteza nos fluxos de caixa"
    ],
    "processo_dados_faltando": "Se a taxa de desconto não for informada, sugerir uma faixa (por exemplo, entre 10% e 15%) e realizar o cálculo com um valor intermediário apenas como exercício, deixando claro que o resultado muda conforme a taxa. Se faltarem anos na projeção de fluxo de caixa, deixar explícito que a análise está incompleta e trabalhar apenas com os dados disponíveis, recomendando complementação.",
    "formato_saida": "Texto explicando: 1) Premissas utilizadas (investimento inicial, fluxos, taxa de desconto, horizonte de análise), 2) Cálculo resumido do VPL e da TIR, 3) Interpretação em linguagem simples (se o projeto é financeiramente atrativo ou não dentro dessas premissas), 4) Lista de riscos e variáveis que mais afetam o resultado (ex: volume de vendas, preço, custo variável, taxa de juros)."
  },
  {
    "title": "Mapear custos fixos e variáveis e calcular a margem de contribuição.",
    "papel": "Consultor de Custos para PMEs",
    "contexto": "A empresa não sabe exatamente quais custos são fixos ou variáveis e tem dificuldade para entender se cada produto ou serviço realmente dá dinheiro. É preciso organizar essa visão para tomar decisões melhores sobre preços, cortes de gastos e foco em produtos mais rentáveis.",
    "lista_verificacao_inicial": [
      "Lista de despesas mensais com descrição e valor (pode ser relatório contábil, planilha ou exportação do ERP)",
      "Informação sobre o volume de produção ou vendas em um período típico (ex: unidades, horas vendidas, serviços executados)",
      "Se possível, receita total e receita por produto/serviço ou por linha de produto",
      "Se houver, estrutura básica de preços (preço de venda e principais custos diretos por produto/serviço)"
    ],
    "objetivo": "Classificar custos em fixos e variáveis, estimar a margem de contribuição total e, se houver dados, por produto ou linha de produto, ajudando a empresa a enxergar o quanto cada venda contribui para pagar os custos fixos e gerar lucro.",
    "restricoes": [
      "Explicar de forma didática a diferença entre custo fixo e variável com exemplos práticos aplicados à realidade de PMEs",
      "Evitar discussões excessivamente técnicas sobre métodos de custeio que não tenham impacto direto na tomada de decisão do gestor",
      "Deixar claro que alguns custos podem ter componente misto (fixo + variável) e que será necessário julgamento gerencial para classificá-los",
      "Não assumir estrutura de custos sofisticada se o usuário não tiver dados; trabalhar com o que estiver disponível, de forma clara",
      "Evitar números super detalhados se isso atrapalhar a compreensão; priorizar visão gerencial e prática"
    ],
    "processo_dados_faltando": "Se houver dúvidas sobre a classificação de um custo, classificá-lo inicialmente como 'Misto' ou 'Fixo' e sinalizar ao usuário que é um ponto para revisão posterior. Se o volume de vendas não for informado, calcular apenas a margem de contribuição percentual com base em receita e custos variáveis totais, deixando claro a limitação. Se não houver dados por produto, trabalhar inicialmente com margem global.",
    "formato_saida": "Tabela em Markdown com colunas: 'Despesa', 'Classificação (Fixo/Variável/Misto)', 'Valor Mensal', 'Observações (se houver dúvida na classificação)'. Em seguida, apresentar o cálculo resumido da margem de contribuição total (em valor e percentual) e uma breve interpretação aplicada à realidade da PME (por exemplo, se a margem é suficiente para cobrir os custos fixos e gerar lucro)."
  },
  {
    "title": "Definir uma política de descontos comercialmente atrativa e financeiramente segura.",
    "papel": "Estrategista Comercial-Financeiro",
    "contexto": "A equipe de vendas quer poder dar descontos para fechar negócios, mas a gestão teme perda de margem e lucro. A empresa precisa de regras claras para não 'dar desconto no feeling' e acabar vendendo sem ganhar dinheiro.",
    "lista_verificacao_inicial": [
      "Margem de contribuição média dos principais produtos/serviços ou, se possível, margem por linha de produto",
      "Custo de capital ou taxa mínima de retorno esperada (mesmo que em faixa aproximada)",
      "Objetivo principal da política de desconto (ex: aumentar volume de vendas, desovar estoque parado, conquistar novos clientes estratégicos, reduzir inadimplência)",
      "Se existir, histórico de descontos praticados atualmente (faixas mais comuns e impactos em vendas e margem)"
    ],
    "objetivo": "Desenhar faixas de desconto por tipo de cliente, canal ou produto, garantindo que, mesmo com o desconto, a margem permaneça acima de um piso mínimo saudável e compatível com o risco do negócio.",
    "restricoes": [
      "Nunca permitir desconto que reduza a margem abaixo da soma do custo de capital + uma margem de segurança definida (ex: +5%), deixando essa referência clara",
      "Manter a política simples o suficiente para que vendedores entendam e apliquem sem dúvidas, evitando fórmulas complexas no dia a dia",
      "Separar claramente regras para campanhas promocionais temporárias e condições padrão do dia a dia",
      "Deixar claro que a política é um guia e não substitui senso crítico em negociações estratégicas, que podem exigir aprovação superior",
      "Evitar promessas do tipo 'aumente vendas em X%' sem base em dados históricos"
    ],
    "processo_dados_faltando": "Se o custo de capital não for informado, assumir uma taxa de referência (ex: 12% ao ano) explicitando que deve ser ajustada à realidade da empresa. Se a margem atual não for conhecida, usar um valor provisório (ex: 30%) apenas como exemplo, reforçando fortemente que é apenas para ilustração. Se não houver histórico de descontos, construir a política a partir de boas práticas e pedir validação do gestor.",
    "formato_saida": "Descrição textual clara da política (em linguagem que possa ser copiada para um manual interno ou comunicado à equipe comercial), seguida de uma tabela em Markdown com colunas: 'Segmento/Produto', 'Desconto Máximo Permitido', 'Margem Final Estimada (aproximada)', 'Observações/Regras Especiais'."
  },
  {
    "title": "Monitorar e projetar a inadimplência da carteira de clientes.",
    "papel": "Analista de Crédito e Cobrança com foco em PMEs",
    "contexto": "A inadimplência está aumentando, afetando o caixa e trazendo insegurança sobre a qualidade da carteira de clientes. A empresa precisa entender o tamanho do problema, onde ele está concentrado e quais ações priorizar.",
    "lista_verificacao_inicial": [
      "Relatório de idade de saldos (aging) do contas a receber por faixa de atraso (ex: a vencer, 0–30, 31–60, 61–90, >90 dias)",
      "Histórico de perdas por inadimplência dos últimos 12 meses (valores baixados como perda ou considerados incobráveis)",
      "Volume total de vendas a prazo no mesmo período (últimos 12 meses ou período disponível)",
      "Se possível, principais políticas atuais de crédito e cobrança (prazo padrão, limites, critérios de concessão)"
    ],
    "objetivo": "Calcular o índice de inadimplência atual, sugerir uma provisão de devedores duvidosos por faixa de atraso e priorizar clientes para ação de cobrança, ajudando a empresa a agir antes que o problema fique fora de controle.",
    "restricoes": [
      "Utilizar o método de aging (faixas de atraso) para classificar risco, explicando a lógica de cada faixa",
      "Basear a provisão em percentuais históricos ou, na falta deles, em faixas padrão que façam sentido para PMEs, deixando sempre claro que são referências e não regras fixas",
      "Evitar linguagem acusatória em relação a clientes; focar em gestão de risco e relacionamento de longo prazo",
      "Não sugerir ações de cobrança agressivas que possam ferir legislação, código de defesa do consumidor ou relações comerciais",
      "Explicitar que decisões extremas (suspender vendas, acionar jurídico) exigem avaliação humana e, se necessário, orientação jurídica"
    ],
    "processo_dados_faltando": "Se não houver histórico de perdas, usar percentuais de referência (exemplos: 2% para 31–90 dias, 10% para acima de 90 dias) apenas como exercício, enfatizando que são estimativas que devem ser calibradas com o tempo. Se o aging não estiver estruturado, orientar como organizar o contas a receber por faixas de atraso antes de prosseguir com uma análise mais profunda.",
    "formato_saida": "Tabela em Markdown com as faixas de atraso, valores em aberto, percentual de inadimplência e percentuais de provisão, além do valor provisionado sugerido. Em seguida, lista dos principais clientes em atraso por valor e/ou risco (sem expor dados sensíveis desnecessários) e recomendações de abordagem de cobrança em camadas (preventiva, amigável, escalada gradual)."
  },
  {
    "title": "Preparar análise financeira para solicitação de empréstimo bancário.",
    "papel": "Consultor Financeiro para Relacionamento Bancário",
    "contexto": "A empresa precisa captar recursos via empréstimo e o banco solicitou uma análise técnica que comprove capacidade de pagamento. O objetivo é organizar os dados em um formato que facilite a aprovação ou, pelo menos, fortaleça a negociação.",
    "lista_verificacao_inicial": [
      "DRE e Balanço Patrimonial dos últimos 3 anos (ou o período disponível), em mesma moeda e com breve indicação de eventuais mudanças contábeis relevantes",
      "Projeção de fluxo de caixa para os próximos 2 a 3 anos, incluindo o impacto do novo empréstimo (entrada do recurso e pagamento das parcelas)",
      "Valor do empréstimo desejado, prazo, carência e taxa de juros proposta (se já houver oferta de banco)",
      "Garantias disponíveis, se existirem (ex: imóveis, máquinas, recebíveis), com nível básico de descrição",
      "Informar o segmento e porte da empresa e a finalidade principal do empréstimo (ex: capital de giro, investimento, refinanciamento)"
    ],
    "objetivo": "Elaborar uma análise que apresente os principais indicadores de desempenho e endividamento, incluindo EBITDA e DSCR (Debt Service Coverage Ratio), para demonstrar capacidade de honrar a dívida, equilibrando linguagem acessível ao empresário e adequada para instituições financeiras.",
    "restricoes": [
      "Usar linguagem adequada a instituições financeiras, porém mantendo clareza para gestores que não são especialistas em finanças",
      "Destacar pontos fortes da empresa, mas também reconhecer fragilidades com propostas de mitigação, evitando 'vender ilusão'",
      "Não 'maquiar' números ou sugerir práticas contábeis inadequadas ou ilegais",
      "Reforçar que o documento é um apoio à negociação e não garantia de aprovação do crédito",
      "Evitar projeções extremamente otimistas; trabalhar com cenários conservadores ou realistas, deixando explícitas as premissas"
    ],
    "processo_dados_faltando": "Se as projeções de fluxo de caixa não existirem, construir um cenário conservador baseado no histórico e em premissas explícitas (ex: crescimento de X%, despesas crescendo Y%). Se nem histórico completo houver, focar em explicar as limitações, o estágio do negócio (ex: empresa nova) e trabalhar com o que estiver disponível, evitando conclusões categóricas e ressaltando a necessidade de complementar dados.",
    "formato_saida": "Relatório em texto estruturado com seções: 1) Resumo da Empresa (atividade, porte, histórico resumido), 2) Análise de Resultados Históricos (faturamento, lucratividade, evolução), 3) Indicadores-Chave (incluindo EBITDA e DSCR, explicando em linguagem simples o significado de cada um), 4) Projeções e Capacidade de Pagamento considerando o novo empréstimo, 5) Conclusões e Recomendações para a negociação com o banco (incluindo eventuais ajustes de prazo, valor ou garantias)."
  },
  {
    "title": "Comparar regimes tributários sob a ótica financeira básica da PME.",
    "papel": "Consultor Tributário-Financeiro para PMEs",
    "contexto": "A empresa quer avaliar se o regime tributário atual ainda faz sentido financeiramente, mas não deseja entrar em análises jurídicas profundas. A intenção é ter uma visão preliminar, em linguagem simples, sobre qual regime tende a ser mais adequado ao perfil da empresa.",
    "lista_verificacao_inicial": [
      "Faturamento bruto anual estimado ou realizado (informar se é estimativa ou dado real)",
      "Margem de lucro líquida aproximada (ou faixa esperada de lucratividade)",
      "Regime tributário atual (ex: Simples Nacional, Lucro Presumido, Lucro Real)",
      "CNAE principal e, se houver, atividades secundárias relevantes",
      "Se possível, principais tributos incidentes hoje (ex: ICMS, ISS, PIS, COFINS, IRPJ, CSLL), mesmo que de forma resumida"
    ],
    "objetivo": "Comparar de forma preliminar os principais regimes tributários possíveis (Simples Nacional, Lucro Presumido, Lucro Real) em termos de carga tributária aproximada e complexidade operacional, sinalizando qual tende a ser mais vantajoso e por quais motivos, sempre reforçando o caráter orientativo e não definitivo da análise.",
    "restricoes": [
      "Deixar muito claro que a análise é preliminar e não substitui estudo detalhado com contador ou consultor tributário habilitado",
      "Usar alíquotas e faixas de forma simplificada, sem prometer exatidão centesimal ou valores finais de impostos a pagar",
      "Não considerar benefícios fiscais específicos, regimes especiais detalhados ou situações muito particulares sem mais dados",
      "Evitar promessas do tipo 'você com certeza vai pagar X%' ou 'você vai economizar exatamente Y%'",
      "Evitar aconselhamentos que possam ser interpretados como parecer tributário formal; manter caráter de simulação e comparação básica"
    ],
    "processo_dados_faltando": "Se faturamento ou margem não forem fornecidos, trabalhar apenas com cenários qualitativos (ex: lucro baixo, lucro médio, lucro alto) e explicar como cada regime costuma se comportar em cada cenário. Se houver dúvidas sobre o CNAE ou atividade real, lembrar que o enquadramento tributário depende de detalhes e recomendar fortemente validação com profissional habilitado antes de qualquer mudança.",
    "formato_saida": "Tabela comparativa em Markdown com colunas: 'Regime', 'Base de Cálculo Simplificada', 'Carga Tributária Estimada (faixa aproximada)', 'Complexidade Operacional (baixa/média/alta)', 'Comentários'. Ao final, um parágrafo com recomendação preliminar (por exemplo, qual regime tende a ser mais adequado para o cenário descrito) e alertas sobre a necessidade de estudo mais profundo com contador."
  }
],

/* ---------- MARKETING ---------- */
marketing: [
  {
    "title": "Desenvolver um plano de marketing digital para um trimestre.",
    "papel": "Estrategista de Marketing Digital orientado a PMEs",
    "contexto": "A empresa quer aumentar sua presença online e gerar leads qualificados, mas tem recursos limitados (tempo, dinheiro e equipe). O objetivo é ter um plano simples, realista e focado em poucos canais que realmente façam diferença nos próximos 3 meses.",
    "lista_verificacao_inicial": [
      "Descrição do público-alvo ou principais perfis de clientes atuais",
      "Orçamento disponível para marketing no trimestre (mesmo que aproximado)",
      "Canais atuais em uso (ex: Instagram, WhatsApp, site, Google Meu Negócio, email)",
      "Principais produtos/serviços que se deseja destacar",
      "Objetivos de negócio para o período (ex: % de aumento em leads, vendas, agendamentos)"
    ],
    "objetivo": "Criar um plano tático de 3 meses, com definição de objetivos claros, canais prioritários, tipos de conteúdo/ações, frequência mínima, métricas de acompanhamento e cronograma simples.",
    "restricoes": [
      "Focar prioritariamente em canais de baixo custo e alto potencial para PMEs (ex: orgânico, email marketing, WhatsApp, Google Meu Negócio)",
      "Limitar o plano a no máximo 3 canais principais para evitar dispersão",
      "Incluir métricas de ROI ou retorno aproximado, explicando como acompanhar, mesmo que de forma simples",
      "Evitar recomendações genéricas, sempre adaptando às informações fornecidas sobre público e produtos",
      "Deixar claro o que é essencial (mínimo viável) e o que é opcional, para quem tem pouco tempo"
    ],
    "processo_dados_faltando": "Se o público-alvo não for definido, propor 1 a 2 perfis prováveis com base no produto/serviço e deixar claro que são hipóteses a validar. Se o orçamento não for informado, criar um plano focado 100% em esforços orgânicos e ações de baixo custo, incluindo uma sugestão de quanto investir, caso a empresa decida anunciar.",
    "formato_saida": "Texto estruturado com: 1) Objetivos de negócio e de marketing para o trimestre, 2) Canais escolhidos e justificativa, 3) Ações táticas por canal, 4) Tabela em Markdown com o cronograma por semana (Semana, Canal, Ação, Responsável), 5) Lista de KPIs essenciais (ex: leads gerados, taxa de conversão, custo por lead, vendas atribuídas)."
  },
  {
    "title": "Criar um script de vendas para a equipe de prospecção (SDR/Pré-vendas).",
    "papel": "Head de Vendas para PMEs",
    "contexto": "A equipe de SDRs ou quem faz a primeira abordagem comercial precisa de um guia padronizado para prospecção via telefone, WhatsApp, email e/ou LinkedIn, para evitar improvisos e aumentar a taxa de agendamento de reuniões.",
    "lista_verificacao_inicial": [
      "Persona do cliente ideal (segmento, porte, cargo ou perfil de decisão)",
      "Principais problemas/dor que o produto/serviço resolve",
      "Principais objeções dos clientes (ex: preço, tempo, prioridade, confiança)",
      "Diferencial competitivo do produto/serviço em relação aos concorrentes ou alternativas",
      "Tipo de canal principal para abordagem (telefone, WhatsApp, email, LinkedIn ou misto)"
    ],
    "objetivo": "Elaborar um script prático que ajude a capturar a atenção do contato, qualificar o lead de forma respeitosa e agendar uma próxima etapa (reunião, demonstração ou proposta), com opções de frases e perguntas-chave.",
    "restricoes": [
      "O script deve ser conciso, com no máximo 5 grandes etapas (ex: abertura, conexão, diagnóstico, proposta de valor, fechamento do agendamento)",
      "Incluir sugestões de perguntas abertas para entender o contexto do lead, não apenas discurso pronto",
      "Incluir respostas adaptáveis para as 3 principais objeções, em linguagem humana e não robótica",
      "Evitar jargões de vendas excessivamente técnicos; focar em conversa natural",
      "Prever variações curtas de abordagem para canais diferentes (telefone, WhatsApp, email, LinkedIn) quando fizer sentido"
    ],
    "processo_dados_faltando": "Se as objeções não forem listadas, utilizar objeções padrão como 'não tenho tempo', 'estou sem verba' e 'vou pensar e te retorno', criando respostas possíveis. Se a persona não for definida, descrever uma persona genérica baseada no produto/serviço antes de criar o script.",
    "formato_saida": "Texto estruturado com a sequência do script: 1) Abertura (com 2 a 3 opções de frase), 2) Perguntas de Qualificação, 3) Apresentação de Valor adaptada à dor do cliente, 4) Chamada para Ação (marcar reunião, enviar proposta), 5) Tratativa das 3 principais objeções com exemplos de resposta. Incluir um mini-roteiro específico para WhatsApp (mensagens curtas em sequência)."
  },
  {
    "title": "Analisar a concorrência direta e indireta.",
    "papel": "Analista de Mercado para PMEs",
    "contexto": "A empresa está se preparando para lançar um novo produto/serviço ou entrar em um novo mercado e precisa entender melhor quem são seus concorrentes diretos e indiretos, seus diferenciais e oportunidades de posicionamento.",
    "lista_verificacao_inicial": [
      "Lista de 3 a 5 concorrentes principais (diretos e, se possível, indiretos)",
      "Principais produtos/serviços oferecidos pela concorrência, com faixa de preço se disponível",
      "Diferenciais percebidos no mercado (ex: atendimento, marca, prazo, variedade, localização)",
      "Links de sites, perfis em redes sociais ou materiais públicos dos concorrentes"
    ],
    "objetivo": "Produzir uma análise estruturada contendo SWOT (Forças, Fraquezas, Oportunidades, Ameaças) para o negócio do usuário em relação à concorrência, destacando possíveis posicionamentos, melhorias e oportunidades de nicho.",
    "restricoes": [
      "A análise deve ser baseada apenas em informações publicamente disponíveis ou fornecidas pelo usuário; não assumir acesso a dados internos dos concorrentes",
      "Ser objetivo e factual, evitando especulações agressivas ou acusações",
      "Diferenciar claramente concorrentes diretos (mesmo tipo de solução para o mesmo público) e indiretos (alternativas que resolvem o mesmo problema de outra forma)",
      "Priorizar insights práticos para tomada de decisão, não apenas descrição"
    ],
    "processo_dados_faltando": "Se a lista de concorrentes não for fornecida, assumir que o usuário precisa de ajuda para identificá-los e, em vez da análise detalhada, fornecer um método passo a passo para encontrá-los (ex: buscas no Google, marketplaces, redes sociais, indicações de clientes). Se faltarem detalhes de produtos ou preços, trabalhar com faixas ou descrições qualitativas.",
    "formato_saida": "1) Tabelas em Markdown com a SWOT do negócio do usuário em comparação ao conjunto de concorrentes (em vez de SWOT separada para cada um, se isso ficar complexo), 2) Lista dos principais concorrentes com breve descrição, 3) Resumo em texto com insights estratégicos: posicionamento sugerido, oportunidades de diferenciação e riscos competitivos."
  },
  {
    "title": "Calcular o Customer Lifetime Value (LTV) de um cliente.",
    "papel": "Analista de Dados de Marketing para PMEs",
    "contexto": "A empresa quer entender o valor de longo prazo de um cliente para tomar decisões mais conscientes sobre quanto pode investir em aquisição e retenção, sem se basear apenas em intuição.",
    "lista_verificacao_inicial": [
      "Ticket médio por venda (em moeda local)",
      "Frequência de compra média por cliente (ex: compras/ano)",
      "Margem de contribuição média por venda (percentual ou valor)",
      "Taxa de retenção anual de clientes (ou tempo médio de relacionamento em anos, se existir)",
      "Se possível, taxa de desconto ou custo de capital aproximado (para trazer o valor ao presente)"
    ],
    "objetivo": "Calcular o LTV usando uma fórmula adequada ao nível de dados disponível (simplificada ou mais avançada) e interpretar o resultado de forma direta para apoiar decisões de CAC (Custo de Aquisição de Cliente) e de retenção.",
    "restricoes": [
      "Usar uma fórmula simples quando houver poucos dados, deixando claro que se trata de uma aproximação",
      "Somente utilizar fórmulas mais complexas (com taxa de retenção e desconto) se as taxas estiverem minimamente definidas",
      "Explicar a fórmula em linguagem simples e mostrar o passo a passo do cálculo",
      "Evitar transmitir falsa precisão em cenários com alta incerteza; trabalhar com faixas quando necessário"
    ],
    "processo_dados_faltando": "Se a taxa de retenção ou o tempo de vida estiver faltando, calcular o LTV histórico simples: LTV = Ticket Médio × Frequência de Compra Anual × Margem × Tempo Médio de Relacionamento (em anos), deixando claro que é um modelo mais básico. Se o tempo de vida também não for conhecido, assumir um valor padrão (exemplo: 3 anos para PMEs), deixando explícito que é apenas uma referência inicial.",
    "formato_saida": "Texto estruturado com: 1) Dados utilizados, 2) Fórmula escolhida (simples ou avançada) explicada em linguagem não técnica, 3) Passo a passo do cálculo, 4) Valor final ou faixa de LTV, 5) Recomendações práticas sobre CAC máximo e estratégias de retenção com base no resultado."
  },
  {
    "title": "Estruturar um programa de fidelidade para clientes B2C.",
    "papel": "Especialista em Experiência do Cliente para PMEs",
    "contexto": "A empresa busca aumentar a recorrência de compra e o valor gasto por cliente, criando um programa de fidelidade que seja fácil de entender, simples de operar e que realmente gere percepção de vantagem para o cliente.",
    "lista_verificacao_inicial": [
      "Dados de compra média e frequência de compra dos clientes (mesmo que aproximados)",
      "Perfil dos clientes mais valiosos (ticket médio, frequência, produtos preferidos)",
      "Orçamento disponível para o programa (em percentual da receita ou valor mensal)",
      "Principais canais de relacionamento com o cliente (loja física, e-commerce, WhatsApp, aplicativo, etc.)"
    ],
    "objetivo": "Criar a estrutura de um programa de fidelidade viável para a realidade da empresa, com regras de pontuação, benefícios, critérios de resgate e estimativa simples de custo, favorecendo a recorrência e o aumento do ticket médio.",
    "restricoes": [
      "O programa deve ser simples de explicar em poucos minutos a qualquer cliente ou colaborador",
      "Os benefícios devem ser percebidos como de alto valor para o cliente, mas com custo controlado para a empresa (ex: benefícios de relacionamento, acesso, bônus em produtos com boa margem)",
      "Evitar regras excessivamente complexas (muitos níveis, exceções ou cálculos difíceis)",
      "Prever como o programa será operacionalizado (planilha, sistema, app, cartão físico, etc.) em linhas gerais"
    ],
    "processo_dados_faltando": "Se os dados de clientes não estiverem disponíveis, projetar um programa genérico baseado em pontos por valor gasto (ex: a cada R$ X em compras, o cliente ganha Y pontos) e em recompensas desejadas pelo público alvo. Se o orçamento não for informado, focar em benefícios não-monetários (ex: acesso antecipado a lançamentos, atendimento prioritário, conteúdo exclusivo, brindes estratégicos de baixo custo).",
    "formato_saida": "Texto estruturado descrevendo: 1) Nome e conceito do programa, 2) Regras de Pontuação (como o cliente acumula), 3) Níveis do programa (se houver) com critérios de entrada, 4) Benefícios de cada nível, 5) Regras de resgate (como usar os pontos), 6) Plano de Implantação em 5 etapas, incluindo comunicação inicial e treinamento da equipe."
  },
  {
    "title": "Otimizar a página principal do site para conversão (SEO e Copywriting).",
    "papel": "Consultor de CRO (Conversion Rate Optimization) para PMEs",
    "contexto": "O site recebe algum tráfego, mas a taxa de conversão (contatos, leads, vendas ou agendamentos) é baixa. A empresa precisa de recomendações práticas para melhorar a mensagem, a estrutura e os elementos-chave da página principal.",
    "lista_verificacao_inicial": [
      "URL do site ou da página principal",
      "Público-alvo principal (quem deve chegar nesta página)",
      "Principal ação desejada na página (CTA) ex: pedir orçamento, falar no WhatsApp, comprar, se cadastrar",
      "Se possível, taxa de conversão atual estimada e volume de acessos"
    ],
    "objetivo": "Fornecer um diagnóstico com recomendações específicas de copy, estrutura, elementos de confiança (prova social, garantias) e SEO básico, com foco em aumentar a conversão sem exigir uma grande reformulação técnica.",
    "restricoes": [
      "As recomendações devem ser de rápida implementação sempre que possível (mudanças de texto, ordem dos blocos, CTAs, elementos visuais simples)",
      "Focar especialmente nos elementos acima da dobra (above the fold): título principal, subtítulo, CTA e prova de valor",
      "Incluir sugestões de palavras-chave para SEO alinhadas ao público e à oferta principal",
      "Evitar termos técnicos de marketing sem explicação, deixando claro o impacto prático de cada melhoria"
    ],
    "processo_dados_faltando": "Se o público-alvo ou o CTA não forem fornecidos, inferir com base no conteúdo atual do site e deixar claro que as recomendações são genéricas. Se a URL não for fornecida, criar um checklist genérico de boas práticas para a página principal, que o usuário possa aplicar ao próprio site.",
    "formato_saida": "Lista numerada de recomendações de otimização, agrupadas em seções: 1) Copy/Texto (título, subtítulo, benefícios, provas sociais), 2) Design/UX (hierarquia visual, espaçamento, elementos de confiança), 3) Chamadas para Ação (CTAs) com exemplos de textos, 4) SEO (palavras-chave, meta descrição, headings)."
  },
  {
    "title": "Planejar uma campanha de email marketing para reativar clientes inativos.",
    "papel": "Especialista em Email Marketing para PMEs",
    "contexto": "A empresa possui uma base de clientes que não compram há mais de 6 meses e sabe que está deixando receita na mesa. É preciso criar uma campanha de reativação respeitosa, focada em valor e não apenas em desconto.",
    "lista_verificacao_inicial": [
      "Tamanho da base de clientes inativos (número aproximado)",
      "Tempo médio de inatividade (ex: >6 meses, >12 meses)",
      "Histórico de compras desses clientes (se disponível, ao menos categorias ou valores médios)",
      "Oferta especial, benefício ou motivo relevante para reativação (ex: novidade, condição especial, conteúdo útil)"
    ],
    "objetivo": "Criar uma sequência de 3 emails automatizados para reativar clientes inativos, combinando resgate de relacionamento, proposta de valor e, se fizer sentido, uma oferta específica para estimular o retorno.",
    "restricoes": [
      "Os emails devem ser pessoais e focados em valor (relacionamento, benefício, utilidade), não apenas em empurrar vendas",
      "A sequência deve se estender por aproximadamente 2 semanas, com espaçamento adequado entre os envios",
      "Incluir assunto (subject) e corpo do email, com tom amigável e respeitoso à base de clientes",
      "Evitar excesso de pressão ou linguagem de urgência exagerada"
    ],
    "processo_dados_faltando": "Se o histórico de compras não estiver disponível, criar emails mais genéricos focados nos benefícios gerais de ser cliente e em novidades relevantes. Se nenhuma oferta especial for definida, sugerir uma (exemplo: cupom de X% na próxima compra, bônus em produto específico ou brinde) e deixar claro que é apenas uma sugestão.",
    "formato_saida": "Tabela em Markdown com colunas: 'Email (1, 2, 3)', 'Dia do Envio (ex: Dia 1, Dia 7, Dia 14)', 'Assunto (Sugestão)', 'Objetivo do Email' e 'Corpo do Email (texto completo)'."
  },
  {
    "title": "Definir a persona ideal do cliente (Buyer Persona).",
    "papel": "Estrategista de Conteúdo para PMEs",
    "contexto": "Falta clareza sobre para quem a empresa está vendendo, o que faz com que campanhas de marketing e ações comerciais tenham baixa efetividade e mensagens genéricas demais.",
    "lista_verificacao_inicial": [
      "Dados demográficos básicos de clientes atuais (ex: idade, região, tipo de empresa, cargo)",
      "Principais desafios/problemas que o produto/serviço resolve na prática",
      "Motivos pelos quais clientes escolhem a empresa em vez de concorrentes (quando houver essa percepção)",
      "Onde a persona costuma buscar informação (ex: redes sociais, Google, grupos, eventos, indicação)"
    ],
    "objetivo": "Criar um perfil semi-fictício e detalhado de 1 a 2 personas principais, incluindo dados demográficos, psicográficos, desafios, objetivos, objeções comuns e como a empresa pode ajudar cada uma delas.",
    "restricoes": [
      "A persona deve ser o mais realista possível e, sempre que possível, baseada em dados reais de clientes, não apenas em suposições",
      "Dar um nome e uma breve descrição visual à persona (ex: como se fosse uma ficha de personagem) para facilitar a comunicação interna",
      "Evitar exageros caricatos; focar em comportamentos, dores e objetivos concretos"
    ],
    "processo_dados_faltando": "Se dados demográficos ou de desafios estiverem faltando, construir uma persona provisória baseada no uso típico do produto/serviço e em boas práticas de mercado, sinalizando claramente que é uma hipótese inicial a ser validada com clientes reais.",
    "formato_saida": "Texto estruturado para cada persona com: 1) Nome e breve descrição visual (Foto fictícia / estilo), 2) Demografia (idade, região, cargo/tipo de negócio), 3) Contexto de vida/trabalho, 4) Desafios Principais, 5) Objetivos de Negócio ou Pessoais ligados ao produto/serviço, 6) Onde Busca Informação, 7) Como nosso produto/serviço ajuda, 8) Principais Objeções e como podem ser trabalhadas na comunicação."
  },
  {
    "title": "Analisar o funil de vendas e identificar gargalos.",
    "papel": "Analista de Vendas para PMEs",
    "contexto": "A empresa gera leads, mas converte poucos em clientes e não sabe em qual etapa do funil está perdendo mais oportunidades. É preciso visualizar o funil de forma simples e encontrar os principais gargalos.",
    "lista_verificacao_inicial": [
      "Número de leads gerados por mês (ou período escolhido)",
      "Número de oportunidades qualificadas geradas a partir desses leads",
      "Número de propostas enviadas (se for uma etapa relevante para o negócio)",
      "Número de vendas fechadas no período",
      "Duração média do ciclo de vendas (da entrada do lead até o fechamento)"
    ],
    "objetivo": "Calcular as taxas de conversão entre as principais etapas do funil e apontar a etapa com maior perda de oportunidades, sugerindo possíveis causas e ações para melhoria.",
    "restricoes": [
      "Considerar um funil simples de 3 a 4 etapas, conforme os dados: ex: Lead → Oportunidade → Proposta → Cliente",
      "Calcular as taxas em percentual, explicando de forma clara (ex: de cada 100 leads, quantos viram clientes)",
      "Evitar excesso de termos técnicos; priorizar compreensão pelo gestor",
      "Sempre relacionar os resultados com possíveis ações (ex: melhorar qualificação, ajustar oferta, revisar script)"
    ],
    "processo_dados_faltando": "Se os números de uma etapa estiverem faltando, pular a análise dessa taxa específica e explicar a limitação, focando nas etapas que têm dados. Se nenhum número for fornecido, descrever o modelo ideal de funil de vendas para o tipo de negócio e sugerir quais dados passar a registrar.",
    "formato_saida": "1) Tabela em Markdown representando o funil, com colunas: 'Etapa', 'Quantidade', 'Taxa de Conversão para a próxima etapa', 2) Breve texto identificando o(s) gargalo(s) principal(is) e 3) Lista de possíveis causas e ações recomendadas para testar melhorias na etapa mais crítica."
  },
  {
    "title": "Criar um relatório de desempenho de marketing (Dashboard).",
    "papel": "Gestor de Marketing para PMEs",
    "contexto": "A empresa precisa visualizar em um só lugar o desempenho das principais campanhas e canais de marketing, para saber o que está funcionando e onde ajustar, sem depender de relatórios complexos.",
    "lista_verificacao_inicial": [
      "Dados de tráfego do site (ex: sessões, páginas mais acessadas, origem do tráfego)",
      "Dados de conversões (ex: leads gerados, formulários preenchidos, vendas realizadas)",
      "Dados de mídias sociais (ex: alcance, engajamento, crescimento de seguidores)",
      "Investimento em cada canal (se aplicável, mesmo que aproximado)",
      "Período de análise (ex: último mês, último trimestre)"
    ],
    "objetivo": "Compilar os dados em uma visão de dashboard resumido, destacando os KPIs mais importantes por canal e consolidado, permitindo comparações simples (ex: orgânico vs. pago, canal A vs. canal B) e tomada de decisão ágil.",
    "restricoes": [
      "Incluir apenas os 5 a 7 KPIs mais importantes para evitar sobrecarga de informação",
      "Calcular o ROI ou, se não for possível, métricas de eficiência por canal (ex: custo por lead, custo por venda) quando houver dados de investimento",
      "Manter uma linguagem acessível, explicando o que significa cada KPI",
      "Organizar o dashboard de forma que possa ser facilmente replicado em planilha ou ferramenta de BI simples"
    ],
    "processo_dados_faltando": "Se dados de um canal específico estiverem faltando, omiti-lo do cálculo comparativo e apenas sinalizar essa ausência. Se os dados de investimento não estiverem disponíveis, focar em métricas de desempenho não financeiras (ex: taxa de conversão, crescimento percentual, CPL orgânico estimado vs. pago, se viável).",
    "formato_saida": "Tabela consolidada em Markdown com linhas para cada canal (e uma linha de total/geral) e colunas com os principais KPIs (ex: Sessões, Leads, Vendas, Taxa de Conversão, Investimento, Custo por Lead, ROI – quando aplicável). Em seguida, um breve comentário destacando 3 insights principais (ex: canal com melhor desempenho, canal que consome muito recurso com pouco resultado, oportunidade de teste A/B)."
  }
],

/* ---------- OPERAÇÕES ---------- */
operacoes: [
  {
    "title": "Mapear e otimizar o fluxo de valor de um processo operacional específico.",
    "papel": "Consultor em Lean/Fluxos Operacionais para PMEs",
    "contexto": "Existem gargalos, retrabalho ou desperdícios em um processo produtivo ou de prestação de serviço que impactam o tempo de entrega, a qualidade e o custo. A empresa quer visualizar o fluxo de ponta a ponta e identificar pontos de melhoria.",
    "lista_verificacao_inicial": [
      "Descrição passo a passo do processo atual (do início ao fim)",
      "Tempo aproximado gasto em cada etapa (mesmo que estimado)",
      "Identificação de etapas que não agregam valor percebido pelo cliente (ex: esperas, retrabalhos, movimentações desnecessárias)",
      "Volume médio processado (ex: pedidos/dia, ordens de produção/dia, atendimentos/dia)"
    ],
    "objetivo": "Criar um mapa de fluxo de valor (Value Stream Mapping) atual em formato textual e propor um fluxo futuro otimizado, com foco em reduzir lead time, desperdícios e excesso de variação, sem perder qualidade.",
    "restricoes": [
      "Identificar claramente onde ocorrem os 7 tipos de desperdício (superprodução, espera, transporte, excesso de processamento, estoque, movimento, defeitos), explicando em linguagem simples",
      "Focar na redução do tempo total do ciclo (lead time), mas também comentar impacto em qualidade e custo quando fizer sentido",
      "Evitar desenhar um futuro ideal inalcançável; propor melhorias realistas para a realidade da empresa",
      "Sugerir priorização das melhorias em 'ganhos rápidos' e melhorias de médio prazo"
    ],
    "processo_dados_faltando": "Se os tempos das etapas não forem fornecidos, trabalhar com uma estimativa qualitativa (lento/médio/rápido) e indicar onde coletar dados depois. Se a identificação de etapas sem valor não estiver clara, listar as etapas e sugerir perguntas para o usuário classificar o que agrega e o que não agrega valor.",
    "formato_saida": "1) Fluxograma textual do estado atual usando setas e colchetes (ex: [Etapa 1] -> [Etapa 2] -> ...), 2) Lista dos principais desperdícios identificados com exemplos, 3) Fluxograma textual do estado futuro proposto, 4) Lista de melhorias recomendadas, priorizadas em curto, médio e longo prazo."
  },
  {
    "title": "Calcular os indicadores-chave de desempenho (KPIs) para a operação.",
    "papel": "Gestor de Operações para PMEs",
    "contexto": "A empresa não possui métricas claras para avaliar a eficiência, a qualidade e a pontualidade da área operacional, dificultando decisões e conversas com a equipe.",
    "lista_verificacao_inicial": [
      "Dados de produção (ex: unidades produzidas ou atendimentos concluídos por período)",
      "Dados de qualidade (ex: unidades defeituosas, retrabalho, devoluções de clientes)",
      "Dados de entrega (ex: prazo acordado vs. prazo efetivo, número de entregas no prazo)",
      "Capacidade teórica ou máxima dos recursos (máquinas, equipes, turnos), se disponível"
    ],
    "objetivo": "Definir e calcular os principais KPIs operacionais adequados à realidade da empresa, como Eficiência, Taxa de Defeitos, Taxa de Entrega no Prazo e, se fizer sentido, OEE (Eficiência Global dos Equipamentos), explicando o que cada indicador significa.",
    "restricoes": [
      "Os KPIs devem ser simples de calcular e acompanhar em planilha ou sistema básico",
      "Fornecer a fórmula de cálculo de cada KPI e um exemplo com os dados informados",
      "Evitar um excesso de indicadores; focar nos 3 a 5 mais relevantes para o negócio",
      "Explicar em linguagem acessível o que é considerado 'bom', 'atenção' e 'ruim' quando possível"
    ],
    "processo_dados_faltando": "Se dados específicos para um KPI estiverem faltando, calcular apenas os demais indicadores possíveis e listar quais dados seriam necessários para completar o cálculo. Se não houver dados suficientes para cálculo de nenhum KPI, sugerir um plano simples de coleta de dados para iniciar o controle.",
    "formato_saida": "Tabela em Markdown com colunas: 'Nome do KPI', 'Fórmula', 'Cálculo com os dados fornecidos', 'Valor Final' e 'Interpretação (resumida)'."
  },
  {
    "title": "Otimizar os níveis de estoque para reduzir custos sem causar ruptura.",
    "papel": "Gestor de Estoques para PMEs",
    "contexto": "A empresa sofre com capital imobilizado em estoque excessivo ou com frequente falta de itens críticos para produção ou vendas, gerando perda de vendas e estresse na operação.",
    "lista_verificacao_inicial": [
      "Lista de itens de estoque com demanda média mensal (ou por período relevante)",
      "Tempo de reposição (lead time) dos principais fornecedores para esses itens",
      "Se possível, classificação ABC atual ou percepção de quais itens são mais críticos (A), intermediários (B) e menos críticos (C)",
      "Custo de pedido e custo de posse do estoque (mesmo que estimados), se disponíveis"
    ],
    "objetivo": "Calcular, de forma prioritária para itens críticos (classe A), o estoque de segurança, o ponto de pedido e, se os dados permitirem, o lote econômico de compra (EOQ), ajudando a equilibrar nível de serviço e custo de estoque.",
    "restricoes": [
      "Aplicar a lógica de classificação ABC para priorizar a análise dos itens mais importantes",
      "Usar fórmulas padrão de gestão de estoque, explicando em linguagem simples o significado de cada uma",
      "Evitar excesso de complexidade se a empresa estiver começando o controle; sugerir implantação por etapas",
      "Deixar claro que os parâmetros devem ser revisados periodicamente (ex: a cada 6 meses ou sempre que houver mudança relevante na demanda)"
    ],
    "processo_dados_faltando": "Se o custo de pedido ou de posse não for conhecido, focar inicialmente no cálculo de estoque de segurança e ponto de pedido, que dependem principalmente da demanda e do lead time, explicando que o lote econômico ficará para uma segunda etapa. Se a demanda não for fornecida, descrever o método de cálculo e sugerir ao usuário que levante os dados antes de aplicar os números.",
    "formato_saida": "Tabela em Markdown para os itens críticos (classe A) com colunas: 'Item', 'Demanda Média', 'Lead Time', 'Estoque de Segurança (se calculado)', 'Ponto de Pedido', 'Lote Econômico (se calculado)' e observações relevantes."
  },
  {
    "title": "Selecionar o melhor fornecedor com base em critérios múltiplos.",
    "papel": "Comprador / Gestor de Suprimentos para PMEs",
    "contexto": "A empresa precisa escolher um novo fornecedor para um componente, insumo ou serviço importante, equilibrando preço, qualidade, prazo e outros critérios relevantes (ex: prazo de pagamento, atendimento, localização).",
    "lista_verificacao_inicial": [
      "Lista de fornecedores candidatos com nome e contato",
      "Cotação de preços de cada fornecedor para o item/serviço em questão",
      "Informações sobre qualidade (ex: índice de defeitos, referências, certificações) e prazo de entrega",
      "Se possível, informações sobre condições comerciais (prazo de pagamento, política de devolução, suporte)"
    ],
    "objetivo": "Aplicar uma matriz de decisão ponderada para ranquear os fornecedores com base em critérios-chave e recomendar o mais adequado, deixando claro o racional da escolha.",
    "restricoes": [
      "Atribuir pesos aos critérios principais (exemplo padrão: Preço: 40%, Qualidade: 35%, Prazo: 25%), ajustando conforme informações do usuário",
      "A pontuação final deve ser a soma ponderada das notas atribuídas em cada critério para cada fornecedor",
      "Utilizar uma escala simples de notas (ex: 1 a 5) para facilitar o entendimento e aplicação em planilha",
      "Deixar claro que a análise é um apoio à decisão e não substitui visitas técnicas ou testes, quando forem críticos"
    ],
    "processo_dados_faltando": "Se informações de qualidade ou prazo de um fornecedor estiverem faltando, atribuir uma nota conservadora (por exemplo, a menor nota da escala) para aquele critério, sinalizando explicitamente a incerteza. Se os pesos não forem fornecidos, usar os pesos padrão da restrição como exemplo inicial.",
    "formato_saida": "Tabela em Markdown com colunas: 'Fornecedor', 'Nota Preço', 'Nota Qualidade', 'Nota Prazo', 'Outros Critérios (se houver)', 'Nota Ponderada Final' e 'Comentário'. Ao final, uma recomendação do fornecedor selecionado com breve justificativa."
  },
  {
    "title": "Elaborar um plano de manutenção preventiva para equipamentos.",
    "papel": "Coordenador de Manutenção para PMEs",
    "contexto": "Paradas não planejadas de máquinas ou equipamentos estão causando atrasos na operação, horas extras e aumento de custos com consertos emergenciais. A empresa quer ser mais preventiva e menos reativa.",
    "lista_verificacao_inicial": [
      "Lista de equipamentos críticos para a operação (aqueles cuja parada impacta mais o negócio)",
      "Histórico de falhas ou manutenções corretivas mais relevantes (tipo de falha, frequência aproximada)",
      "Recomendações do fabricante para manutenção (se disponíveis)",
      "Capacidade da equipe (quem pode executar as manutenções e em que horários)"
    ],
    "objetivo": "Criar um cronograma de manutenção preventiva para os equipamentos críticos, especificando periodicidade, tarefas principais, janelas de execução e responsáveis, de forma que possa ser colocado em prática pela equipe.",
    "restricoes": [
      "Focar inicialmente nos equipamentos com maior impacto na operação em caso de falha",
      "Definir tarefas simples, objetivas e verificáveis (ex: inspecionar, lubrificar, apertar, testar)",
      "Evitar plano utópico que a equipe não consiga executar dentro da rotina",
      "Sugerir formas de registrar a execução (checklist em papel, planilha, sistema simples)"
    ],
    "processo_dados_faltando": "Se o histórico de falhas não estiver disponível, criar um plano genérico baseado nas recomendações do fabricante e em boas práticas (por horas de uso ou tempo, ex: mensal, trimestral). Se as recomendações do fabricante não forem conhecidas, assumir uma periodicidade conservadora e sugerir que o gestor busque essa informação para ajustes futuros.",
    "formato_saida": "Tabela em Markdown com colunas: 'Equipamento', 'Tarefa de Manutenção', 'Periodicidade (ex: mensal, trimestral)', 'Responsável' e 'Observações'."
  },
  {
    "title": "Analisar a capacidade produtiva atual e futura da empresa.",
    "papel": "Planejador de Capacidade para PMEs",
    "contexto": "A empresa recebeu uma grande encomenda ou está planejando crescer e precisa saber se a capacidade atual é suficiente para atender a nova demanda sem prejudicar os clientes atuais.",
    "lista_verificacao_inicial": [
      "Capacidade produtiva máxima atual (unidades por período, horas disponíveis, atendimentos possíveis, conforme o tipo de negócio)",
      "Utilização média atual da capacidade (percentual aproximado)",
      "Volume da nova demanda (quantidade e prazo para atendimento)",
      "Possibilidades de ajuste de curto prazo (ex: horas extras, segundo turno, terceirização parcial)"
    ],
    "objetivo": "Calcular a capacidade ociosa atual e verificar se ela é suficiente para absorver a nova demanda dentro do prazo, propondo ações alternativas se não for possível com a configuração atual.",
    "restricoes": [
      "Considerar a capacidade produtiva como relativamente fixa no curtíssimo prazo (estruturas, máquinas, equipe atual), a menos que seja informado o contrário",
      "Apresentar os cálculos de forma simples, em termos de unidades ou horas disponíveis vs. necessárias",
      "As ações propostas devem ser realistas para PMEs (ex: reorganização de turnos, horas extras controladas, terceirização pontual, reprogramação de pedidos)",
      "Evitar assumir ganhos milagrosos de produtividade sem mudanças claras no processo"
    ],
    "processo_dados_faltando": "Se a utilização média não for fornecida, assumir um valor de referência (ex: 80%) e deixar claro que se trata de hipótese. Se a capacidade máxima não for conhecida, descrever como o usuário pode calculá-la (ex: horas de turno × número de recursos × eficiência aproximada) antes de fazer uma análise mais precisa.",
    "formato_saida": "Texto estruturado com: 1) Cálculos de capacidade atual (teórica e utilizada), 2) Comparação entre capacidade disponível e demanda adicional, 3) Conclusão sobre viabilidade de atender a nova demanda, 4) Lista numerada de ações recomendadas (ex: adequações de turno, priorização de pedidos, terceirização)."
  },
  {
    "title": "Avaliar e mitigar riscos operacionais.",
    "papel": "Gestor de Riscos Operacionais para PMEs",
    "contexto": "Há preocupação com eventos que possam interromper a operação (ex: quebra de equipamento, falta de matéria-prima, ausência de pessoas-chave, falhas de sistemas), mas não existe um mapa formal de riscos.",
    "lista_verificacao_inicial": [
      "Lista de processos operacionais críticos (produção, logística, atendimento, TI, etc.)",
      "Possíveis modos de falha relevantes para cada processo (mesmo que em brainstorm inicial)",
      "Impacto estimado de cada risco (ex: financeiro, atraso, imagem) e probabilidade aproximada",
      "Se possível, histórico de incidentes relevantes ocorridos nos últimos anos"
    ],
    "objetivo": "Criar uma matriz de risco (Impacto × Probabilidade) simples, classificar os riscos em faixas (baixo, médio, alto) e elaborar um plano de ação para os riscos de maior relevância.",
    "restricoes": [
      "Classificar os riscos de forma qualitativa (ex: Baixo, Médio, Alto) para facilitar entendimento",
      "Priorizar ações de mitigação práticas e de curto/médio prazo, sem exigir grandes investimentos inicialmente",
      "Evitar linguagem excessivamente técnica de gestão de riscos; focar em clareza para o dia a dia da empresa",
      "Diferenciar ações de prevenção (evitar que o risco ocorra) e ações de contingência (o que fazer se ocorrer)"
    ],
    "processo_dados_faltando": "Se a lista de riscos não for fornecida, gerar uma lista de riscos genéricos comuns em PMEs (ex: dependência de um único fornecedor, falta de backup de dados, dependência de uma pessoa-chave, falhas elétricas, problemas logísticos). Se impacto/probabilidade não forem fornecidos, listar os riscos e propor critérios simples para o usuário classificá-los.",
    "formato_saida": "Tabela em Markdown com colunas: 'Risco', 'Impacto (Baixo/Médio/Alto)', 'Probabilidade (Baixa/Média/Alta)', 'Classificação Geral', 'Ação de Mitigação Principal'. Em seguida, breve texto comentando os 3 a 5 riscos mais críticos e as ações prioritárias."
  },
  {
    "title": "Projetar o layout físico de uma nova instalação (fábrica, armazém ou escritório).",
    "papel": "Planejador de Layout Operacional",
    "contexto": "A empresa está mudando para um novo espaço ou reorganizando o espaço atual e quer melhorar o fluxo de pessoas, materiais e informações, reduzindo deslocamentos desnecessários e cruzamentos confusos.",
    "lista_verificacao_inicial": [
      "Dimensões aproximadas do espaço disponível (largura, comprimento, divisões principais)",
      "Lista de áreas/departamentos/equipamentos que precisam ser alocados (ex: recebimento, estoque, produção, expedição, escritórios, atendimento)",
      "Fluxo básico de materiais ou pessoas entre as áreas (quem precisa ficar perto de quem)",
      "Restrições físicas ou legais (ex: colunas, portas, banheiros, saídas de emergência)"
    ],
    "objetivo": "Propor um layout em blocos que minimize a distância percorrida em processos críticos e reduza cruzamentos desnecessários, mantendo segurança, conforto e lógica operacional.",
    "restricoes": [
      "Representar o layout de forma simplificada (em blocos), sem buscar proporções milimétricas",
      "Considerar a proximidade entre áreas com alto fluxo mútuo (ex: recebimento perto do estoque, estoque perto da produção, produção perto da expedição)",
      "Evitar soluções que violem normas de segurança ou fluxos básicos (ex: bloquear saídas de emergência)",
      "Prever possíveis áreas de expansão futura, quando fizer sentido"
    ],
    "processo_dados_faltando": "Se as dimensões não forem fornecidas, criar um layout conceitual relativo, indicando apenas a ordem e proximidade dos blocos. Se o fluxo entre áreas não for especificado, assumir um fluxo linear simples (ex: Recebimento → Estoque → Produção/Operação → Expedição/Atendimento) e sugerir que o usuário ajuste conforme a realidade.",
    "formato_saida": "Diagrama em texto (usando caracteres como #, |, - e nomes de áreas entre colchetes) representando o layout proposto em linhas, acompanhado de uma legenda explicando cada bloco e uma breve justificativa para as principais decisões de disposição."
  },
  {
    "title": "Implementar um sistema de controle de qualidade baseado em inspeção por amostragem.",
    "papel": "Inspetor de Qualidade para PMEs",
    "contexto": "A empresa precisa controlar a qualidade de lotes recebidos de fornecedores ou da produção interna, mas não tem condição de inspecionar 100% dos itens. É necessário um plano de amostragem simples e confiável.",
    "lista_verificacao_inicial": [
      "Tamanho do lote a ser inspecionado (ou faixa típica de tamanho)",
      "Nível de Qualidade Aceitável (NQA) desejado, mesmo que aproximado",
      "Nível de inspeção desejado (normal, rigorosa ou reduzida), se definido",
      "Se possível, histórico de qualidade dos últimos lotes (percentual de defeitos típico)"
    ],
    "objetivo": "Determinar um plano de amostragem simples: tamanho da amostra (n) e número de aceitação (c), usando como referência princípios da ISO 2859-1 (ou MIL-STD-105E) ou tabelas simplificadas, e explicar como aplicar a regra de decisão.",
    "restricoes": [
      "Utilizar uma abordagem simplificada e didática, sem exigir acesso a tabelas completas oficiais",
      "Assumir inspeção normal se o nível de inspeção não for especificado",
      "Explicar a lógica por trás da amostragem (por que não é necessário inspecionar 100% em todos os casos)",
      "Evitar linguagem estatística pesada; focar em explicação prática"
    ],
    "processo_dados_faltando": "Se o NQA não for fornecido, utilizar um valor padrão (ex: 1,0% ou 2,5%, conforme o tipo de produto) apenas como referência, deixando claro que deve ser ajustado ao contexto. Se o tamanho do lote não for informado, descrever conceitualmente como definir tamanho de amostra e número de aceitação, sem chegar a valores específicos.",
    "formato_saida": "Texto estruturado com: 1) Descrição do plano de amostragem proposto (Tamanho do Lote N, Tamanho da Amostra n, Número de Aceitação c), 2) Regra de decisão clara (ex: aceitar o lote se encontrar 'c' ou menos defeitos na amostra; rejeitar se encontrar mais que 'c'), 3) Breve explicação de quando revisar o plano (ex: se o histórico de qualidade melhorar ou piorar)."
  },
  {
    "title": "Analisar a cadeia de suprimentos (supply chain) para identificar vulnerabilidades.",
    "papel": "Estrategista de Cadeia de Suprimentos para PMEs",
    "contexto": "Eventos recentes (ex: pandemia, problemas logísticos, instabilidade de fornecedores) mostraram que a cadeia de suprimentos é frágil. A empresa quer mapear riscos e pensar em alternativas para ficar menos exposta.",
    "lista_verificacao_inicial": [
      "Lista dos principais fornecedores (matérias-primas, produtos para revenda, serviços críticos)",
      "Localização geográfica dos fornecedores (cidade, região, país)",
      "Itens com único fornecedor (single source) e itens com alternativas já mapeadas",
      "Tempo de reposição (lead time) típico por fornecedor ou por grupo de itens",
      "Se possível, histórico de falhas de abastecimento (atrasos, rupturas, problemas de qualidade)"
    ],
    "objetivo": "Mapear os elos principais da cadeia de suprimentos, identificar pontos de maior vulnerabilidade (concentração de risco, lead time longo, itens sem backup) e propor ações para diversificar, reduzir exposição e aumentar a resiliência.",
    "restricoes": [
      "Focar nos itens realmente críticos para a operação (sem os quais a empresa para ou perde vendas importantes)",
      "Destacar vulnerabilidades em termos simples (ex: dependência de um único fornecedor em outra região/país)",
      "Propor ações de médio/longo prazo factíveis para PMEs (ex: buscar segundo fornecedor, aumentar estoque de segurança de item estratégico, negociar acordos diferentes)",
      "Evitar recomendações genéricas sem conexão com os dados fornecidos"
    ],
    "processo_dados_faltando": "Se a lista de fornecedores não for detalhada, trabalhar com uma análise qualitativa dos riscos mais comuns em cadeias de suprimentos de PMEs (ex: concentração de fornecedores, distância logística, dependência de um único transporte). Se a localização não for fornecida, assumir que há risco de concentração em uma única região e destacar isso como ponto de atenção a ser detalhado pelo usuário.",
    "formato_saida": "Texto estruturado com: 1) Mapeamento da Cadeia (lista de itens críticos e seus fornecedores principais), 2) Identificação de Vulnerabilidades em formato de lista (ex: single source, lead time longo, risco geográfico), 3) Plano de Ação para Resiliência com lista numerada de ações sugeridas, priorizando as mais impactantes e viáveis."
  }
],

  /* ---------- RH ---------- */
"rh": [
  {
    "title": "Criar descrições de cargo detalhadas e atraentes.",
    "papel": "Recrutador Sênior especializado em PMEs",
    "contexto": "A empresa precisa contratar e percebe que as descrições de cargo atuais são superficiais, repetitivas ou pouco claras. Isso atrai candidatos inadequados e gera retrabalho no processo seletivo.",
    "lista_verificacao_inicial": [
      "Título do cargo",
      "Departamento e liderança direta",
      "Principais responsabilidades e entregas esperadas",
      "Competências técnicas e comportamentais necessárias"
    ],
    "objetivo": "Elaborar uma descrição de cargo completa, clara e atrativa, que ajude a empresa a comunicar o que realmente espera do profissional e que sirva tanto para atração quanto para alinhamento interno.",
    "restricoes": [
      "Usar linguagem inclusiva, humana e simples",
      "Classificar requisitos em obrigatórios e desejáveis",
      "Evitar frases vagas como 'ser proativo' sem explicar o comportamento esperado"
    ],
    "processo_dados_faltando": "Se responsabilidades ou requisitos não forem informados, criar uma descrição base alinhada ao título informado e sinalizar que deve ser validada. Se não houver liderança direta definida, manter como campo aberto.",
    "formato_saida": "Texto estruturado em: Título, Missão do Cargo, Principais Responsabilidades (lista), Requisitos e Qualificações (obrigatórios/desejáveis) e Benefícios."
  },
  {
    "title": "Estruturar um plano de onboarding para novos colaboradores.",
    "papel": "Coordenador de RH com foco em Integração",
    "contexto": "A empresa sofre com rotatividade nos primeiros meses de contratação, indicando falhas no processo de integração. Não há uma rotina estruturada para recepção, alinhamento de expectativas e acompanhamento do novo colaborador.",
    "lista_verificacao_inicial": [
      "Cargo do novo colaborador",
      "Departamento",
      "Checklist de documentos, acessos e ferramentas necessárias"
    ],
    "objetivo": "Criar um plano de onboarding que garanta uma integração clara, acolhedora e organizada, incluindo atividades com RH, gestor e equipe durante a primeira semana.",
    "restricoes": [
      "O plano deve ser realista e exequível para uma PME",
      "Incluir aspectos burocráticos, técnicos e culturais",
      "Evitar sobrecarga de informações no primeiro dia"
    ],
    "processo_dados_faltando": "Se cargo e departamento não forem informados, criar um plano genérico. Se o checklist não existir, usar uma base padrão (documentação, e-mail, acessos, ferramentas).",
    "formato_saida": "Tabela em Markdown com: Dia, Horário, Atividade, Responsável e Observações."
  },
  {
    "title": "Definir um plano de desenvolvimento de carreira para um colaborador.",
    "papel": "Gestor de Talentos com foco em PMEs",
    "contexto": "Um colaborador com bom desempenho deseja crescer e a empresa quer reter talento, mas ainda não existe um plano claro de desenvolvimento individual ou de progressão de carreira.",
    "lista_verificacao_inicial": [
      "Cargo atual e principais habilidades do colaborador",
      "Objetivos de carreira do colaborador",
      "Competências futuras necessárias para o negócio"
    ],
    "objetivo": "Construir um PDI com metas claras, ações de desenvolvimento e critérios de evolução para curto e médio prazo.",
    "restricoes": [
      "O plano deve ser realista e conectado às oportunidades reais da empresa",
      "Definir prazos e entregáveis mensuráveis",
      "Evitar metas vagas ou genéricas demais"
    ],
    "processo_dados_faltando": "Se os objetivos do colaborador não forem especificados, focar em fortalecer competências essenciais e preparar para crescimento interno. Se as necessidades da empresa não forem informadas, considerar habilidades transferíveis e liderança.",
    "formato_saida": "Texto estruturado em: Objetivo de Carreira, Metas (Curto/Médio Prazo), Ações de Desenvolvimento e Métricas de Sucesso."
  },
  {
    "title": "Estruturar uma pesquisa de clima organizacional.",
    "papel": "Especialista em Cultura e Engajamento",
    "contexto": "A empresa percebe queda na motivação e sinais de desgaste, mas não tem dados estruturados sobre satisfação, comunicação, liderança e ambiente.",
    "lista_verificacao_inicial": [
      "Tamanho da empresa",
      "Áreas de preocupação suspeitas",
      "Objetivo da pesquisa (diagnóstico geral ou tema específico)"
    ],
    "objetivo": "Criar uma pesquisa de clima com perguntas fechadas e abertas que ajude a empresa a entender pontos fortes e fragilidades.",
    "restricoes": [
      "Garantir anonimato total",
      "Incluir 15 a 20 perguntas objetivas",
      "Agrupar por temas claros"
    ],
    "processo_dados_faltando": "Se não houver áreas de preocupação definidas, usar temas padrão como Liderança, Comunicação, Reconhecimento, Ambiente e Crescimento. Se objetivo não estiver claro, assumir diagnóstico geral.",
    "formato_saida": "Lista organizada por dimensões, com perguntas em escala de 1 a 5 e 2 a 3 perguntas abertas."
  },
  {
    "title": "Criar uma política de remuneração e benefícios competitiva.",
    "papel": "Consultor de Remuneração para PMEs",
    "contexto": "A empresa enfrenta dificuldade para atrair ou reter talentos, e não possui uma política clara de remuneração, faixas salariais ou benefícios estruturados.",
    "lista_verificacao_inicial": [
      "Lista de cargos e faixas atuais",
      "Pesquisa salarial de mercado (se houver)",
      "Orçamento disponível"
    ],
    "objetivo": "Desenhar uma política clara, justa e competitiva de cargos, salários e benefícios.",
    "restricoes": [
      "Manter equilíbrio entre equidade interna e competitividade externa",
      "Considerar benefícios de alto valor percebido e baixo custo",
      "Evitar estruturas complexas que PMEs não conseguem manter"
    ],
    "processo_dados_faltando": "Se não houver pesquisa de mercado, corrigir distorções internas. Se não houver orçamento, priorizar benefícios não monetários.",
    "formato_saida": "Texto com: Princípios, Estrutura de Cargos e Salários (exemplo em tabela) e Benefícios Propostos."
  },
  {
    "title": "Elaborar um programa de reconhecimento de funcionários.",
    "papel": "Gestor de Engajamento e Cultura",
    "contexto": "Os colaboradores sentem que seus esforços não são percebidos, gerando queda na motivação e no senso de pertencimento.",
    "lista_verificacao_inicial": [
      "Valores e cultura desejada",
      "Orçamento disponível",
      "Comportamentos que se quer incentivar"
    ],
    "objetivo": "Criar um programa simples, justo e motivador de reconhecimento.",
    "restricoes": [
      "Ser inclusivo e acessível a todos",
      "Valorizar reconhecimento não financeiro quando possível",
      "Evitar premiações que incentivem competição tóxica"
    ],
    "processo_dados_faltando": "Se os valores não forem claros, trabalhar com comportamentos universais. Se não houver orçamento, focar em reconhecimento simbólico e público.",
    "formato_saida": "Texto com: Nome do Programa, Categorias, Critérios, Processo de Indicação e Recompensas."
  },
  {
    "title": "Conduzir análise de competências (gap analysis) da equipe.",
    "papel": "Responsável por Treinamento e Desenvolvimento",
    "contexto": "A empresa quer crescer, mas não sabe quais competências a equipe já domina e quais precisam ser desenvolvidas para suportar a estratégia.",
    "lista_verificacao_inicial": [
      "Lista de colaboradores e cargos",
      "Competências requeridas por cargo",
      "Nível atual de proficiência"
    ],
    "objetivo": "Mapear gaps por colaborador e consolidar prioridades críticas.",
    "restricoes": [
      "Classificar gaps em crítico, médio e baixo",
      "Priorizar o impacto no negócio"
    ],
    "processo_dados_faltando": "Se o nível atual não for informado, explicar como avaliar. Se competências não forem listadas, usar padrões por cargo.",
    "formato_saida": "Tabela em Markdown com: Competência, Criticidade, Gap Total e Ação Recomendada."
  },
  {
    "title": "Criar guia para conduzir feedbacks eficazes (1-on-1).",
    "papel": "Líder de Equipe orientado a desenvolvimento",
    "contexto": "Gestores têm dificuldade em dar feedback claro e construtivo, gerando conflitos, ruídos e baixa performance.",
    "lista_verificacao_inicial": [
      "Objetivo da reunião",
      "Contexto específico"
    ],
    "objetivo": "Fornecer um roteiro simples, seguro e repetível para conversas de feedback.",
    "restricoes": [
      "Seguir modelo Situation-Behavior-Impact (SBI)",
      "Incluir perguntas abertas"
    ],
    "processo_dados_faltando": "Se objetivo não for informado, usar reunião de alinhamento geral. Incluir seções para feedback positivo e corretivo.",
    "formato_saida": "Roteiro com: Abertura, Contexto (SBI), Discussão, Plano de Ação e Encerramento."
  },
  {
    "title": "Desenvolver política de trabalho remoto/híbrido.",
    "papel": "Especialista em Modelos Flexíveis de Trabalho",
    "contexto": "A empresa está adotando trabalho remoto/híbrido e precisa de regras claras para manter alinhamento, produtividade e engajamento.",
    "lista_verificacao_inicial": [
      "Modelo desejado",
      "Cargos elegíveis",
      "Preocupações da liderança"
    ],
    "objetivo": "Criar um documento que defina expectativas, responsabilidades e práticas de trabalho remoto/híbrido.",
    "restricoes": [
      "Focar em autonomia e entrega",
      "Definir padrões de comunicação"
    ],
    "processo_dados_faltando": "Se não houver modelo definido, sugerir opções. Se preocupações não forem informadas, trabalhar com produtividade e comunicação.",
    "formato_saida": "Texto com: Elegibilidade, Expectativas, Comunicação, Segurança da Informação e Suporte."
  },
  {
    "title": "Planejar programa de treinamento baseado nos gaps identificados.",
    "papel": "Coordenador de T&D",
    "contexto": "A análise de competências mostrou lacunas importantes, e a empresa precisa traduzi-las em um plano anual de treinamentos.",
    "lista_verificacao_inicial": [
      "Gaps priorizados",
      "Número de colaboradores",
      "Orçamento disponível"
    ],
    "objetivo": "Montar um plano anual de treinamento com temas, públicos, formatos e custos.",
    "restricoes": [
      "Priorizar treinamentos de maior impacto",
      "Considerar alternativas de baixo custo"
    ],
    "processo_dados_faltando": "Se gaps não forem listados, usar temas essenciais de PMEs. Se não houver orçamento, sugerir custos aproximados.",
    "formato_saida": "Tabela em Markdown com: Tema, Público, Modalidade, Cronograma e Custo."
  }
],

/* ---------- COMUNICAÇÃO & RELACIONAMENTO COM CLIENTE ---------- */
"comunicacao": [
  {
    "title": "Criar um script padronizado para o primeiro contato com leads qualificados.",
    "papel": "Especialista em Pré-vendas e Atendimento Proativo",
    "contexto": "A abordagem inicial da equipe é inconsistênte e longa demais, o que reduz a taxa de conexão e de agendamento de reuniões.",
    "lista_verificacao_inicial": [
      "Perfil da persona (segmento, cargo, dores principais)",
      "Proposta de valor",
      "Objeções comuns no primeiro contato",
      "Objetivo imediato do contato"
    ],
    "objetivo": "Criar um script direto, humano e leve que facilite o início da conversa, qualifique o lead e gere avanço no funil.",
    "restricoes": [
      "Máximo de 3 a 5 minutos em ligação ou 5 a 8 mensagens no WhatsApp",
      "Foco em criar interesse, não explicar tudo",
      "Perguntas simples que não gerem 'interrogatório'",
      "Frases adaptáveis ao canal (telefone/chat/email)"
    ],
    "processo_dados_faltando": "Se persona não estiver clara, gerar uma provisória. Se objeções não forem informadas, usar padrão B2B.",
    "formato_saida": "Roteiro com: Abertura, Validação inicial, 3-5 perguntas-chave, Mini-pitch e Chamada para Ação."
  },
  {
    "title": "Desenvolver templates de email para nutrição de leads.",
    "papel": "Copywriter de Relacionamento e Funil",
    "contexto": "A base de leads não recebe conteúdo consistente e só é acionada para vendas, o que reduz engajamento e conversão.",
    "lista_verificacao_inicial": [
      "Estágios do funil",
      "Principais dúvidas e objeções",
      "Materiais existentes",
      "Frequência ideal de contato"
    ],
    "objetivo": "Criar uma sequência de emails de nutrição que aqueçam a base e preparem o lead para a compra.",
    "restricoes": [
      "Cada email com um único objetivo",
      "Assuntos atraentes sem clickbait",
      "CTA sempre presente",
      "Tom humano e direto"
    ],
    "processo_dados_faltando": "Se não houver estágios definidos, usar consciência, consideração e decisão. Se não houver conteúdo, sugerir temas.",
    "formato_saida": "Tabela em Markdown com: Nº do Email, Estágio, Objetivo, Assunto, Estrutura e CTA."
  },
  {
    "title": "Criar protocolo de resposta a reclamações em redes sociais.",
    "papel": "Gestor de Crises em Mídias Sociais",
    "contexto": "Reclamações públicas geram desgaste e falta de padrão nas respostas prejudica a reputação da empresa.",
    "lista_verificacao_inicial": [
      "Canais onde aparecem reclamações",
      "Tipos de problemas mais comuns",
      "Prazos possíveis de resposta",
      "Autonomia de quem responde"
    ],
    "objetivo": "Criar um protocolo claro, rápido e empático para transformar reclamações em reconquista de confiança.",
    "restricoes": [
      "SLA realista",
      "Tom empático e profissional",
      "Responder publicamente e mover para privado",
      "Assumir responsabilidade quando apropriado"
    ],
    "processo_dados_faltando": "Se tipos de problemas não forem informados, usar categorias padrão. Se SLA não existir, sugerir 2h comercial.",
    "formato_saida": "Fluxo em texto + modelos de resposta pública + modelos privados."
  },
  {
    "title": "Criar script de pós-venda para reduzir churn.",
    "papel": "Especialista em Retenção de Clientes",
    "contexto": "Clientes compram e depois não recebem acompanhamento adequado, o que gera cancelamentos e baixa fidelização.",
    "lista_verificacao_inicial": [
      "Tipo de produto/serviço",
      "Principais dúvidas do pós-venda",
      "Canais de contato disponíveis",
      "Período crítico de adoção"
    ],
    "objetivo": "Criar um fluxo de acompanhamento pós-compra que aumente adesão, satisfação e retenção.",
    "restricoes": [
      "Pelo menos 3 pontos de contato",
      "Nada de comunicações forçadas ou de upsell precoce",
      "Perguntas abertas para diagnóstico",
      "Registrar aprendizados internamente"
    ],
    "processo_dados_faltando": "Se ciclo de uso não for informado, usar 24h, 7 dias e 30 dias. Se dúvidas não forem informadas, usar padrão.",
    "formato_saida": "Tabela com: Momento, Canal, Objetivo, Roteiro e Métrica."
  },
  {
    "title": "Otimizar respostas do chat/WhatsApp para aumentar conversão.",
    "papel": "Especialista em Conversão via Atendimento Digital",
    "contexto": "As conversas no chat morrem antes de avançar, e não há repertório de respostas rápidas que guiem o cliente.",
    "lista_verificacao_inicial": [
      "Perguntas frequentes",
      "Objeções recorrentes",
      "Horários de pico",
      "Ação desejada ao final"
    ],
    "objetivo": "Criar respostas rápidas e mini-roteiros que direcionem o cliente para o próximo passo.",
    "restricoes": [
      "Respostas curtas e claras",
      "Nada de paredes de texto",
      "Tom humano, não robótico",
      "Sempre incluir CTA"
    ],
    "processo_dados_faltando": "Se FAQs não forem informadas, usar padrões: preço, prazo, pagamento, suporte.",
    "formato_saida": "Tabela com: Situação, Resposta, Pergunta de aprofundamento e CTA."
  },
  {
    "title": "Desenvolver protocolo para recuperar clientes inativos.",
    "papel": "Estrategista de Reativação",
    "contexto": "A empresa ignora clientes antigos enquanto gasta energia só em novos leads, perdendo potenciais reativações.",
    "lista_verificacao_inicial": [
      "Tempo para considerar inativo",
      "Segmentos prioritários",
      "Histórico de compras",
      "Possibilidade de oferecer vantagens"
    ],
    "objetivo": "Criar um plano estruturado para reengajar clientes antigos com mensagens específicas e coleta de motivos da inatividade.",
    "restricoes": [
      "Evitar tom desesperado",
      "Personalizar quando possível",
      "Incluir pergunta de diagnóstico",
      "Fazer ao menos duas tentativas de contato"
    ],
    "processo_dados_faltando": "Se histórico não existir, segmentar apenas por tempo de inatividade. Se não houver orçamento para descontos, usar benefícios não monetários.",
    "formato_saida": "Plano em 3 fases com templates de mensagens."
  },
  {
    "title": "Criar manual de tom e voz da marca.",
    "papel": "Estrategista de Brand Voice",
    "contexto": "A comunicação da empresa varia demais entre pessoas e canais, confundindo clientes e dificultando onboarding de novos colaboradores.",
    "lista_verificacao_inicial": [
      "Valores e posicionamento desejado",
      "Público-alvo",
      "Canais de comunicação",
      "Exemplos bons e ruins atuais"
    ],
    "objetivo": "Desenvolver um guia prático e direto para padronizar a comunicação em todos os canais.",
    "restricoes": [
      "Foco total em exemplos práticos",
      "Adaptar por canal sem perder essência",
      "Definir palavras a usar e evitar",
      "Linguagem adequada ao público brasileiro"
    ],
    "processo_dados_faltando": "Se valores não forem definidos, criar atributos base. Se não houver exemplos, produzir modelos fictícios.",
    "formato_saida": "Documento com: Princípios, Tom por canal, Palavras/expressões, Exemplos reais."
  },
  {
    "title": "Criar pesquisa de satisfação pós-atendimento ou pós-compra.",
    "papel": "Especialista em Experiência do Cliente",
    "contexto": "A empresa não tem forma estruturada de medir satisfação e entender padrões de problemas.",
    "lista_verificacao_inicial": [
      "Momento ideal da pesquisa",
      "Canais possíveis",
      "Métrica principal (NPS, CSAT, CES)",
      "Volume estimado"
    ],
    "objetivo": "Criar uma pesquisa simples, rápida e útil para tomada de decisão.",
    "restricoes": [
      "Levar menos de 2 minutos",
      "Uma pergunta quantitativa e uma aberta",
      "Evitar excesso de perguntas",
      "Definir processo de leitura e ação"
    ],
    "processo_dados_faltando": "Se métrica não for definida, usar CSAT. Se momento não for claro, sugerir teste A/B.",
    "formato_saida": "Questionário + instruções + plano de leitura dos resultados."
  },
  {
    "title": "Desenvolver script para contornar objeções complexas.",
    "papel": "Consultor em Vendas Consultivas",
    "contexto": "A equipe perde vendas diante de objeções difíceis por falta de roteiro claro e técnicas estruturadas.",
    "lista_verificacao_inicial": [
      "5 objeções mais difíceis",
      "Preço médio e pacotes",
      "Diferenciais reais",
      "Limites de negociação"
    ],
    "objetivo": "Criar respostas estruturadas que ajudem o vendedor a explorar, entender e redirecionar objeções.",
    "restricoes": [
      "Usar estrutura ouvir-validar-explorar-responder",
      "Nunca invalidar o cliente",
      "Defender valor antes de falar em desconto",
      "Incluir perguntas de aprofundamento"
    ],
    "processo_dados_faltando": "Se não houver lista, usar objeções padrão: preço, prioridade, concorrência, confiança.",
    "formato_saida": "Tabela em Markdown com: Objeção, Significado real, Perguntas, Resposta modelo, Próximo passo."
  },
  {
    "title": "Criar sistema de comunicação proativa para atrasos e problemas operacionais.",
    "papel": "Gestor de Comunicação Operacional",
    "contexto": "Quando ocorrem problemas (atrasos, falhas técnicas, indisponibilidade), o cliente descobre sozinho, o que gera frustração e sobrecarga do atendimento.",
    "lista_verificacao_inicial": [
      "Pontos da operação sujeitos a falhas",
      "Canais de comunicação disponíveis",
      "Frequência e tipos de problemas",
      "Política atual de compensação"
    ],
    "objetivo": "Criar um protocolo claro e humano para avisar o cliente antes que ele precise reclamar.",
    "restricoes": [
      "Avisar o mais cedo possível",
      "Ser específico e transparente",
      "Nada de mensagens genéricas",
      "Definir quando há compensação"
    ],
    "processo_dados_faltando": "Se pontos críticos não forem informados, usar cenários padrão. Se não houver política de compensação, sugerir critérios.",
    "formato_saida": "Fluxo textual + templates de mensagens (preventivo, atualização e encerramento)."
  }
],

/* ---------- GESTÃO & ESTRATÉGIA ---------- */
gestao: [
  {
    "title": "Conduzir uma análise SWOT (FOFA) prática e aplicável à realidade da empresa.",
    "papel": "Estrategista de Negócios focado em PMEs",
    "contexto": "A empresa precisa revisar o planejamento e tomar decisões difíceis (cortar, investir, pausar projetos), mas não tem uma visão clara e organizada de suas forças, fraquezas, oportunidades e ameaças.",
    "lista_verificacao_inicial": [
      "Missão, visão e valores (se já existirem, mesmo que em rascunho)",
      "Principais produtos/serviços e público-alvo",
      "Principais pontos fortes percebidos pelos clientes e pela equipe (exemplos reais, não só percepções internas)",
      "Principais problemas internos e ameaças externas já identificados (mercado, concorrência, legislação, pessoas)"
    ],
    "objetivo": "Produzir uma matriz SWOT realista, com exemplos concretos, e extrair de 3 a 5 linhas estratégicas claras que possam orientar as próximas ações da empresa nos próximos 6 a 12 meses.",
    "restricoes": [
      "Evitar termos genéricos como 'inovação' ou 'qualidade' sem exemplos concretos do dia a dia",
      "Separar claramente fatores internos (Forças/Fraquezas) de externos (Oportunidades/Ameaças)",
      "Transformar a SWOT em decisões práticas e prioridades, não apenas em um quadro bonito para apresentação",
      "Considerar o contexto brasileiro de PMEs (tributação, crédito, mão de obra, concorrência informal, dependência de poucos clientes)",
      "Sugerir, quando fizer sentido, quais itens da SWOT devem virar projetos, metas ou OKRs"
    ],
    "processo_dados_faltando": "Se missão, visão e valores não existirem, criar versões preliminares baseadas na história e nos objetivos do negócio, em linguagem simples. Se o usuário não listar forças e fraquezas, propor uma lista padrão de PMEs (ex: dependência de dono, baixa formalização, relacionamento com clientes) para ele validar e adaptar.",
    "formato_saida": "1) Matriz SWOT em tabela Markdown (2x2) com itens específicos em cada quadrante, 2) Lista de 3 a 5 insights estratégicos cruzando FOFA (por exemplo, usar uma força para aproveitar uma oportunidade), 3) Sugestão de próximos passos estratégicos em lista numerada, indicando horizonte de tempo (curto, médio prazo)."
  },
  {
    "title": "Definir objetivos e resultados-chave usando a metodologia OKR (Objectives and Key Results).",
    "papel": "Gestor de Estratégia orientado a OKRs para PMEs",
    "contexto": "A empresa tem muitas iniciativas acontecendo ao mesmo tempo, mas pouca clareza sobre o que realmente é prioridade e como medir se está avançando.",
    "lista_verificacao_inicial": [
      "Principais objetivos estratégicos para o próximo ano (mesmo que em rascunho ou em frases soltas)",
      "Áreas ou frentes que precisam de foco (ex: vendas, operação, produto, finanças, pessoas)",
      "Indicadores que já são acompanhados hoje (mesmo que informalmente em planilhas)",
      "Capacidade real da equipe para tocar novos projetos no trimestre (tempo, pessoas, orçamento)"
    ],
    "objetivo": "Construir de 3 a 5 OKRs claros para o próximo trimestre, com Key Results mensuráveis e viáveis, alinhados com a capacidade e o momento da empresa.",
    "restricoes": [
      "Cada Objetivo deve ser inspirador e qualitativo, mas concreto (evitar slogans vazios)",
      "Cada Objetivo deve ter entre 2 e 5 Key Results mensuráveis, com meta-numérica e prazo definido",
      "Evitar ter mais de 5 OKRs no total em empresas pequenas, para não diluir foco",
      "Alinhar os OKRs com a capacidade real de execução, não com a lista de desejos idealizada",
      "Incluir, quando fizer sentido, 1 ou 2 Key Results ligados a saúde do negócio (caixa, clientes, equipe), não só crescimento"
    ],
    "processo_dados_faltando": "Se os objetivos anuais não forem claros, propor 3 eixos padrão para PMEs (Crescimento de Receita, Eficiência Operacional, Satisfação do Cliente) e ajudar o usuário a escolher os mais relevantes. Se não houver indicadores atuais, sugerir métricas simples de fácil medição (por exemplo, número de propostas enviadas, tempo de entrega, NPS simples).",
    "formato_saida": "Lista de OKRs em texto livre, estruturados como: 'Objetivo: [descrição]. Key Results: 1) [KR com meta numérica e prazo], 2) [KR...].' Incluir um comentário breve por OKR sobre riscos, dependências e frequência recomendada de acompanhamento (ex: semanal, quinzenal)."
  },
  {
    "title": "Construir um Canvas de Modelo de Negócios (Business Model Canvas) enxuto e utilizável.",
    "papel": "Consultor em Modelagem de Negócios para PMEs brasileiras",
    "contexto": "A empresa está revisando seu modelo de negócio (ou criando um novo) e precisa enxergar em uma única página como cria, entrega e captura valor.",
    "lista_verificacao_inicial": [
      "Descrição do produto ou serviço principal (especificando o que é, não só o nome)",
      "Segmentos de clientes e perfis que a empresa pretende atender",
      "Canais de venda e relacionamento já utilizados (on-line, off-line, misto)",
      "Principais fontes de receita e principais custos do negócio (sem entrar em planilhas detalhadas)"
    ],
    "objetivo": "Preencher os 9 blocos do Business Model Canvas de forma clara, concisa e prática, servindo de base para decisões e ajustes no modelo de negócio.",
    "restricoes": [
      "Manter cada bloco com no máximo 3 a 5 itens, evitando parágrafos longos e textos genéricos",
      "Garantir coerência entre Proposta de Valor, Segmentos de Clientes e Canais (não podem se contradizer)",
      "Não detalhar demais finanças no Canvas, deixando profundidade financeira para outras análises",
      "Adaptar o Canvas ao contexto de PMEs brasileiras (tamanho de equipe, recursos, realidade local), evitando copiar modelos de grandes empresas",
      "Sinalizar, quando perceber, blocos que estão vazios ou frágeis e que pedem validação no mercado"
    ],
    "processo_dados_faltando": "Se o usuário não tiver clareza total sobre cliente ideal ou proposta de valor, criar hipóteses iniciais e sinalizar explicitamente que precisam ser validadas. Se faltarem dados de custos ou receitas, trabalhar com visão qualitativa (de onde vem o dinheiro e para onde vai) e sugerir próximos passos para detalhamento financeiro posterior.",
    "formato_saida": "Tabela em Markdown no formato de Canvas (blocos nomeados) com os 9 quadrantes preenchidos de forma resumida, seguida de 3 observações sobre pontos de atenção ou incoerências percebidas (por exemplo: proposta de valor forte, mas canal de venda incoerente)."
  },
  {
    "title": "Realizar uma análise de viabilidade de um novo projeto, produto ou serviço.",
    "papel": "Analista de Projetos focado em decisões rápidas de PMEs",
    "contexto": "A empresa está considerando lançar algo novo ou investir em um projeto e precisa de uma análise de viabilidade enxuta para decidir se vale a pena avançar.",
    "lista_verificacao_inicial": [
      "Descrição do projeto/produto/serviço proposto",
      "Público-alvo estimado e problema que será resolvido",
      "Estimativa de investimento inicial e custos recorrentes para manter a operação",
      "Expectativa de receita (mesmo que em faixas ou cenários) e prazo estimado para início das vendas"
    ],
    "objetivo": "Fornecer uma análise rápida, misturando visão qualitativa e números simples, para decidir se o projeto deve avançar, ser ajustado ou ser arquivado por enquanto, antes de entrar em modelos financeiros mais complexos.",
    "restricoes": [
      "Evitar modelos financeiros complexos; focar em lógica simples e transparente (cenários de melhor, médio e pior caso)",
      "Destacar claramente os 3 principais riscos e os 3 principais fatores críticos de sucesso",
      "Enfatizar que se trata de uma análise preliminar, não de um laudo definitivo ou de VPL/TIR detalhado",
      "Considerar a capacidade da equipe atual para absorver o novo projeto sem colapsar a operação existente",
      "Evitar conclusões extremas; sugerir ajustes ou testes pilotos quando fizer sentido"
    ],
    "processo_dados_faltando": "Se não houver dados de receita ou custos, focar em viabilidade qualitativa (mercado, diferenciais, riscos) e sugerir quais números o usuário precisa levantar para aprofundar a análise financeira depois. Se o público-alvo não for claro, trabalhar com hipóteses e critérios para validar (ex: entrevistas, testes rápidos).",
    "formato_saida": "Texto estruturado em 5 seções: 1) Resumo executivo, 2) Viabilidade de mercado, 3) Viabilidade operacional (equipe, processos, tecnologia), 4) Viabilidade financeira simplificada (quando possível, com payback simples em anos) e 5) Recomendação final (Prosseguir, Ajustar antes, Não prosseguir agora), com justificativa clara."
  },
  {
    "title": "Criar um plano de sucessão para cargos-chave e reduzir risco de dependência de pessoas específicas.",
    "papel": "Gestor de Talentos Sênior com foco em continuidade do negócio",
    "contexto": "A empresa depende fortemente de poucas pessoas (fundadores ou líderes) e há risco elevado se alguém sair, adoecer ou se afastar.",
    "lista_verificacao_inicial": [
      "Lista de cargos e pessoas considerados críticos para o funcionamento da empresa",
      "Principais responsabilidades, decisões e relacionamentos que cada cargo-chave concentra",
      "Possíveis sucessores internos (mesmo que ainda não preparados) para cada cargo",
      "Tempo estimado necessário para preparar um sucessor minimamente pronto"
    ],
    "objetivo": "Elaborar um plano de sucessão que identifique vulnerabilidades, sucessores potenciais e ações de desenvolvimento para reduzir o risco de paralisação ou perda de conhecimento.",
    "restricoes": [
      "Tratar o tema com foco em continuidade do negócio, não como ameaça a pessoas ou justificativa para desligamento",
      "Incluir pelo menos 2 alternativas de sucessão para cada cargo crítico, quando possível (sucessores internos, externos ou planos de emergência)",
      "Evitar planos meramente teóricos; propor ações de desenvolvimento concretas e factíveis",
      "Não expor detalhes sensíveis da empresa ou nomes reais na resposta; trabalhar por cargos e perfis",
      "Incluir, quando fizer sentido, ações de documentação de processos e transferência de conhecimento"
    ],
    "processo_dados_faltando": "Se não houver lista de cargos-chave, ajudar o usuário a identificá-los com perguntas simples (por exemplo: 'Se essa pessoa sair hoje, o que para amanhã?'). Se não houver sucessores claros, focar na definição do perfil ideal e no desenho de um processo para identificar, atrair ou desenvolver pessoas.",
    "formato_saida": "Tabela em Markdown com colunas: Cargo-chave, Risco atual (Baixo/Médio/Alto), Sucessores potenciais (se houver) e Ações de desenvolvimento sugeridas. Em seguida, um resumo destacando quais cargos exigem ação imediata nos próximos 3 a 6 meses."
  },
  {
    "title": "Facilitar uma sessão de brainstorming estruturada para gerar ideias de inovação.",
    "papel": "Facilitador de Inovação para times enxutos",
    "contexto": "A empresa sente que está repetindo sempre as mesmas soluções e quer gerar ideias novas de produtos, serviços ou melhorias internas, mas as reuniões acabam virando reclamações ou discussões sem foco.",
    "lista_verificacao_inicial": [
      "Tema ou problema específico a ser trabalhado na sessão (ex: melhorar experiência do cliente, reduzir retrabalho, aumentar ticket médio)",
      "Perfil dos participantes (áreas representadas, tempo de casa, nível de decisão)",
      "Regras básicas desejadas para a sessão (ex: não criticar ideias na fase inicial, tempo de fala equilibrado)",
      "Tempo disponível para a sessão (ex: 60 ou 90 minutos) e formato (presencial, on-line, misto)"
    ],
    "objetivo": "Conduzir uma sessão de brainstorming organizada, que gere muitas ideias inicialmente e que, ao final, produza uma lista priorizada de poucas ideias para teste em curto prazo.",
    "restricoes": [
      "Separar claramente a fase de geração de ideias da fase de avaliação e priorização",
      "Garantir que todas as pessoas participem, não só as mais falantes (usar técnicas de rodadas ou escrita silenciosa)",
      "Manter o foco em problemas reais do negócio, não em 'inovar por inovar' desconectado da estratégia",
      "Concluir a sessão com pelo menos 2 ou 3 ideias com responsáveis, prazo e próximo passo definidos (teste, piloto, estudo)",
      "Registrar as ideias descartadas de forma simples, para eventual reuso futuro"
    ],
    "processo_dados_faltando": "Se o tema não for especificado, usar a pergunta-guia 'Como podemos melhorar a experiência dos nossos clientes nos próximos 90 dias?'. Se não houver regras definidas, sugerir regras simples de brainstorming (quantidade acima de qualidade na primeira fase, sem julgamento, construir em cima da ideia do outro, tempo limitado).",
    "formato_saida": "Roteiro da sessão em 4 etapas (Aquecimento, Geração de Ideias, Agrupamento/Categorização e Priorização), com instruções, perguntas disparadoras e critério de seleção das melhores ideias (por exemplo, matriz Impacto x Esforço em uma tabela Markdown simples)."
  },
  {
    "title": "Desenvolver um plano de comunicação interna para uma mudança organizacional relevante.",
    "papel": "Comunicador Organizacional focado em transparência e confiança",
    "contexto": "A empresa passará por mudança importante (reestruturação, troca de liderança, fusão, mudança de estratégia) e precisa comunicar isso de forma clara e cuidadosa para evitar boatos, medo e queda de produtividade.",
    "lista_verificacao_inicial": [
      "Natureza da mudança (o que vai mudar, por quê, quando e para quem)",
      "Públicos internos impactados (ex: toda empresa, apenas um time, unidades específicas)",
      "Canais de comunicação disponíveis (reuniões, e-mail, murais, grupos digitais, 1:1)",
      "Principais preocupações esperadas por parte dos colaboradores (ex: demissão, mudança de função, perda de benefícios)"
    ],
    "objetivo": "Construir um plano de comunicação interna por fases (antes, durante e depois da mudança), com mensagens-chave, canais, responsáveis e momento certo de cada comunicação.",
    "restricoes": [
      "Manter tom honesto e respeitoso, sem prometer o que não pode ser cumprido",
      "Adaptar a mensagem para cada público (liderança, equipe operacional, administrativo, remoto/presencial)",
      "Prever espaço para perguntas e escuta ativa, não só comunicação de cima para baixo",
      "Evitar excesso de termos técnicos ou corporativês; falar em linguagem humana e direta",
      "Incluir orientações para líderes sobre como lidar com emoções e dúvidas da equipe"
    ],
    "processo_dados_faltando": "Se a mudança não estiver totalmente desenhada, trabalhar com estrutura genérica de plano e destacar o que ainda precisa ser definido antes da comunicação. Se os públicos não forem mapeados, criar pelo menos duas camadas: Liderança (quem comunica) e Demais Colaboradores (quem recebe e executa).",
    "formato_saida": "Tabela em Markdown com colunas: Fase (Pré, Durante, Pós), Público-alvo, Mensagem-chave, Canal, Responsável e Momento (data ou marco). Incluir um pequeno guia com 5 a 7 perguntas que os líderes devem estar preparados para responder."
  },
  {
    "title": "Realizar benchmarking contra concorrentes para enxergar posição competitiva de forma realista.",
    "papel": "Analista de Negócios com foco em comparação de mercado",
    "contexto": "A empresa tem sensação de que está 'atrás' ou 'à frente', mas não tem uma visão estruturada de como se compara aos principais concorrentes em pontos críticos como preço, proposta de valor, atendimento, operação e presença digital.",
    "lista_verificacao_inicial": [
      "Lista de 3 a 5 concorrentes diretos ou alternativas que o cliente poderia escolher",
      "Áreas que o usuário quer comparar (ex: preço, mix de produtos/serviços, atendimento, experiência, canais, operação)",
      "Percepção atual dos clientes sobre a empresa (mesmo que baseada em relatos informais ou reviews)",
      "Fontes de informação que podem ser usadas (sites, redes sociais, reviews, conversas com clientes, visitas de mistério)"
    ],
    "objetivo": "Criar um quadro comparativo simples, com pontos fortes e fracos da empresa versus concorrentes, e indicar oportunidades de melhoria e diferenciação em nível estratégico (não apenas de campanha de marketing).",
    "restricoes": [
      "Usar apenas informações públicas ou relatos éticos (sem espionagem ou dados sensíveis de terceiros)",
      "Focar em aspectos que o negócio realmente consegue agir no curto ou médio prazo",
      "Evitar conclusões superficiais como 'somos melhores em tudo' ou 'somos piores em tudo'",
      "Incluir uma leitura honesta sobre onde a empresa está atrás e precisa correr, conectando com ações concretas",
      "Evitar repetir análise detalhada de campanhas de marketing já coberta em prompts específicos de Marketing"
    ],
    "processo_dados_faltando": "Se a lista de concorrentes não for fornecida, ajudar o usuário a defini-la com base no tipo de cliente e produto/serviço (o que o cliente compara na prática). Se as áreas de benchmarking não forem especificadas, trabalhar com conjunto padrão: Proposta de Valor, Portfólio, Preço, Atendimento/Experiência, Presença Digital e Operação (prazos, confiabilidade).",
    "formato_saida": "Tabela comparativa em Markdown com critérios nas linhas e empresas (incluindo a própria) nas colunas, seguida de um texto com: 1) resumo das principais conclusões, 2) 3 a 5 recomendações de ação prioritária para melhorar a competitividade e o posicionamento."
  },
  {
    "title": "Elaborar um plano de gestão de crises focado nas primeiras 24–48 horas.",
    "papel": "Gestor de Riscos e Crises para PMEs",
    "contexto": "A empresa não está preparada para reagir a eventos de grande impacto (problema grave com cliente, crise em redes sociais, acidente, fraude), o que aumenta o risco de decisões ruins em momentos de pressão.",
    "lista_verificacao_inicial": [
      "Principais tipos de crises plausíveis para o negócio (ex: produto defeituoso, problema trabalhista, ataque online, falha grave de serviço)",
      "Pessoas-chave que devem compor um comitê de crise (cargos, não nomes)",
      "Canais de comunicação internos e externos disponíveis",
      "Valor e reputação que a empresa mais precisa proteger em uma crise (vida e segurança, clientes, marca, caixa)"
    ],
    "objetivo": "Criar um protocolo simples para os primeiros 2 dias de uma crise, definindo quem faz o quê, em que ordem, e como a empresa se comunica interna e externamente.",
    "restricoes": [
      "Priorizar sempre segurança e integridade das pessoas antes de qualquer outra coisa",
      "Evitar excesso de detalhes jurídicos; manter linguagem acessível, mas responsável",
      "Prever diferentes níveis de gravidade (incidente moderado x crise grave) com respostas proporcionais",
      "Ter mensagens iniciais padrão que possam ser adaptadas rapidamente para cada situação"
    ],
    "processo_dados_faltando": "Se os tipos de crise não forem listados, trabalhar com 3 cenários base: Crise de reputação online, Problema grave com cliente, Interrupção importante da operação. Se o comitê não estiver definido, sugerir composição mínima com cargos-chave (fundador/direção, financeiro, jurídico/contábil, comunicação/marketing, operações).",
    "formato_saida": "Texto estruturado como plano de ação com 4 blocos: 1) Gatilho (quando acionar o plano), 2) Comitê de crise e funções, 3) Passo a passo das primeiras 24–48 horas (incluindo decisão, investigação, comunicação e registro), 4) Modelos de mensagem inicial em linguagem simples (para equipe e para clientes)."
  },
  {
    "title": "Definir Missão, Visão e Valores de forma autêntica e utilizável no dia a dia.",
    "papel": "Consultor em Estratégia e Cultura para empresas em crescimento",
    "contexto": "A empresa cresceu, começou a ter mais gente e percebe que as decisões estão dispersas. Precisa formalizar Missão, Visão e Valores para orientar contratações, decisões e prioridades.",
    "lista_verificacao_inicial": [
      "História resumida da empresa e motivo de sua criação",
      "Principais resultados e conquistas até aqui (exemplos concretos, não só números)",
      "Onde os fundadores querem que a empresa esteja em 5 a 10 anos",
      "Comportamentos que são valorizados ou rejeitados no dia a dia (mesmo que não estejam escritos)"
    ],
    "objetivo": "Redigir declarações claras de Missão, Visão e Valores que façam sentido na prática, possam ser explicadas em poucos minutos e sirvam de filtro para decisões e pessoas.",
    "restricoes": [
      "Evitar frases genéricas e vazias que poderiam servir para qualquer empresa",
      "Manter a Missão focada no presente (razão de existir) e a Visão no futuro (onde quer chegar, em linguagem concreta)",
      "Definir de 3 a 5 valores com descrições comportamentais objetivas (o que é 'viver esse valor' no dia a dia)",
      "Garantir que o texto caiba em comunicação interna e externa (site, materiais, reuniões, integrações)"
    ],
    "processo_dados_faltando": "Se os objetivos de longo prazo não forem claros, ajudar o usuário com perguntas orientadoras (por exemplo: 'O que você gostaria que fosse verdade sobre a empresa em 5 anos que ainda não é?'). Se não houver clareza sobre valores, propor uma lista inicial (como colaboração, responsabilidade, foco em cliente) para o usuário escolher e adaptar com exemplos práticos.",
    "formato_saida": "Texto com três blocos: 'Missão: [texto].', 'Visão: [texto].' e 'Valores: [lista de 3 a 5 valores, cada um com pequena descrição comportamental]'. Incluir 3 exemplos breves de como usar isso na prática: 1) em uma contratação, 2) em uma decisão difícil, 3) em um feedback para colaboradores."
  }
],

/* ---------- TRIBUTÁRIO & FISCAL ---------- */
tributario: [
  {
    "title": "Revisar o enquadramento tributário atual e mapear riscos e melhorias possíveis.",
    "papel": "Consultor Tributário Estratégico para PMEs",
    "contexto": "A empresa sente que paga muito imposto e tem dúvidas sobre se está corretamente enquadrada (Simples, Presumido ou Real), se está utilizando os CNAEs adequados e se há riscos relevantes de autuação ligados ao regime atual.",
    "lista_verificacao_inicial": [
      "Faturamento bruto anual projetado e histórico recente (pelo menos últimos 12 meses)",
      "Margem de lucro aproximada (bruta e líquida, se possível)",
      "Regime tributário atual, anexos/apurações utilizados e CNAEs cadastrados",
      "Principais despesas operacionais relevantes para a base de cálculo dos tributos (ex: folha, insumos, serviços)",
      "Existência de atividades diferentes dentro do mesmo CNPJ (ex: serviços e comércio misturados)"
    ],
    "objetivo": "Analisar se o enquadramento tributário atual faz sentido para o perfil e a operação da empresa, identificando riscos de desenquadramento, pontos de atenção e possíveis caminhos de melhoria para discussão com o contador.",
    "restricoes": [
      "Considerar apenas regimes permitidos pelo CNAE, faturamento e regras vigentes do Simples, Presumido e Real",
      "Deixar claro que a análise é de simulação e diagnóstico preliminar e não substitui parecer formal do contador",
      "Indicar vantagens e desvantagens práticas (complexidade, risco, controle necessário), não apenas alíquotas nominais",
      "Trabalhar com cenários conservadores quando houver incerteza em margens ou despesas",
      "Evitar prometer carga exata de imposto; trabalhar com faixas estimadas e linguagem clara de hipótese"
    ],
    "processo_dados_faltando": "Se a margem de lucro não for conhecida, trabalhar com faixas de margem típicas do setor, sinalizando que são estimativas. Se o faturamento for apenas aproximado, montar cenários em faixas (por exemplo, faturamento base e mais 20%) para mostrar sensibilidade.",
    "formato_saida": "1) Tabela comparativa em Markdown com linhas para cada regime (Simples Nacional, Lucro Presumido, Lucro Real) e colunas para: Faixa de carga tributária estimada, Complexidade Operacional, Principais riscos, Comentários. 2) Resumo textual com: a) leitura do regime atual, b) riscos/alertas, c) sugestões de próximos passos com o contador."
  },
  {
    "title": "Mapear obrigações acessórias mensais, trimestrais e anuais para evitar multas por descumprimento.",
    "papel": "Gestor de Obrigações Fiscais e Acessórias",
    "contexto": "A empresa toma multas recorrentes por atraso ou esquecimento de declarações, porque o controle das obrigações está espalhado entre contabilidade, financeiro e operação, sem um calendário único confiável.",
    "lista_verificacao_inicial": [
      "Regime tributário atual (Simples, Presumido, Real)",
      "Estado e município onde a empresa atua (matriz e filiais, se houver)",
      "Número de funcionários, existência de folha própria ou terceirizada e tipo de relação de trabalho predominante",
      "Principais sistemas utilizados (ERP, folha, emissor de notas, sistemas do contador)"
    ],
    "objetivo": "Construir um calendário fiscal personalizado, com a lista organizada das principais obrigações acessórias federais, estaduais e municipais, seus prazos típicos e riscos em caso de descumprimento.",
    "restricoes": [
      "Priorizar as obrigações com maior risco financeiro ou de bloqueio de emissão de notas e de CNDs",
      "Organizar a saída de forma útil para controle prático (datas, responsáveis internos, dependências de sistema/contador)",
      "Sinalizar claramente onde a informação pode variar por estado/município e precisa de confirmação local",
      "Evitar linguagem excessivamente técnica; traduzir siglas e nomes de obrigações em linguagem simples"
    ],
    "processo_dados_faltando": "Se o estado ou município não forem informados, usar um exemplo padrão (como um estado grande) e deixar claro que o calendário é um modelo a ser adaptado. Se o regime não for informado, criar um calendário genérico destacando os pontos que mudam conforme o regime, sugerindo validação com a contabilidade.",
    "formato_saida": "Tabela em Markdown com colunas: Obrigação, Esfera (Federal/Estadual/Municipal), Periodicidade, Prazo típico, Risco principal em caso de atraso, Responsável sugerido (interno/contabilidade). Incluir observações gerais ao final sobre como transformar o calendário em rotina (agenda, alertas, checklists)."
  },
  {
    "title": "Identificar créditos tributários não aproveitados que possuem direito de restituição ou compensação.",
    "papel": "Especialista em Recuperação de Créditos Tributários",
    "contexto": "A empresa suspeita que pode ter créditos de impostos (PIS, COFINS, ICMS, ISS e outros) não aproveitados ao longo dos últimos anos, mas não sabe por onde começar nem quais oportunidades são relevantes para o seu porte.",
    "lista_verificacao_inicial": [
      "Principais tributos pagos pela empresa (federais, estaduais e municipais relevantes)",
      "Perfil de compras e insumos (mercadorias, serviços, energia, fretes, insumos de produção)",
      "Regime de apuração de ICMS e de PIS/COFINS (cumulativo ou não cumulativo, regimes especiais)",
      "Existência de consultorias fiscais anteriores ou processos de recuperação já feitos e seus resultados"
    ],
    "objetivo": "Mapear as frentes mais prováveis de geração de créditos tributários recuperáveis ou compensáveis, priorizando oportunidades com maior potencial de valor e menor risco de autuação.",
    "restricoes": [
      "Focar em créditos com prazo de prescrição ainda válido, considerando janelas de tempo típicas",
      "Evitar teses excessivamente agressivas ou sem jurisprudência/minuta consolidada para PMEs",
      "Destacar a necessidade de documentação consistente para cada tipo de crédito (notas, contratos, laudos)",
      "Classificar oportunidades por potencial de valor versus complexidade de execução, em linguagem acessível",
      "Deixar claro que qualquer ação deve ser conduzida com suporte da contabilidade e, quando necessário, de especialistas jurídicos"
    ],
    "processo_dados_faltando": "Se não houver detalhamento das despesas ou tributos, listar as categorias mais comuns de créditos recuperáveis (ex: insumos, energia, frete) e orientar como o usuário pode levantar dados com a contabilidade e o ERP antes de avançar.",
    "formato_saida": "Lista numerada com, no mínimo, 3 a 5 oportunidades potenciais. Para cada item, indicar: tipo de tributo, base geradora possível, estimativa qualitativa de valor (Baixo/Médio/Alto), grau de complexidade (Baixa/Média/Alta) e próximos passos sugeridos (por exemplo: 'separar notas de energia dos últimos 60 meses e revisar com o contador')."
  },
  {
    "title": "Preparar due diligence fiscal para processos de venda, aquisição ou entrada de investidores.",
    "papel": "Consultor em Due Diligence Tributária e Fiscal",
    "contexto": "A empresa está em negociação de venda, fusão, aquisição ou entrada de investidor, e precisa mapear riscos fiscais e trabalhistas que podem afetar o valuation ou até inviabilizar a operação.",
    "lista_verificacao_inicial": [
      "Balancetes e demonstrações contábeis dos últimos 3 a 5 anos",
      "Certidões negativas ou positivas com efeito de negativas (federais, estaduais e municipais)",
      "Histórico de autuações, notificações fiscais ou parcelamentos em andamento",
      "Situação de obrigações acessórias (entregues em dia, retificadas, em atraso) e de cumprimento das principais rotinas fiscais",
      "Estrutura societária e participação dos sócios (inclusive em outras empresas relevantes)"
    ],
    "objetivo": "Identificar passivos tributários existentes e riscos potenciais, classificando por impacto financeiro e probabilidade, e sugerir caminhos de regularização ou mitigação antes ou durante a negociação.",
    "restricoes": [
      "Focar nos riscos com potencial impacto financeiro relevante ou que possam travar a operação ou o registro de atos societários",
      "Separar claramente o que é risco real do que é apenas falta de documentação organizada",
      "Evitar linguagem jurídica excessivamente complexa, priorizando entendimento gerencial e de investidor",
      "Indicar quando for necessária análise jurídica especializada complementar ou perícia contábil"
    ],
    "processo_dados_faltando": "Se a documentação estiver incompleta, trabalhar com os documentos disponíveis e listar de forma explícita quais informações precisam ser obtidas para uma análise completa. Se não houver histórico de autuações, considerar apenas riscos típicos do setor, porte e regime da empresa.",
    "formato_saida": "Relatório em texto livre com seções: 1) Visão Geral, 2) Riscos Identificados (tabela com colunas Tipo, Valor Estimado ou Faixa, Probabilidade, Status), 3) Oportunidades de Regularização ou Otimização, 4) Recomendações para Negociação/Valuation (por exemplo, ajustes de preço, retenções, condições)."
  },
  {
    "title": "Otimizar a estrutura societária para redução legal da carga tributária e organização do grupo.",
    "papel": "Especialista em Planejamento Societário-Tributário para PMEs",
    "contexto": "A empresa cresceu de forma orgânica, abriu CNPJs conforme a necessidade e hoje tem uma estrutura societária confusa, com possível pagamento de imposto acima do necessário, conflitos de sócios e riscos sucessórios.",
    "lista_verificacao_inicial": [
      "Estrutura societária atual (empresas, sócios, participações e atividades principais de cada CNPJ)",
      "Faturamento por empresa e por atividade relevante",
      "Estados/municípios em que as empresas estão estabelecidas",
      "Objetivos dos sócios (crescimento, sucessão, proteção patrimonial, simplificação, saída futura)"
    ],
    "objetivo": "Analisar a configuração societária atual e propor alternativas de reestruturação que busquem equilíbrio entre carga tributária, simplicidade operativa, proteção patrimonial e aderência à legislação.",
    "restricoes": [
      "Evitar práticas ou estruturas que possam ser consideradas evasivas ou abusivas pela fiscalização",
      "Considerar custos de implementação, manutenção e risco de cada cenário sugerido",
      "Manter a proposta em nível conceitual (caminhos e alternativas) e não como desenho jurídico pronto",
      "Deixar claro que qualquer alteração societária deve ser feita com suporte jurídico e contábil local, respeitando legislação específica"
    ],
    "processo_dados_faltando": "Se o detalhamento das empresas não estiver completo, trabalhar com mapa simplificado (matriz, filiais, outras empresas dos sócios) e registrar as lacunas de informação. Se os objetivos dos sócios forem pouco claros, sugerir perguntas para alinhamento (ex: horizonte de saída, importância de distribuição de lucros vs. reinvestimento).",
    "formato_saida": "Texto estruturado com: 1) Diagnóstico resumido da estrutura atual, 2) Principais problemas e riscos identificados, 3) Possíveis caminhos de reestruturação (2 ou 3 cenários conceituais), 4) Vantagens e desvantagens de cada cenário, 5) Recomendações iniciais de próximos passos para aprofundar com especialistas."
  },
  {
    "title": "Implementar controle para evitar autuações por descumprimento da legislação do Simples Nacional.",
    "papel": "Auditor Especializado em Simples Nacional para Pequenos Negócios",
    "contexto": "Empresas optantes pelo Simples Nacional frequentemente cometem erros que levam à exclusão do regime, autuações ou pagamento indevido de tributos por falta de controle de limites, atividades e receitas.",
    "lista_verificacao_inicial": [
      "CNAEs utilizados e atividades de fato exercidas pela empresa",
      "Faturamento mensal dos últimos 12 a 24 meses (por CNPJ e, se houver, por CNPJs relacionados)",
      "Participação dos sócios em outras empresas (e regimes tributários dessas empresas)",
      "Histórico de notificações ou inconsistências apontadas pelo fisco relacionadas ao Simples (avisos, cartas, intimações)"
    ],
    "objetivo": "Criar um checklist prático de conformidade com as principais regras do Simples Nacional que impactam diretamente a permanência no regime e a correta apuração dos tributos.",
    "restricoes": [
      "Basear-se em regras oficiais vigentes para o Simples, evitando interpretações duvidosas ou ultrapassadas",
      "Diferenciar claramente itens críticos (que podem gerar exclusão, majoração de alíquotas ou autuações relevantes) de itens de melhoria de controle",
      "Sinalizar sempre que exista situação que dependa de análise formal de contador ou advogado, em vez de conclusão automática",
      "Evitar termos muito técnicos sem explicação rápida em linguagem simples"
    ],
    "processo_dados_faltando": "Se não houver detalhes sobre outras empresas dos sócios, alertar para o risco típico de desenquadramento por grupo econômico e sugerir checagem. Se o faturamento não estiver detalhado por mês, trabalhar com total anual e alertar que o controle mensal é essencial para faixas, anexos e sublimites.",
    "formato_saida": "Tabela/Checklist em Markdown com colunas: Item de Verificação, Situação desejada, Risco em caso de não conformidade, Status (campo para o usuário preencher: OK/Pendência) e Observações. Incluir bloco final com 5 a 10 alertas críticos e recomendações prioritárias de ajuste."
  },
  {
    "title": "Elaborar estratégia para aproveitamento de incentivos fiscais estaduais e municipais.",
    "papel": "Consultor em Incentivos Fiscais Regionais",
    "contexto": "A empresa atua em estados e municípios que oferecem benefícios fiscais (redução de alíquota, créditos presumidos, programas de fomento, distritos industriais), mas não aproveita esses incentivos por falta de mapeamento e análise de custo-benefício.",
    "lista_verificacao_inicial": [
      "Localização da(s) unidade(s) da empresa (estado, município, zona urbana/rural, distritos industriais ou afins)",
      "Setor de atuação e CNAEs principais (indústria, comércio, serviços, tecnologia, agro, etc.)",
      "Quantidade de empregos diretos e investimentos recentes ou planejados em ativos e estrutura",
      "Se já houve contato prévio com órgãos de desenvolvimento ou programas de incentivo (secretarias, agências, associações)"
    ],
    "objetivo": "Identificar tipos de incentivos fiscais possivelmente aplicáveis à realidade da empresa e estruturar um plano inicial para avaliação e, se fizer sentido, adesão.",
    "restricoes": [
      "Considerar tanto o benefício tributário quanto os custos e exigências de contrapartida (empregos, investimentos, relatórios)",
      "Evitar incentivos que exijam estrutura de controle incompatível com o porte da empresa",
      "Deixar claro que a viabilidade depende de legislação local detalhada e análise especializada",
      "Separar incentivos de curto prazo (por exemplo, redução de ISS para serviços) dos de longo prazo (por exemplo, ICMS para indústria ou logística)"
    ],
    "processo_dados_faltando": "Se a localização exata ou o setor não forem detalhados, trabalhar com exemplos genéricos de tipos de incentivos que costumam existir (ex: redução de ISS, isenção ou diferimento de ICMS, programas de desenvolvimento local) e orientar o usuário a procurar entidades de desenvolvimento regional, SEBRAE e contabilidade local para confirmação.",
    "formato_saida": "Lista estruturada com pelo menos 3 tipos de incentivos que costumam existir para o tipo de empresa informado. Para cada um, indicar: possível benefício, requisitos gerais, riscos/complexidades e próximos passos sugeridos (quem procurar, que documentos levantar, que perguntas fazer)."
  },
  {
    "title": "Criar procedimento para tratamento de autuações fiscais e defesa administrativa.",
    "papel": "Especialista em Gestão de Autuações e Contencioso Administrativo",
    "contexto": "A empresa recebe notificações e autos de infração fiscais, mas reage de forma desorganizada, perdendo prazos, pagando multas que poderiam ser discutidas ou deixando de registrar aprendizados para prevenir reincidência.",
    "lista_verificacao_inicial": [
      "Tipos mais comuns de autuações já recebidas (ex: atraso em declarações, divergência de notas, glosa de créditos, diferença de base de cálculo)",
      "Valores médios e máximos das multas aplicadas",
      "Fluxo interno atual de recebimento, análise e resposta a notificações (quem recebe, quem analisa, quem decide)",
      "Profissionais/áreas envolvidos hoje (contabilidade, jurídico, fiscal interno, diretoria) e seus limites de atuação"
    ],
    "objetivo": "Desenhar um procedimento padrão para recebimento, classificação, análise e resposta a autuações fiscais, reduzindo o risco de perda de prazo e aumentando a taxa de defesas bem-sucedidas.",
    "restricoes": [
      "Respeitar prazos legais de defesa administrativa e de recursos, deixando essa informação visível no procedimento",
      "Classificar autuações por valor e risco para priorizar esforços (por exemplo, Baixo/Médio/Alto impacto)",
      "Registrar aprendizados de cada caso para ajustes de processo e prevenção de reincidências",
      "Orientar quando faz sentido buscar apoio jurídico/tributário especializado ou negociar redução/pagamento"
    ],
    "processo_dados_faltando": "Se não houver histórico detalhado das autuações, trabalhar com categorias típicas de erros (declarações em atraso, divergência de notas, créditos indevidos) e montar o procedimento em formato genérico. Se não houver jurídico interno, considerar a realidade de PMEs que dependem de suporte externo pontual.",
    "formato_saida": "1) Fluxograma descrito em texto (passo a passo) com etapas de Recebimento, Registro, Classificação, Análise Técnica, Decisão (defender/negociar/pagar), Elaboração de Defesa, Acompanhamento e Aprendizados. 2) Tabela em Markdown com essas etapas, responsáveis sugeridos e prazos típicos. 3) Exemplo breve aplicado a uma autuação comum (por exemplo, atraso em entrega de declaração)."
  },
  {
    "title": "Implementar controle de documentação fiscal para evitar problemas em fiscalizações e perda de créditos.",
    "papel": "Organizador de Documentação Fiscal Digital",
    "contexto": "Notas fiscais, recibos e relatórios fiscais estão dispersos em e-mails, papel, pastas locais e no ERP. Em fiscalizações, a empresa perde tempo e corre risco de autuações por ausência de documentos que existem, mas não são facilmente localizados.",
    "lista_verificacao_inicial": [
      "Volume médio mensal de documentos fiscais emitidos e recebidos (NF-e, NFC-e, NFS-e, CT-e, etc.)",
      "Formas atuais de armazenamento (papel, pastas digitais, sistema de gestão de documentos, ERP, e-mail)",
      "Tempo médio que se leva hoje para localizar documentos em auditorias internas ou externas",
      "Conhecimento dos prazos legais de guarda dos diferentes tipos de documentos (mesmo que aproximado)"
    ],
    "objetivo": "Estruturar um sistema simples e viável de organização e guarda de documentos fiscais, que permita localizar rapidamente o que é necessário e esteja alinhado às exigências legais.",
    "restricoes": [
      "Respeitar prazos de guarda exigidos em lei (em geral, mínimo de 5 anos, podendo variar conforme o tributo)",
      "Considerar tanto documentos eletrônicos quanto físicos remanescentes, sem ignorar nenhum dos dois",
      "Propor uma estrutura compatível com o porte e recursos da empresa (sem exigir tecnologias inviáveis)",
      "Prever backups e contingência em caso de perda de dados (ex: nuvem, cópias externas controladas)"
    ],
    "processo_dados_faltando": "Se o volume exato de documentos não for conhecido, trabalhar com estimativas por tipo de documento e focar mais na lógica da estrutura de organização do que na granularidade. Se não houver sistema de nuvem, sugerir alternativas simples para começar (por exemplo, pastas em nuvem básica com estrutura padronizada).",
    "formato_saida": "Descrição da estrutura de pastas (física e/ou digital), com exemplos de nomes e organização por ano, mês, tipo de documento e CNPJ. Incluir checklist em tabela Markdown com tipos de documentos, prazo de guarda mínimo recomendado e local de armazenamento sugerido (ERP, nuvem, arquivo físico)."
  },
  {
    "title": "Desenvolver política de preços de transferência e operações entre partes relacionadas (quando aplicável).",
    "papel": "Especialista em Preços de Transferência e Operações Intragrupo",
    "contexto": "Grupos empresariais com empresas relacionadas (no Brasil ou exterior) realizam operações entre si sem uma política clara de preços, o que pode gerar risco fiscal, especialmente em operações internacionais ou entre empresas do mesmo grupo com regimes diferentes.",
    "lista_verificacao_inicial": [
      "Estrutura do grupo (empresas, países, atividades, relações societárias básicas)",
      "Principais tipos de operações entre partes relacionadas (venda de produtos, prestação de serviços, empréstimos, royalties, compartilhamento de custos)",
      "Volume anual aproximado dessas operações por tipo e direção (quem vende para quem)",
      "Existência ou não de contratos formais, políticas de preços e registros de como os preços são definidos hoje"
    ],
    "objetivo": "Desenhar, em nível conceitual, uma política de preços de transferência e de operações entre partes relacionadas que traga mais segurança fiscal e previsibilidade ao grupo.",
    "restricoes": [
      "Adequar a política às regras brasileiras de preços de transferência e às obrigações aplicáveis, quando houver operações com exterior",
      "Diferenciar operações puramente domésticas daquelas com empresas no exterior ou com regimes distintos",
      "Prever necessidade de documentação comprobatória mínima para suportar os preços praticados (contratos, planilhas, estudos)",
      "Evitar detalhamento jurídico excessivo, focando em diretrizes práticas e linguagem gerencial para o usuário"
    ],
    "processo_dados_faltando": "Se as operações entre partes relacionadas não forem descritas em detalhe, trabalhar com categorias genéricas (venda de mercadorias, prestação de serviços, empréstimos) e sugerir como o usuário pode mapear essas transações com apoio da contabilidade e da controladoria.",
    "formato_saida": "Texto livre com: 1) Visão geral do risco de preço de transferência e operações intragrupo, 2) Princípios básicos da política (por exemplo, alinhamento a práticas de mercado, consistência e documentação), 3) Diretrizes por tipo de operação (produtos, serviços, operações financeiras), 4) Documentos mínimos recomendados, 5) Próximos passos para implementação formal com suporte contábil e jurídico."
  }
],

/* ---------- COMPRAS & SUPRIMENTOS ---------- */
compras: [
  {
    "title": "Implementar sistema de avaliação e classificação de fornecedores por critérios múltiplos.",
    "papel": "Gestor de Qualidade e Performance de Fornecedores para PMEs",
    "contexto": "A empresa sofre com qualidade irregular, atrasos e preços pouco competitivos, e ainda escolhe e mantém fornecedores sem critérios claros ou histórico estruturado de desempenho. É necessário sair da escolha 'por costume' e passar a usar dados para decidir com quem comprar mais, desenvolver ou substituir.",
    "lista_verificacao_inicial": [
      "Lista atual de fornecedores por categoria de compra (matéria-prima, revenda, serviços, etc.)",
      "Histórico mínimo de entregas por fornecedor (atrasos, devoluções, reclamações, erros de faturamento)",
      "Valores comprados por fornecedor em um período (ex: últimos 12 meses ou outro período representativo)",
      "Critérios que a empresa já considera importantes (preço, prazo, qualidade, atendimento, flexibilidade, localização)",
      "Se possível, divisão dos fornecedores em categorias: críticos, importantes e de apoio"
    ],
    "objetivo": "Criar um sistema simples de pontuação (scoring) que permita classificar fornecedores em grupos (A, B, C) e usar essa classificação na negociação, priorização de pedidos, desenvolvimento de parceiros e substituição gradual dos fornecedores problemáticos.",
    "restricoes": [
      "Incluir no mínimo 4 a 5 critérios mensuráveis (ex: preço, pontualidade, qualidade, atendimento, documentação, flexibilidade)",
      "Definir pesos diferentes por critério de acordo com a criticidade para o negócio (por exemplo, qualidade mais pesada que preço em itens críticos)",
      "Prever periodicidade de reavaliação (ex: trimestral ou semestral) e critérios claros para subir ou descer de categoria",
      "Manter o modelo simples o suficiente para ser atualizado em planilha ou sistema básico, sem exigir softwares complexos",
      "Garantir que a classificação A/B/C tenha efeito prático (ex: prioridade em pedidos, participação em projetos, volume de compras)"
    ],
    "processo_dados_faltando": "Se não houver histórico estruturado, começar com percepção qualitativa da equipe de compras, operação e qualidade, registrando notas por critério como ponto de partida e marcando onde há suposição. Se não forem definidos pesos, assumir pesos iguais inicialmente, explicando a limitação, e sugerir ajustes posteriores com base na experiência.",
    "formato_saida": "Tabela em Markdown com colunas: 'Fornecedor', 'Critérios avaliados', 'Nota por critério', 'Peso de cada critério', 'Nota final ponderada', 'Classificação (A, B ou C)' e, ao final, um texto curto com orientações práticas de uso da classificação na rotina (ex: como isso impacta negociações e priorização)."
  },
  {
    "title": "Desenvolver estratégia de compras coletivas com outras PMEs do mesmo setor.",
    "papel": "Estrategista de Compras Colaborativas e Redução de Custos",
    "contexto": "A empresa tem pouco poder de barganha individual e paga caro em itens-chave. Outras PMEs da região enfrentam o mesmo problema, mas não há coordenação entre elas para negociar em conjunto.",
    "lista_verificacao_inicial": [
      "Principais itens ou categorias com maior peso no custo (top 10 itens de maior valor ou volume)",
      "Volume médio mensal de compra desses itens na empresa (quantidade e valor)",
      "Rede de PMEs semelhantes com as quais exista algum nível de confiança (contatos, associações, grupos de WhatsApp, núcleos setoriais, sindicatos, etc.)",
      "Sensibilidade dos fornecedores a volume (a partir de qual quantidade ou valor negociam melhores preços ou condições)",
      "Possibilidade logística de consolidar pedidos e distribuir os itens (quem recebe, quem fraciona, frete)"
    ],
    "objetivo": "Estruturar um modelo de compras conjuntas que permita à empresa negociar preços melhores, prazos diferenciados ou condições mais vantajosas sem criar uma estrutura burocrática ou tirar a autonomia operacional de cada participante.",
    "restricoes": [
      "Definir regras simples e claras de participação (quem entra, como sai, como decide, como paga, como lida com inadimplência)",
      "Deixar transparente como será feita a divisão de volumes, fretes e descontos entre as empresas, evitando conflitos futuros",
      "Evitar criar uma estrutura jurídica/administrativa pesada demais; começar com um acordo simples e evoluir conforme a maturidade do grupo",
      "Considerar impactos logísticos (armazenagem, prazos de entrega, local de recebimento, fracionamento de cargas)"
    ],
    "processo_dados_faltando": "Se o volume de compras das outras PMEs não for conhecido, trabalhar com estimativas iniciais e deixar claro que precisam ser validadas em um levantamento simples. Se não houver grupo formal de PMEs, sugerir caminhos para montar um grupo piloto (por exemplo, começar com 3 empresas de confiança) e testar o modelo em poucos itens.",
    "formato_saida": "Resumo em texto estruturado com: 1) Proposta de modelo de compras coletivas (como funciona na prática), 2) Principais regras de governança (decisão, pagamento, responsabilidade), 3) Plano em 5 etapas para testar o modelo com poucos itens e poucas empresas, 4) Estrutura de planilha em tabela Markdown para simular ganho de economia por volume (colunas: 'Item', 'Volume Individual', 'Volume Coletivo', 'Preço Individual', 'Preço Coletivo', 'Economia estimada')."
  },
  {
    "title": "Criar sistema de previsão de demanda para otimizar compras e evitar excesso/escassez.",
    "papel": "Planejador de Demanda e Compras para PMEs",
    "contexto": "As compras são feitas 'no olho' ou só com base no sentimento do gestor, o que gera falta de produtos em alguns períodos e excesso de estoque parado em outros, impactando vendas e caixa.",
    "lista_verificacao_inicial": [
      "Histórico de vendas ou consumo dos últimos 6 a 12 meses por item (ou pelo menos por categoria)",
      "Informações de sazonalidade conhecidas (datas sazonais fortes, meses fracos, promoções recorrentes, eventos específicos)",
      "Lead time médio de reposição de cada fornecedor ou categoria (dias, semanas ou meses)",
      "Nível de serviço desejado (probabilidade de não faltar produto, por exemplo: 90%, 95% ou 98%)",
      "Itens ou categorias mais importantes para o negócio (curva ABC ou percepção do gestor)"
    ],
    "objetivo": "Construir uma lógica de previsão simples, porém prática, que sirva de base para o planejamento de compras, ajudando a reduzir capital empatado em estoque sem aumentar rupturas em itens críticos.",
    "restricoes": [
      "Priorizar inicialmente os itens de maior impacto (curva A) para não complicar demais o sistema logo no início",
      "Usar métodos simples aplicáveis em planilha (ex: média móvel, médias ajustadas por sazonalidade, ajustes manuais documentados)",
      "Incluir margens de segurança maiores para itens críticos ou com lead time longo, explicando o motivo",
      "Registrar claramente as premissas usadas (ex: crescimento, sazonalidade, eventos) para facilitar revisões mensais ou bimestrais"
    ],
    "processo_dados_faltando": "Se o histórico de vendas for curto ou incompleto, trabalhar com médias simples e ajustes qualitativos (opinião do gestor) marcando essas estimativas como premissas. Se não houver mapeamento de sazonalidade, sugerir a criação de um calendário de sazonalidade ao longo dos próximos meses, enquanto se aplica um modelo inicial mais simples.",
    "formato_saida": "Modelo de planilha em formato de tabela Markdown com colunas: 'Item', 'Demanda média (período)', 'Ajuste de sazonalidade (%)', 'Demanda ajustada', 'Lead time', 'Nível de serviço desejado', 'Estoque de segurança sugerido', 'Quantidade recomendada de compra', 'Observações sobre premissas'."
  },
  {
    "title": "Implementar processo de due diligence para novos fornecedores estratégicos.",
    "papel": "Auditor de Fornecedores e Gestor de Risco de Suprimentos",
    "contexto": "Novos fornecedores são aprovados apenas com base em preço ou indicação, sem análise mínima de risco financeiro, jurídico, operacional ou de reputação, aumentando a chance de problemas sérios no futuro.",
    "lista_verificacao_inicial": [
      "Critérios atuais (se existirem) usados para aprovar novos fornecedores",
      "Tipos de riscos que mais preocupam a empresa (ex: risco de não entrega, risco financeiro, risco trabalhista, risco ambiental, risco de imagem)",
      "Documentos hoje solicitados (se houver procedimento) e onde são armazenados",
      "Categorias de fornecedores mais sensíveis para o negócio (ex: matéria-prima crítica, logística, tecnologia, serviços recorrentes)"
    ],
    "objetivo": "Criar um checklist de due diligence proporcional ao tamanho da empresa e ao risco do fornecedor, ajudando a prevenir a maioria dos problemas que poderiam ter sido identificados antes da contratação.",
    "restricoes": [
      "Equilibrar a profundidade da análise com a realidade da PME (não criar um processo inviável de ser aplicado)",
      "Diferenciar níveis de rigor: fornecedores estratégicos x fornecedores de baixo risco, com exigências diferentes",
      "Incluir itens mínimos de verificação jurídica (ex: CNPJ, pendências), financeira (ex: consultas básicas), operacional (ex: capacidade) e de reputação (ex: referências, notícias)",
      "Prever como registrar, revisar e atualizar essas informações ao longo do tempo (por exemplo, revisão anual ou a cada renovação de contrato)"
    ],
    "processo_dados_faltando": "Se os riscos ainda não forem claros para o usuário, sugerir uma lista inicial de riscos típicos de fornecedores e ajudar a priorizá-los em alto, médio e baixo. Se não houver qualquer documentação hoje, começar com um modelo mínimo de checklist e recomendar que ele seja ampliado gradualmente conforme a empresa amadurece o processo.",
    "formato_saida": "Checklist em tabela Markdown com colunas: 'Área de análise (jurídica, financeira, operacional, reputacional)', 'Item a verificar', 'Evidência/documento sugerido', 'Critério de aprovação (aceite/não aceite ou nota mínima)' e 'Ação em caso de não conformidade (aprovar com ressalvas, reprovar, solicitar complemento)'."
  },
  {
    "title": "Desenvolver estratégia de diversificação de fornecedores para reduzir riscos.",
    "papel": "Estrategista de Cadeia de Suprimentos para Compras em PMEs",
    "contexto": "A operação depende fortemente de um ou poucos fornecedores em itens críticos. Qualquer problema com eles (preço, quebra, atraso, mudança de política) afeta diretamente clientes e faturamento.",
    "lista_verificacao_inicial": [
      "Lista de itens críticos para operação e identificação dos que dependem de um único fornecedor (single source)",
      "Impacto da falta de cada item (financeiro, operacional, reputacional) em termos práticos",
      "Alternativas conhecidas de mercado (mesmo que ainda não testadas) para esses itens críticos",
      "Condições mínimas aceitáveis em novos fornecedores (qualidade, prazo, preço, localização, certificações)",
      "Lead time típico desses itens e dificuldades logísticas atuais"
    ],
    "objetivo": "Mapear onde a empresa está mais exposta à falta ou à dependência de fornecedores e construir um plano progressivo de diversificação, começando pelos itens de maior risco e impacto.",
    "restricoes": [
      "Não trocar todos os fornecedores ao mesmo tempo; trabalhar com transição gradual para não desorganizar a operação",
      "Manter ou melhorar o padrão de qualidade durante a diversificação, evitando quedas bruscas em nome de preço",
      "Registrar testes iniciais (lotes piloto, pedidos menores) com novos fornecedores e medir resultados antes de aumentar volume",
      "Considerar não apenas preço, mas risco total (continuidade, logística, capacidade, estabilidade do fornecedor)"
    ],
    "processo_dados_faltando": "Se não houver classificação de criticidade dos itens, ajudar a criar um ranking simples (alto, médio, baixo) com base no impacto de falta e no tempo de reposição. Se não existirem alternativas claras, sugerir caminhos para pesquisa (ex: associações, feiras, marketplaces B2B, indicação de outras empresas).",
    "formato_saida": "Matriz em Markdown com colunas: 'Item', 'Grau de criticidade (Alto/Médio/Baixo)', 'Número de fornecedores atuais', 'Risco de concentração (Alto/Médio/Baixo)', 'Plano de diversificação (ações/resumo)', 'Prazo estimado' e 'Status (não iniciado, em teste, implementado)'."
  },
  {
    "title": "Otimizar processo de cotação para garantir melhor custo-benefício sem travar a operação.",
    "papel": "Especialista em Processos de Compra e Negociação para PMEs",
    "contexto": "O processo de cotação é demorado, mal documentado e muitas vezes apenas formal, sem garantir melhor custo-benefício. Em outros casos, a empresa não cota nada e compra sempre do mesmo fornecedor, sem comparar alternativas.",
    "lista_verificacao_inicial": [
      "Número atual de cotações realizadas por compra ou categoria de item (prática real, não apenas política escrita)",
      "Tempo médio entre solicitação de cotação e fechamento do pedido",
      "Critérios de decisão usados hoje (ex: menor preço, prazo, condição de pagamento, histórico de problemas)",
      "Ferramenta usada para registrar cotações (planilha, sistema, e-mails soltos, nenhum registro)",
      "Categorias de compra que exigem mais rigor na cotação (itens de alto valor ou alta criticidade)"
    ],
    "objetivo": "Redesenhar o fluxo de cotação para ser rápido, rastreável e focado em custo-benefício real (não só preço unitário), permitindo decisões melhores sem travar a operação.",
    "restricoes": [
      "Estabelecer como regra padrão a busca de pelo menos 3 cotações por item relevante, sempre que possível, com exceções claramente definidas",
      "Documentar, de forma simples, o motivo da escolha do fornecedor (ex: melhor combinação preço + prazo + qualidade)",
      "Definir prazos-alvo para cada etapa da cotação (ex: resposta de fornecedores, análise interna, aprovação)",
      "Evitar burocracia desnecessária para compras pequenas ou emergenciais, mas prevendo um registro mínimo mesmo nesses casos"
    ],
    "processo_dados_faltando": "Se as métricas atuais (tempo, número de cotações) não forem conhecidas, sugerir um levantamento amostral com base nos últimos pedidos relevantes. Se não houver registro padronizado, propor um modelo inicial de planilha ou formulário para centralizar as cotações e decisões.",
    "formato_saida": "1) Fluxograma em texto com as etapas do processo de cotação (ex: Solicitação → Coleta de propostas → Comparação → Aprovação → Pedido), 2) Modelo de tabela em Markdown para registrar: 'Item/Serviço', 'Fornecedor', 'Preço', 'Prazo', 'Condição de pagamento', 'Outros critérios', 'Fornecedor escolhido', 'Justificativa da escolha'."
  },
  {
    "title": "Implementar sistema de avaliação de desempenho contínuo de fornecedores.",
    "papel": "Gestor de Performance e Relacionamento com Fornecedores para PMEs",
    "contexto": "Após a contratação, o desempenho dos fornecedores não é acompanhado de forma estruturada. Problemas só são lembrados em momentos de crise, e bons fornecedores não são reconhecidos ou priorizados de forma consistente.",
    "lista_verificacao_inicial": [
      "Principais indicadores que impactam o dia a dia (ex: atrasos, devoluções, erros de faturamento, problemas de qualidade, suporte)",
      "Frequência desejada de avaliação (mensal, bimestral, trimestral) e canais de feedback",
      "Capacidade da equipe para registrar informações no dia a dia (quem registra, onde, com que frequência)",
      "Possíveis consequências para desempenho ruim e incentivos para bom desempenho (ex: aumento de volume, contratos, prazos melhores)"
    ],
    "objetivo": "Criar um sistema simples e contínuo de acompanhamento para que a empresa enxergue os fornecedores que estão entregando bem ou mal ao longo do tempo e use isso na tomada de decisão, feedback e negociação.",
    "restricoes": [
      "Escolher poucos indicadores, mas relevantes e fáceis de medir na prática (ex: % de entregas no prazo, % de devoluções, qualidade de atendimento)",
      "Permitir comparação entre fornecedores da mesma categoria, inclusive em gráficos simples se o usuário desejar",
      "Prever um momento de feedback periódico com os fornecedores-chave para discutir resultados",
      "Conectar o resultado da avaliação com decisões reais (manter, desenvolver, reduzir volume, substituir, negociar condições)"
    ],
    "processo_dados_faltando": "Se não houver qualquer registro até hoje, começar com um período de observação de 1 a 3 meses em que a equipe registra notas rápidas (ex: escala 1–5) por entrega. Se a equipe se mostrar resistente a registrar, simplificar ao máximo a coleta de dados e integrar com rotinas existentes (ex: conferência de NF, recebimento físico).",
    "formato_saida": "Modelo de painel/tabela em Markdown com colunas: 'Fornecedor', 'Período', 'Indicador 1', 'Indicador 2', 'Indicador 3' (conforme definidos), 'Nota Global', 'Comentários Relevantes' e 'Decisão Sugerida (manter/desenvolver/reduzir/substituir)'."
  },
  {
    "title": "Desenvolver política de compras sustentáveis e éticas adaptada à realidade de PMEs.",
    "papel": "Consultor em Compras Sustentáveis e Critérios ESG para PMEs",
    "contexto": "A empresa sente pressão de clientes, mercado ou valores internos para adotar práticas mais sustentáveis e éticas, mas não sabe como incorporar isso nas decisões de compra sem aumentar demais os custos ou criar burocracia impossível de manter.",
    "lista_verificacao_inicial": [
      "Critérios ESG que já são considerados, mesmo que de forma intuitiva (ex: evitar certos fornecedores, preferir origens específicas)",
      "Setores ou linhas de produto em que sustentabilidade é mais sensível (ex: alimentos, madeira, produtos químicos, embalagens)",
      "Fornecedores que já possuem certificações ou práticas sustentáveis conhecidas (ex: selos, certificações, compromissos públicos)",
      "Limite máximo de aumento de custo que a empresa está disposta a aceitar para escolhas mais sustentáveis (faixa ou percentual)",
      "Principais valores/Princípios da empresa relacionados a ética, meio ambiente e responsabilidade social"
    ],
    "objetivo": "Criar uma política prática, clara e escalável que inclua critérios éticos e ambientais na seleção e manutenção de fornecedores, sem descolar da realidade financeira e operacional da PME.",
    "restricoes": [
      "Focar em poucos critérios ESG inicialmente, priorizando os mais relevantes para o tipo de negócio e setor",
      "Definir critérios mensuráveis e que possam ser verificados sem auditorias complexas ou muito caras",
      "Deixar claro que a política será aplicada de forma gradativa, começando pelas categorias mais sensíveis e ampliando com o tempo",
      "Incluir exemplos concretos do que é aceitável e do que não é, reduzindo espaço para interpretações ambíguas"
    ],
    "processo_dados_faltando": "Se a empresa não tiver clareza sobre seus valores ou prioridades ESG, ajudar a priorizar 3 a 5 princípios-guia (ex: evitar trabalho análogo ao escravo, reduzir desperdício, priorizar insumos recicláveis). Se não houver fornecedores com certificações formais, focar inicialmente em práticas básicas (condições de trabalho, origem dos insumos, descarte responsável, cumprimento da legislação).",
    "formato_saida": "Documento em texto estruturado com: 1) Princípios da política de compras sustentáveis, 2) Critérios ESG mínimos por tipo de fornecedor (ex: obrigatório x desejável), 3) Como esses critérios entram no processo de avaliação, seleção e renovação de fornecedores, 4) Exemplos práticos de decisões (aceitar/recusar/condicionar fornecedor) e 5) Orientação de como comunicar essa política internamente e para fornecedores."
  },
  {
    "title": "Criar sistema de gestão de contratos com fornecedores.",
    "papel": "Gestor de Contratos e Relacionamento com Fornecedores para PMEs",
    "contexto": "Contratos ficam dispersos em e-mails, pastas físicas ou computadores pessoais. Datas de vencimento, reajustes, multas e condições especiais são esquecidas, gerando riscos, renovação automática indesejada e perda de oportunidades de renegociação.",
    "lista_verificacao_inicial": [
      "Lista de contratos ativos (mesmo que incompleta) e onde estão arquivados (físico ou digital)",
      "Principais informações que a empresa precisa controlar em cada contrato (vigência, prazos, multas, reajustes, volumes mínimos, exclusividades)",
      "Responsáveis internos pelo relacionamento com cada fornecedor-chave",
      "Ferramentas disponíveis para controle (planilha, sistema, agenda compartilhada, software de gestão)",
      "Eventos críticos comuns (renovação automática, reajuste anual, carência, multas por rescisão)"
    ],
    "objetivo": "Implementar um sistema simples para centralizar informações dos contratos, acompanhar prazos críticos e facilitar renegociações e decisões de continuidade, reduzindo surpresas e riscos.",
    "restricoes": [
      "Evitar depender apenas da memória ou de uma única pessoa para lembrar datas e condições relevantes",
      "Definir alertas com antecedência mínima (ex: 60 ou 90 dias antes do vencimento ou reajuste) para análise e decisão",
      "Registrar versões e alterações relevantes dos contratos (aditivos, renegociações importantes)",
      "Garantir que o sistema possa ser mantido mesmo com troca de pessoas na equipe (processo, não só pessoa)"
    ],
    "processo_dados_faltando": "Se a lista atual de contratos não existir, orientar como fazer um mapeamento inicial em ondas (começando pelos fornecedores mais críticos e de maior valor). Se não houver ferramenta digital definida, sugerir um modelo de planilha estruturada combinado com alertas em agenda compartilhada (e-mail, calendário, sistema simples).",
    "formato_saida": "Estrutura de base/tabela em Markdown com colunas: 'Fornecedor', 'Objeto do contrato', 'Data de início', 'Data de término', 'Condições de reajuste', 'Cláusulas relevantes (multas, exclusividade, volumes mínimos)', 'Prazos críticos (renovação, aviso prévio)', 'Responsável interno' e 'Próximos passos'. Incluir também recomendações de rotina de revisão (ex: check trimestral dos contratos mais relevantes)."
  },
  {
    "title": "Otimizar processo de pagamento a fornecedores para melhorar relacionamento e negociar vantagens.",
    "papel": "Estrategista de Pagamentos e Relacionamento com Fornecedores",
    "contexto": "Pagamentos são feitos muitas vezes em cima da hora, gerando risco de atraso, perda de descontos, desgaste com fornecedores e uso pouco inteligente de linhas de crédito e fluxo de caixa.",
    "lista_verificacao_inicial": [
      "Prazo médio de pagamento atual por fornecedor ou categoria, se conhecido",
      "Existência (ou não) de descontos por pagamento antecipado e respectivas condições",
      "Rotina atual de programação de pagamentos (diária, semanal, sem padrão definido)",
      "Custo financeiro do capital (taxa de juros média de linhas de crédito utilizadas ou taxa de oportunidade do caixa)",
      "Fornecedores estratégicos com quem seria interessante fortalecer relacionamento"
    ],
    "objetivo": "Desenhar uma estratégia de pagamentos que equilibre saúde de caixa, bom relacionamento com fornecedores e aproveitamento de oportunidades de desconto ou condições especiais, com uma rotina previsível e organizada.",
    "restricoes": [
      "Manter visão consolidada dos pagamentos futuros (por semana e por mês) para evitar surpresas de caixa",
      "Negociar, quando fizer sentido, troca de prazo por desconto real ou outras contrapartidas de valor (ex: condições comerciais melhores)",
      "Evitar atrasos crônicos que prejudiquem a reputação da empresa e encareçam as compras",
      "Automatizar, quando possível, a programação de pagamentos (ex: agenda de pagamentos), mantendo etapas mínimas de aprovação"
    ],
    "processo_dados_faltando": "Se não houver clareza do prazo médio atual, sugerir um cálculo simples com base em amostra dos últimos meses (valores pagos, datas de emissão e pagamento). Se o custo do capital não for conhecido, assumir uma taxa média de mercado como referência e mostrar o raciocínio de comparação com descontos por antecipação (quanto vale financeiramente antecipar o pagamento).",
    "formato_saida": "Matriz em Markdown com colunas: 'Fornecedor', 'Prazo atual', 'Desconto por antecipação (se houver)', 'Alternativas de prazo/condição possíveis', 'Custo financeiro estimado x benefício do desconto', 'Recomendação de estratégia (antecipar, manter prazo, renegociar)' e, ao final, um mini-roteiro de renegociação para a equipe de compras/financeiro usar em conversas com fornecedores."
  }
],

/* ---------- CRÉDITO & FOMENTO ---------- */
credito: [
  {
    "title": "Diagnosticar a elegibilidade da empresa para principais linhas de crédito de PMEs.",
    "papel": "Consultor de Elegibilidade Creditícia para PMEs",
    "contexto": "A empresa precisa de capital, mas não sabe para quais linhas de crédito realmente se encaixa, nem quais fazem sentido em termos de custo, limite e burocracia. Hoje os pedidos são tentativos, gerando negativas, perda de tempo, queda de score e desgaste com bancos e cooperativas.",
    "lista_verificacao_inicial": [
      "Faturamento bruto anual, faixa de faturamento e tempo de existência da empresa",
      "Situação cadastral atual (restrições em nome da empresa e dos sócios, protestos, pendências fiscais)",
      "Endividamento atual (valor aproximado, principais credores, tipo de dívida e prazos)",
      "Principais garantias disponíveis (reais, aval, faturamento futuro, recebíveis, imóveis, veículos)",
      "Objetivo do crédito (capital de giro, investimento, refinanciamento de dívida, antecipação de recebíveis, outros)",
      "Relação atual com instituições financeiras (bancos, cooperativas, fintechs e tempo de relacionamento)"
    ],
    "objetivo": "Mapear o perfil financeiro e cadastral da empresa e cruzar esse perfil com os tipos de linhas de crédito mais comuns para PMEs, indicando onde a empresa tem maior probabilidade de aprovação, com condições minimamente saudáveis e compatíveis com o fluxo de caixa.",
    "restricoes": [
      "Focar em linhas com custo efetivo total (CET) compatível com a realidade da empresa, evitando olhar apenas a taxa nominal divulgada",
      "Priorizar instituições reguladas e produtos destinados a empresas/PMEs, evitando usar crédito pessoal dos sócios como solução padrão",
      "Sempre considerar o impacto das parcelas no fluxo de caixa, endividamento total e compromissos já existentes",
      "Evitar recomendações que levem a sobreendividamento estrutural para 'tampar buracos' sem plano de ajuste operacional",
      "Deixar claro que o resultado é um encaminhamento preliminar e não substitui análise formal da instituição financeira"
    ],
    "processo_dados_faltando": "Se o faturamento ou tempo de existência não forem informados, trabalhar com faixas aproximadas (por exemplo, até R$ 360 mil/ano, até R$ 4,8 milhões/ano, acima disso) e sinalizar que a elegibilidade é apenas indicativa. Se não houver clareza sobre a situação cadastral, orientar como consultar (ex: bureaus de crédito, certidões fiscais) e sugerir regularização como etapa anterior ou paralela aos pedidos.",
    "formato_saida": "Tabela comparativa em Markdown com colunas: 'Tipo de linha de crédito', 'Perfil típico exigido', 'Vantagens e limitações', 'Probabilidade de aprovação (Alta/Média/Baixa)', 'Pontos de atenção'. Ao final, um bloco de texto com 'Próximos passos recomendados' em até 5 itens práticos."
  },
  {
    "title": "Elaborar projeto para acesso a recursos do BNDES ou linhas similares para inovação e modernização.",
    "papel": "Especialista em Estruturação de Projetos para BNDES e Fomento",
    "contexto": "A empresa quer modernizar o parque tecnológico, automatizar processos ou inovar em produtos/serviços, mas não sabe como transformar essa intenção em um projeto 'bancável' dentro da lógica de bancos de fomento (BNDES, bancos de desenvolvimento regionais, cooperativas com linhas de fomento, etc.).",
    "lista_verificacao_inicial": [
      "Descrição clara do projeto de inovação ou modernização (o que será feito, em linguagem simples)",
      "Investimento total estimado e itens que o compõem (máquinas, equipamentos, software, consultorias, obras, treinamento, capital de giro associado)",
      "Impactos esperados no negócio (produtividade, redução de custos, aumento de capacidade, qualidade, acesso a novos mercados)",
      "Capacidade da empresa de arcar com contrapartida e serviço da dívida (fluxo de caixa projetado, margem, endividamento atual)",
      "Prazo estimado de implantação do projeto e principais marcos (ex: compra, instalação, início de operação)"
    ],
    "objetivo": "Transformar a ideia de investimento em um projeto estruturado, com objetivos, orçamento, cronograma e métricas de resultado, alinhado às exigências típicas de linhas de financiamento para inovação e modernização, facilitando conversas com bancos de fomento e agentes financeiros credenciados.",
    "restricoes": [
      "Adequar a estrutura do projeto à realidade de uma PME (texto claro, técnico o suficiente, mas sem jargões vazios)",
      "Explicitar como o projeto gera retorno econômico ou de eficiência mensurável, mesmo que em estimativas conservadoras",
      "Prever fases factíveis (início, meio, fim) com marcos objetivos de acompanhamento (ex: % de conclusão, indicadores de resultado)",
      "Considerar exigências típicas de análise de risco (garantias, capacidade de pagamento, regularidade fiscal) sem prometer aprovação",
      "Deixar claro que o projeto é uma base para negociação e não garante acesso automático aos recursos"
    ],
    "processo_dados_faltando": "Se o projeto ainda estiver vago, ajudar o usuário a transformar a ideia em escopo mínimo, respondendo às perguntas: o que será feito, por que, quanto custa, em quanto tempo, qual impacto esperado. Se o valor do investimento não estiver definido, trabalhar com faixas e estimativas por categoria, registrando essas hipóteses de forma explícita.",
    "formato_saida": "Estrutura de projeto em texto livre com seções: 1) Resumo Executivo, 2) Objetivos do Projeto (negócio e técnicos), 3) Descrição Técnica do Investimento, 4) Orçamento Detalhado por categoria, 5) Cronograma por Fase (com marcos), 6) Fontes de Recursos (próprios e de terceiros), 7) Indicadores de Resultado esperados (financeiros e operacionais)."
  },
  {
    "title": "Preparar documentação para financiamento com garantia de fundos de aval e garantias complementares.",
    "papel": "Consultor em Estruturação de Garantias para Crédito",
    "contexto": "A empresa até teria capacidade de pagamento, mas esbarra sempre no mesmo problema: falta de garantias suficientes ou bem organizadas para que bancos liberem linhas mais baratas e com limite adequado.",
    "lista_verificacao_inicial": [
      "Valor, prazo e finalidade do crédito pretendido",
      "Garantias reais ou pessoais já utilizadas e ainda disponíveis (imóveis, veículos, máquinas, aval dos sócios)",
      "Participação atual ou potencial em fundos de aval ou garantias complementares (ex: FAMPE, fundos regionais, cooperativas)",
      "Histórico de relacionamento com instituições financeiras (tipo de conta, tempo de relacionamento, produtos já utilizados, pontualidade)",
      "Situação cadastral básica da empresa e dos sócios (sem entrar em detalhes sigilosos)"
    ],
    "objetivo": "Organizar e dimensionar as garantias possíveis, identificando oportunidades de uso de fundos de aval, garantias complementares ou estruturas mistas que aumentem a chance de aprovação do crédito com custo melhor, dentro de um nível de risco aceitável para a empresa e seus sócios.",
    "restricoes": [
      "Respeitar os limites e condições dos fundos de aval e das instituições envolvidas, evitando suposições irreais",
      "Evitar estruturas de garantia que coloquem em risco desproporcional o patrimônio dos sócios ou da família",
      "Avaliar o custo total da garantia (taxas, contrapartidas) versus o ganho na taxa de juros ou nas condições do crédito",
      "Documentar claramente as responsabilidades assumidas pela empresa, pelos sócios e pelos fundos de aval",
      "Ressaltar que a decisão final sobre garantias cabe à instituição financeira e aos próprios sócios, não ao modelo sugerido"
    ],
    "processo_dados_faltando": "Se não houver clareza sobre garantias disponíveis, conduzir um checklist mínimo de bens, recebíveis, estoque financiável e relações bancárias. Se o fundo de aval específico não for conhecido, trabalhar com exemplo genérico e sugerir consulta à cooperativa, Sebrae ou banco de desenvolvimento regional para identificação das opções reais.",
    "formato_saida": "1) Checklist documental em tabela Markdown com colunas: 'Tipo de garantia', 'Descrição', 'Documento/comprovação necessária', 'Situação (disponível/em uso)', 2) Quadro-resumo em texto com: 'Valor pretendido', 'Estrutura de garantias proposta (reais, aval, fundos de aval)', 'Vantagens', 'Riscos', 'Pontos a negociar com a instituição financeira'."
  },
  {
    "title": "Desenvolver estratégia de acesso ao crédito para microempreendedores individuais (MEI) e microempresas.",
    "papel": "Especialista em Crédito para MEI e Micro Negócios",
    "contexto": "O microempreendedor precisa de capital para comprar estoque, equipamentos ou aliviar o giro, mas encontra barreiras em bancos tradicionais e acaba recorrendo a crédito caro e desorganizado (cartão, cheque especial, empréstimos informais).",
    "lista_verificacao_inicial": [
      "Atividade principal do MEI ou microempresa e ticket médio de vendas",
      "Faturamento mensal estimado, tempo de CNPJ ativo e sazonalidade básica",
      "Relação atual com bancos, fintechs e cooperativas (contas, maquininhas, carteiras digitais, histórico de uso de crédito)",
      "Objetivo do crédito (estoque, equipamento, capital de giro temporário, regularização de dívidas, reforma, outros)",
      "Endividamento atual em crédito pessoal e empresarial (quando existir)"
    ],
    "objetivo": "Mapear caminhos de crédito mais acessíveis e estruturados para MEIs e microempresas, considerando linhas formais, produtos de maquininhas, cooperativas de crédito e fintechs, reduzindo a dependência de crédito caro e rotativo.",
    "restricoes": [
      "Evitar recomendar soluções com custo efetivo total abusivo ou que incentivem endividamento em cascata para pagar dívidas antigas",
      "Explicar de forma simples a diferença entre crédito rotativo, crédito parcelado e linhas estruturadas de capital de giro",
      "Considerar o impacto das parcelas no fluxo de caixa real do negócio (margem, sazonalidade, prazos de recebimento)",
      "Priorizar opções que ajudem a construir histórico positivo de crédito e relacionamento com instituições formais",
      "Deixar claro que o crédito não substitui a necessidade de organizar preços, custos e fluxo de caixa do negócio"
    ],
    "processo_dados_faltando": "Se o faturamento for apenas estimado, trabalhar com faixas (ex: até R$ 10 mil/mês, até R$ 30 mil/mês, acima disso), deixando claro que limites e taxas reais dependerão das políticas de cada instituição. Se o histórico bancário for fraco, sugerir passos para fortalecer relacionamento (ex: concentrar recebimentos, manter saldo mínimo, usar produtos básicos) antes ou em paralelo ao pedido.",
    "formato_saida": "Lista estruturada em texto com 3 a 5 tipos de solução de crédito possíveis (ex: microcrédito produtivo, crédito via cooperativa, antecipação via maquininha), cada uma contendo: 'Perfil ideal', 'Vantagens', 'Riscos', 'Documentação mínima sugerida', 'Como iniciar a conversa com a instituição'."
  },
  {
    "title": "Estruturar operação de crédito com antecipação de recebíveis (duplicatas, cartões, boletos).",
    "papel": "Especialista em Antecipação de Recebíveis e Capital de Giro",
    "contexto": "A empresa tem vendas a prazo relevantes, mas sofre com falta de caixa no curto prazo. Acaba antecipando recebíveis de maneira desordenada, pagando caro e sem comparar alternativas.",
    "lista_verificacao_inicial": [
      "Valor e composição da carteira de recebíveis (duplicatas, boletos, cartões, carnês, outros)",
      "Prazo médio de recebimento atual, formas de pagamento e principais clientes envolvidos",
      "Taxas praticadas hoje em eventuais antecipações (maquininha, banco, factoring) e periodicidade de uso",
      "Necessidade de capital de giro (valor e prazo) que se deseja cobrir com a operação de antecipação",
      "Existência de outras linhas de crédito disponíveis para comparação de custo"
    ],
    "objetivo": "Desenhar uma estratégia de uso da antecipação de recebíveis como instrumento de capital de giro planejado, comparando custo efetivo com outras alternativas de crédito e evitando dependência crônica dessa ferramenta.",
    "restricoes": [
      "Priorizar recebíveis de melhor qualidade (clientes com histórico positivo e baixo risco de inadimplência)",
      "Comparar explicitamente o custo efetivo da antecipação com o custo de outras linhas de crédito acessíveis à empresa",
      "Evitar recomendar antecipação sistemática de 100% da carteira, sugerindo limites e regras internas (ex: teto mensal)",
      "Prever controles mínimos para acompanhar o impacto da operação no fluxo de caixa futuro (entradas já 'vendidas')",
      "Destacar que antecipação recorrente pode mascarar problemas estruturais de margem ou prazos de pagamento"
    ],
    "processo_dados_faltando": "Se não houver dados detalhados da carteira, trabalhar com médias e simulações por faixas (ex: 30, 60, 90 dias), deixando claro que são estimativas. Se as taxas atuais forem desconhecidas, usar faixas típicas apenas como referência comparativa, sempre registrando como hipótese e recomendando consulta exata à instituição.",
    "formato_saida": "Análise em texto livre com: 1) simulação de pelo menos 2 a 3 cenários (sem antecipação, antecipação parcial, antecipação mais intensa), 2) tabela em Markdown com colunas 'Cenário', 'Valor antecipado', 'Custo aproximado', 'Impacto no caixa futuro', 3) recomendações de política interna para uso consciente da antecipação (regras, limites e monitoramento)."
  },
  {
    "title": "Preparar o negócio para captação de investimento-anjo ou capital semente.",
    "papel": "Consultor em Preparação para Investidores Anjo",
    "contexto": "A empresa tem um modelo de negócio inovador ou escalável, mas ainda comunica o negócio de forma muito operacional. Precisa traduzir o que faz em uma narrativa que faça sentido para investidores: problema, solução, mercado, tração, escala, risco e retorno.",
    "lista_verificacao_inicial": [
      "Modelo de negócio atual (como ganha dinheiro) e problema central que resolve para o cliente",
      "Tração existente (clientes, receita, crescimento, testes, parcerias, validações relevantes)",
      "Time fundador (perfil, complementaridade de competências, dedicação ao negócio)",
      "Necessidade de capital (quanto, para quê, em quanto tempo) e visão de futuro (onde quer chegar)",
      "Estrutura societária básica e eventuais passivos que precisem ser explicados"
    ],
    "objetivo": "Organizar o negócio em um pacote de informações que facilite conversas com investidores-anjo, deixando claro o potencial de retorno, os riscos, o uso dos recursos e o tipo de parceria que a empresa está buscando.",
    "restricoes": [
      "Manter a avaliação (valuation) em patamar coerente com o estágio e os números do negócio, evitando cifras desconectadas da realidade",
      "Destacar claramente como o capital será usado para destravar crescimento (e não apenas para cobrir rombos recorrentes)",
      "Ser transparente sobre riscos, hipóteses ainda não testadas e pontos a validar nos próximos ciclos",
      "Evitar promessas mirabolantes de retorno sem qualquer base de dados, benchmark ou testes mínimos",
      "Deixar claro que não se trata de oferta pública de valores mobiliários, mas de preparação para conversas individuais com investidores"
    ],
    "processo_dados_faltando": "Se a tração ainda for baixa, focar na clareza do problema, solução, público-alvo e tamanho de mercado, explicitando que se trata de estágio muito inicial. Se o valor de capital desejado não estiver definido, ajudar a estimar com base em um plano básico de 12 a 18 meses (pessoas, produto, marketing, operação).",
    "formato_saida": "Roteiro de pitch deck (máx. 10 slides) em texto, com bullet points sugeridos para cada slide: 1) Problema, 2) Solução, 3) Mercado e oportunidade, 4) Modelo de Negócio, 5) Tração/Validações, 6) Estratégia de Crescimento, 7) Time, 8) Projeções e métricas-chave, 9) Tese de Investimento (por que faz sentido), 10) Uso dos Recursos e formato do aporte desejado."
  },
  {
    "title": "Otimizar o score de crédito da empresa para melhorar condições de empréstimo.",
    "papel": "Consultor de Melhoria de Score Empresarial",
    "contexto": "Mesmo com faturamento e operações razoáveis, a empresa enfrenta taxas altas ou negativas em pedidos de crédito por conta de histórico de atrasos, baixa formalização ou cadastro mal cuidado.",
    "lista_verificacao_inicial": [
      "Score atual (ou faixa de score) da empresa e dos principais sócios, quando isso impactar diretamente",
      "Histórico recente de atrasos, renegociações, protestos ou dívidas em aberto (por tipo de credor: impostos, fornecedores, bancos, trabalhistas)",
      "Relação com bancos e cooperativas (tempo de relacionamento, produtos utilizados, movimentação média, histórico de crédito)",
      "Organização de documentos financeiros (DRE, balanço, fluxo de caixa, declarações fiscais) e frequência de atualização",
      "Situação cadastral básica (endereços, CNAE, quadro societário, dados desatualizados)"
    ],
    "objetivo": "Identificar fatores que derrubam o score e construir um plano prático de 3 a 6 meses para melhorar a percepção de risco da empresa junto a bancos, cooperativas e bureaus de crédito, aumentando as chances de crédito com condições melhores.",
    "restricoes": [
      "Focar exclusivamente em práticas legais, transparentes e sustentáveis (sem 'atalhos' duvidosos ou promessas de aumento instantâneo de score)",
      "Equilibrar pagamento de dívidas com manutenção mínima do capital de giro para que a empresa continue operando",
      "Orientar atualização cadastral, organização de informações e relacionamento com instituições de forma gradual e realista",
      "Evitar recomendações genéricas demais; adaptar sempre que possível às informações fornecidas sobre o contexto da empresa",
      "Deixar claro que a evolução de score depende de políticas dos bureaus e instituições, não sendo controlável 100% pela empresa"
    ],
    "processo_dados_faltando": "Se o score exato não for conhecido, trabalhar com o relato de dificuldade de crédito como sinal e sugerir consulta formal (bureaus, bancos) como ação prioritária. Se o histórico de atrasos não estiver detalhado, agrupar por categorias principais (impostos, fornecedores, bancos) para direcionar as ações.",
    "formato_saida": "Plano de ação em texto livre organizado em fases, por exemplo: 1) Diagnóstico rápido, 2) Limpeza de Pendências Críticas, 3) Organização de Informações Financeiras e Fiscais, 4) Fortalecimento de Relacionamento com Instituições, 5) Monitoramento e Rotina. Cada fase com lista de ações, responsável sugerido (quando fizer sentido) e prazos aproximados."
  },
  {
    "title": "Acessar linhas de crédito para exportação e operações de comércio exterior (quando aplicável).",
    "papel": "Especialista em Crédito para Exportação e Comércio Exterior",
    "contexto": "A empresa já exporta ou está em fase de começar a exportar, mas não sabe como financiar produção, prazos maiores ou oscilações cambiais sem estrangular o caixa.",
    "lista_verificacao_inicial": [
      "Produto ou serviço a ser exportado, principais clientes-alvo e país(es) de destino",
      "Volume estimado de exportações (valor e quantidade) e prazos de pagamento negociados ou pretendidos",
      "Experiência prévia em comércio exterior (nenhuma / poucas operações / exportação recorrente)",
      "Relação atual com bancos que operam câmbio e produtos de financiamento à exportação",
      "Estrutura interna para lidar com processos de exportação (documentação, logística, compliance básico)"
    ],
    "objetivo": "Mapear opções de financiamento e apoio financeiro atreladas à exportação, ajudando a empresa a estruturar operações com risco menor, fluxo de caixa mais previsível e melhor alinhamento entre produção, embarque e recebimento.",
    "restricoes": [
      "Considerar de forma separada linhas para pré-embarque (produção, estoque) e pós-embarque (prazo ao cliente), quando aplicável",
      "Avaliar o impacto da variação cambial na operação, nas margens e na capacidade de pagamento das dívidas em moeda local",
      "Focar em soluções alinhadas ao porte da empresa e à complexidade das operações (não presumir grande estrutura de comércio exterior)",
      "Evitar criar estruturas de crédito excessivamente complexas para operações de baixo volume ou baixa recorrência"
    ],
    "processo_dados_faltando": "Se a empresa ainda não exporta, trabalhar em caráter preparatório, explicando quais informações, estrutura mínima e parceiros serão necessários para acessar crédito atrelado à exportação. Se não houver banco parceiro com expertise em câmbio, sugerir critérios para escolha (tarifas, experiência em PME, suporte consultivo).",
    "formato_saida": "Quadro em texto livre apresentando: 1) Tipos de linhas de financiamento à exportação mais comuns (em linguagem simples), 2) Quando cada tipo faz sentido, 3) Riscos principais (câmbio, inadimplência, prazos), 4) Checklist mínimo de preparação da empresa para conversar com banco ou agente financeiro sobre crédito para exportação."
  },
  {
    "title": "Estruturar financiamento para projetos de eficiência energética e sustentabilidade (crédito verde).",
    "papel": "Consultor em Crédito Verde e Projetos de Eficiência",
    "contexto": "A empresa quer reduzir custos de energia, resíduos ou impacto ambiental (ex: energia solar, troca de iluminação, máquinas mais eficientes), mas o investimento inicial é alto para o caixa atual.",
    "lista_verificacao_inicial": [
      "Descrição do projeto de eficiência ou sustentabilidade (por exemplo: usina solar, retrofit de iluminação, substituição de equipamentos)",
      "Investimento estimado por item (equipamentos, instalação, obras, projetos, licenças) e economia anual esperada em R$ ou % de custo",
      "Vida útil estimada dos equipamentos e prazo desejado de financiamento",
      "Linhas de crédito verde ou incentivos já avaliados (se houver) e principais bancos/cooperativas com os quais a empresa se relaciona",
      "Situação atual de consumo (ex: valor médio mensal de energia, água, insumos relevantes)"
    ],
    "objetivo": "Montar um racional financeiro que mostre se o projeto 'se paga', alinhando a estrutura de financiamento com o prazo de retorno do investimento e com o fluxo de caixa da empresa, para apoiar discussões com bancos e cooperativas.",
    "restricoes": [
      "Basear a análise em premissas conservadoras de economia (evitar superestimar ganhos)",
      "Deixar explícitas todas as hipóteses usadas em cálculo de payback, VPL ou outros indicadores, em linguagem simples",
      "Avaliar se o prazo de financiamento proposto é coerente com a vida útil dos equipamentos e com a economia gerada",
      "Evitar assumir benefícios fiscais, subsídios ou programas públicos sem pelo menos verificar elegibilidade mínima em alto nível"
    ],
    "processo_dados_faltando": "Se a economia esperada não for conhecida, trabalhar com cenários estimados (por exemplo, redução de 10%, 20% e 30% do custo atual), deixando claro que os números são ilustrativos e precisam ser validados com fornecedor/projeto técnico. Se não houver orçamento detalhado, usar valores de referência por tipo de projeto, marcando-os como estimativas.",
    "formato_saida": "Resumo financeiro em texto contendo: 1) Estimativa de investimento total e por categoria, 2) Economia anual projetada em cada cenário, 3) Cálculo simples de payback em anos, 4) Comentários sobre VPL/TIR quando pertinente (sem fórmula pesada), 5) Quadro com possíveis linhas de crédito verde a considerar e pontos de atenção para a conversa com o banco."
  },
  {
    "title": "Desenvolver estratégia de capital de giro sazonal para negócios com forte sazonalidade.",
    "papel": "Especialista em Crédito Sazonal e Gestão de Caixa",
    "contexto": "Negócios com alta e baixa temporada (ex: datas comemorativas, turismo, escolar, moda) sofrem com estresse de caixa nos períodos de preparação de estoque e equipe, mas escolhem crédito sem planejar o encaixe dos pagamentos com as vendas futuras.",
    "lista_verificacao_inicial": [
      "Mapa de sazonalidade (meses fortes e fracos) e variação de faturamento ao longo do ano",
      "Necessidade de capital de giro adicional nos períodos de preparação de estoque, equipe e marketing",
      "Prazos médios de recebimento dos clientes nas altas e baixas temporadas (por forma de pagamento)",
      "Linhas de crédito já utilizadas para cobrir sazonalidade, seus prazos, taxas e forma de uso (planejada ou emergencial)",
      "Existência de estoques residuais ou dívidas recorrentes de safras anteriores que ainda impactam o caixa"
    ],
    "objetivo": "Desenhar uma estratégia de capital de giro sazonal em que os prazos de pagamento do crédito conversem com o ciclo real de entrada de caixa, reduzindo o risco de 'bola de neve' nas baixas temporadas e melhora a previsibilidade do caixa ao longo do ano.",
    "restricoes": [
      "Alinhar, tanto quanto possível, datas de vencimento das parcelas com os meses de maior entrada de caixa (alta temporada ou logo após ela)",
      "Evitar contrair crédito de longo prazo para necessidades estritamente sazonais sem analisar o efeito cumulativo em anos seguintes",
      "Considerar mistura de instrumentos (ex: estoque financiado, capital de giro, antecipação pontual de recebíveis), explicando vantagens e riscos de cada um",
      "Registrar um plano claro de uso do crédito, evitando que recursos destinados à temporada sejam drenados para outras despesas não relacionadas"
    ],
    "processo_dados_faltando": "Se o mapa de sazonalidade não estiver pronto, usar histórico de vendas dos últimos 12 a 24 meses para identificar meses mais fortes e mais fracos. Se o prazo de recebimento não for conhecido, trabalhar com médias por forma de pagamento (à vista, cartão, boleto) e indicar a necessidade de refinar esses dados no futuro.",
    "formato_saida": "Projeção simplificada de fluxo de caixa sazonal em tabela Markdown, com linhas representando meses e colunas como: 'Faturamento estimado', 'Entradas efetivas de caixa', 'Saídas operacionais', 'Parcelas de crédito', 'Saldo de caixa projetado'. Ao final, recomendações textuais sobre volume, prazo e tipo de linha mais adequado para a sazonalidade apresentada."
  }
],

"produtividade": [
  {
    "title": "Criar um sistema de planejamento semanal que equilibre trabalho estratégico e operacional.",
    "papel": "Consultor de Planejamento Inteligente para PMEs",
    "contexto": "O empreendedor passa o dia apagando incêndios, reage a demandas urgentes e não consegue reservar tempo para atividades estratégicas que fariam o negócio crescer.",
    "lista_verificacao_inicial": [
      "Horários de maior energia e foco ao longo do dia",
      "Tarefas estratégicas que vêm sendo negligenciadas",
      "Compromissos fixos e inegociáveis da semana",
      "Metas semanais mais importantes (negócio e pessoais)"
    ],
    "objetivo": "Estruturar uma semana que reserve blocos claros para trabalho estratégico (no mínimo 6 horas) e distribua atividades operacionais de forma realista, com espaço para imprevistos.",
    "restricoes": [
      "Respeitar o ritmo natural de energia (manhã, tarde, noite)",
      "Incluir buffers diários para imprevistos e demandas urgentes",
      "Manter espaço para vida pessoal e autocuidado, evitando agenda impossível",
      "Garantir que o plano possa ser ajustado com facilidade semana a semana"
    ],
    "processo_dados_faltando": "Se os horários de alta energia não forem informados, assumir blocos padrão (manhã para foco, tarde para operação) e sugerir um experimento de uma semana para mapear os melhores períodos na prática.",
    "formato_saida": "1) Template de planejamento semanal em formato de tabela Markdown (dias x blocos de horário), 2) Sistema simples de priorização (por exemplo: Estratégico, Operacional, Pessoal) e 3) Guia curto com 3 a 5 orientações de ajustes progressivos semanais."
  },
  {
    "title": "Implementar a técnica do tempo bloqueado para microempreendedores sobrecarregados.",
    "papel": "Especialista em Foco Profundo para Negócios Enxutos",
    "contexto": "O microempreendedor trabalha o dia inteiro, mas sente que não produz o suficiente, pois é constantemente interrompido e alterna tarefas o tempo todo.",
    "lista_verificacao_inicial": [
      "Principais fontes de interrupção no dia a dia",
      "Tarefas que exigem concentração profunda",
      "Compromissos inegociáveis (atendimentos, reuniões, horários de pico)",
      "Períodos com menos movimento ou menor volume de interrupções"
    ],
    "objetivo": "Criar um calendário com blocos de tempo protegidos para tarefas de alta importância, aumentando a entrega real em atividades críticas sem ampliar a carga horária.",
    "restricoes": [
      "Começar com blocos curtos de foco (25–45 minutos) para facilitar a adaptação",
      "Prever pequenas pausas entre blocos para descanso e reorientação",
      "Definir regras claras para proteger os blocos de interrupções desnecessárias",
      "Manter comunicação transparente com equipe e clientes sobre novos horários de atendimento"
    ],
    "processo_dados_faltando": "Se as fontes de interrupção não forem listadas, trabalhar com as mais comuns em PMEs (WhatsApp, ligações, redes sociais, demandas internas soltas) e sugerir um registro simples de interrupções por alguns dias.",
    "formato_saida": "1) Cronograma diário/semana em tabela Markdown com blocos de foco e blocos de atendimento, 2) Lista de técnicas práticas de proteção de foco (notificações, acordos de horário, regras de porta/Status) e 3) Mini-sistema de acompanhamento (checklist diário ou contagem de blocos concluídos)."
  },
  {
    "title": "Desenvolver sistema de delegação eficaz para gestores que precisam liberar tempo.",
    "papel": "Mentor em Delegação Inteligente para Gestores de PMEs",
    "contexto": "O gestor centraliza tudo, resolve problemas de todos e gasta grande parte do tempo em tarefas que poderiam ser feitas por outras pessoas, travando o crescimento da equipe e do negócio.",
    "lista_verificacao_inicial": [
      "Tarefas que realmente só o gestor pode fazer (decisão, relacionamento chave)",
      "Habilidades, potenciais e limitações da equipe atual",
      "Tarefas repetitivas ou operacionais que consomem muitas horas",
      "Nível atual de confiança e autonomia delegada à equipe"
    ],
    "objetivo": "Identificar atividades delegáveis, mapear quem pode assumi-las e estruturar um plano de delegação que libere pelo menos 5 horas semanais do gestor para liderança e estratégia.",
    "restricoes": [
      "Garantir transferência gradual de responsabilidade, com orientação e alinhamento de expectativas",
      "Definir claramente o que é responsabilidade da pessoa e o que permanece com o gestor",
      "Evitar microgestão após a delegação, mantendo acompanhamento por indicadores e checkpoints",
      "Documentar minimamente o passo a passo das tarefas críticas para facilitar a delegação"
    ],
    "processo_dados_faltando": "Se as habilidades da equipe não forem descritas, propor um mapeamento rápido de competências por pessoa (forças, pontos de atenção, interesses). Se não forem listadas tarefas repetitivas, usar exemplos comuns de PMEs (relatórios, follow-ups, agendamentos, rotinas administrativas).",
    "formato_saida": "1) Matriz de delegação em tabela Markdown (Tarefa x Quem executa x Nível de autonomia x Checkpoints), 2) Planos de treinamento ou sombra para tarefas críticas e 3) Sistema leve de acompanhamento (reuniões rápidas, indicadores simples ou quadro kanban)."
  },
  {
    "title": "Criar ritual matinal produtivo adaptado à realidade de empresários brasileiros.",
    "papel": "Arquiteto de Rituais Produtivos para Empreendedores",
    "contexto": "O empreendedor começa o dia já reagindo a WhatsApp, e-mails e problemas, sem um momento mínimo de organização mental, o que gera sensação de caos desde cedo.",
    "lista_verificacao_inicial": [
      "Rotina matinal atual (do acordar até o início do trabalho)",
      "Primeiras tarefas normalmente realizadas ao ligar o celular ou o computador",
      "Nível médio de energia pela manhã",
      "Principais preocupações que ocupam a mente ao acordar"
    ],
    "objetivo": "Desenvolver um ritual matinal de aproximadamente 30 minutos que traga mais clareza, sensação de controle e direcionamento para o resto do dia.",
    "restricoes": [
      "Adaptar o ritual a diferentes contextos (filhos, deslocamento, trabalho em casa ou presencial)",
      "Incluir pelo menos um elemento físico (movimento), um mental (organização) e um emocional (autoobservação ou gratidão)",
      "Evitar propostas que exijam mudanças radicais de horário logo de início",
      "Manter o ritual simples o suficiente para ser repetido em pelo menos 5 dias por semana"
    ],
    "processo_dados_faltando": "Se a rotina atual não for descrita, montar um ritual base com passos simples (hidratação, breve movimento, revisão da agenda, definição de 3 prioridades do dia) e indicar que o usuário pode adaptar conforme testar na prática.",
    "formato_saida": "1) Sequência detalhada do ritual matinal em passos numerados, 2) Sugestões de variações rápidas para dias mais corridos e 3) Guia de implementação gradual (como começar com 10 minutos e evoluir até 30)."
  },
  {
    "title": "Otimizar o processo de tomada de decisões rápidas do dia a dia.",
    "papel": "Especialista em Agilidade Decisória para Negócios",
    "contexto": "Pequenas decisões diárias (descontos, prazos, prioridades, exceções) consomem energia mental e tempo do gestor, que acaba cansado e sem foco para decisões realmente estratégicas.",
    "lista_verificacao_inicial": [
      "Decisões recorrentes que aparecem toda semana",
      "Critérios (explícitos ou implícitos) hoje usados para decidir",
      "Consequências mais comuns de decisões erradas ou demoradas",
      "Pessoas que poderiam decidir no lugar do gestor em alguns casos"
    ],
    "objetivo": "Reduzir o tempo e o desgaste mental com decisões operacionais, criando critérios, limites e fluxos simples para que a equipe trate boa parte das questões do dia a dia.",
    "restricoes": [
      "Balancear agilidade com segurança mínima (não decidir no impulso em temas sensíveis)",
      "Definir limites claros de valor, risco ou impacto para decisões delegadas",
      "Registrar decisões padrão em formato de diretriz para evitar recomeçar do zero a cada caso",
      "Prever revisão periódica das regras para ajustes conforme a empresa cresce"
    ],
    "processo_dados_faltando": "Se as decisões recorrentes não forem listadas, usar categorias típicas (descontos, prazos, condições especiais, prioridade de atendimento, exceções de política) e ajudar o usuário a exemplificar.",
    "formato_saida": "1) Framework de decisão rápida (perguntas em sequência: posso delegar? qual impacto? qual regra se aplica?), 2) Checklist de critérios para as principais decisões recorrentes e 3) Matriz de autoridade em tabela Markdown (Tipo de decisão x Quem decide x Limite x Quando escalar)."
  },
  {
    "title": "Implementar sistema de gerenciamento de interrupções e demandas externas.",
    "papel": "Gestor de Atenção Seletiva em PMEs",
    "contexto": "O empreendedor é interrompido o tempo todo por mensagens, ligações e dúvidas, o que fragmenta o dia, aumenta o estresse e reduz drasticamente a capacidade de foco profundo.",
    "lista_verificacao_inicial": [
      "Principais fontes de interrupção (internas e externas)",
      "Horários de maior incidência de demandas inesperadas",
      "Forma atual de triagem (se é que existe)",
      "Pessoas ou canais que poderiam filtrar ou organizar as demandas"
    ],
    "objetivo": "Criar um sistema simples de triagem e horários protegidos que diminua interrupções não essenciais sem prejudicar a experiência do cliente.",
    "restricoes": [
      "Manter um canal claro para questões realmente urgentes",
      "Comunicar as novas regras de forma positiva, explicando o benefício para todos",
      "Evitar criar barreiras excessivas que afastem clientes ou equipe",
      "Incluir formas de registrar demandas para resposta posterior (fila organizada)"
    ],
    "processo_dados_faltando": "Se as fontes de interrupção não forem detalhadas, propor que o usuário registre todas as interrupções por dois a três dias, categorizando-as por tipo e urgência percebida.",
    "formato_saida": "1) Protocolo de triagem em texto (como classificar urgente, importante e não urgente), 2) Sistema de horários protegidos em tabela Markdown (janela para foco, janela para atendimento) e 3) Scripts de comunicação para explicar o novo fluxo à equipe e clientes."
  },
  {
    "title": "Desenvolver método de revisão semanal para aprendizado e ajuste contínuo.",
    "papel": "Facilitador de Aprendizado Contínuo para Gestores",
    "contexto": "Sem uma revisão estruturada, a semana passa e o gestor repete os mesmos erros na gestão do tempo, sem aproveitar aprendizados nem consolidar o que funcionou bem.",
    "lista_verificacao_inicial": [
      "Se existe hoje algum tipo de revisão ou apenas 'fecha a semana e recomeça'",
      "Principais desafios percebidos na organização do tempo",
      "Vitórias recentes em produtividade que merecem ser repetidas",
      "Metas pessoais e profissionais de melhoria"
    ],
    "objetivo": "Criar um ritual de revisão semanal de 30 a 45 minutos que ajude a analisar o que funcionou, o que não funcionou e definir experimentos de ajustes para a semana seguinte.",
    "restricoes": [
      "Focar em aprendizado e melhoria contínua, evitando autocrítica pesada",
      "Registrar por escrito os principais insights e decisões",
      "Incluir momento para celebrar pequenos avanços e conquistas",
      "Manter o método simples, para não virar mais uma tarefa pesada"
    ],
    "processo_dados_faltando": "Se não houver qualquer método atual, usar perguntas-guia simples (o que deu certo, o que não deu, o que vou fazer diferente) como base para montar o ritual.",
    "formato_saida": "1) Template de revisão semanal em formato de perguntas/reflexões, 2) Modelo simples de registro (em Markdown ou lista) para acompanhar evolução e 3) Pequeno plano de experimentos semanais (1 a 3 mudanças por semana)."
  },
  {
    "title": "Criar sistema de gestão de energia (não apenas tempo) para prevenção de burnout.",
    "papel": "Consultor em Gestão de Energia para Empreendedores",
    "contexto": "Mesmo organizando a agenda, o empreendedor vive exausto, com sono ruim, irritação e dificuldade de concentração, o que indica um problema de energia, não só de tempo.",
    "lista_verificacao_inicial": [
      "Atividades diárias que recarregam energia (ou que já recarregaram no passado)",
      "Atividades que mais drenam energia física, mental e emocional",
      "Sinais físicos e emocionais de cansaço constante",
      "Práticas atuais de autocuidado (sono, alimentação, movimento, descanso mental)"
    ],
    "objetivo": "Desenvolver um plano de gestão de energia que combine pequenos ajustes de rotina, pausas inteligentes e práticas de recuperação, reduzindo a sensação de exaustão ao longo de 30 dias.",
    "restricoes": [
      "Considerar as dimensões física, mental e emocional de forma integrada",
      "Propor mudanças pequenas e progressivas, evitando planos radicais",
      "Adaptar as sugestões à realidade financeira e de tempo do empreendedor",
      "Evitar recomendações médicas específicas, focando em organização de rotina e hábitos gerais"
    ],
    "processo_dados_faltando": "Se atividades que recarregam energia não forem listadas, sugerir experimentos breves com diferentes práticas (caminhada leve, pausas sem tela, respiração, hobbies simples) e pedir para o usuário observar efeitos ao longo da semana.",
    "formato_saida": "1) Mapa de energia pessoal em tabela (atividades que recarregam x drenam), 2) Plano de recarga estratégica com pequenas ações diárias e semanais e 3) Sistema simples de monitoramento (escala de energia diária de 1 a 5 com espaço para notas)."
  },
  {
    "title": "Otimizar o uso de tecnologia para automação de tarefas repetitivas.",
    "papel": "Especialista em Produtividade Digital para PMEs",
    "contexto": "Muitas horas por semana são gastas em tarefas repetitivas (envio de mensagens, organização de agenda, lançamentos manuais) que poderiam ser automatizadas com ferramentas simples.",
    "lista_verificacao_inicial": [
      "Lista das tarefas mais repetitivas e previsíveis do dia ou da semana",
      "Ferramentas tecnológicas já utilizadas (planilhas, sistemas, aplicativos)",
      "Orçamento disponível para novas ferramentas (se houver)",
      "Nível de familiaridade da equipe com tecnologia e automações simples"
    ],
    "objetivo": "Identificar oportunidades de automação de baixo custo e fácil implementação que liberem pelo menos algumas horas semanais para atividades de maior valor.",
    "restricoes": [
      "Priorizar soluções simples e com curva de aprendizado baixa",
      "Evitar depender de ferramentas muito complexas ou caras para uma PME",
      "Garantir que exista alguém responsável por cuidar das automações criadas",
      "Planejar transição gradual do processo manual para o automatizado"
    ],
    "processo_dados_faltando": "Se as tarefas repetitivas não forem listadas, trabalhar com casos comuns (agendamentos, lembretes, cobranças, e-mails padrão, organização de leads) e sugerir que o usuário faça um levantamento rápido ao longo de alguns dias.",
    "formato_saida": "1) Lista priorizada de automações em formato de tabela Markdown (Tarefa, Ferramenta sugerida, Esforço de implantação, Horas economizadas estimadas), 2) Mini-tutorial em texto para as 1 ou 2 automações mais importantes e 3) Plano de implantação gradual em etapas."
  },
  {
    "title": "Desenvolver sistema de produtividade em equipe para gestores.",
    "papel": "Arquiteto de Produtividade Coletiva",
    "contexto": "A equipe trabalha muito, mas há retrabalho, tarefas esquecidas e falta de clareza de prioridades, o que reduz o resultado coletivo mesmo com pessoas esforçadas.",
    "lista_verificacao_inicial": [
      "Sistemas atuais de comunicação interna (reuniões, WhatsApp, e-mail, ferramentas)",
      "Ferramentas de gestão de tarefas ou projetos já utilizadas (se existirem)",
      "Nível de clareza sobre prioridades semanais e mensais para a equipe",
      "Grau de maturidade em accountability (quem é dono de quê)"
    ],
    "objetivo": "Criar um sistema simples e compartilhado de organização de trabalho em equipe, com clareza de prioridades, responsáveis e prazos, melhorando a produtividade coletiva sem virar burocracia.",
    "restricoes": [
      "Respeitar o porte da empresa e o nível de maturidade da equipe",
      "Manter o sistema de tarefas e comunicação o mais simples possível (menos ferramentas, melhor uso)",
      "Definir rituais curtos de alinhamento (reuniões rápidas semanais ou diárias)",
      "Garantir que todos entendam o sistema e saibam usá-lo no dia a dia"
    ],
    "processo_dados_faltando": "Se não forem descritas ferramentas ou rotinas atuais, propor um modelo base usando ferramentas simples (planilha compartilhada ou aplicativo gratuito) e sugerir rituais mínimos de alinhamento.",
    "formato_saida": "1) Desenho de um sistema integrado de produtividade em equipe (comunicação + tarefas + acompanhamento), 2) Plano de implementação em fases (piloto, ajustes, expansão) e 3) Tabela em Markdown com exemplo de quadro de tarefas (Responsável, Tarefa, Prioridade, Prazo, Status)."
  }
],

  /* ---------- EM BREVE INOVAÇÃO & CRIATIVIDADE ---------- */

  /* ---------- EM BREVE LIDERANÇA & GESTÃO DE EQUIPE ---------- */
};

/* ===========================
   STATE MANAGEMENT
   =========================== */
let currentList = [];
let currentIndex = 0;
let currentPrompt = '';
let currentTitle = '';
let currentChapterKey = '';
let lastScrollPosition = 0;
let toastTimer = null;

/* ===========================
   DOM ELEMENTS
   =========================== */
const backdrop = document.getElementById('modal-backdrop');
const modal = document.getElementById('prompt-modal');
const modalContent = document.getElementById('modal-content');
const modalTitle = document.getElementById('modal-title');
const modalDescription = document.getElementById('modal-description');
const modalProgress = document.getElementById('modal-progress');

/* ===========================
   EMBED DETECTION & COMPATIBILITY
   =========================== */
function setupEmbedCompatibility() {
  // Detecta se está em iframe
  const isEmbed = (() => {
    try { return window.self !== window.top } catch { return true }
  })();

  if (isEmbed) {
    document.documentElement.classList.add('is-embed');

    // Remove classes comuns de travamento
    ['no-scroll','lock-scroll','scroll-locked'].forEach(cls => {
      document.documentElement.classList.remove(cls);
      document.body.classList.remove(cls);
    });

    // Heurística de webview mobile
    const isCoarse = window.matchMedia && window.matchMedia('(pointer:coarse)').matches;
    const isSmallVH = window.innerHeight && window.innerHeight <= 850;
    const ua = (navigator.userAgent || '').toLowerCase();
    const isAndroid = ua.includes('android');
    const isIOS = /\(i[^;]+;( u;)? cpu.+mac os x/.test(navigator.userAgent);
    if (isCoarse || isSmallVH || isAndroid || isIOS) {
      document.documentElement.classList.add('iframe-mobile');
    }

    // Destrava rolagem no embed, sem criar containers roláveis internos
    document.documentElement.style.overflowY = 'auto';
    document.body.style.overflowY = 'auto';
    document.documentElement.style.webkitOverflowScrolling = 'touch';
    document.body.style.webkitOverflowScrolling = 'touch';
    document.documentElement.style.overscrollBehaviorY = 'contain';
    document.body.style.overscrollBehaviorY = 'contain';
    document.body.style.touchAction = 'pan-y';
  }

  // Fallback de 100vh em navegadores antigos
  const supportsDVH = window.CSS && CSS.supports && CSS.supports('height','100dvh');
  if (!supportsDVH) {
    const setVH = () => {
      const vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty('--vh', `${vh}px`);
    };
    setVH();
    window.addEventListener('resize', setVH, { passive:true });
    window.addEventListener('orientationchange', setVH, { passive:true });
  }
}

/* ===========================
   UTILITY FUNCTIONS
   =========================== */
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text ?? '';
  return div.innerHTML;
}

// Debounce
const debounce = (func, wait) => {
  let timeout;
  return function executedFunction(...args) {
    const later = () => { clearTimeout(timeout); func(...args); };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
};

// Copiar com fallback
async function copyText(text) {
  try {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      await navigator.clipboard.writeText(text);
    } else {
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    }
    showNotification('Copiado! 📋', 'success');
  } catch {
    showNotification('Erro ao copiar', 'error');
  }
}

// Toast
function showNotification(msg, type = 'success') {
  const el = document.getElementById('toast');
  if (!el) return;
  el.textContent = msg;
  el.className = 'toast show ' + type;
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => { el.className = 'toast'; }, 3000);
}

/* ===========================
   MODAL SYSTEM
   =========================== */
function initializeTipSystem() {
  const tip = document.querySelector('.modal-content .tip');
  if (!tip) return;
  const title = tip.querySelector('.tip-title');
  const content = tip.querySelector('.tip-content');
  const chev = tip.querySelector('.chev');
  if (!title || !content || !chev) return;

  content.style.display = 'none';
  chev.style.transform = 'rotate(0deg)';
  tip.classList.remove('open');

  title.addEventListener('click', function(e) {
    const isOpen = tip.classList.contains('open');
    tip.classList.toggle('open', !isOpen);
    content.style.display = isOpen ? 'none' : 'block';
    chev.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    e.stopPropagation();
  });
}

function initializeModalContent() {
  setTimeout(initializeTipSystem, 50);
}

// Gera prompt
function generatePromptFromStructure(item) {
  let prompt = `🧠 Atue como: ${item.papel}\n\n`;

  // Troca de texto do cenário -> "🎯 Contexto:"
  prompt += `🎯 Contexto:\n${item.contexto}\n\n`;

  prompt += `📊 Considere as informações disponíveis:\n`;
  if (item.lista_verificacao_inicial && item.lista_verificacao_inicial.length > 0) {
    item.lista_verificacao_inicial.forEach((dado, i) => {
      prompt += `${i + 1}. ${dado}\n`;
    });
  } else {
    prompt += `Descreva os dados relevantes disponíveis.\n`;
  }

  prompt += `\n⚙️ Siga estas diretrizes:\n`;
  const diretrizes = [];

  // Objetivo (com bullet)
  if (item.objetivo) {
    diretrizes.push(`• Objetivo principal: ${item.objetivo}`);
  }

  // Restrições (com bullet)
  if (item.restricoes && item.restricoes.length) {
    item.restricoes.forEach(r => diretrizes.push(`• ${r}`));
  }

  // Processo para dados faltando (SEM bullet + COM espaçamento antes)
  if (item.processo_dados_faltando) {
    diretrizes.push(`\n❗ Quando faltarem dados: ${item.processo_dados_faltando}`);
  }

  if (diretrizes.length) {
    diretrizes.forEach(d => {
      prompt += `${d}\n`;
    });
  } else {
    prompt += `• Explique sua linha de raciocínio e valide se há dados insuficientes.\n`;
  }

  prompt += `\n📝 Apresente o resultado assim:\n${item.formato_saida}`;

  return prompt;
}

function applyToModal(description = '') {
  const item = currentList[currentIndex];
  if (!item) return;

  currentTitle = item.title;
  const promptCompleto = generatePromptFromStructure(item);

  modalContent.innerHTML = `

<div class="prompt-section">
  <div class="prompt-content">${escapeHtml(promptCompleto)}</div>
</div>`;

  modalTitle.textContent = currentTitle;
  modalDescription.textContent = description;
  modalProgress.textContent = `${currentIndex + 1}/${currentList.length}`;
  modalProgress.style.display = ''; // reexibe caso tenha sido ocultado na busca

  updateNavButtons();

  const copyButtonText = document.getElementById('copy-prompt-btn-text');
  if (copyButtonText) {
    copyButtonText.textContent = `Copiar Prompt (${currentIndex + 1}/${currentList.length})`;
  }

  currentPrompt = promptCompleto;
  initializeModalContent();
}

function updateNavButtons() {
  const prevBtn = document.querySelector('.modal-nav button:first-child');
  const nextBtn = document.querySelector('.modal-nav button:last-child');
  if (prevBtn) {
    prevBtn.disabled = currentIndex === 0;
    prevBtn.setAttribute('aria-label', currentIndex === 0 ? 'Primeiro prompt' : 'Prompt anterior');
  }
  if (nextBtn) {
    nextBtn.disabled = currentIndex === currentList.length - 1;
    nextBtn.setAttribute('aria-label', currentIndex === currentList.length - 1 ? 'Último prompt' : 'Próximo prompt');
  }
}

function showChapterPrompts(key) {
  lastScrollPosition = window.scrollY || 0;

  const list = PROMPTS[key] || [];
  if (!list.length) {
    showNotification('Em breve adicionaremos os prompts desta seção.', 'info');
    return;
  }

  let chapterCard = document.querySelector(`.chapter-card[data-key="${key}"]`);
  if (!chapterCard) {
    chapterCard = Array.from(document.querySelectorAll('.chapter-card'))
      .find(el => (el.getAttribute('onclick') || '').includes(`'${key}'`));
  }

  const description = chapterCard ?
    (chapterCard.querySelector('.chapter-description')?.textContent || '').trim() : '';

  document.querySelectorAll('.chapter-card').forEach(card => card.classList.remove('active'));
  if (chapterCard) chapterCard.classList.add('active');

  currentChapterKey = key;
  currentList = list;
  currentIndex = 0;

  applyToModal(description);
  openModal();
}

function nextPrompt() {
  if (currentIndex < currentList.length - 1) {
    currentIndex++;
    const description = modalDescription.textContent;
    applyToModal(description);
  } else {
    showNotification('🎉 Último prompt desta seção!', 'success');
  }
}

function prevPrompt() {
  if (currentIndex > 0) {
    currentIndex--;
    const description = modalDescription.textContent;
    applyToModal(description);
  } else {
    showNotification('📄 Primeiro prompt da seção', 'info');
  }
}

function openModal() {
  if (!backdrop || !modal) return;

  backdrop.classList.add('active');
  modal.classList.add('active');

  const isEmbed = document.documentElement.classList.contains('is-embed');

  // Em embed, não bloqueie rolagem do fundo. Fora do embed, bloqueio leve.
  if (!isEmbed) {
    const preventBackgroundScroll = (e) => {
      if (!modal.contains(e.target)) e.preventDefault();
    };
    modal._preventBgScroll = preventBackgroundScroll;
    document.addEventListener('touchmove', preventBackgroundScroll, { passive:false });
  }

  document.addEventListener('keydown', onModalKeydown);

  const firstFocusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
  if (firstFocusable) firstFocusable.focus();
}

function closeModal() {
  if (!backdrop || !modal) return;

  backdrop.classList.remove('active');
  modal.classList.remove('active');

  if (modal._preventBgScroll) {
    document.removeEventListener('touchmove', modal._preventBgScroll);
    modal._preventBgScroll = null;
  }
  document.removeEventListener('keydown', onModalKeydown);

  // Restaura posição de scroll do fundo
  setTimeout(() => { window.scrollTo(0, lastScrollPosition || 0); }, 10);
}

// Teclas dentro do modal
function onModalKeydown(e) {
  const isOpen = backdrop.classList.contains('active');
  if (!isOpen) return;

  switch(e.key) {
    case 'Escape':
      e.preventDefault(); closeModal(); break;
    case 'ArrowRight':
      if (!e.ctrlKey && !e.metaKey) { e.preventDefault(); nextPrompt(); }
      break;
    case 'ArrowLeft':
      if (!e.ctrlKey && !e.metaKey) { e.preventDefault(); prevPrompt(); }
      break;
    case 'c':
      if (e.ctrlKey || e.metaKey) { e.preventDefault(); copyCurrentPrompt(); }
      break;
  }
}

// Focus trap dentro do modal
if (modal) {
  modal.addEventListener('keydown', (e) => {
    if (e.key !== 'Tab') return;
    const focusables = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (!focusables.length) return;
    const first = focusables[0];
    const last = focusables[focusables.length - 1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  });
}

function copyCurrentPrompt() { copyText(currentPrompt); }

/* ===========================
   SCROLL & UI EFFECTS
   =========================== */
function updateHeader() {
  const header = document.getElementById('header');
  if (!header) return;
  // Mantido vazio por ora, mas a função existe para futuras microinterações
}

// Smooth scroll sem bloquear fundo em embed
function initSmoothScroll() {
  const supportsSmooth = 'scrollBehavior' in document.documentElement.style;
  const isEmbed = document.documentElement.classList.contains('is-embed');

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (!href || href === '#') return;
      const target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: (isEmbed || !supportsSmooth) ? 'auto' : 'smooth', block:'start' });
    });
  });
}

/* ===========================
   THEME MANAGEMENT
   =========================== */
let currentTheme = 'dark';

function toggleTheme() {
  currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', currentTheme);
  try { localStorage.setItem('theme', currentTheme); } catch {}
  const button = document.getElementById('theme-toggle');
  if (button) {
    button.style.transform = 'scale(0.9)';
    setTimeout(() => { button.style.transform = ''; }, 150);
  }
  showNotification(currentTheme === 'dark' ? '🌙 Modo escuro ativado' : '☀️ Modo claro ativado', 'success');
}

function initializeTheme() {
  try {
    const stored = localStorage.getItem('theme');
    currentTheme = stored ? stored :
      (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
  } catch { currentTheme = 'dark'; }
  document.documentElement.setAttribute('data-theme', currentTheme);
}

/* ===========================
   SEARCH SYSTEM
   =========================== */
function initSearch() {
  const searchInput = document.getElementById('search-input');
  if (!searchInput) return;

  const suggestionsContainer = document.createElement('div');
  suggestionsContainer.className = 'search-suggestions';
  searchInput.parentNode.appendChild(suggestionsContainer);

  function showSuggestions(term) {
    suggestionsContainer.innerHTML = '';
    suggestionsContainer.style.display = 'none';
    const hasVisibleResults = document.querySelector('.chapter-card.search-match, .show-all-results-btn');
    if (hasVisibleResults || term.length < 2) return;

    const allTerms = getAllSearchableTerms();
    const matches = allTerms.filter(item => item.toLowerCase().includes(term.toLowerCase())).slice(0, 5);
    if (matches.length > 0) {
      matches.forEach(match => {
        const div = document.createElement('div');
        div.className = 'search-suggestion';
        div.innerHTML = highlightMatch(match, term);
        div.addEventListener('click', () => {
          searchInput.value = match;
          suggestionsContainer.style.display = 'none';
          performSearch(match);
        });
        suggestionsContainer.appendChild(div);
      });
      suggestionsContainer.style.display = 'block';
    }
  }

  function clearSearch() {
    suggestionsContainer.style.display = 'none';
    document.querySelectorAll('.chapter-card').forEach(card => {
      card.style.opacity = '1';
      card.style.display = 'block';
      card.classList.remove('search-match', 'search-no-match');
      const counter = card.querySelector('.search-counter');
      if (counter) counter.style.display = 'none';
    });
    const existingButton = document.querySelector('.show-all-results-btn');
    if (existingButton) existingButton.remove();
  }

  searchInput.addEventListener('input', function(e) {
    const term = e.target.value;
    if (term === '') { clearSearch(); return; }
    showSuggestions(term);
    performSearch(term);
  });

  document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
      suggestionsContainer.style.display = 'none';
    }
  });

  searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      performSearch(searchInput.value);
      suggestionsContainer.style.display = 'none';
    }
  });
}

function performSearch(searchTerm) {
  const chapterCards = document.querySelectorAll('.chapter-card');
  const term = searchTerm.toLowerCase().trim();

  // Limpeza
  chapterCards.forEach(card => {
    const counter = card.querySelector('.search-counter');
    if (counter) counter.style.display = 'none';
    card.classList.remove('search-match', 'search-no-match');
    card.style.display = 'block';
    card.style.opacity = '1';
  });

  const existingButton = document.querySelector('.show-all-results-btn');
  if (existingButton) existingButton.remove();

  const suggestionsContainer = document.querySelector('.search-suggestions');
  if (suggestionsContainer) suggestionsContainer.style.display = 'none';

  if (term === '') return;

  let foundMatches = false;
  const matchingPrompts = [];

  chapterCards.forEach(card => {
    const onclickAttr = card.getAttribute('onclick');
    const dataCategory = card.getAttribute('data-category');
    const keyFromOnclick = onclickAttr && /'([^']+)'/.exec(onclickAttr)?.[1];
    const categoryKey = dataCategory || keyFromOnclick;
    if (!categoryKey) return;

    const prompts = PROMPTS[categoryKey] || [];
    const categoryMatches = prompts.filter(p =>
      (p.title && p.title.toLowerCase().includes(term)) ||
      (p.papel && p.papel.toLowerCase().includes(term)) ||
      (p.objetivo && p.objetivo.toLowerCase().includes(term))
    );

    if (categoryMatches.length > 0) {
      card.classList.add('search-match');
      card.style.opacity = '1';
      let counter = card.querySelector('.search-counter');
      if (!counter) {
        counter = document.createElement('div');
        counter.className = 'search-counter';
        card.appendChild(counter);
      }
      counter.textContent = categoryMatches.length;
      counter.style.display = 'block';
      foundMatches = true;

      categoryMatches.forEach(match => {
        matchingPrompts.push({
          ...match,
          category: categoryKey,
          categoryName: getCategoryName(categoryKey)
        });
      });
    } else {
      card.classList.add('search-no-match');
      card.style.opacity = '0.4';
    }
  });

  if (foundMatches && matchingPrompts.length > 0) {
    addViewAllButton(matchingPrompts, term);
  } else if (term.length > 0) {
    showNotification('Nenhum prompt encontrado para: ' + term, 'error');
  }
}

function addViewAllButton(results, searchTerm) {
  const searchContainer = document.querySelector('.search-container');
  const existingButton = document.querySelector('.show-all-results-btn');
  if (existingButton) existingButton.remove();

  const button = document.createElement('button');
  button.className = 'show-all-results-btn';
  button.innerHTML = `
    <span>🔍 Ver todos os ${results.length} resultados para "${searchTerm}"</span>
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
  `;
  button.addEventListener('click', () => { showSearchResultsModal(results, searchTerm); });
  searchContainer.appendChild(button);
}

function getAllSearchableTerms() {
  const terms = [];
  Object.keys(PROMPTS).forEach(category => {
    (PROMPTS[category] || []).forEach(prompt => {
      if (prompt.title) terms.push(prompt.title);
      if (prompt.papel) terms.push(prompt.papel);
    });
  });
  return [...new Set(terms)];
}

function getCategoryName(key) {
  const names = {
    gestao: 'Gestão & Estratégia',
    financas: 'Finanças',
    tributario: 'Tributário & Fiscal',
    operacoes: 'Operações & Estoque',
    compras: 'Compras & Suprimentos',
    marketing: 'Marketing & Vendas',
    comunicacao: 'Comunicação & Cliente',
    credito: 'Crédito & Fomento',
    rh: 'RH & Pessoas'
  };
  return names[key] || key;
}

function highlightMatch(text, term) {
  const regex = new RegExp(`(${term})`, 'gi');
  return String(text).replace(regex, '<mark>$1</mark>');
}

function showSearchResultsModal(results, searchTerm) {
  if (results.length === 0) return;

  const modalContentHTML = `
    <div class="search-results-header">
      <h3>🔍 Resultados para: "${searchTerm}"</h3>
      <p>Encontrados ${results.length} prompts em ${[...new Set(results.map(r => r.categoryName))].length} categorias</p>
    </div>
    <div class="search-results-list">
      ${results.map((result, index) => `
        <div class="search-result-item" onclick="openPromptFromSearch('${result.category}', ${findPromptIndex(result.category, result.title)})">
          <div class="result-header">
            <span class="result-badge">${result.categoryName}</span>
            <span class="result-number">${index + 1}</span>
          </div>
          <h4 class="result-title">${escapeHtml(result.title)}</h4>
          <p class="result-role">👤 ${escapeHtml(result.papel || '')}</p>
          <p class="result-objective">${result.objetivo ? escapeHtml(result.objetivo).slice(0,100) + '...' : ''}</p>
        </div>
      `).join('')}
    </div>
    <div class="search-results-actions">
      <button class="btn btn-ghost" onclick="closeModal()">Voltar para categorias</button>
    </div>
  `;

  modalTitle.textContent = 'Resultados da Busca';
  modalDescription.textContent = '';
  modalContent.innerHTML = modalContentHTML;
  modalProgress.style.display = 'none';

  openModal();
}

function findPromptIndex(category, title) {
  const prompts = PROMPTS[category] || [];
  return prompts.findIndex(prompt => prompt.title === title);
}

function openPromptFromSearch(category, index) {
  currentChapterKey = category;
  currentList = PROMPTS[category] || [];
  currentIndex = Math.max(0, index);
  const chapterCard = document.querySelector(`.chapter-card[onclick*="${category}"], .chapter-card[data-category="${category}"]`);
  const description = chapterCard ? (chapterCard.querySelector('.chapter-description')?.textContent || '').trim() : '';
  applyToModal(description);
}

/* ===========================
   CHAPTER CARDS INITIALIZATION
   =========================== */
function initializeChapterCards() {
  document.querySelectorAll('.chapter-card[data-category]').forEach(card => {
    const category = card.getAttribute('data-category');
    card.addEventListener('click', () => { showChapterPrompts(category); });
    card.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); showChapterPrompts(category); }
    });
  });
}

// ==========================
// AUTO-PULAR PARA #secoes NO EMBED (Kiwify)
// ==========================
function jumpToSectionsOnEmbed() {
  const isEmbed = document.documentElement.classList.contains('is-embed');
  if (!isEmbed) return;

  const go = () => {
    const target = document.getElementById('secoes');
    if (!target) return;

    try {
      target.scrollIntoView({ behavior: 'auto', block: 'start' });
    } catch {
      if (location.hash !== '#secoes') location.hash = '#secoes';
    }
  };

  // espera o layout estabilizar
  requestAnimationFrame(() => setTimeout(go, 80));
}

/* ===========================
   INITIALIZATION
   =========================== */
function initializeApp() {
  try {
    // 1) Embed primeiro
    setupEmbedCompatibility();

// 1.5) Se estiver no embed, já pula para #secoes
jumpToSectionsOnEmbed();

    // 2) Tema
    initializeTheme();

    // 3) Scroll effects com guarda
    let scrollRAF = null;
    const onScroll = () => {
      if (scrollRAF) return;
      scrollRAF = requestAnimationFrame(() => {
        try { /* updateScrollProgress opcional */ } catch {}
        try { updateHeader(); } catch {}
        scrollRAF = null;
      });
    };
    window.addEventListener('scroll', onScroll, { passive:true });
    window.addEventListener('load', onScroll, { once:true });

    // 4) Âncoras
    initSmoothScroll();

    // 5) Cards
    initializeChapterCards();

    // 6) Acessibilidade modal (labels padrão)
    document.querySelectorAll('.modal-nav button').forEach(btn => {
      if (!btn.getAttribute('aria-label')) {
        const isPrev = (btn.textContent || '').toLowerCase().includes('anterior');
        btn.setAttribute('aria-label', isPrev ? 'Prompt anterior' : 'Próximo prompt');
      }
    });

    // 7) Busca
    initSearch();

    // 8) IntersectionObserver defensivo
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.style.animationPlayState = 'running'; });
      }, { threshold:0.1, rootMargin:'0px 0px -10% 0px' });
      document.querySelectorAll('.chapter-card').forEach(el => observer.observe(el));
    }

    console.log('Aplicação inicializada');
  } catch (err) {
    console.error('Falha ao iniciar', err);
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeApp);
} else {
  initializeApp();
}

/* ===========================
   GLOBAL FUNCTIONS
   =========================== */
window.toggleTheme = toggleTheme;
window.showChapterPrompts = showChapterPrompts;
window.nextPrompt = nextPrompt;
window.prevPrompt = prevPrompt;
window.copyCurrentPrompt = copyCurrentPrompt;
window.closeModal = closeModal;
window.openPromptFromSearch = openPromptFromSearch;
</script>
</body>
</html>