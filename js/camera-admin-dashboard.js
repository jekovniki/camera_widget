const hide = document.getElementById('canvas');

hide.style.opacity = "0";
hide.style.position = "absolute";

function generateUrl() {
    setTimeout(() => {
        const url = document.getElementById('photo').src;
        const myUrl = document.getElementById('generate_url');
        myUrl.value = url;
    }, '500');
  }