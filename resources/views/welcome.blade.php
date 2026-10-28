<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" style="color-scheme: dark; background-color: #020617;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>University Connect</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        :root {
            --uc-cyan: 103, 232, 249;
            --uc-blue: 59, 130, 246;
            --uc-indigo: 99, 102, 241;
            --uc-violet: 139, 92, 246;
            --uc-pink: 236, 72, 153;
            --uc-emerald: 52, 211, 153;
            --uc-bg: 2, 6, 23;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Figtree', sans-serif;
            color: #fff;
            background:
                radial-gradient(circle at 12% 12%, rgba(59, 130, 246, .17), transparent 25%),
                radial-gradient(circle at 88% 24%, rgba(217, 70, 239, .15), transparent 27%),
                radial-gradient(circle at 54% 82%, rgba(34, 211, 238, .09), transparent 28%),
                rgb(2 6 23);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -3;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(99, 102, 241, .07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, .07) 1px, transparent 1px);
            background-size: 54px 54px;
            mask-image: linear-gradient(to bottom, #000 0%, rgba(0,0,0,.8) 55%, transparent 100%);
            animation: ucGridMove 22s linear infinite;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
            opacity: .025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitchTiles'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.8'/%3E%3C/svg%3E");
        }

        @keyframes ucGridMove {
            to { background-position: 54px 54px; }
        }

        .uc-page {
            position: relative;
            min-height: 100vh;
            isolation: isolate;
        }

        .uc-ambient {
            position: fixed;
            border-radius: 9999px;
            pointer-events: none;
            filter: blur(85px);
            z-index: -2;
            opacity: .34;
        }

        .uc-ambient.a {
            width: 460px;
            height: 460px;
            left: -180px;
            top: 18%;
            background: rgba(var(--uc-blue), .42);
            animation: ucAmbientA 14s ease-in-out infinite alternate;
        }

        .uc-ambient.b {
            width: 520px;
            height: 520px;
            right: -190px;
            top: 16%;
            background: rgba(var(--uc-pink), .32);
            animation: ucAmbientB 17s ease-in-out infinite alternate;
        }

        .uc-ambient.c {
            width: 380px;
            height: 380px;
            left: 44%;
            bottom: -190px;
            background: rgba(var(--uc-cyan), .20);
            animation: ucAmbientC 19s ease-in-out infinite alternate;
        }

        @keyframes ucAmbientA {
            to { transform: translate3d(80px, -45px, 0) scale(1.16); }
        }

        @keyframes ucAmbientB {
            to { transform: translate3d(-70px, 55px, 0) scale(.92); }
        }

        @keyframes ucAmbientC {
            to { transform: translate3d(-80px, -70px, 0) scale(1.15); }
        }

        #uc-cursor-glow {
            position: fixed;
            left: 50%;
            top: 50%;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(var(--uc-cyan), .12), rgba(var(--uc-indigo), .055) 35%, transparent 68%);
            filter: blur(6px);
        }

        .uc-shell {
            width: min(1240px, calc(100% - 36px));
            margin: 0 auto;
        }

        .uc-nav {
            position: relative;
            z-index: 100;
            margin-top: 18px;
            padding: 13px 16px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, .11);
            background:
                linear-gradient(135deg, rgba(15, 23, 42, .78), rgba(15, 23, 42, .42));
            backdrop-filter: blur(28px) saturate(150%);
            box-shadow:
                0 25px 80px rgba(0, 0, 0, .28),
                inset 0 1px 0 rgba(255, 255, 255, .08);
        }

        .uc-brand-icon {
            position: relative;
            width: 44px;
            height: 44px;
            border-radius: 15px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #6366f1, #a855f7 55%, #ec4899);
            box-shadow: 0 12px 35px rgba(139, 92, 246, .32);
        }

        .uc-brand-icon::before,
        .uc-brand-icon::after {
            content: "";
            position: absolute;
            inset: -6px;
            border-radius: 19px;
            border: 1px solid rgba(var(--uc-cyan), .24);
            animation: ucBrandPulse 3.2s ease-out infinite;
        }

        .uc-brand-icon::after {
            animation-delay: 1.6s;
        }

        @keyframes ucBrandPulse {
            0% { transform: scale(.78); opacity: 0; }
            24% { opacity: .75; }
            100% { transform: scale(1.38); opacity: 0; }
        }

        .uc-login {
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 43px;
            padding: 0 18px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 900;
            color: white;
            background: linear-gradient(100deg, #6366f1, #9333ea, #d946ef);
            box-shadow: 0 14px 40px rgba(147, 51, 234, .30);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .uc-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 55px rgba(147, 51, 234, .42);
        }

        .uc-login::after {
            content: "";
            position: absolute;
            top: -80%;
            left: -40%;
            width: 34%;
            height: 260%;
            transform: rotate(20deg);
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.45), transparent);
            animation: ucButtonShine 4s ease-in-out infinite;
        }

        @keyframes ucButtonShine {
            0%, 58% { left: -45%; }
            100% { left: 125%; }
        }

        .uc-hero {
            position: relative;
            min-height: calc(100vh - 105px);
            display: grid;
            grid-template-columns: minmax(0, .94fr) minmax(520px, 1.06fr);
            align-items: center;
            gap: 48px;
            padding: 54px 0 72px;
        }

        .uc-copy {
            position: relative;
            z-index: 10;
            animation: ucHeroIn .85s cubic-bezier(.2,.8,.2,1) both;
        }

        @keyframes ucHeroIn {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: none; }
        }

        .uc-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 13px;
            border-radius: 999px;
            border: 1px solid rgba(var(--uc-cyan), .15);
            background: rgba(var(--uc-cyan), .055);
            color: rgb(165 243 252);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .17em;
            text-transform: uppercase;
        }

        .uc-kicker-dot {
            position: relative;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgb(var(--uc-emerald));
            box-shadow: 0 0 18px rgba(var(--uc-emerald), .9);
        }

        .uc-kicker-dot::after {
            content: "";
            position: absolute;
            inset: -5px;
            border-radius: inherit;
            border: 1px solid rgba(var(--uc-emerald), .48);
            animation: ucSignal 1.8s ease-out infinite;
        }

        @keyframes ucSignal {
            from { transform: scale(.55); opacity: .9; }
            to { transform: scale(1.85); opacity: 0; }
        }

        .uc-title {
            margin: 22px 0 0;
            font-size: clamp(3rem, 5.25vw, 5.55rem);
            line-height: .94;
            letter-spacing: -.062em;
            font-weight: 900;
        }

        .uc-title-gradient {
            display: block;
            margin-top: 5px;
            background: linear-gradient(100deg, #67e8f9 0%, #93c5fd 20%, #a5b4fc 42%, #d8b4fe 63%, #f9a8d4 82%, #67e8f9 100%);
            background-size: 260% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: ucTextFlow 6s linear infinite;
        }

        @keyframes ucTextFlow {
            to { background-position: 260% center; }
        }

        .uc-description {
            max-width: 650px;
            margin-top: 24px;
            color: rgb(203 213 225);
            font-size: 16px;
            line-height: 1.8;
        }

        .uc-access {
            position: relative;
            overflow: hidden;
            max-width: 610px;
            margin-top: 26px;
            padding: 17px 18px;
            border-radius: 22px;
            border: 1px solid rgba(var(--uc-cyan), .14);
            background:
                linear-gradient(135deg, rgba(var(--uc-cyan), .07), rgba(var(--uc-indigo), .04));
            box-shadow: inset 0 1px 0 rgba(255,255,255,.045);
        }

        .uc-access::after {
            content: "";
            position: absolute;
            width: 140px;
            height: 140px;
            right: -70px;
            top: -75px;
            border-radius: 50%;
            background: rgba(var(--uc-cyan), .13);
            filter: blur(30px);
        }

        .uc-access-icon {
            flex: 0 0 46px;
            width: 46px;
            height: 46px;
            border-radius: 15px;
            display: grid;
            place-items: center;
            color: rgb(103 232 249);
            background: rgba(var(--uc-cyan), .10);
            border: 1px solid rgba(var(--uc-cyan), .11);
        }

        /* =========================================================
           ULTRA PREMIUM 3D NETWORK
        ========================================================== */

        .uc-visual-wrap {
            position: relative;
            min-height: 625px;
            perspective: 1500px;
            display: grid;
            place-items: center;
        }

        .uc-network {
            position: relative;
            width: min(100%, 590px);
            aspect-ratio: 1 / 1;
            transform-style: preserve-3d;
            will-change: transform;
            transition: transform .16s ease-out;
        }

        .uc-network::before {
            content: "";
            position: absolute;
            inset: 5%;
            border-radius: 50%;
            background:
                radial-gradient(circle at 50% 50%, rgba(var(--uc-violet), .15), transparent 37%),
                radial-gradient(circle at 30% 28%, rgba(var(--uc-cyan), .10), transparent 27%);
            filter: blur(20px);
            animation: ucNetworkGlow 5s ease-in-out infinite alternate;
        }

        @keyframes ucNetworkGlow {
            to { transform: scale(1.09); filter: blur(28px); }
        }

        .uc-holo-panel {
            position: absolute;
            inset: 3%;
            overflow: hidden;
            border-radius: 42px;
            border: 1px solid rgba(255,255,255,.10);
            background:
                linear-gradient(rgba(255,255,255,.024) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.024) 1px, transparent 1px),
                radial-gradient(circle at center, rgba(var(--uc-indigo), .10), transparent 55%),
                rgba(2, 6, 23, .36);
            background-size: 32px 32px, 32px 32px, auto, auto;
            backdrop-filter: blur(16px);
            box-shadow:
                0 55px 130px rgba(0,0,0,.36),
                inset 0 1px 0 rgba(255,255,255,.06);
        }

        .uc-holo-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, transparent 18%, rgba(255,255,255,.055) 46%, transparent 72%);
            transform: translateX(-130%);
            animation: ucGlassSweep 7.5s ease-in-out infinite;
        }

        @keyframes ucGlassSweep {
            0%, 58% { transform: translateX(-130%); }
            84%, 100% { transform: translateX(130%); }
        }

        .uc-scan-line {
            position: absolute;
            left: 8%;
            right: 8%;
            height: 1px;
            z-index: 2;
            background: linear-gradient(90deg, transparent, rgba(var(--uc-cyan), .75), transparent);
            box-shadow: 0 0 18px rgba(var(--uc-cyan), .55);
            animation: ucScanY 5.5s linear infinite;
            opacity: .42;
        }

        @keyframes ucScanY {
            from { top: 8%; }
            to { top: 92%; }
        }

        #uc-network-canvas {
            position: absolute;
            inset: 3%;
            width: 94%;
            height: 94%;
            border-radius: 42px;
            pointer-events: none;
            opacity: .9;
        }

        .uc-orbit {
            position: absolute;
            left: 50%;
            top: 47%;
            border-radius: 50%;
            pointer-events: none;
            transform-style: preserve-3d;
        }

        .uc-orbit.one {
            width: 210px;
            height: 210px;
            margin: -105px 0 0 -105px;
            border: 1px solid rgba(var(--uc-cyan), .22);
            animation: ucOrbitOne 12s linear infinite;
        }

        .uc-orbit.two {
            width: 330px;
            height: 330px;
            margin: -165px 0 0 -165px;
            border: 1px solid rgba(var(--uc-violet), .18);
            animation: ucOrbitTwo 19s linear infinite reverse;
        }

        .uc-orbit.three {
            width: 445px;
            height: 250px;
            margin: -125px 0 0 -222.5px;
            border: 1px dashed rgba(var(--uc-emerald), .15);
            animation: ucOrbitThree 23s linear infinite;
        }

        .uc-orbit.four {
            width: 485px;
            height: 485px;
            margin: -242.5px 0 0 -242.5px;
            border: 1px solid rgba(var(--uc-pink), .08);
            animation: ucOrbitFour 31s linear infinite reverse;
        }

        @keyframes ucOrbitOne {
            to { transform: rotate(360deg); }
        }

        @keyframes ucOrbitTwo {
            to { transform: rotate(360deg) rotateX(64deg); }
        }

        @keyframes ucOrbitThree {
            from { transform: rotate(-24deg) rotateY(58deg); }
            to { transform: rotate(336deg) rotateY(58deg); }
        }

        @keyframes ucOrbitFour {
            from { transform: rotateX(68deg) rotate(0); }
            to { transform: rotateX(68deg) rotate(360deg); }
        }

        .uc-orbit-dot {
            position: absolute;
            width: 9px;
            height: 9px;
            left: 50%;
            top: -5px;
            border-radius: 50%;
            background: rgb(var(--uc-cyan));
            box-shadow:
                0 0 10px rgba(var(--uc-cyan), 1),
                0 0 28px rgba(var(--uc-cyan), .8);
        }

        .uc-orbit.two .uc-orbit-dot {
            background: rgb(216 180 254);
            box-shadow: 0 0 24px rgba(var(--uc-violet), .95);
        }

        .uc-orbit.three .uc-orbit-dot {
            background: rgb(110 231 183);
            box-shadow: 0 0 24px rgba(var(--uc-emerald), .95);
        }

        .uc-core {
            position: absolute;
            left: 50%;
            top: 47%;
            z-index: 20;
            width: 132px;
            height: 132px;
            transform: translate(-50%, -50%);
            border-radius: 39px;
            display: grid;
            place-items: center;
            text-align: center;
            background:
                linear-gradient(145deg,
                    rgba(var(--uc-cyan), .23),
                    rgba(var(--uc-indigo), .30) 46%,
                    rgba(var(--uc-pink), .17)
                );
            border: 1px solid rgba(207, 250, 254, .27);
            box-shadow:
                0 0 100px rgba(var(--uc-violet), .27),
                0 25px 70px rgba(0,0,0,.38),
                inset 0 1px 0 rgba(255,255,255,.16);
            backdrop-filter: blur(24px);
            animation: ucCoreFloat 4.6s ease-in-out infinite;
        }

        .uc-core::before,
        .uc-core::after {
            content: "";
            position: absolute;
            inset: -18px;
            border-radius: 47px;
            border: 1px solid rgba(var(--uc-cyan), .16);
            animation: ucCoreRing 6s linear infinite;
        }

        .uc-core::after {
            inset: -38px;
            border-color: rgba(var(--uc-violet), .11);
            animation-duration: 9s;
            animation-direction: reverse;
        }

        @keyframes ucCoreFloat {
            0%, 100% { transform: translate(-50%, -50%) translateY(0) rotate(-1deg); }
            50% { transform: translate(-50%, -50%) translateY(-11px) rotate(1deg); }
        }

        @keyframes ucCoreRing {
            to { transform: rotate(360deg); }
        }

        .uc-core-icon {
            font-size: 32px;
            color: rgb(207 250 254);
            filter: drop-shadow(0 0 15px rgba(var(--uc-cyan), .42));
        }

        .uc-core-title {
            margin-top: 7px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .17em;
            color: rgb(226 232 240);
        }

        /* The only Students / Alumni / Jobs count block */
        .uc-stats {
            position: absolute;
            left: 7%;
            right: 7%;
            top: 7%;
            z-index: 30;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 9px;
        }

        .uc-stat {
            position: relative;
            overflow: hidden;
            min-width: 0;
            padding: 13px 14px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,.09);
            background: rgba(15, 23, 42, .64);
            backdrop-filter: blur(18px);
            box-shadow:
                0 18px 45px rgba(0,0,0,.22),
                inset 0 1px 0 rgba(255,255,255,.055);
            animation: ucStatFloat 5.2s ease-in-out infinite;
        }

        .uc-stat:nth-child(2) { animation-delay: -1.5s; }
        .uc-stat:nth-child(3) { animation-delay: -3s; }

        @keyframes ucStatFloat {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .uc-stat::after {
            content: "";
            position: absolute;
            width: 70px;
            height: 70px;
            right: -35px;
            top: -38px;
            border-radius: 50%;
            background: var(--stat-glow);
            filter: blur(17px);
        }

        .uc-stat-top {
            display: flex;
            align-items: center;
            gap: 7px;
            color: rgb(148 163 184);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .uc-stat-number {
            margin-top: 6px;
            font-size: 22px;
            line-height: 1;
            font-weight: 900;
            color: var(--stat-color);
        }

        .uc-node {
            position: absolute;
            z-index: 25;
            min-width: 142px;
            padding: 11px 13px;
            border-radius: 17px;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(15, 23, 42, .72);
            backdrop-filter: blur(19px);
            box-shadow: 0 18px 52px rgba(0,0,0,.30);
            animation: ucNodeFloat 5.6s ease-in-out infinite;
        }

        .uc-node strong {
            display: block;
            font-size: 11px;
            color: #fff;
        }

        .uc-node small {
            display: block;
            margin-top: 3px;
            color: rgb(148 163 184);
            font-size: 9px;
        }

        .uc-node i {
            margin-right: 6px;
            color: rgb(var(--uc-cyan));
        }

        .uc-node.a {
            left: 5%;
            top: 30%;
        }

        .uc-node.b {
            right: 4%;
            top: 33%;
            animation-delay: -1.6s;
        }

        .uc-node.c {
            left: 7%;
            bottom: 15%;
            animation-delay: -3.1s;
        }

        .uc-node.d {
            right: 6%;
            bottom: 13%;
            animation-delay: -4.2s;
        }

        @keyframes ucNodeFloat {
            0%,100% { transform: translateY(0) rotate(-.4deg); }
            50% { transform: translateY(-10px) rotate(.4deg); }
        }

        .uc-live-label {
            position: absolute;
            z-index: 28;
            left: 50%;
            bottom: 6%;
            transform: translateX(-50%);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(var(--uc-emerald), .14);
            background: rgba(2,6,23,.68);
            color: rgb(167 243 208);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .11em;
            text-transform: uppercase;
            backdrop-filter: blur(16px);
        }

        .uc-live-label span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgb(var(--uc-emerald));
            box-shadow: 0 0 12px rgba(var(--uc-emerald), .9);
            animation: ucLivePulse 1.7s ease-in-out infinite;
        }

        @keyframes ucLivePulse {
            50% { transform: scale(1.35); opacity: .6; }
        }

        .uc-footer {
            padding: 20px 0 34px;
            color: rgb(100 116 139);
            font-size: 12px;
        }

        .uc-footer-line {
            padding-top: 22px;
            border-top: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .uc-particle {
            position: fixed;
            width: 3px;
            height: 3px;
            z-index: -1;
            pointer-events: none;
            border-radius: 50%;
            background: rgba(255,255,255,.7);
            opacity: .18;
            animation: ucParticleRise var(--duration) linear infinite;
            animation-delay: var(--delay);
        }

        @keyframes ucParticleRise {
            from { transform: translate3d(0, 105vh, 0) scale(.65); opacity: 0; }
            15% { opacity: .22; }
            85% { opacity: .12; }
            to { transform: translate3d(var(--drift), -12vh, 0) scale(1.35); opacity: 0; }
        }

        @media (max-width: 1100px) {
            .uc-hero {
                grid-template-columns: 1fr;
                gap: 18px;
                padding-top: 52px;
            }

            .uc-copy {
                max-width: 790px;
            }

            .uc-visual-wrap {
                min-height: 610px;
            }
        }

        @media (max-width: 700px) {
            .uc-shell {
                width: min(100% - 24px, 1240px);
            }

            .uc-nav {
                margin-top: 12px;
                border-radius: 20px;
            }

            .uc-brand-subtitle {
                display: none;
            }

            .uc-hero {
                padding-top: 40px;
                padding-bottom: 48px;
            }

            .uc-title {
                font-size: clamp(2.75rem, 13vw, 4.3rem);
            }

            .uc-description {
                font-size: 15px;
            }

            .uc-visual-wrap {
                min-height: 520px;
            }

            .uc-network {
                width: 100%;
            }

            .uc-holo-panel,
            #uc-network-canvas {
                border-radius: 28px;
            }

            .uc-stats {
                left: 5%;
                right: 5%;
                top: 6%;
                gap: 6px;
            }

            .uc-stat {
                padding: 10px 9px;
                border-radius: 14px;
            }

            .uc-stat-top {
                font-size: 7px;
                letter-spacing: .07em;
            }

            .uc-stat-number {
                font-size: 18px;
            }

            .uc-core {
                width: 100px;
                height: 100px;
                border-radius: 30px;
            }

            .uc-orbit.one {
                width: 170px;
                height: 170px;
                margin: -85px 0 0 -85px;
            }

            .uc-orbit.two {
                width: 260px;
                height: 260px;
                margin: -130px 0 0 -130px;
            }

            .uc-orbit.three {
                width: 330px;
                height: 190px;
                margin: -95px 0 0 -165px;
            }

            .uc-orbit.four {
                width: 370px;
                height: 370px;
                margin: -185px 0 0 -185px;
            }

            .uc-node {
                min-width: 112px;
                padding: 8px 9px;
                border-radius: 13px;
            }

            .uc-node strong {
                font-size: 9px;
            }

            .uc-node small {
                font-size: 7px;
            }

            .uc-node.a { left: 4%; top: 29%; }
            .uc-node.b { right: 3%; top: 32%; }
            .uc-node.c { left: 4%; bottom: 15%; }
            .uc-node.d { right: 3%; bottom: 14%; }

            .uc-live-label {
                font-size: 7px;
                bottom: 5%;
            }

            .uc-footer-line {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 430px) {
            .uc-brand-name {
                font-size: 13px;
            }

            .uc-login {
                min-height: 39px;
                padding: 0 13px;
                font-size: 11px;
            }

            .uc-visual-wrap {
                min-height: 465px;
            }

            .uc-node.d {
                display: none;
            }

            .uc-node {
                min-width: 103px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
            }

            #uc-cursor-glow,
            .uc-particle {
                display: none !important;
            }

            .uc-network {
                transform: none !important;
            }
        }

        /* Compact desktop composition; small screens retain natural scrolling. */
        @media (min-width: 900px) {
            .uc-nav { margin-top: 12px; padding: 10px 14px; }
            .uc-hero { min-height: calc(100svh - 90px); grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: clamp(20px, 3vw, 42px); padding: 22px 0; }
            .uc-title { margin-top: 16px; font-size: clamp(2.5rem, 4.2vw, 4.3rem); line-height: 1.02; }
            .uc-description { margin-top: 18px; font-size: 14px; line-height: 1.7; }
            .uc-access { margin-top: 18px; padding: 13px 15px; border-radius: 18px; }
            .uc-access .text-sm { font-size: 12px; line-height: 1.6; }
            .uc-visual-wrap { min-height: 0; }
            .uc-network { width: min(100%, 530px, calc(100svh - 120px)); }
        }
        @media (min-width: 900px) and (max-height: 700px) {
            .uc-title { font-size: clamp(2.25rem, 3.8vw, 3.4rem); }
            .uc-hero { padding: 14px 0; }
            .uc-kicker { font-size: 9px; padding: 7px 10px; }
        }
        body { overflow-x: clip; }
        .uc-welcome-header { position: sticky; top: 0; z-index: 1000; padding-top: 12px; }
        .uc-welcome-header .uc-nav { margin-top: 0; }
        .uc-welcome-header.uc-scrolled .uc-nav { background: rgba(9,15,31,.9); backdrop-filter: blur(20px); border-color: rgba(165,180,252,.2); }
    </style>
</head>

<body>
<div class="uc-page">
    <div class="uc-ambient a"></div>
    <div class="uc-ambient b"></div>
    <div class="uc-ambient c"></div>
    <div id="uc-cursor-glow"></div>

    <header class="uc-shell uc-welcome-header">
        <nav class="uc-nav flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="uc-brand-icon shrink-0">
                    <i class="fas fa-graduation-cap text-white"></i>
                </div>

                <div class="min-w-0">
                    <p class="uc-brand-name font-black text-white leading-tight truncate">
                        University Connect
                    </p>
                    <p class="uc-brand-subtitle mt-0.5 text-[10px] font-bold text-slate-500">
                        University Campus Network
                    </p>
                </div>
            </div>

            <div class="shrink-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="uc-login">
                        <i class="fas fa-table-columns"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="uc-login">
                        <i class="fas fa-right-to-bracket"></i>
                        Login
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="uc-shell">
        <section class="uc-hero">
            {{-- LEFT SIDE --}}
            <div class="uc-copy">
                <div class="uc-kicker">
                    <span class="uc-kicker-dot"></span>
                    Official Database Verified Access
                </div>

                <h1 class="uc-title">
                    Smart Bridge Between
                    <span class="uc-title-gradient">Students & Alumni</span>
                </h1>

                <p class="uc-description">
                    University Connect is a smart digital ecosystem that connects verified
                    students, alumni and university administration through mentorship,
                    career opportunities, events, networking and campus communication.
                </p>

                @guest
                    <div class="uc-access">
                        <div class="relative z-10 flex items-start gap-4">
                            <div class="uc-access-icon">
                                <i class="fas fa-id-card"></i>
                            </div>

                            <div>
                                <p class="font-black text-white">
                                    Verified Account Access
                                </p>

                                <p class="mt-1 text-sm leading-6 text-slate-400">
                                    Students and Alumni can access their university-provided
                                    accounts using their verified email address or Official ID.
                                </p>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

            {{-- RIGHT SIDE: REAL DATA + 3D LIVE NETWORK --}}
            <div class="uc-visual-wrap" id="uc-visual-wrap">
                <div class="uc-network" id="uc-network">
                    <div class="uc-holo-panel"></div>
                    <canvas id="uc-network-canvas"></canvas>
                    <div class="uc-scan-line"></div>

                    {{-- Real database statistics: shown only here --}}
                    <div class="uc-stats">
                        <div
                            class="uc-stat"
                            style="--stat-color:rgb(103 232 249);--stat-glow:rgba(34,211,238,.28);"
                        >
                            <div class="uc-stat-top">
                                <i class="fas fa-user-graduate text-cyan-300"></i>
                                Students
                            </div>
                            <div class="uc-stat-number">
                                {{ $homeStats['students'] ?? 0 }}
                            </div>
                        </div>

                        <div
                            class="uc-stat"
                            style="--stat-color:rgb(240 171 252);--stat-glow:rgba(217,70,239,.28);"
                        >
                            <div class="uc-stat-top">
                                <i class="fas fa-user-tie text-fuchsia-300"></i>
                                Alumni
                            </div>
                            <div class="uc-stat-number">
                                {{ $homeStats['alumni'] ?? 0 }}
                            </div>
                        </div>

                        <div
                            class="uc-stat"
                            style="--stat-color:rgb(110 231 183);--stat-glow:rgba(16,185,129,.28);"
                        >
                            <div class="uc-stat-top">
                                <i class="fas fa-briefcase text-emerald-300"></i>
                                Jobs
                            </div>
                            <div class="uc-stat-number">
                                {{ $homeStats['jobs'] ?? 0 }}
                            </div>
                        </div>
                    </div>

                    {{-- Rotating 3D orbital system --}}
                    <div class="uc-orbit one">
                        <span class="uc-orbit-dot"></span>
                    </div>

                    <div class="uc-orbit two">
                        <span class="uc-orbit-dot"></span>
                    </div>

                    <div class="uc-orbit three">
                        <span class="uc-orbit-dot"></span>
                    </div>

                    <div class="uc-orbit four"></div>

                    <div class="uc-core">
                        <div>
                            <i class="fas fa-graduation-cap uc-core-icon"></i>
                            <div class="uc-core-title">
                                UNIVERSITY<br>CONNECT
                            </div>
                        </div>
                    </div>

                    <div class="uc-node a">
                        <strong>
                            <i class="fas fa-shield-halved"></i>
                            Verified Access
                        </strong>
                        <small>University identity</small>
                    </div>

                    <div class="uc-node b">
                        <strong>
                            <i class="fas fa-handshake-angle"></i>
                            Mentorship
                        </strong>
                        <small>Student ↔ Alumni</small>
                    </div>

                    <div class="uc-node c">
                        <strong>
                            <i class="fas fa-briefcase"></i>
                            Career Network
                        </strong>
                        <small>Jobs & opportunities</small>
                    </div>

                    <div class="uc-node d">
                        <strong>
                            <i class="fas fa-building-columns"></i>
                            Campus Network
                        </strong>
                        <small>One connected ecosystem</small>
                    </div>

                    <div class="uc-live-label">
                        <span></span>
                        University Connect Ecosystem
                    </div>
                </div>
            </div>
        </section>

        <footer class="uc-footer">
            <div class="uc-footer-line">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-white">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <p class="font-black text-slate-300">University Connect</p>
                        <p class="text-[10px] text-slate-600">Campus Network</p>
                    </div>
                </div>

                <p>
                    &copy; {{ date('Y') }} University Connect. All rights reserved.
                </p>
            </div>
        </footer>
    </main>
</div>

<script>
    (() => {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const finePointer = window.matchMedia('(pointer: fine)').matches;

        /* ---------------------------------------------------------
           Cursor atmosphere
        --------------------------------------------------------- */
        const cursorGlow = document.getElementById('uc-cursor-glow');

        if (!reduceMotion && finePointer && cursorGlow) {
            let targetX = window.innerWidth / 2;
            let targetY = window.innerHeight / 2;
            let currentX = targetX;
            let currentY = targetY;

            window.addEventListener('pointermove', (event) => {
                targetX = event.clientX;
                targetY = event.clientY;
            }, { passive: true });

            const animateCursor = () => {
                currentX += (targetX - currentX) * .075;
                currentY += (targetY - currentY) * .075;

                cursorGlow.style.left = currentX + 'px';
                cursorGlow.style.top = currentY + 'px';

                requestAnimationFrame(animateCursor);
            };

            animateCursor();
        }

        /* ---------------------------------------------------------
           Premium 3D mouse parallax
        --------------------------------------------------------- */
        const visualWrap = document.getElementById('uc-visual-wrap');
        const network = document.getElementById('uc-network');

        if (
            !reduceMotion &&
            finePointer &&
            visualWrap &&
            network &&
            window.innerWidth > 900
        ) {
            visualWrap.addEventListener('pointermove', (event) => {
                const rect = visualWrap.getBoundingClientRect();

                const x = (event.clientX - rect.left) / rect.width - .5;
                const y = (event.clientY - rect.top) / rect.height - .5;

                network.style.transform =
                    `rotateY(${x * 8}deg) rotateX(${-y * 6}deg) translate3d(${x * 5}px, ${y * 4}px, 0)`;
            });

            visualWrap.addEventListener('pointerleave', () => {
                network.style.transform =
                    'rotateY(0deg) rotateX(0deg) translate3d(0,0,0)';
            });
        }

        /* ---------------------------------------------------------
           Live connected-network canvas
           Decorative only — no fake analytics/data.
        --------------------------------------------------------- */
        const canvas = document.getElementById('uc-network-canvas');

        if (canvas && !reduceMotion) {
            const ctx = canvas.getContext('2d');

            let width = 0;
            let height = 0;
            let dpr = Math.min(window.devicePixelRatio || 1, 2);
            let points = [];
            let animationFrame = null;

            const resizeCanvas = () => {
                const rect = canvas.getBoundingClientRect();

                width = rect.width;
                height = rect.height;

                canvas.width = Math.floor(width * dpr);
                canvas.height = Math.floor(height * dpr);

                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

                const count = Math.max(
                    36,
                    Math.min(68, Math.floor(width / 8))
                );

                points = Array.from({ length: count }, () => ({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - .5) * .25,
                    vy: (Math.random() - .5) * .25,
                    radius: .55 + Math.random() * 1.25,
                    phase: Math.random() * Math.PI * 2
                }));
            };

            const render = (time = 0) => {
                ctx.clearRect(0, 0, width, height);

                const centerX = width / 2;
                const centerY = height * .47;

                points.forEach((point, index) => {
                    point.x += point.vx;
                    point.y += point.vy;

                    if (point.x < 0 || point.x > width) {
                        point.vx *= -1;
                    }

                    if (point.y < 0 || point.y > height) {
                        point.vy *= -1;
                    }

                    const pulse =
                        .25 +
                        .22 * Math.sin(time * .0016 + point.phase);

                    ctx.beginPath();
                    ctx.arc(
                        point.x,
                        point.y,
                        point.radius,
                        0,
                        Math.PI * 2
                    );

                    ctx.fillStyle =
                        `rgba(207,250,254,${Math.max(.10, pulse)})`;

                    ctx.fill();

                    const centerDistance = Math.hypot(
                        point.x - centerX,
                        point.y - centerY
                    );

                    if (centerDistance < 190) {
                        ctx.beginPath();
                        ctx.moveTo(point.x, point.y);
                        ctx.lineTo(centerX, centerY);

                        ctx.strokeStyle =
                            `rgba(129,140,248,${
                                (1 - centerDistance / 190) * .09
                            })`;

                        ctx.lineWidth = .55;
                        ctx.stroke();
                    }

                    for (
                        let secondIndex = index + 1;
                        secondIndex < points.length;
                        secondIndex++
                    ) {
                        const other = points[secondIndex];

                        const distance = Math.hypot(
                            point.x - other.x,
                            point.y - other.y
                        );

                        if (distance < 68) {
                            ctx.beginPath();
                            ctx.moveTo(point.x, point.y);
                            ctx.lineTo(other.x, other.y);

                            ctx.strokeStyle =
                                `rgba(103,232,249,${
                                    (1 - distance / 68) * .075
                                })`;

                            ctx.lineWidth = .45;
                            ctx.stroke();
                        }
                    }
                });

                const glow = ctx.createRadialGradient(
                    centerX,
                    centerY,
                    0,
                    centerX,
                    centerY,
                    160
                );

                glow.addColorStop(0, 'rgba(99,102,241,.12)');
                glow.addColorStop(.5, 'rgba(34,211,238,.025)');
                glow.addColorStop(1, 'rgba(2,6,23,0)');

                ctx.fillStyle = glow;
                ctx.beginPath();
                ctx.arc(
                    centerX,
                    centerY,
                    160,
                    0,
                    Math.PI * 2
                );
                ctx.fill();

                animationFrame = requestAnimationFrame(render);
            };

            resizeCanvas();
            render();

            window.addEventListener(
                'resize',
                resizeCanvas,
                { passive: true }
            );

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    if (animationFrame) {
                        cancelAnimationFrame(animationFrame);
                    }
                } else {
                    render();
                }
            });
        }

        /* ---------------------------------------------------------
           Ambient depth particles
        --------------------------------------------------------- */
        if (!reduceMotion && window.innerWidth > 700) {
            const fragment = document.createDocumentFragment();

            for (let index = 0; index < 20; index++) {
                const particle = document.createElement('span');

                particle.className = 'uc-particle';
                particle.style.left = `${Math.random() * 100}%`;

                particle.style.setProperty(
                    '--duration',
                    `${12 + Math.random() * 14}s`
                );

                particle.style.setProperty(
                    '--delay',
                    `${-Math.random() * 20}s`
                );

                particle.style.setProperty(
                    '--drift',
                    `${-60 + Math.random() * 120}px`
                );

                fragment.appendChild(particle);
            }

            document.body.appendChild(fragment);
        }
    })();
</script>
<script>
    (() => {
        const header = document.querySelector('.uc-welcome-header');
        const update = () => header?.classList.toggle('uc-scrolled', window.scrollY > 8);
        window.addEventListener('scroll', update, { passive: true });
        update();
    })();
</script>
</body>
</html>
