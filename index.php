<?php
/**
 * Movie Tracker - Premium Cinematic Dashboard
 * Refăcut complet cu un design modern, glassmorphic, animații fluide și stocare locală.
 */
date_default_timezone_set('Europe/Bucharest');
$current_time = date('d-m-Y H:i:s');
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Tracker — Organizează-ți experiența cinematografică</title>
    
    <!-- Google Fonts: Outfit (modern, geometric) și JetBrains Mono pentru date numerice -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #06070a;
            --bg-glass: rgba(13, 15, 28, 0.7);
            --bg-glass-hover: rgba(20, 22, 41, 0.85);
            --border-glass: rgba(255, 255, 255, 0.08);
            --border-glass-hover: rgba(255, 255, 255, 0.15);
            
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --text-dark: #6b7280;
            
            --accent-primary: #6366f1;
            --accent-secondary: #a855f7;
            --accent-gradient: linear-gradient(135deg, #6366f1, #a855f7);
            
            --status-completed: #10b981;
            --status-completed-bg: rgba(16, 185, 129, 0.1);
            --status-watching: #06b6d4;
            --status-watching-bg: rgba(6, 182, 212, 0.1);
            --status-plan: #f59e0b;
            --status-plan-bg: rgba(245, 158, 11, 0.1);
            
            --rating-color: #fbbf24;
            --font-main: 'Outfit', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            
            --shadow-neon: 0 8px 32px rgba(99, 102, 241, 0.2);
            --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: var(--font-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Fundal dinamic nebulos */
        .nebula {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.6;
            pointer-events: none;
        }

        .nebula-purple {
            top: -10%;
            left: -10%;
            width: 45vw;
            height: 45vw;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.25) 0%, rgba(168, 85, 247, 0) 70%);
            animation: floatSlow 20s infinite alternate;
        }

        .nebula-blue {
            bottom: -10%;
            right: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, rgba(99, 102, 241, 0) 70%);
            animation: floatSlow 25s infinite alternate-reverse;
        }

        @keyframes floatSlow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(4%, 5%) scale(1.1); }
        }

        /* Containerul principal pentru tranzitia ecranelor */
        .app-wrapper {
            width: 100%;
            max-width: 1200px;
            padding: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ------------------ WELCOME CARD ------------------ */
        .welcome-card {
            background: var(--bg-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 28px;
            padding: 3.5rem 2.5rem;
            width: 100%;
            max-width: 480px;
            text-align: center;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            transition: var(--transition-smooth);
            animation: scaleIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Efect de stralucire ambientala pe card */
        .welcome-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.4), transparent);
        }

        .welcome-card.hidden {
            opacity: 0;
            transform: scale(0.9) translateY(-30px);
            pointer-events: none;
            display: none !important;
        }

        .logo-box {
            margin-bottom: 2rem;
            display: inline-block;
            position: relative;
        }

        .clapperboard-svg {
            filter: drop-shadow(0 8px 16px rgba(99, 102, 241, 0.35));
            transition: var(--transition-smooth);
        }

        .welcome-card:hover .clapperboard-svg {
            transform: scale(1.08) rotate(-5deg);
        }

        .clapper-bar {
            transform-origin: 4px 11px;
            animation: clapAnim 3s infinite ease-in-out;
        }

        @keyframes clapAnim {
            0%, 100% { transform: rotate(0deg); }
            45%, 55% { transform: rotate(-10deg); }
        }

        .welcome-card h1 {
            font-size: 2.6rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #ffffff 40%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .welcome-card .tagline {
            color: var(--text-muted);
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 2.2rem;
        }

        .welcome-card .time-box {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0.7rem 1.4rem;
            border-radius: 16px;
            color: var(--text-main);
            font-family: var(--font-mono);
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 3rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .welcome-card .time-box svg {
            color: var(--accent-primary);
            filter: drop-shadow(0 0 4px rgba(99, 102, 241, 0.5));
        }

        .action-button {
            width: 100%;
            background: var(--accent-gradient);
            color: #ffffff;
            border: none;
            border-radius: 16px;
            padding: 1.1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: var(--font-main);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: var(--shadow-neon);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.6s ease;
        }

        .action-button:hover::before {
            left: 100%;
        }

        .action-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 36px rgba(99, 102, 241, 0.35);
            filter: brightness(1.05);
        }

        .action-button:active {
            transform: translateY(0);
        }

        .action-button svg {
            transition: var(--transition-smooth);
        }

        .action-button:hover svg {
            transform: translateX(4px);
        }

        /* ------------------ DASHBOARD CONTAINER ------------------ */
        .dashboard-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            opacity: 1;
            transition: var(--transition-smooth);
        }

        .dashboard-container.hidden {
            display: none !important;
            opacity: 0;
        }

        /* Header Dashboard */
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            padding: 1.25rem 2rem;
            border-radius: 20px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .dash-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .dash-brand h2 {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dash-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .dash-clock {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.02);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .dash-clock svg {
            color: var(--accent-primary);
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            color: #ef4444;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-family: var(--font-main);
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition-smooth);
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
        }

        /* Statistici */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
        }

        .stat-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            padding: 1.5rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: var(--transition-smooth);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: var(--border-glass-hover);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-icon.purple { background: rgba(99, 102, 241, 0.1); color: #818cf8; }
        .stat-icon.green { background: rgba(16, 185, 129, 0.1); color: #34d399; }
        .stat-icon.cyan { background: rgba(6, 182, 212, 0.1); color: #22d3ee; }
        .stat-icon.gold { background: rgba(251, 191, 36, 0.1); color: #fbbf24; }

        .stat-detalii {
            display: flex;
            flex-direction: column;
        }

        .stat-valoare {
            font-size: 1.75rem;
            font-weight: 700;
            font-family: var(--font-mono);
            line-height: 1.2;
            color: #ffffff;
        }

        .stat-titlu {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.1rem;
        }

        /* Control Panel: Căutare, Filtre, Buton Adăugare */
        .controls-panel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .search-container {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .search-container svg {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dark);
            transition: var(--transition-smooth);
            pointer-events: none;
        }

        .input-search {
            width: 100%;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            padding: 1rem 1rem 1rem 3rem;
            color: #ffffff;
            font-family: var(--font-main);
            font-size: 0.95rem;
            transition: var(--transition-smooth);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .input-search:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15), inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .input-search:focus + svg {
            color: var(--accent-primary);
        }

        .filter-group {
            display: flex;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-glass);
            padding: 0.35rem;
            border-radius: 16px;
            gap: 0.25rem;
        }

        .tab-filter {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 0.65rem 1.25rem;
            border-radius: 12px;
            cursor: pointer;
            font-family: var(--font-main);
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
        }

        .tab-filter:hover {
            color: #ffffff;
        }

        .tab-filter.active {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-add-movie {
            background: var(--accent-gradient);
            color: #ffffff;
            border: none;
            border-radius: 16px;
            padding: 0.95rem 1.6rem;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: var(--font-main);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow-neon);
            transition: var(--transition-smooth);
        }

        .btn-add-movie:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(99, 102, 241, 0.3);
            filter: brightness(1.05);
        }

        .btn-add-movie:active {
            transform: translateY(0);
        }

        /* Movie Grid & Cards */
        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .movie-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 22px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: var(--transition-smooth);
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .movie-card:hover {
            transform: translateY(-6px);
            border-color: var(--border-glass-hover);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.35);
        }

        .movie-poster-area {
            height: 220px;
            width: 100%;
            position: relative;
            overflow: hidden;
            background: #11131f;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .movie-poster-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-smooth);
        }

        .movie-card:hover .movie-poster-img {
            transform: scale(1.05);
        }

        /* Custom dynamic gradient block if no image is available */
        .movie-poster-gradient {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.5rem;
            position: relative;
            background: linear-gradient(135deg, #1e1b4b 0%, #311042 100%);
        }

        .movie-poster-gradient::before {
            content: '🎬';
            position: absolute;
            top: 25%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            opacity: 0.25;
            filter: grayscale(1);
        }

        .poster-fallback-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .poster-fallback-genre {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--accent-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Overlay cu detalii pe imagine */
        .movie-poster-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(6, 7, 10, 0.95) 0%, rgba(6, 7, 10, 0) 100%);
            padding: 1.25rem 1.25rem 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .movie-rating {
            background: rgba(251, 191, 36, 0.15);
            border: 1px solid rgba(251, 191, 36, 0.25);
            color: var(--rating-color);
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: var(--font-mono);
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            backdrop-filter: blur(8px);
        }

        .movie-genre-badge {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            backdrop-filter: blur(8px);
        }

        .movie-info {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            background: rgba(6, 7, 10, 0.3);
        }

        .movie-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .movie-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }

        .movie-card-year {
            font-size: 0.85rem;
            font-family: var(--font-mono);
            color: var(--text-dark);
            font-weight: 500;
            padding-top: 0.2rem;
        }

        .movie-status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-pill {
            padding: 0.3rem 0.7rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .status-pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-pill.completed {
            background: var(--status-completed-bg);
            color: var(--status-completed);
        }
        .status-pill.completed::before { background-color: var(--status-completed); }

        .status-pill.watching {
            background: var(--status-watching-bg);
            color: var(--status-watching);
        }
        .status-pill.watching::before { background-color: var(--status-watching); }

        .status-pill.plan {
            background: var(--status-plan-bg);
            color: var(--status-plan);
        }
        .status-pill.plan::before { background-color: var(--status-plan); }

        .movie-notes {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-top: 0.25rem;
            min-height: 2.5rem;
        }

        .movie-actions {
            border-top: 1px solid var(--border-glass);
            padding: 0.85rem 1.25rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            background: rgba(6, 7, 10, 0.4);
            opacity: 0.7;
            transition: var(--transition-smooth);
        }

        .movie-card:hover .movie-actions {
            opacity: 1;
        }

        .btn-action-icon {
            background: transparent;
            border: none;
            color: var(--text-dark);
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
        }

        .btn-action-icon:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-action-icon.delete-hover:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        /* Empty State */
        .empty-state {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 4rem 2rem;
            text-align: center;
            max-width: 480px;
            margin: 2rem auto;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            animation: fadeIn 0.5s ease;
        }

        .empty-state.hidden {
            display: none !important;
        }

        .empty-icon {
            font-size: 4rem;
            filter: drop-shadow(0 8px 16px rgba(99, 102, 241, 0.2));
            margin-bottom: 0.5rem;
        }

        .empty-state h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.5;
            max-width: 320px;
            margin-bottom: 0.75rem;
        }

        /* ------------------ MODAL OVERLAY ------------------ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(3, 4, 7, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.hidden {
            display: none !important;
            opacity: 0;
        }

        .modal-card {
            background: #0d0f1c;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            width: 100%;
            max-width: 580px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.6);
            overflow: hidden;
            animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .modal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.3), transparent);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .modal-header h3 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .btn-close-modal {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
        }

        .btn-close-modal:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        @media (max-width: 480px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            color: #ffffff;
            font-family: var(--font-main);
            font-size: 0.95rem;
            transition: var(--transition-smooth);
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
            background: rgba(255, 255, 255, 0.05);
        }

        .form-select option {
            background-color: #0d0f1c;
            color: #ffffff;
        }

        /* Rating in Form */
        .form-rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 0.25rem;
            font-size: 1.8rem;
            padding-top: 0.2rem;
        }

        .form-rating-stars input {
            display: none;
        }

        .form-rating-stars label {
            color: rgba(255, 255, 255, 0.15);
            cursor: pointer;
            transition: var(--transition-smooth);
            user-select: none;
        }

        .form-rating-stars label:hover,
        .form-rating-stars label:hover ~ label,
        .form-rating-stars input:checked ~ label {
            color: var(--rating-color);
            filter: drop-shadow(0 0 4px rgba(251, 191, 36, 0.4));
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 1.5rem 2rem;
            background: rgba(6, 7, 10, 0.2);
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: var(--text-muted);
            padding: 0.85rem 1.5rem;
            border-radius: 12px;
            font-family: var(--font-main);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition-smooth);
        }

        .btn-cancel:hover {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.02);
        }

        /* ------------------ VERIFICATION UTILS & GLOBAL BUTTONS ------------------ */
        .btn-primary-form {
            background: var(--accent-gradient);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1.5rem;
            font-family: var(--font-main);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            box-shadow: var(--shadow-neon);
            transition: var(--transition-smooth);
        }

        .btn-primary-form:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.25);
        }

        /* Animatii */
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(24px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @media (max-width: 768px) {
            .dash-header {
                flex-direction: column;
                gap: 1rem;
                padding: 1.25rem;
                text-align: center;
            }
            .dash-meta {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }
            .dash-clock {
                width: 100%;
                justify-content: center;
            }
            .btn-logout {
                width: 100%;
                justify-content: center;
            }
            .controls-panel {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-group {
                overflow-x: auto;
            }
            .btn-add-movie {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- Nebuloase de fundal -->
    <div class="nebula nebula-purple"></div>
    <div class="nebula nebula-blue"></div>

    <div class="app-wrapper">

        <!-- 1. ECRANUL DE BUN VENIT (WELCOME SCREEN) -->
        <div id="welcome-view" class="welcome-card">
            <div class="logo-box">
                <svg class="clapperboard-svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="url(#clapper-grad)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <defs>
                        <linearGradient id="clapper-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#6366f1" />
                            <stop offset="100%" stop-color="#a855f7" />
                        </linearGradient>
                    </defs>
                    <path class="clapper-bar" d="M4 11h16L18 7H6z" />
                    <path d="M4 11v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9" />
                    <path d="M9 11v11" />
                    <path d="M15 11v11" />
                    <path d="M6 7l3 4" />
                    <path d="M10 7l3 4" />
                    <path d="M14 7l3 4" />
                </svg>
            </div>
            <h1>Movie Tracker</h1>
            <p class="tagline">Bun venit la Movie Tracker!</p>
            
            <div class="time-box">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span id="welcome-datetime"><?php echo $current_time; ?></span>
            </div>

            <button class="action-button" onclick="enterDashboard()">
                <span>Intră în cont</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </div>

        <!-- 2. DASHBOARD MAIN VIEW -->
        <div id="dashboard-view" class="dashboard-container hidden">
            <!-- Header Dashboard -->
            <header class="dash-header">
                <div class="dash-brand">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="url(#clapper-grad)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 11h16L18 7H6z" />
                        <path d="M4 11v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9" />
                    </svg>
                    <h2>Movie Tracker</h2>
                </div>
                <div class="dash-meta">
                    <div class="dash-clock">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span id="dash-datetime"><?php echo $current_time; ?></span>
                    </div>
                    <button class="btn-logout" onclick="exitDashboard()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Ieșire</span>
                    </button>
                </div>
            </header>

            <!-- Sectiune Statistici -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                            <line x1="7" y1="2" x2="7" y2="22"></line>
                            <line x1="17" y1="2" x2="17" y2="22"></line>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <line x1="2" y1="7" x2="7" y2="7"></line>
                            <line x1="2" y1="17" x2="7" y2="17"></line>
                            <line x1="17" y1="17" x2="22" y2="17"></line>
                            <line x1="17" y1="7" x2="22" y2="7"></line>
                        </svg>
                    </div>
                    <div class="stat-detalii">
                        <span class="stat-valoare" id="stat-total">0</span>
                        <span class="stat-titlu">Total Filme</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="stat-detalii">
                        <span class="stat-valoare" id="stat-completed">0</span>
                        <span class="stat-titlu">Vizionate</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon cyan">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="stat-detalii">
                        <span class="stat-valoare" id="stat-watching">0</span>
                        <span class="stat-titlu">În Curs</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <div class="stat-detalii">
                        <span class="stat-valoare" id="stat-rating">0.0</span>
                        <span class="stat-titlu">Rating Mediu</span>
                    </div>
                </div>
            </section>

            <!-- Panou Control -->
            <section class="controls-panel">
                <div class="search-container">
                    <input type="text" id="search-input" class="input-search" placeholder="Caută un film după titlu, gen..." oninput="handleSearch()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <div class="filter-group">
                    <button class="tab-filter active" id="filter-all" onclick="filterByStatus('all')">Toate</button>
                    <button class="tab-filter" id="filter-completed" onclick="filterByStatus('completed')">Vizionate</button>
                    <button class="tab-filter" id="filter-watching" onclick="filterByStatus('watching')">În Curs</button>
                    <button class="tab-filter" id="filter-plan" onclick="filterByStatus('plan')">De Văzut</button>
                </div>
                <button class="btn-add-movie" onclick="openAddModal()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Adaugă Film</span>
                </button>
            </section>

            <!-- Sectiune Filme -->
            <div class="movies-container">
                <div class="movies-grid" id="movies-grid">
                    <!-- Randat dinamic prin JavaScript -->
                </div>
                
                <!-- Stare golita -->
                <div id="empty-state" class="empty-state hidden">
                    <span class="empty-icon">🎬</span>
                    <h3>Niciun film adăugat</h3>
                    <p>Momentan lista ta de filme este goală sau filtrele aplicate nu returnează rezultate.</p>
                    <button class="btn-add-movie" onclick="openAddModal()">Adaugă un film acum</button>
                </div>
            </div>

        </div>

    </div>

    <!-- 3. MODAL ADĂUGARE / EDITARE FILM -->
    <div id="movie-modal" class="modal-overlay hidden">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modal-title">Adaugă un Film Nou</h3>
                <button class="btn-close-modal" onclick="closeModal()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="movie-form" onsubmit="saveMovie(event)">
                <input type="hidden" id="movie-id">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="movie-title">Titlul Filmului</label>
                        <input type="text" id="movie-title" class="form-input" placeholder="Ex: Inception, Avatar, Gladiator..." required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="movie-genre">Gen</label>
                            <select id="movie-genre" class="form-select" required>
                                <option value="" disabled selected>Selectează un gen</option>
                                <option value="Acțiune">Acțiune</option>
                                <option value="Sci-Fi">Sci-Fi</option>
                                <option value="Dramă">Dramă</option>
                                <option value="Comedie">Comedie</option>
                                <option value="Thriller">Thriller</option>
                                <option value="Horror">Horror</option>
                                <option value="Aventură">Aventură</option>
                                <option value="Romance">Romance</option>
                                <option value="Animație">Animație</option>
                                <option value="Mister">Mister</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="movie-year">Anul Lansării</label>
                            <input type="number" id="movie-year" class="form-input" placeholder="Ex: 2024" min="1888" max="2035" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="movie-status">Stadiu Vizionare</label>
                            <select id="movie-status" class="form-select" onchange="handleStatusChange()" required>
                                <option value="plan">De Văzut (Plan to Watch)</option>
                                <option value="watching">În Curs (Watching)</option>
                                <option value="completed">Vizionate (Completed)</option>
                            </select>
                        </div>
                        <div class="form-group" id="rating-form-group">
                            <label>Ratingul tău</label>
                            <div class="form-rating-stars">
                                <input type="radio" name="star-rating" id="star-5" value="5"><label for="star-5">★</label>
                                <input type="radio" name="star-rating" id="star-4" value="4"><label for="star-4">★</label>
                                <input type="radio" name="star-rating" id="star-3" value="3"><label for="star-3">★</label>
                                <input type="radio" name="star-rating" id="star-2" value="2"><label for="star-2">★</label>
                                <input type="radio" name="star-rating" id="star-1" value="1"><label for="star-1">★</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="movie-poster">Imagine Copertă (Poster URL - Opțional)</label>
                        <input type="url" id="movie-poster" class="form-input" placeholder="https://exemplu.ro/imagine.jpg">
                    </div>

                    <div class="form-group">
                        <label for="movie-notes">Note Personale</label>
                        <textarea id="movie-notes" class="form-textarea" rows="3" placeholder="Scrie câteva impresii despre film..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Renunță</button>
                    <button type="submit" class="btn-primary-form" id="btn-save">Salvează Filmul</button>
                </div>
            </form>
        </div>
    </div>

    <!-- LOGICĂ JAVASCRIPT -->
    <script>
        // Array global cu filmele stocate în aplicație
        let movies = [];
        let currentFilter = 'all';

        // 1. Ceas dinamic în timp real
        function startLiveClock() {
            function updateClock() {
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const year = now.getFullYear();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                
                const timeString = `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
                
                const welcomeClock = document.getElementById('welcome-datetime');
                const dashClock = document.getElementById('dash-datetime');
                
                if (welcomeClock) welcomeClock.textContent = timeString;
                if (dashClock) dashClock.textContent = timeString;
            }
            setInterval(updateClock, 1000);
            updateClock();
        }

        // 2. Inițializarea aplicației și pre-popularea cu date demonstrative dacă este prima vizită
        window.addEventListener('DOMContentLoaded', () => {
            startLiveClock();
            
            // Verificăm dacă sunt deja filme în localStorage
            const storedMovies = localStorage.getItem('movie_tracker_movies');
            const userLogged = localStorage.getItem('movie_tracker_logged');

            if (storedMovies) {
                movies = JSON.parse(storedMovies);
            } else {
                // Generăm date demonstrative frumoase (WOW din prima secundă!)
                movies = [
                    {
                        id: 'demo-1',
                        title: 'Inception',
                        genre: 'Sci-Fi',
                        year: 2010,
                        status: 'completed',
                        rating: 5,
                        poster: 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=400&auto=format&fit=crop',
                        notes: 'O capodoperă regizată de Christopher Nolan. Conceptul de vis în vis este absolut genial, iar finalul lasă loc de interpretări.'
                    },
                    {
                        id: 'demo-2',
                        title: 'Gladiator II',
                        genre: 'Acțiune',
                        year: 2024,
                        status: 'watching',
                        rating: 4,
                        poster: 'https://images.unsplash.com/photo-1559583985-c80d8ad9b29f?q=80&w=400&auto=format&fit=crop',
                        notes: 'Continuarea unui film de epocă legendar. Scenele din colosseum sunt incredibil de bine realizate.'
                    },
                    {
                        id: 'demo-3',
                        title: 'Interstellar',
                        genre: 'Sci-Fi',
                        year: 2014,
                        status: 'completed',
                        rating: 5,
                        poster: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=400&auto=format&fit=crop',
                        notes: 'Coloana sonoră compusă de Hans Zimmer și acuratețea științifică creează o atmosferă profund emoționantă și imersivă.'
                    },
                    {
                        id: 'demo-4',
                        title: 'The Dark Knight',
                        genre: 'Thriller',
                        year: 2008,
                        status: 'plan',
                        rating: 0,
                        poster: 'https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?q=80&w=400&auto=format&fit=crop',
                        notes: 'Cel mai bun film cu supereroi realizat vreodată. Prestația lui Heath Ledger în rolul lui Joker este istorică.'
                    }
                ];
                saveToLocalStorage();
            }

            // Dacă utilizatorul a fost deja autentificat anterior, trecem direct în Dashboard
            if (userLogged === 'true') {
                document.getElementById('welcome-view').classList.add('hidden');
                document.getElementById('dashboard-view').classList.remove('hidden');
                updateStats();
                renderMovies();
            }
        });

        // 3. Salvare în Local Storage
        function saveToLocalStorage() {
            localStorage.setItem('movie_tracker_movies', JSON.stringify(movies));
        }

        // 4. Autentificare/Tranziție către Dashboard
        function enterDashboard() {
            localStorage.setItem('movie_tracker_logged', 'true');
            const welcomeCard = document.getElementById('welcome-view');
            const dashContainer = document.getElementById('dashboard-view');
            
            welcomeCard.style.transform = 'scale(0.95) translateY(-20px)';
            welcomeCard.style.opacity = '0';
            
            setTimeout(() => {
                welcomeCard.classList.add('hidden');
                dashContainer.classList.remove('hidden');
                updateStats();
                renderMovies();
            }, 300);
        }

        // 5. Ieșire din Dashboard
        function exitDashboard() {
            localStorage.setItem('movie_tracker_logged', 'false');
            const welcomeCard = document.getElementById('welcome-view');
            const dashContainer = document.getElementById('dashboard-view');
            
            dashContainer.style.transform = 'translateY(20px)';
            dashContainer.style.opacity = '0';
            
            setTimeout(() => {
                dashContainer.classList.add('hidden');
                dashContainer.style.transform = '';
                dashContainer.style.opacity = '';
                
                welcomeCard.classList.remove('hidden');
                welcomeCard.style.transform = 'scale(1) translateY(0)';
                welcomeCard.style.opacity = '1';
            }, 300);
        }

        // 6. Actualizare Statistici
        function updateStats() {
            const total = movies.length;
            const completed = movies.filter(m => m.status === 'completed').length;
            const watching = movies.filter(m => m.status === 'watching').length;
            
            // Calculăm ratingul mediu pentru filmele vizionate/notate
            const ratedMovies = movies.filter(m => m.rating > 0);
            const avgRating = ratedMovies.length > 0
                ? (ratedMovies.reduce((sum, m) => sum + m.rating, 0) / ratedMovies.length).toFixed(1)
                : '0.0';

            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-completed').textContent = completed;
            document.getElementById('stat-watching').textContent = watching;
            document.getElementById('stat-rating').textContent = avgRating;
        }

        // 7. Randarea Listei de Filme în Grid
        function renderMovies(filteredMoviesList = null) {
            const grid = document.getElementById('movies-grid');
            const emptyState = document.getElementById('empty-state');
            grid.innerHTML = '';
            
            let listToRender = filteredMoviesList || movies;
            
            // Aplicăm filtrele active de status dacă nu avem o listă custom transmisă
            if (!filteredMoviesList) {
                if (currentFilter !== 'all') {
                    listToRender = listToRender.filter(m => m.status === currentFilter);
                }
                
                // Aplicăm căutarea dacă există text în search input
                const searchQuery = document.getElementById('search-input').value.toLowerCase().trim();
                if (searchQuery) {
                    listToRender = listToRender.filter(m => 
                        m.title.toLowerCase().includes(searchQuery) || 
                        m.genre.toLowerCase().includes(searchQuery)
                    );
                }
            }

            if (listToRender.length === 0) {
                grid.style.display = 'none';
                emptyState.classList.remove('hidden');
                return;
            }

            grid.style.display = 'grid';
            emptyState.classList.add('hidden');

            listToRender.forEach(movie => {
                const card = document.createElement('div');
                card.className = 'movie-card';
                card.setAttribute('data-id', movie.id);

                // Definire badge de status
                let statusLabel = '';
                let statusClass = movie.status;
                if (movie.status === 'completed') statusLabel = 'Vizionate';
                else if (movie.status === 'watching') statusLabel = 'În Curs';
                else if (movie.status === 'plan') statusLabel = 'De Văzut';

                // Definire rating
                const ratingStarsStr = movie.rating > 0 ? '★ ' + movie.rating : 'Nenotat';

                // Creare poster area (imagine sau gradient cool)
                let posterHTML = '';
                if (movie.poster && movie.poster.trim() !== '') {
                    posterHTML = `
                        <div class="movie-poster-area">
                            <img class="movie-poster-img" src="${escapeHTML(movie.poster)}" alt="${escapeHTML(movie.title)}" onerror="this.src=''; this.outerHTML=getGradientPoster('${escapeHTML(movie.title)}', '${escapeHTML(movie.genre)}');">
                            <div class="movie-poster-overlay">
                                <span class="movie-rating">${ratingStarsStr}</span>
                                <span class="movie-genre-badge">${escapeHTML(movie.genre)}</span>
                            </div>
                        </div>
                    `;
                } else {
                    posterHTML = getGradientPoster(movie.title, movie.genre, ratingStarsStr);
                }

                card.innerHTML = `
                    ${posterHTML}
                    <div class="movie-info">
                        <div class="movie-title-row">
                            <h4 class="movie-card-title">${escapeHTML(movie.title)}</h4>
                            <span class="movie-card-year">${movie.year}</span>
                        </div>
                        <div class="movie-status-row">
                            <span class="status-pill ${statusClass}">${statusLabel}</span>
                        </div>
                        <p class="movie-notes" title="${escapeHTML(movie.notes || 'Fără note.')}">
                            ${escapeHTML(movie.notes || 'Nicio impresie personală salvată încă.')}
                        </p>
                    </div>
                    <div class="movie-actions">
                        <button class="btn-action-icon" onclick="editMovie('${movie.id}')" title="Editează film">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </button>
                        <button class="btn-action-icon delete-hover" onclick="deleteMovie('${movie.id}')" title="Șterge film">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                `;

                grid.appendChild(card);
            });
        }

        // Returnează o randare de poster tip gradient dinamic pe bază de gen
        function getGradientPoster(title, genre, ratingStr = 'Nenotat') {
            let gradColor = 'linear-gradient(135deg, #1e1b4b 0%, #311042 100%)';
            
            // Mapăm culori potrivite pentru genuri pentru varietate vizuală premium
            if (genre === 'Sci-Fi' || genre === 'Mister') {
                gradColor = 'linear-gradient(135deg, #2e1065 0%, #03001e 100%)';
            } else if (genre === 'Acțiune' || genre === 'Thriller') {
                gradColor = 'linear-gradient(135deg, #450a0a 0%, #1a0505 100%)';
            } else if (genre === 'Dramă' || genre === 'Romance') {
                gradColor = 'linear-gradient(135deg, #14532d 0%, #022c22 100%)';
            } else if (genre === 'Comedie' || genre === 'Animație') {
                gradColor = 'linear-gradient(135deg, #7c2d12 0%, #3c0c00 100%)';
            } else if (genre === 'Horror') {
                gradColor = 'linear-gradient(135deg, #1f1f1f 0%, #000000 100%)';
            }

            return `
                <div class="movie-poster-area" style="background: ${gradColor};">
                    <div class="movie-poster-gradient" style="background: transparent;">
                        <h4 class="poster-fallback-title">${escapeHTML(title)}</h4>
                        <span class="poster-fallback-genre">${escapeHTML(genre)}</span>
                    </div>
                    <div class="movie-poster-overlay">
                        <span class="movie-rating">${ratingStr}</span>
                        <span class="movie-genre-badge">${escapeHTML(genre)}</span>
                    </div>
                </div>
            `;
        }

        // 8. Căutare live și filtrare
        function handleSearch() {
            renderMovies();
        }

        function filterByStatus(status) {
            currentFilter = status;
            
            // Actualizăm tab-ul activ în UI
            const tabs = document.querySelectorAll('.tab-filter');
            tabs.forEach(tab => tab.classList.remove('active'));
            document.getElementById(`filter-${status}`).classList.add('active');
            
            renderMovies();
        }

        // 9. Administrare Modal (Adăugare / Editare)
        function openAddModal() {
            document.getElementById('modal-title').textContent = 'Adaugă un Film Nou';
            document.getElementById('movie-form').reset();
            document.getElementById('movie-id').value = '';
            
            // Debifăm toate stelele
            const stars = document.getElementsByName('star-rating');
            stars.forEach(s => s.checked = false);
            
            document.getElementById('rating-form-group').style.display = 'block';
            
            document.getElementById('movie-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('movie-modal').classList.add('hidden');
        }

        function handleStatusChange() {
            const status = document.getElementById('movie-status').value;
            const ratingGroup = document.getElementById('rating-form-group');
            
            // Dacă filmul nu a fost vizionat încă, ascundem secțiunea de rating
            if (status === 'plan') {
                ratingGroup.style.opacity = '0.3';
                ratingGroup.style.pointerEvents = 'none';
                const stars = document.getElementsByName('star-rating');
                stars.forEach(s => s.checked = false);
            } else {
                ratingGroup.style.opacity = '1';
                ratingGroup.style.pointerEvents = 'auto';
            }
        }

        // 10. Salvare Film (Adăugare / Editare)
        function saveMovie(event) {
            event.preventDefault();
            
            const id = document.getElementById('movie-id').value;
            const title = document.getElementById('movie-title').value.trim();
            const genre = document.getElementById('movie-genre').value;
            const year = parseInt(document.getElementById('movie-year').value);
            const status = document.getElementById('movie-status').value;
            const poster = document.getElementById('movie-poster').value.trim();
            const notes = document.getElementById('movie-notes').value.trim();
            
            // Obținem valoarea ratingului
            let rating = 0;
            if (status !== 'plan') {
                const checkedStar = document.querySelector('input[name="star-rating"]:checked');
                if (checkedStar) {
                    rating = parseInt(checkedStar.value);
                }
            }

            if (id) {
                // Cazul de EDITARE film existent
                const index = movies.findIndex(m => m.id === id);
                if (index !== -1) {
                    movies[index] = { id, title, genre, year, status, rating, poster, notes };
                }
            } else {
                // Cazul de ADĂUGARE film nou
                const newId = 'movie-' + Date.now();
                movies.push({ id: newId, title, genre, year, status, rating, poster, notes });
            }

            saveToLocalStorage();
            updateStats();
            renderMovies();
            closeModal();
        }

        // 11. Editare Film existent
        function editMovie(id) {
            const movie = movies.find(m => m.id === id);
            if (!movie) return;

            document.getElementById('modal-title').textContent = 'Editează Filmul';
            document.getElementById('movie-id').value = movie.id;
            document.getElementById('movie-title').value = movie.title;
            document.getElementById('movie-genre').value = movie.genre;
            document.getElementById('movie-year').value = movie.year;
            document.getElementById('movie-status').value = movie.status;
            document.getElementById('movie-poster').value = movie.poster || '';
            document.getElementById('movie-notes').value = movie.notes || '';

            // Setăm stelele bifate
            const stars = document.getElementsByName('star-rating');
            stars.forEach(s => {
                s.checked = (parseInt(s.value) === movie.rating);
            });

            handleStatusChange();
            document.getElementById('movie-modal').classList.remove('hidden');
        }

        // 12. Ștergere Film
        function deleteMovie(id) {
            if (confirm('Sigur dorești să ștergi acest film din listă?')) {
                movies = movies.filter(m => m.id !== id);
                saveToLocalStorage();
                updateStats();
                renderMovies();
            }
        }

        // Helper securizare HTML inputs
        function escapeHTML(str) {
            if (!str) return '';
            return str.replace(/[&<>'"]/g, 
                tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                }[tag] || tag)
            );
        }
    </script>
</body>
</html>
