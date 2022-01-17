/***************** Service Worker (PWA) */
let urlCourante = document.location.href;
if (urlCourante.includes('pages')) {
    urlSW = '../sw.js';
} else {
    urlSW = 'sw.js';
}
if (navigator.serviceWorker) {
    navigator.serviceWorker.register(urlSW)
        .then(registration => {
            console.log("Enregistrement du service worker effectué");
        })
        .catch(error => {console.log(error)});
}


/***************** Button add to home screen */
if (document.getElementById('add-button')) {
    let deferredPrompt;
    const addBtn = document.getElementById('add-button');
    addBtn.style.display = 'none';
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        // Update UI to notify the user they can add to home screen
        if (navigator.onLine) {
            addBtn.style.display = 'block';
        }

        addBtn.addEventListener('click', (e) => {
            // hide our user interface that shows our A2HS button
            addBtn.style.display = 'none';
            // Show the prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the A2HS prompt');
                } else {
                    console.log('User dismissed the A2HS prompt');
                }
                deferredPrompt = null;
            });
        });
    });
}


/*********** Remove success or error message after 10s */
if (document.getElementById('message')) {
    const message = document.getElementById('message');

    function removeMessage() {
        message.remove();
    }

    setTimeout(removeMessage, 5000);
}



/*********** afficher des informations */
if (document.getElementById("btn-info") && document.getElementById("div-info")) {
    const btnInfo = document.getElementById("btn-info");
    const divInfo = document.getElementById("div-info");

    btnInfo.addEventListener('click', function() {
        divInfo.hidden = !divInfo.hidden;
        if (!divInfo.hidden) {
            btnInfo.innerHTML = "Masquer mes informations personnel";
        } else {
            btnInfo.innerHTML = "Voir mes informations personnel";
        }
    });
}


/***************** Show hide inputs of callback */
if (document.getElementById("speedcall")) {
    const speedcall = document.getElementById("speedcall");
    const btnText = speedcall.children[0];
    const timeChoice = document.getElementById("time-choice");
    const date = document.getElementById('monselect-date');
    const hours = document.getElementById('monselect-hours');
    const croix = document.getElementById('croix-rouge');
    const spanOu = document.getElementById("span-ou");

    speedcall.addEventListener("click", function () {
        timeChoice.hidden = !timeChoice.hidden;
        date.required = hours.required = true;
        speedcall.style.display = "none";
        spanOu.style.display = "none";
        document.getElementById('h-speedcall').value = !timeChoice.hidden;
    });

    croix.addEventListener("click", function () {
        timeChoice.hidden = !timeChoice.hidden;
        date.required = hours.required = false;
        speedcall.style.display = "block";
        spanOu.style.display = "block";
        document.getElementById('h-speedcall').value = !timeChoice.hidden;
    });
}


/*************************** Change background Date false */
if (document.getElementById('monselect-date')) {
    const picker = document.getElementById('monselect-date');
    let para = document.getElementById('para');

    picker.addEventListener('input', function(e){
        let day = new Date(this.value).getUTCDay();
        // getUTCDay : renvoie le jour de la semaine pour la date renseignée d'après UTC. La numérotation commence à 0, et dimanche est considéré comme le premier jour de la semaine.
        if([6,0].includes(day)){
            e.preventDefault();
            this.value = '';
            picker.style.backgroundColor = "tomato";
            para.textContent = "Veuillez saisir une date valide";
        }else{
            picker.style.backgroundColor = "white";
            para.textContent = "";
        }
    });
}


/***************** Edit profil password required or not */
if (document.getElementById('old-password')
    && document.getElementById('new-password')
    && document.getElementById('confirm-new-password')) {

    const oldPassword = document.getElementById('old-password');
    const newPassword = document.getElementById('new-password');
    const newPasswordTwo = document.getElementById('confirm-new-password');

    oldPassword.addEventListener('input', function () {
        if(oldPassword.value.length > 0
            || newPassword.value.length > 0
            || newPasswordTwo.value.length > 0) {
            oldPassword.required = true;
            newPassword.required = true;
            newPasswordTwo.required = true;
        } else {
            oldPassword.required = false;
            newPassword.required = false;
            newPasswordTwo.required = false;
        }
    });

    newPassword.addEventListener('input', function () {
        if(oldPassword.value.length > 0
            || newPassword.value.length > 0
            || newPasswordTwo.value.length > 0) {
            oldPassword.required = true;
            newPassword.required = true;
            newPasswordTwo.required = true;
        } else {
            oldPassword.required = false;
            newPassword.required = false;
            newPasswordTwo.required = false;
        }
    });

    newPasswordTwo.addEventListener('input', function () {
        if(oldPassword.value.length > 0
            || newPassword.value.length > 0
            || newPasswordTwo.value.length > 0) {
            oldPassword.required = true;
            newPassword.required = true;
            newPasswordTwo.required = true;
        } else {
            oldPassword.required = false;
            newPassword.required = false;
            newPasswordTwo.required = false;
        }
    });
}

const nav = document.getElementById('nav');
const menu = document.getElementById('burger');


menu.addEventListener("click", function(){
    if(nav.style.display == "block"){
        nav.style.display = "none";
        menu.src = '../img/burger.png';
    }else{
        nav.style.display = "block";
        menu.src = '../img/fermer.png';
    }
});