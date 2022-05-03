const hide = document.getElementById('canvas');
const isAdminPage = document.querySelector('article .field__item');
if(isAdminPage) {
    document.querySelector('.camera').style.display = "none";
    document.querySelector('#canvas').style.display = "none";
}

hide.style.opacity = "0";
hide.style.position = "absolute";
      
function generateUrl() {
    setTimeout(() => {
        const url = document.getElementById('photo').src;
        const myUrl = document.getElementById('generate_url');
        myUrl.value = url;
    }, '500');
  }