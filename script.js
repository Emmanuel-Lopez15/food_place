const container = document.querySelector(".container");
const btnSignIn = document.getElementById("btn-sign-in");
const btnSignUp = document.getElementById("btn-sign-up");

btnSignIn.addEventListener("click",()=>{
   container.classList.remove("toggle");
});
btnSignUp.addEventListener("click",()=>{
   container.classList.add("toggle");
});



// Obtener el botón
const scrollTopBtn = document.getElementById("scrollTopBtn");

// Mostrar el botón al hacer scroll
window.onscroll = function () {
  if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    scrollTopBtn.style.display = "block";
  } else {
    scrollTopBtn.style.display = "none";
  }
};

// Al hacer clic, desplazar hacia el inicio
scrollTopBtn.onclick = function () {
  window.scrollTo({
    top: 0,
    behavior: "smooth", // Animación suave
  });
};

