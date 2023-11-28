const fullname = document.getElementById('fullname');
const regname = document.getElementById('regname');
const email = document.getElementById('email');
const regemail = document.getElementById('regemail');
const password = document.getElementById('password');
const rpassword = document.getElementById('rpassword');
const emaillog = document.getElementById('emaillog');
const regnamex = document.getElementById('regnamex');

const validname = /^[a-zA-Z]{3,}\s[a-zA-Z]{3,}$/;
const validemail = /^(([a-zA-Z]{1,})\d{1,}@[a-z]{1,}\.[a-z]{1,3}|[a-z]+@[a-z]+\.[a-z]{1,3})$/;




fullname.addEventListener('input', e => {
    const inputValue = fullname.value;
    if (validname.test(inputValue)) {
        regname.innerText = 'Name is Valid';
        regname.style.color = 'green';
        regname.style.display = 'block';
    } else {
        regname.innerText = 'Name is Invalid';
        regname.style.color = 'red';
        regname.style.display = 'block';
        e.preventDefault();
    }
});

email.addEventListener('input', e => {
    const emailValue = email.value;
    if (validemail.test(emailValue)) {
        regemail.innerText = 'email is Valid';
        regemail.style.color = 'green';
        regemail.style.display = 'block';
    } else {
        regemail.innerText = 'email is Invalid';
        regemail.style.color = 'red';
        regemail.style.display = 'block';
        e.preventDefault();
    }
});
emaillog.addEventListener('input', e => {
    const emailValue = emaillog.value;
    if (validemail.test(emailValue)) {
        regnamex.innerText = 'email is Valid';
        regnamex.style.color = 'green';
        regnamex.style.display = 'block';
    } else {
        regnamex.innerText = 'email is Invalid';
        regnamex.style.color = 'red';
        regnamex.style.display = 'block';
        e.preventDefault();
    }
});

rpassword.addEventListener('input', e => {
    const pass = password.value;
    const rpass = rpassword.value;
    if (pass !== rpass) {
        rpassword.style.border = '1px solid red';
    } else {
        rpassword.style.border = '1px solid #ccc';
    }
});


