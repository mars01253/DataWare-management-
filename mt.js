const log = document.getElementById('login');
const X = document.getElementById('X');
const logpop = document.getElementById('logpop');

log.addEventListener("click", e=>{
    console.log("h");
    logpop.style.top = '20%';
})
X.addEventListener("click" , e=>{
    logpop.style.top = '-50%';
})