const userid = document.getElementById('userid');
const param = new URLSearchParams(window.location.search);
const actualvalue = param.get('iduser');

console.log('Value from URL:', actualvalue);

if (userid) {
  userid.value = actualvalue;
  console.log('Updated value of userid:', userid.value);
} 
