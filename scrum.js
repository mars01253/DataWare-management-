const creatteam = document.getElementById('crteam');
const teampopup = document.getElementById('teampopup');
const projectpopup = document.getElementById('projectpopup');
const closeform = document.querySelector('.closeform');

creatteam.addEventListener("click", () => {
    teampopup.style.display = 'block';
});
closeform.addEventListener("click", () => {
    teampopup.style.display = 'none';
});
