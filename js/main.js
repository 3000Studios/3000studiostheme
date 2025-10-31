
// 3000 Studios main.js
console.log("3000 Studios main.js active");

// Random background music
const tracks=[
  "http://3000studios.com/wp-content/uploads/2025/10/atmospheric-tech-387712.mp3",
  "http://3000studios.com/wp-content/uploads/2025/10/smooth-coffee-254076.mp3",
  "http://3000studios.com/wp-content/uploads/2025/10/lounge-398189.mp3",
  "http://3000studios.com/wp-content/uploads/2025/10/good-vibe-398183.mp3",
  "http://3000studios.com/wp-content/uploads/2025/10/coffee-lounge-145030.mp3"
];
window.addEventListener("load",()=>{
  const a=new Audio(tracks[Math.floor(Math.random()*tracks.length)]);
  a.volume=.3;a.loop=true;a.play().catch(()=>{});
});

// Click sound
document.querySelectorAll("a").forEach(l=>l.addEventListener("click",()=>new Audio("http://3000studios.com/wp-content/uploads/2025/10/click-21156.mp3").play()));

// Particle background
const c=document.getElementById("particle-bg");
if(c){
  const ctx=c.getContext("2d");let w,h,p=[];
  const r=()=>{w=c.width=innerWidth;h=c.height=innerHeight};r();addEventListener("resize",r);
  for(let i=0;i<80;i++)p.push({x:Math.random()*w,y:Math.random()*h,vx:(Math.random()-.5)*.6,vy:(Math.random()-.5)*.6});
  (function d(){
    ctx.clearRect(0,0,w,h);ctx.fillStyle="rgba(0,255,231,.7)";
    for(const s of p){ctx.beginPath();ctx.arc(s.x,s.y,1.8,0,Math.PI*2);ctx.fill();s.x+=s.vx;s.y+=s.vy;
      if(s.x<0||s.x>w)s.vx*=-1;if(s.y<0||s.y>h)s.vy*=-1;}
    requestAnimationFrame(d);
  })();
}

// Simple game
const g=document.getElementById("mini-game");
if(g){
  const x=g.getContext("2d");let X=320,Y=180,dx=2,dy=2;
  (function l(){
    x.fillStyle="#0a0a0a";x.fillRect(0,0,640,360);
    x.fillStyle="#00ffe7";x.beginPath();x.arc(X,Y,15,0,Math.PI*2);x.fill();
    X+=dx;Y+=dy;if(X<15||X>625)dx*=-1;if(Y<15||Y>345)dy*=-1;
    requestAnimationFrame(l);
  })();
}

// Crypto ticker
async function crypto(){
  try{
    const r=await fetch("https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=percent_change_24h_desc&per_page=5&page=1");
    const d=await r.json();
    document.getElementById("crypto-ticker").innerHTML=d.map(c=>`${c.name}: ${c.price_change_percentage_24h.toFixed(2)}%`).join(" • ");
  }catch(e){console.error(e);}
}
crypto();
