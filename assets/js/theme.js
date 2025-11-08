// Copyright (c) 2025 NAME.
// All rights reserved.
// Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.

/*!
 * 3000 Studios Theme
 * Copyright © 2025 3000 Studios. All rights reserved.
 */

/* theme.js scaffold: initializes audio via Howler, sets nav video src if THREEK present,
   and provides a lightweight custom cursor + ripple trigger.  Expand per CodePen effects later. */
(function(){
  const cfg = window.THREEK || {};

  // 1) If nav video element exists and THREEK.navVideo is available, ensure source is set (fallback handled in header.php)
  try{
    const navVideo = document.getElementById('nav-bg-video');
    if(navVideo && cfg.navVideo){
      const src = navVideo.querySelector('source');
      if(src) src.src = cfg.navVideo;
      // reload video element to pick up new source
      try{ navVideo.load(); }catch(e){}
    }
  }catch(e){console.warn(e)}

  // 2) Howler playlist - choose random track and set up click/hover sounds
  if(window.Howl){
    const playlist = Array.isArray(cfg.music)? cfg.music : [];
    if(playlist.length){
      const track = playlist[Math.floor(Math.random()*playlist.length)];
      window.__THREEK_BGM = new Howl({src:[track], loop:true, volume:0.35, html5:true});
      // try autoplay, but also wait for user interaction to comply with browsers
      const tryPlay = ()=>{ window.__THREEK_BGM.play(); document.removeEventListener('pointerdown', tryPlay); };
      document.addEventListener('pointerdown', tryPlay);
      window.addEventListener('load', ()=>{ try{ window.__THREEK_BGM.play(); }catch(e){} });
    }

    // Click sound for navigation
    if(cfg.clickSound){
      window.__THREEK_CLICK = new Howl({src:[cfg.clickSound], volume:0.9, html5:true});
      document.addEventListener('click', (e)=>{
        const a = e.target.closest('a,button');
        if(a){ try{ window.__THREEK_CLICK.play(); }catch(err){} }
      });
    }

    // Hello/announcer on home page after delay
    if(document.body.classList.contains('home') && cfg.helloSound){
      setTimeout(()=>{ try{ new Howl({src:[cfg.helloSound], volume:0.95, html5:true}).play(); }catch(e){} }, cfg.helloDelay||5000);
    }
  }

  // 3) Simple cursor canvas (particles) - inspired by CodePen pointer particles
  (function cursorCanvas(){
    const cvs = document.createElement('canvas'); cvs.id='cursor-canvas'; document.body.appendChild(cvs);
    const ctx = cvs.getContext('2d'); let w=0,h=0; function resize(){ w=cvs.width=innerWidth; h=cvs.height=innerHeight; }
    resize(); addEventListener('resize', resize);
    const particles=[];
    function spawn(x,y){ for(let i=0;i<6;i++){ particles.push({x,y,vx:(Math.random()-.5)*2,vy:(Math.random()-.5)*2,life:60}); } }
    addEventListener('pointermove', e=>{ spawn(e.clientX,e.clientY); });
    function loop(){ ctx.clearRect(0,0,w,h); for(let i=particles.length-1;i>=0;i--){ const p=particles[i]; p.x+=p.vx; p.y+=p.vy; p.life--; ctx.fillStyle='rgba(0,255,231,'+(p.life/60)+')'; ctx.beginPath(); ctx.arc(p.x,p.y,2.2,0,Math.PI*2); ctx.fill(); if(p.life<=0) particles.splice(i,1); } requestAnimationFrame(loop); }
    loop();
  })();

  // 4) Lightweight ripple effect over nav: create canvas overlay inside .nav-wrap and draw concentric circles on pointermove
  (function navRipples(){
    const wrap = document.querySelector('.nav-wrap'); if(!wrap) return; const c=document.createElement('canvas'); c.className='nav-ripples'; wrap.appendChild(c); const ctx=c.getContext('2d');
    function resize(){ c.width = wrap.clientWidth; c.height = wrap.clientHeight; c.style.width = wrap.clientWidth+'px'; c.style.height = wrap.clientHeight+'px'; }
    resize(); new ResizeObserver(resize).observe(wrap);
    const ripples=[];
    wrap.addEventListener('pointermove', e=>{ const r=wrap.getBoundingClientRect(); ripples.push({x:e.clientX-r.left,y:e.clientY-r.top,t:0}); if(ripples.length>20) ripples.shift(); });
    function draw(){ ctx.clearRect(0,0,c.width,c.height); for(let i=ripples.length-1;i>=0;i--){ const r=ripples[i]; r.t+=1; const a=1-(r.t/60); if(a<=0){ ripples.splice(i,1); continue; } ctx.strokeStyle='rgba(0,255,231,'+a*0.6+')'; ctx.lineWidth=2; ctx.beginPath(); ctx.arc(r.x,r.y, r.t*2, 0, Math.PI*2); ctx.stroke(); } requestAnimationFrame(draw); }
    draw();
  })();
  
  // 5) Footer "attack" repel effect (moves footer email away from mouse)
  (function footerAttack(){
    const el = document.getElementById('footer-attack'); if(!el) return;
    document.addEventListener('mousemove', e=>{
      const r = el.getBoundingClientRect();
      const dx = e.clientX - (r.left + r.width/2);
      const dy = e.clientY - (r.top + r.height/2);
      const dist = Math.hypot(dx,dy) || 1;
      const force = Math.max(0, 120 - dist);
      const tx = (-(dx/dist) * force);
      const ty = (-(dy/dist) * force);
      el.style.transform = `translate(${tx}px, ${ty}px)`;
    });
  })();

  // 6) Mobile menu toggle functionality
  (function mobileMenuToggle(){
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.nav');
    if(!toggle || !nav) return;
    
    toggle.addEventListener('click', function(){
      const isOpen = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e){
      if(!nav.contains(e.target) && !toggle.contains(e.target)){
        nav.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  })();

})();
