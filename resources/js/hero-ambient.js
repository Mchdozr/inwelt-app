export function initHeroAmbient() {
    const canvas = document.querySelector('[data-hero-ambient]');

    if (!canvas) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const ctx = canvas.getContext('2d');

    if (!ctx) {
        return;
    }

    const brandRgb = { r: 23, g: 92, b: 211 };
    const particles = [];
    const particleCount = 52;
    const linkDistance = 140;
    let width = 0;
    let height = 0;
    let animationId = 0;
    let pointer = { x: 0.5, y: 0.5, active: false };

    function readBrandColor() {
        const root = getComputedStyle(document.documentElement);
        const raw = root.getPropertyValue('--color-iw-brand').trim();

        if (!raw.startsWith('#')) {
            return brandRgb;
        }

        const hex = raw.replace('#', '');

        return {
            r: parseInt(hex.slice(0, 2), 16),
            g: parseInt(hex.slice(2, 4), 16),
            b: parseInt(hex.slice(4, 6), 16),
        };
    }

    function resize() {
        const parent = canvas.parentElement;
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        width = parent?.clientWidth ?? canvas.clientWidth;
        height = parent?.clientHeight ?? canvas.clientHeight;
        canvas.width = Math.floor(width * dpr);
        canvas.height = Math.floor(height * dpr);
        canvas.style.width = `${width}px`;
        canvas.style.height = `${height}px`;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function seedParticles() {
        particles.length = 0;

        for (let i = 0; i < particleCount; i += 1) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.35,
                vy: (Math.random() - 0.5) * 0.35,
                radius: Math.random() * 1.6 + 0.8,
                alpha: Math.random() * 0.35 + 0.15,
            });
        }
    }

    function draw() {
        const color = readBrandColor();
        const isDark = document.documentElement.dataset.theme === 'dark';
        const lineAlpha = isDark ? 0.14 : 0.1;
        const dotAlpha = isDark ? 0.55 : 0.4;

        ctx.clearRect(0, 0, width, height);

        const parallaxX = pointer.active ? (pointer.x - 0.5) * 18 : 0;
        const parallaxY = pointer.active ? (pointer.y - 0.5) * 12 : 0;

        for (const particle of particles) {
            particle.x += particle.vx;
            particle.y += particle.vy;

            if (particle.x < -20) particle.x = width + 20;
            if (particle.x > width + 20) particle.x = -20;
            if (particle.y < -20) particle.y = height + 20;
            if (particle.y > height + 20) particle.y = -20;
        }

        for (let i = 0; i < particles.length; i += 1) {
            for (let j = i + 1; j < particles.length; j += 1) {
                const a = particles[i];
                const b = particles[j];
                const dx = a.x - b.x;
                const dy = a.y - b.y;
                const dist = Math.hypot(dx, dy);

                if (dist > linkDistance) {
                    continue;
                }

                const fade = 1 - dist / linkDistance;
                ctx.beginPath();
                ctx.moveTo(a.x + parallaxX * fade, a.y + parallaxY * fade);
                ctx.lineTo(b.x + parallaxX * fade, b.y + parallaxY * fade);
                ctx.strokeStyle = `rgba(${color.r}, ${color.g}, ${color.b}, ${lineAlpha * fade})`;
                ctx.lineWidth = 0.8;
                ctx.stroke();
            }
        }

        for (const particle of particles) {
            const gradient = ctx.createRadialGradient(
                particle.x + parallaxX * 0.3,
                particle.y + parallaxY * 0.3,
                0,
                particle.x + parallaxX * 0.3,
                particle.y + parallaxY * 0.3,
                particle.radius * 4,
            );
            gradient.addColorStop(0, `rgba(${color.r}, ${color.g}, ${color.b}, ${particle.alpha * dotAlpha})`);
            gradient.addColorStop(1, `rgba(${color.r}, ${color.g}, ${color.b}, 0)`);
            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.arc(particle.x + parallaxX * 0.3, particle.y + parallaxY * 0.3, particle.radius * 4, 0, Math.PI * 2);
            ctx.fill();
        }

        animationId = window.requestAnimationFrame(draw);
    }

    const onResize = () => {
        resize();
        seedParticles();
    };

    const shell = canvas.closest('.hero-shell');

    shell?.addEventListener('pointermove', (event) => {
        const rect = shell.getBoundingClientRect();
        pointer = {
            x: (event.clientX - rect.left) / rect.width,
            y: (event.clientY - rect.top) / rect.height,
            active: true,
        };
    });

    shell?.addEventListener('pointerleave', () => {
        pointer.active = false;
    });

    resize();
    seedParticles();
    draw();

    window.addEventListener('resize', onResize);

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            window.cancelAnimationFrame(animationId);
            return;
        }

        draw();
    });
}
